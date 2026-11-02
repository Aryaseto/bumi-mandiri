<?php

namespace App\Services;

use App\Models\Activity;

class ReviewService
{
    /**
     * Sinkronkan rating_avg dan review_count aktivitas dari ulasan
     * yang sudah disetujui. Dipakai oleh publik, admin, dan seeder.
     */
    public function syncActivityRating(int $activityId): void
    {
        $activity = Activity::find($activityId);

        if (! $activity) {
            return;
        }

        $approved = $activity->reviews();

        $activity->forceFill([
            'rating_avg' => round((float) ($approved->avg('rating') ?? 0), 2),
            'review_count' => $approved->count(),
        ])->save();
    }
}
