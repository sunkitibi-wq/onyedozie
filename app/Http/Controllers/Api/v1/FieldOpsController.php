<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AgentLocation;
use App\Models\DoorKnock;
use App\Models\LeaderboardPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FieldOpsController extends Controller
{
    public function ping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'accuracy' => 'nullable|numeric',
            'battery_level' => 'nullable|integer|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $location = AgentLocation::create([
            'user_id' => $user->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'accuracy' => $request->accuracy,
            'battery_level' => $request->battery_level,
            'recorded_at' => now(),
        ]);

        // Update user's last seen
        $user->last_seen_at = now();
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Location ping saved.',
            'data' => $location
        ]);
    }

    public function doorKnock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'address_description' => 'nullable|string',
            'voter_name' => 'nullable|string',
            'outcome' => 'required|string|in:supportive,neutral,opposed,not home',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $knock = DoorKnock::create([
            'user_id' => $user->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'address_description' => $request->address_description,
            'voter_name' => $request->voter_name,
            'outcome' => $request->outcome,
            'notes' => $request->notes,
            'visited_at' => now(),
        ]);

        // Reward points: 2 points for logging a door knock
        LeaderboardPoint::create([
            'user_id' => $user->id,
            'points' => 2,
            'source_type' => 'door_knock',
            'source_id' => $knock->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Door knock logged successfully! 2 campaign points awarded.',
            'data' => $knock
        ]);
    }

    public function doorKnockHistory(Request $request)
    {
        $user = $request->user();

        $history = DoorKnock::where('user_id', $user->id)
            ->orderBy('visited_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Door knock history retrieved.',
            'data' => $history
        ]);
    }
}
