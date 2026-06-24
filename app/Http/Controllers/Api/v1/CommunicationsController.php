<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\WhatsappBroadcast;
use App\Models\ScheduledPost;
use App\Jobs\SendWhatsappBroadcastJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommunicationsController extends Controller
{
    /**
     * Get list of WhatsApp Broadcasts.
     */
    public function getBroadcasts(Request $request): JsonResponse
    {
        $broadcasts = WhatsappBroadcast::orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Broadcasts retrieved successfully.',
            'data' => $broadcasts->items(),
            'meta' => [
                'current_page' => $broadcasts->currentPage(),
                'last_page' => $broadcasts->lastPage(),
                'total' => $broadcasts->total(),
            ]
        ]);
    }

    /**
     * Create/Trigger a new WhatsApp Broadcast.
     */
    public function sendBroadcast(Request $request): JsonResponse
    {
        if (!$request->user()->hasAnyRole(['Admin', 'Super Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Campaign Admins can dispatch broadcasts.',
            ], 403);
        }

        $request->validate([
            'message' => 'required|string',
            'media' => 'nullable|file|image|max:10240', // max 10MB
            'audience_type' => 'required|string|in:all,lga,role,custom',
            'audience_filter' => 'nullable|array',
        ]);

        $mediaPath = null;
        if ($request->hasFile('media')) {
            $mediaPath = $request->file('media')->store('broadcasts', 'public');
        }

        $broadcast = WhatsappBroadcast::create([
            'message' => $request->message,
            'media_path' => $mediaPath,
            'audience_type' => $request->audience_type,
            'audience_filter' => $request->audience_filter ?? [],
            'status' => 'pending',
        ]);

        // Dispatch background queue job
        SendWhatsappBroadcastJob::dispatch($broadcast);

        return response()->json([
            'success' => true,
            'message' => 'Broadcast queued for dispatch.',
            'data' => $broadcast,
        ]);
    }

    /**
     * Get list of scheduled/published social media posts.
     */
    public function getScheduledPosts(Request $request): JsonResponse
    {
        $posts = ScheduledPost::orderBy('scheduled_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Scheduled social posts retrieved successfully.',
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total(),
            ]
        ]);
    }

    /**
     * Create a new scheduled social media post.
     */
    public function schedulePost(Request $request): JsonResponse
    {
        if (!$request->user()->hasAnyRole(['Admin', 'Super Admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only Campaign Admins can schedule social media posts.',
            ], 403);
        }

        $request->validate([
            'content' => 'required|string',
            'media' => 'nullable|array|max:4',
            'media.*' => 'file|image|max:10240',
            'platforms' => 'required|array|min:1',
            'platforms.*' => 'string|in:facebook,instagram,twitter,x',
            'scheduled_at' => 'required|date',
        ]);

        $mediaPaths = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaPaths[] = $file->store('social_posts', 'public');
            }
        }

        $post = ScheduledPost::create([
            'content' => $request->content,
            'media_paths' => $mediaPaths,
            'platforms' => $request->platforms,
            'scheduled_at' => $request->scheduled_at,
            'status' => 'scheduled',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Social media post scheduled successfully.',
            'data' => $post,
        ]);
    }
}
