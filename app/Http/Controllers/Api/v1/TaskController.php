<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\LeaderboardPoint;
use App\Models\User;
use App\Notifications\GeneralCampaignNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get tasks assigned to this specific user, or general roles matching the user's role
        $tasks = Task::where(function ($query) use ($user) {
            $query->where('assigned_user_id', $user->id)
                  ->orWhere('assigned_to_role', $user->roles->first()?->name);
        })
        ->orderBy('deadline', 'asc')
        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully.',
            'data' => $tasks
        ]);
    }

    public function complete(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found.'
            ], 404);
        }

        $user = $request->user();

        // Check permission
        if ($task->assigned_user_id !== $user->id && $task->assigned_to_role !== $user->roles->first()?->name) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $task->status = 'completed';
        $task->save();

        $completion = TaskCompletion::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'notes' => $request->notes,
            'completed_at' => now(),
        ]);

        // Reward points: 3 points for completing a task
        LeaderboardPoint::create([
            'user_id' => $user->id,
            'points' => 3,
            'source_type' => 'task',
            'source_id' => $completion->id,
        ]);

        // Notify the task creator and all Super Admins
        $notification = new GeneralCampaignNotification(
            'Task Completed: ' . $task->title,
            $user->name . ' has submitted a completion report for this task. Please review and verify.',
            'task_completed',
            ['task_id' => $task->id]
        );
        // Notify task creator
        if ($task->created_by) {
            $creator = User::find($task->created_by);
            $creator?->notify($notification);
        }
        // Notify all Super Admins (skip if already notified above)
        User::role('Super Admin')->where('id', '!=', $task->created_by)->each(fn ($a) => $a->notify($notification));

        return response()->json([
            'success' => true,
            'message' => 'Task completed successfully! 3 campaign points awarded.',
            'data' => $completion
        ]);
    }
}
