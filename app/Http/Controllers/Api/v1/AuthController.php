<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\OtpCooldownException;
use App\Services\OtpExpiredException;
use App\Services\OtpInvalidException;
use App\Services\OtpTooManyAttemptsException;
use App\Services\SmsOtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:LGA Coordinator,Ward Coordinator,Polling Unit Coordinator,Volunteer',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'polling_unit_id' => 'nullable|exists:polling_units,id',
            'occupation' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Volunteers active immediately; coordinators pending approval
        $status = $request->role === 'Volunteer' ? 'active' : 'pending_approval';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $status,
            'lga_id' => $request->lga_id,
            'ward_id' => $request->ward_id,
            'polling_unit_id' => $request->polling_unit_id,
            'occupation' => $request->occupation,
        ]);

        $user->assignRole($request->role);

        $otpService->issue($user->phone, SmsOtpService::PURPOSE_PHONE_VERIFICATION);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'user' => $user->load(['roles', 'lga', 'ward', 'pollingUnit'])->loadSum('leaderboardPoints as points', 'points'),
                'token' => $token
            ]
        ], 201);
    }

    public function login(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number or password.'
            ], 401);
        }

        if ($user->status === 'suspended') {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if (!$user->phone_verified_at) {
            try {
                $otpService->issue($user->phone, SmsOtpService::PURPOSE_PHONE_VERIFICATION);
            } catch (OtpCooldownException $e) {
                // Ignore cooldown on login
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => $user->load(['roles', 'lga', 'ward', 'pollingUnit'])->loadSum('leaderboardPoints as points', 'points'),
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }

    public function verifyOtp(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'otp' => 'required|string|size:6',
            'purpose' => 'nullable|string|in:phone_verification,password_reset',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($limitedResponse = $this->rateLimitOtp($request, 'verify')) {
            return $limitedResponse;
        }

        $purpose = $request->purpose ?: $this->otpPurposeForVerification($request->phone);

        try {
            $otpService->verify(
                $request->phone,
                $request->otp,
                $purpose,
                markVerified: true,
            );

            $user = User::where('phone', $request->phone)->first();

            if ($user && $purpose === SmsOtpService::PURPOSE_PHONE_VERIFICATION) {
                $user->phone_verified_at = now();
                $user->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.'
            ]);
        } catch (OtpExpiredException|OtpInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (OtpTooManyAttemptsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 429);
        }
    }

    public function resendOtp(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'purpose' => 'nullable|string|in:phone_verification,password_reset',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($limitedResponse = $this->rateLimitOtp($request, 'request')) {
            return $limitedResponse;
        }

        $purpose = $request->purpose ?: SmsOtpService::PURPOSE_PHONE_VERIFICATION;

        try {
            $otpService->issue($request->phone, $purpose);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your registered email address.'
            ]);
        } catch (OtpCooldownException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'retry_after' => $e->retryAfter,
            ], 429);
        }
    }

    public function forgotPassword(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($limitedResponse = $this->rateLimitOtp($request, 'request')) {
            return $limitedResponse;
        }

        try {
            $otpService->issue($request->phone, SmsOtpService::PURPOSE_PASSWORD_RESET);

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your registered email address.'
            ]);
        } catch (OtpCooldownException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'retry_after' => $e->retryAfter,
            ], 429);
        }
    }

    public function resetPassword(Request $request, SmsOtpService $otpService): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'otp' => 'required|string|size:6',
            'password' => ['required', 'string', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($limitedResponse = $this->rateLimitOtp($request, 'verify')) {
            return $limitedResponse;
        }

        try {
            $otpService->verify(
                $request->phone,
                $request->otp,
                SmsOtpService::PURPOSE_PASSWORD_RESET,
                markVerified: true,
                consume: true,
            );
        } catch (OtpExpiredException|OtpInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (OtpTooManyAttemptsException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 429);
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.'
        ]);
    }

    private function otpPurposeForVerification(string $phone): string
    {
        $phoneVerificationOtpExists = PhoneOtp::query()
            ->where('phone', $phone)
            ->where('purpose', SmsOtpService::PURPOSE_PHONE_VERIFICATION)
            ->whereNull('consumed_at')
            ->exists();

        return $phoneVerificationOtpExists
            ? SmsOtpService::PURPOSE_PHONE_VERIFICATION
            : SmsOtpService::PURPOSE_PASSWORD_RESET;
    }

    private function rateLimitOtp(Request $request, string $type): ?JsonResponse
    {
        $configKey = $type === 'request' ? 'otp.request_rate_limit' : 'otp.verify_rate_limit';
        $maxAttempts = (int) config("{$configKey}.max_attempts");
        $decaySeconds = (int) config("{$configKey}.decay_seconds");
        $key = "otp:{$type}:" . sha1($request->ip() . '|' . $request->input('phone'));

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many OTP attempts. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        RateLimiter::hit($key, $decaySeconds);

        return null;
    }
}
