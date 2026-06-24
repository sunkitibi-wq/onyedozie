<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\MediaLibrary;
use App\Models\User;
use App\Models\LeaderboardPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function news(Request $request)
    {
        $news = News::orderBy('published_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'News feed retrieved.',
            'data' => $news->items(),
            'meta' => [
                'current_page' => $news->currentPage(),
                'total' => $news->total(),
            ]
        ]);
    }

    public function media(Request $request)
    {
        $media = MediaLibrary::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Media assets retrieved.',
            'data' => $media
        ]);
    }

    public function leaderboard(Request $request)
    {
        // Get top 50 users based on total leaderboard points
        $topUsers = User::where('status', 'active')
            ->select('users.id', 'users.name', 'users.phone', 'users.polling_unit_id')
            ->with(['pollingUnit'])
            ->withSum('leaderboardPoints', 'points')
            ->orderByDesc('leaderboard_points_sum_points')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Leaderboard retrieved.',
            'data' => $topUsers
        ]);
    }

    public function notifications(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Notifications retrieved.',
            'data' => $notifications->items(),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'total' => $notifications->total(),
            ]
        ]);
    }

    public function readNotifications(Request $request)
    {
        $user = $request->user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.'
        ]);
    }
}
