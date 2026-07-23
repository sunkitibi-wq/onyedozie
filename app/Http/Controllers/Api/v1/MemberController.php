<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with(['roles', 'lga', 'ward', 'pollingUnit']);

        if ($request->has('lga_id')) {
            $query->where('lga_id', $request->lga_id);
        }

        if ($request->has('ward_id')) {
            $query->where('ward_id', $request->ward_id);
        }

        if ($request->has('polling_unit_id')) {
            $query->where('polling_unit_id', $request->polling_unit_id);
        }

        if ($request->has('role')) {
            $query->role($request->role);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('occupation')) {
            $query->where('occupation', $request->occupation);
        }

        if ($request->has('recruited_by_me') && $request->recruited_by_me == 1) {
            $recruitedIds = \App\Models\LeaderboardPoint::where('user_id', $request->user()->id)
                ->where('source_type', 'recruitment')
                ->pluck('source_id');
            $query->whereIn('id', $recruitedIds);
        }

        $members = $query->paginate(50);

        return response()->json([
            'success' => true,
            'message' => 'Members retrieved successfully.',
            'data' => $members->items(),
            'meta' => [
                'current_page' => $members->currentPage(),
                'total' => $members->total(),
                'last_page' => $members->lastPage(),
            ]
        ]);
    }

    public function show($id)
    {
        $user = User::with(['roles', 'lga', 'ward', 'pollingUnit'])
            ->withSum('leaderboardPoints as points', 'points')
            ->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Member details retrieved.',
            'data' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Member not found.'
            ], 404);
        }

        if ($request->user()->id !== $user->id && !$request->user()->hasRole(['Super Admin', 'Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:8',
            'device_token' => 'nullable|string',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'polling_unit_id' => 'nullable|exists:polling_units,id',
            'occupation' => 'nullable|string|max:255',
            'passport' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('device_token')) {
            $user->device_token = $request->device_token;
        }

        if ($request->has('lga_id')) {
            $user->lga_id = $request->lga_id;
        }

        if ($request->has('ward_id')) {
            $user->ward_id = $request->ward_id;
        }

        if ($request->has('polling_unit_id')) {
            $user->polling_unit_id = $request->polling_unit_id;
        }

        if ($request->has('occupation')) {
            $user->occupation = $request->occupation;
        }

        if ($request->hasFile('passport')) {
            $path = $request->file('passport')->store('passports', 'public');
            $user->passport_path = '/storage/' . $path;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user->load(['roles', 'lga', 'ward', 'pollingUnit'])->loadSum('leaderboardPoints as points', 'points')
        ]);
    }

    public function recruit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'occupation' => 'nullable|string|max:255',
            'lga_id' => 'nullable|exists:lgas,id',
            'ward_id' => 'nullable|exists:wards,id',
            'polling_unit_id' => 'nullable|exists:polling_units,id',
            'notes' => 'nullable|string|max:1000',
            'passport' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle passport photo upload if provided
        $passportPath = null;
        if ($request->hasFile('passport')) {
            $path = $request->file('passport')->store('passports', 'public');
            $passportPath = '/storage/' . $path;
        }

        // Generate a temporary password from the last 4 digits of the phone + '1234'
        $tempPassword = substr(preg_replace('/\D/', '', $request->phone), -4) . '1234';

        $volunteer = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($tempPassword),
            'occupation' => $request->occupation,
            'lga_id' => $request->lga_id,
            'ward_id' => $request->ward_id,
            'polling_unit_id' => $request->polling_unit_id,
            'passport_path' => $passportPath,
            'status' => 'active',
        ]);

        $volunteer->assignRole('Volunteer');

        // Award 10 points to the recruiter
        $recruiter = $request->user();
        \App\Models\LeaderboardPoint::create([
            'user_id' => $recruiter->id,
            'points' => 10,
            'source_type' => 'recruitment',
            'source_id' => $volunteer->id,
            'earned_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Volunteer '{$volunteer->name}' recruited successfully! +10 campaign points.",
            'data' => [
                'volunteer' => $volunteer->load('roles'),
                'recruiter_points_awarded' => 10,
            ]
        ], 201);
    }

    public function nearby(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'nullable|numeric', // in km, default 5
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius ?? 5;

        // Approximate 1 degree latitude = 111 km
        // Approximate 1 degree longitude = 111 * cos(latitude) ≈ 111 km near equator (Nigeria is at 6-8° N, so approx 110km)
        $latDelta = $radius / 111.0;
        $lngDelta = $radius / (111.0 * cos(deg2rad($lat)));

        // Filter users who are active, have locations in agent_locations, and are within the bounding box
        $users = User::where('status', 'active')
            ->whereNotNull('polling_unit_id')
            ->whereHas('pollingUnit', function ($q) use ($lat, $lng, $latDelta, $lngDelta) {
                $q->whereBetween('lat', [$lat - $latDelta, $lat + $latDelta])
                  ->whereBetween('lng', [$lng - $lngDelta, $lng + $lngDelta]);
            })
            ->with(['pollingUnit'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Nearby members retrieved.',
            'data' => $users
        ]);
    }
}
