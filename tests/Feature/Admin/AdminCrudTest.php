<?php

namespace Tests\Feature\Admin;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);

        return User::factory()->create(['role_id' => $role->id]);
    }

    private function destination(string $name = 'Camp Ground', array $extra = []): Destination
    {
        return Destination::create(array_merge([
            'name' => $name,
            'slug' => Destination::uniqueSlug($name),
        ], $extra));
    }

    private function activity(Destination $destination, array $extra = []): Activity
    {
        $category = Category::firstOrCreate(['slug' => 'adventure'], ['name' => 'Adventure']);

        return Activity::create(array_merge([
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'name' => 'Tubing Seru',
            'slug' => Activity::uniqueSlug($extra['name'] ?? 'Tubing Seru'),
            'price' => 75000,
            'duration_minutes' => 120,
            'capacity' => 20,
            'is_active' => true,
        ], $extra));
    }

    private function activityPayload(Destination $destination, array $extra = []): array
    {
        $category = Category::firstOrCreate(['slug' => 'adventure'], ['name' => 'Adventure']);

        return array_merge([
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'name' => 'Rafting Pemula',
            'price' => 150000,
            'duration_minutes' => 90,
            'capacity' => 12,
        ], $extra);
    }

    // ---------------------------------------------------------------- akses

    public function test_regular_user_cannot_manage_destinations_or_activities(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('admin.destinations.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.destinations.store'), ['name' => 'X'])->assertForbidden();
        $this->actingAs($user)->get(route('admin.activities.index'))->assertForbidden();
    }

    // ----------------------------------------------------------- destinasi

    public function test_admin_can_create_destination_with_cover_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post(route('admin.destinations.store'), [
            'name' => 'Sungai Cipelang',
            'location' => 'Sukabumi',
            'latitude' => -6.9,
            'longitude' => 106.9,
            'google_maps_url' => 'https://maps.google.com/?q=cipelang',
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
        ]);

        $response->assertRedirect(route('admin.destinations.index'));

        $destination = Destination::firstOrFail();
        $this->assertSame('sungai-cipelang', $destination->slug);
        Storage::disk('public')->assertExists($destination->cover_image);
    }

    public function test_duplicate_destination_name_is_a_validation_error_not_a_500(): void
    {
        $admin = $this->admin();
        $this->destination('Camp Ground');

        $this->actingAs($admin)
            ->post(route('admin.destinations.store'), ['name' => 'Camp Ground'])
            ->assertSessionHasErrors('name');

        $this->assertSame(1, Destination::count());
    }

    public function test_names_that_slugify_the_same_get_unique_slugs(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.destinations.store'), ['name' => 'Camp Ground'])->assertRedirect();
        $this->actingAs($admin)->post(route('admin.destinations.store'), ['name' => 'Camp-Ground'])->assertRedirect();

        $this->assertEqualsCanonicalizing(
            ['camp-ground', 'camp-ground-2'],
            Destination::pluck('slug')->all()
        );
    }

    public function test_invalid_coordinates_and_maps_url_are_rejected(): void
    {
        $this->actingAs($this->admin())->post(route('admin.destinations.store'), [
            'name' => 'Lokasi Salah',
            'latitude' => 120,
            'longitude' => 400,
            'google_maps_url' => 'bukan-url',
        ])->assertSessionHasErrors(['latitude', 'longitude', 'google_maps_url']);
    }

    public function test_updating_destination_keeps_slug_and_replaces_old_cover(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.destinations.store'), [
            'name' => 'Lembah Hijau',
            'cover_image' => UploadedFile::fake()->image('old.jpg'),
        ]);
        $destination = Destination::firstOrFail();
        $oldCover = $destination->cover_image;

        $this->actingAs($admin)->put(route('admin.destinations.update', $destination), [
            'name' => 'Lembah Hijau Baru',
            'cover_image' => UploadedFile::fake()->image('new.jpg'),
        ])->assertRedirect(route('admin.destinations.index'));

        $destination->refresh();
        $this->assertSame('lembah-hijau', $destination->slug);
        $this->assertSame('Lembah Hijau Baru', $destination->name);
        Storage::disk('public')->assertMissing($oldCover);
        Storage::disk('public')->assertExists($destination->cover_image);
    }

    public function test_cover_image_can_be_removed(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.destinations.store'), [
            'name' => 'Bukit Pinus',
            'cover_image' => UploadedFile::fake()->image('c.jpg'),
        ]);
        $destination = Destination::firstOrFail();
        $path = $destination->cover_image;

        $this->actingAs($admin)->put(route('admin.destinations.update', $destination), [
            'name' => 'Bukit Pinus',
            'remove_cover_image' => '1',
        ]);

        $this->assertNull($destination->fresh()->cover_image);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_edit_form_hides_seeder_placeholder_for_maps_url(): void
    {
        $destination = $this->destination('Camp Ground', ['google_maps_url' => '[DATA PERLU DIKONFIRMASI]']);

        $this->actingAs($this->admin())
            ->get(route('admin.destinations.edit', $destination))
            ->assertOk()
            ->assertDontSee('DATA PERLU DIKONFIRMASI');
    }

    public function test_destination_with_activities_cannot_be_deleted(): void
    {
        $admin = $this->admin();
        $destination = $this->destination();
        $activity = $this->activity($destination);

        $this->actingAs($admin)
            ->from(route('admin.destinations.index'))
            ->delete(route('admin.destinations.destroy', $destination))
            ->assertRedirect(route('admin.destinations.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('destinations', ['id' => $destination->id]);
        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
    }

    public function test_empty_destination_can_be_deleted_and_cover_is_removed(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.destinations.store'), [
            'name' => 'Kosong',
            'cover_image' => UploadedFile::fake()->image('c.jpg'),
        ]);
        $destination = Destination::firstOrFail();
        $path = $destination->cover_image;

        $this->actingAs($admin)->delete(route('admin.destinations.destroy', $destination))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_destination_index_supports_search(): void
    {
        $this->destination('Sungai Cipelang', ['location' => 'Sukabumi']);
        $this->destination('Bukit Pinus', ['location' => 'Bandung']);

        $this->actingAs($this->admin())
            ->get(route('admin.destinations.index', ['q' => 'cipelang']))
            ->assertOk()
            ->assertSee('Sungai Cipelang')
            ->assertDontSee('Bukit Pinus');
    }

    // ----------------------------------------------------------- aktivitas

    public function test_admin_can_create_activity_with_unique_slug_and_flags(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $destination = $this->destination();

        $payload = $this->activityPayload($destination, [
            'is_featured' => '1',
            'cover_image' => UploadedFile::fake()->image('a.jpg'),
        ]);

        $this->actingAs($admin)->post(route('admin.activities.store'), $payload)
            ->assertRedirect(route('admin.activities.index'));
        // Nama sama dibuat dua kali: slug tidak boleh bentrok.
        $this->actingAs($admin)->post(route('admin.activities.store'), $this->activityPayload($destination))
            ->assertRedirect(route('admin.activities.index'));

        $this->assertSame(2, Activity::count());
        $this->assertEqualsCanonicalizing(['rafting-pemula', 'rafting-pemula-2'], Activity::pluck('slug')->all());

        $first = Activity::where('slug', 'rafting-pemula')->firstOrFail();
        $this->assertTrue($first->is_featured);
        $this->assertFalse($first->is_active, 'Checkbox Active yang tidak dicentang harus menjadi false.');
        Storage::disk('public')->assertExists($first->cover_image);
    }

    public function test_activity_validation_errors_are_returned(): void
    {
        $this->actingAs($this->admin())->post(route('admin.activities.store'), [
            'name' => '',
            'price' => -5,
            'capacity' => 0,
            'category_id' => 999,
            'destination_id' => 999,
        ])->assertSessionHasErrors(['name', 'price', 'capacity', 'category_id', 'destination_id', 'duration_minutes']);
    }

    public function test_updating_activity_replaces_old_cover_and_keeps_slug(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $destination = $this->destination();

        $this->actingAs($admin)->post(route('admin.activities.store'), $this->activityPayload($destination, [
            'is_active' => '1',
            'cover_image' => UploadedFile::fake()->image('old.jpg'),
        ]));
        $activity = Activity::firstOrFail();
        $oldCover = $activity->cover_image;

        $this->actingAs($admin)->put(route('admin.activities.update', $activity), $this->activityPayload($destination, [
            'name' => 'Rafting Lanjutan',
            'is_active' => '1',
            'cover_image' => UploadedFile::fake()->image('new.jpg'),
        ]))->assertRedirect(route('admin.activities.index'));

        $activity->refresh();
        $this->assertSame('rafting-pemula', $activity->slug);
        $this->assertSame('Rafting Lanjutan', $activity->name);
        Storage::disk('public')->assertMissing($oldCover);
        Storage::disk('public')->assertExists($activity->cover_image);
    }

    public function test_activity_with_booking_history_cannot_be_deleted(): void
    {
        $admin = $this->admin();
        $activity = $this->activity($this->destination());
        $user = User::factory()->create();

        $booking = Booking::create([
            'booking_code' => 'BM-2026-00001',
            'user_id' => $user->id,
            'booking_date' => now()->addDay()->toDateString(),
            'participants' => 2,
            'subtotal' => 150000,
            'total_price' => 150000,
            'status' => 'completed',
        ]);
        BookingItem::create([
            'booking_id' => $booking->id,
            'activity_id' => $activity->id,
            'quantity' => 2,
            'price' => 75000,
            'subtotal' => 150000,
        ]);

        $this->actingAs($admin)
            ->from(route('admin.activities.index'))
            ->delete(route('admin.activities.destroy', $activity))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
        $this->assertDatabaseHas('booking_items', ['activity_id' => $activity->id]);
    }

    public function test_activity_without_bookings_can_be_deleted_and_cover_is_removed(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $destination = $this->destination();

        $this->actingAs($admin)->post(route('admin.activities.store'), $this->activityPayload($destination, [
            'cover_image' => UploadedFile::fake()->image('a.jpg'),
        ]));
        $activity = Activity::firstOrFail();
        $path = $activity->cover_image;

        $this->actingAs($admin)->delete(route('admin.activities.destroy', $activity))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_activity_index_filters_by_search_status_and_destination(): void
    {
        $camp = $this->destination('Camp Ground');
        $river = $this->destination('Sungai Cipelang');

        $this->activity($camp, ['name' => 'Camping Malam']);
        $this->activity($river, ['name' => 'River Tubing']);
        $this->activity($river, ['name' => 'Arung Lama', 'is_active' => false]);

        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.activities.index', ['q' => 'tubing']))
            ->assertSee('River Tubing')->assertDontSee('Camping Malam');

        $this->actingAs($admin)->get(route('admin.activities.index', ['destination' => $river->id, 'status' => 'inactive']))
            ->assertSee('Arung Lama')->assertDontSee('River Tubing')->assertDontSee('Camping Malam');
    }

    public function test_activity_form_pages_render(): void
    {
        $admin = $this->admin();
        $activity = $this->activity($this->destination());

        $this->actingAs($admin)->get(route('admin.activities.create'))->assertOk();
        $this->actingAs($admin)->get(route('admin.activities.edit', $activity))->assertOk()->assertSee('Tubing Seru');
        $this->actingAs($admin)->get(route('admin.destinations.create'))->assertOk();
    }
}
