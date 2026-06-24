<?php

namespace App\Services;

use App\Models\PhoneOtp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SmsOtpService
{
    public const PURPOSE_PASSWORD_RESET = 'password_reset';
    public const PURPOSE_PHONE_VERIFICATION = 'phone_verification';

    public function issue(string $phone, string $purpose): PhoneOtp
    {
        $this->enforceCooldown($phone, $purpose);

        PhoneOtp::query()
            ->where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $code = $this->generateCode();

        $otp = PhoneOtp::create([
            'phone' => $phone,
            'purpose' => $purpose,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes((int) config('otp.expires_in_minutes', 10)),
            'attempts' => 0,
            'max_attempts' => (int) config('otp.max_attempts', 5),
            'last_sent_at' => now(),
        ]);

        $this->send($phone, $code, $purpose);

        return $otp;
    }

    public function verify(string $phone, string $code, string $purpose, bool $markVerified = false, bool $consume = false): PhoneOtp
    {
        $otp = $this->latestUsableOtp($phone, $purpose);

        if (! $otp) {
            throw new OtpInvalidException('Invalid or expired OTP.');
        }

        if ($otp->isExpired()) {
            throw new OtpExpiredException('OTP has expired. Please request a new code.');
        }

        if (! $otp->hasAttemptsRemaining()) {
            throw new OtpTooManyAttemptsException('Too many incorrect OTP attempts. Please request a new code.');
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');

            if (! $otp->fresh()->hasAttemptsRemaining()) {
                throw new OtpTooManyAttemptsException('Too many incorrect OTP attempts. Please request a new code.');
            }

            throw new OtpInvalidException('Invalid OTP.');
        }

        $updates = [];

        if ($markVerified && ! $otp->verified_at) {
            $updates['verified_at'] = now();
        }

        if ($consume) {
            $updates['consumed_at'] = now();
        }

        if ($updates !== []) {
            $otp->forceFill($updates)->save();
        }

        return $otp->fresh();
    }

    private function latestUsableOtp(string $phone, string $purpose): ?PhoneOtp
    {
        return PhoneOtp::query()
            ->where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('consumed_at')
            ->latest()
            ->first();
    }

    private function enforceCooldown(string $phone, string $purpose): void
    {
        $cooldown = (int) config('otp.resend_cooldown_seconds', 60);

        if ($cooldown <= 0) {
            return;
        }

        $latest = $this->latestUsableOtp($phone, $purpose);

        if (! $latest || ! $latest->last_sent_at) {
            return;
        }

        $retryAt = $latest->last_sent_at->addSeconds($cooldown);

        if ($retryAt->isFuture()) {
            $retryAfter = (int) ceil(now()->diffInSeconds($retryAt, true));

            throw new OtpCooldownException(
                'Please wait before requesting another OTP.',
                $retryAfter
            );
        }
    }

    private function generateCode(): string
    {
        $testingCode = config('otp.testing_code');

        if (app()->environment('testing') && $testingCode) {
            return str_pad((string) $testingCode, (int) config('otp.length', 6), '0', STR_PAD_LEFT);
        }

        $length = (int) config('otp.length', 6);
        $min = 10 ** ($length - 1);
        $max = (10 ** $length) - 1;

        return (string) random_int($min, $max);
    }

    private function send(string $phone, string $code, string $purpose): void
    {
        $message = "Your Onyendozi verification code is {$code}. It expires in "
            . config('otp.expires_in_minutes', 10)
            . ' minutes.';

        // Lookup the user associated with this phone number to get their email address.
        $user = \App\Models\User::where('phone', $phone)->first();

        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new OtpMail($code, (int) config('otp.expires_in_minutes', 10), $purpose));
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email.', [
                    'email' => $user->email,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            Log::warning('Could not send OTP email: user not found or email address is empty.', [
                'phone' => $this->maskPhone($phone),
            ]);
        }

        // Keep local logging for developer convenience
        Log::channel(config('logging.default'))->info('OTP email sent (logged locally).', [
            'phone' => $this->maskPhone($phone),
            'email' => $user ? $user->email : null,
            'purpose' => $purpose,
            'message' => app()->isProduction() ? 'redacted' : $message,
        ]);
    }

    private function maskPhone(string $phone): string
    {
        return str_repeat('*', max(strlen($phone) - 4, 0)) . substr($phone, -4);
    }
}
