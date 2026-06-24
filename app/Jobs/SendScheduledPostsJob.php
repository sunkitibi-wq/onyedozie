<?php

namespace App\Jobs;

use App\Models\ScheduledPost;
use App\Services\SocialMediaService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendScheduledPostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(SocialMediaService $socialMediaService): void
    {
        // 1. Process posts due for publishing
        $pendingPosts = ScheduledPost::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($pendingPosts as $post) {
            $socialMediaService->publish($post);
        }

        // 2. Simulate progressive engagement growth for older posts
        $socialMediaService->updateEngagementMetrics();
    }
}
