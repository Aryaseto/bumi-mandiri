<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitorRatingTest extends TestCase
{
    use RefreshDatabase;

    private function makeActivity(): Activity
    {
        $category = Category::create(['name' => 'Adventure', 'slug' => 'adventure']);
        $destination = Destination::create(['name' => 'Camp Ground', 'slug' => 'camp-ground']);

        return Activity::create([
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'name' => 'Tubing Experience',
            'slug' => 'tubing-experience',
            'price' => 75000,
            'duration_minutes' => 120,
            'capacity' => 20,
            'is_active' => true,
        ]);
    }

    private function makeAdmin(): User
    {
        $role = Role::create(['name' => 'admin']);

        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_guest_can_submit_rating_without_booking(): void
    {
        $activity = $this->makeActivity();

        $response = $this->post(route('reviews.visitor.store', $activity), [
            'name' => 'Rina Kartika',
            'email' => 'rina@example.com',
            'rating' => 5,
            'comment' => 'Arusnya seru dan pemandunya ramah.',
        ]);

        $response->assertRedirect(route('activities.show', $activity).'#rating');

        $this->assertDatabaseHas('reviews', [
            'activity_id' => $activity->id,
            'user_id' => null,
            'booking_id' => null,
            'guest_name' => 'Rina Kartika',
            'rating' => 5,
            'is_approved' => true,
        ]);

        $activity->refresh();
        $this->assertEquals(5.0, (float) $activity->rating_avg);
        $this->assertSame(1, $activity->review_count);
    }

    public function test_rating_requires_name_and_star(): void
    {
        $activity = $this->makeActivity();

        $this->post(route('reviews.visitor.store', $activity), [
            'name' => '',
            'rating' => 0,
        ])->assertSessionHasErrors(['name', 'rating']);

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_honeypot_field_blocks_spam(): void
    {
        $activity = $this->makeActivity();

        $this->post(route('reviews.visitor.store', $activity), [
            'name' => 'Bot Pengunjung',
            'rating' => 5,
            'website' => 'https://spam.example.com',
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_admin_can_hide_rating_and_average_is_recalculated(): void
    {
        $activity = $this->makeActivity();
        $review = Review::create([
            'activity_id' => $activity->id,
            'guest_name' => 'Bimo Aditya',
            'rating' => 3,
            'comment' => 'Tempatnya bagus, parkir agak penuh.',
            'is_approved' => true,
        ]);

        app(ReviewService::class)->syncActivityRating($activity->id);

        $this->actingAs($this->makeAdmin())
            ->put(route('admin.reviews.approve', $review))
            ->assertRedirect();

        $this->assertFalse($review->fresh()->is_approved);

        $activity->refresh();
        $this->assertEquals(0.0, (float) $activity->rating_avg);
        $this->assertSame(0, $activity->review_count);
    }
}
