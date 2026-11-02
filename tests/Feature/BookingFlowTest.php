<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(): User
    {
        $role = Role::create(['name' => 'user']);

        return User::factory()->create(['role_id' => $role->id]);
    }

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

    public function test_guest_cannot_access_booking_page(): void
    {
        $activity = $this->makeActivity();

        $this->get(route('booking.start', $activity))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_booking(): void
    {
        $user = $this->makeUser();
        $activity = $this->makeActivity();

        $response = $this->actingAs($user)->post(route('booking.store', $activity), [
            'booking_date' => now()->addDay()->toDateString(),
            'participants' => 5,
        ]);

        $booking = Booking::first();

        $response->assertRedirect(route('booking.confirmed', $booking));
        $this->assertNotNull($booking);
        $this->assertStringStartsWith('BM-' . now()->year . '-', $booking->booking_code);
        $this->assertEquals(75000 * 5, $booking->total_price);
    }

    public function test_admin_route_is_protected_from_regular_user(): void
    {
        $user = $this->makeUser();

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }
}
