<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\LeaderboardPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()->with(['lga', 'ward', 'creator']);

        // Scope to user's LGA or Ward if requested
        if ($request->has('lga_id')) {
            $query->where('lga_id', $request->lga_id);
        }

        if ($request->has('ward_id')) {
            $query->where('ward_id', $request->ward_id);
        }

        $events = $query->orderBy('date', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Events retrieved successfully.',
            'data' => $events->items(),
            'meta' => [
                'current_page' => $events->currentPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    public function show($id)
    {
        $event = Event::with(['lga', 'ward', 'creator', 'attendances.user'])->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Event detail retrieved.',
            'data' => $event
        ]);
    }

    public function checkIn(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found.'
            ], 404);
        }

        $user = $request->user();

        // Check if already checked in
        $exists = EventAttendance::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already checked in to this event.'
            ], 400);
        }

        $attendance = EventAttendance::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'checked_in_at' => now(),
            'method' => $request->input('method', 'manual'),
        ]);

        // Reward points: 5 points for attending an event
        LeaderboardPoint::create([
            'user_id' => $user->id,
            'points' => 5,
            'source_type' => 'event',
            'source_id' => $attendance->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully checked in. 5 campaign points awarded!',
            'data' => $attendance
        ]);
    }
}
