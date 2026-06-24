<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\ResultEntry;
use App\Models\Incident;
use App\Models\IncidentMedia;
use App\Models\VoiceReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResultController extends Controller
{
    public function uploadResults(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'polling_unit_id' => 'required|exists:polling_units,id',
            'ec8a_image_path' => 'required|string', // file path/URL
            'results' => 'required|array', // key value: party => votes
            'results.*.party' => 'required|string',
            'results.*.votes' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        // Check if already uploaded for this PU
        $existing = Result::where('polling_unit_id', $request->polling_unit_id)->first();
        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Results have already been submitted for this Polling Unit.'
            ], 400);
        }

        $result = Result::create([
            'user_id' => $user->id,
            'polling_unit_id' => $request->polling_unit_id,
            'ec8a_image_path' => $request->ec8a_image_path,
            'submitted_at' => now(),
        ]);

        foreach ($request->results as $entry) {
            ResultEntry::create([
                'result_id' => $result->id,
                'party' => $entry['party'],
                'votes' => $entry['votes'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'EC8A result sheet and vote entries uploaded successfully.',
            'data' => $result->load('entries')
        ]);
    }

    public function myPuResults(Request $request)
    {
        $user = $request->user();

        if (!$user->polling_unit_id) {
            return response()->json([
                'success' => false,
                'message' => 'No Polling Unit assigned to your profile.'
            ], 400);
        }

        $result = Result::where('polling_unit_id', $user->polling_unit_id)
            ->with(['entries', 'pollingUnit'])
            ->first();

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'No result uploaded yet for your Polling Unit.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Polling unit results retrieved.',
            'data' => $result
        ]);
    }

    public function reportIncident(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:violence,ballot stuffing,INEC official misconduct,result falsification,other',
            'description' => 'required|string',
            'urgency' => 'required|string|in:low,medium,high,critical',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'media' => 'nullable|array',
            'media.*.path' => 'required|string',
            'media.*.type' => 'required|string|in:photo,video',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $incident = Incident::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'description' => $request->description,
            'urgency' => $request->urgency,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'status' => 'reported',
            'reported_at' => now(),
        ]);

        if ($request->has('media')) {
            foreach ($request->media as $m) {
                IncidentMedia::create([
                    'incident_id' => $incident->id,
                    'path' => $m['path'],
                    'type' => $m['type'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Incident reported successfully.',
            'data' => $incident->load('media')
        ], 201);
    }

    public function uploadVoiceReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audio_path' => 'required|string',
            'duration' => 'nullable|integer',
            'transcript' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        $report = VoiceReport::create([
            'user_id' => $user->id,
            'audio_path' => $request->audio_path,
            'duration' => $request->duration,
            'transcript' => $request->transcript,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voice report uploaded successfully.',
            'data' => $report
        ], 201);
    }
}
