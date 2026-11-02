<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_package_index_renders_with_packages(): void
    {
        Package::create([
            'name' => 'Weekend Escape',
            'slug' => 'weekend-escape',
            'target_segment' => 'Keluarga',
            'description' => 'Paket akhir pekan untuk keluarga.',
            'base_price' => 250000,
            'min_participants' => 2,
            'max_participants' => 20,
            'estimated_duration' => 2,
            'is_active' => true,
        ]);

        $this->get(route('packages.index'))
            ->assertOk()
            ->assertSee('Weekend Escape');
    }

    public function test_package_detail_renders(): void
    {
        $package = Package::create([
            'name' => 'Team Adventure',
            'slug' => 'team-adventure',
            'target_segment' => 'Perusahaan',
            'description' => 'Paket outbound untuk tim.',
            'base_price' => 400000,
            'min_participants' => 10,
            'max_participants' => 50,
            'estimated_duration' => 4,
            'is_active' => true,
        ]);

        $this->get(route('packages.show', $package))
            ->assertOk()
            ->assertSee('Team Adventure');
    }

    public function test_activity_index_hides_rating_filter_while_there_are_no_reviews(): void
    {
        $this->get(route('activities.index'))
            ->assertOk()
            ->assertDontSee('name="rating"', false)
            ->assertDontSee('Rating tertinggi');
    }

    public function test_article_placeholder_is_replaced_with_an_honest_note(): void
    {
        $article = Article::create([
            'author_id' => User::factory()->create()->id,
            'title' => 'Panduan Tubing',
            'slug' => 'panduan-tubing',
            'excerpt' => 'Panduan singkat untuk pemula.',
            'content' => '[DATA PERLU DIKONFIRMASI] Isi artikel belum tersedia.',
            'reading_time' => 3,
            'is_published' => true,
        ]);

        $this->get(route('articles.show', $article))
            ->assertOk()
            ->assertSee('Konten artikel sedang dilengkapi.')
            ->assertDontSee('[DATA PERLU DIKONFIRMASI]');
    }
}
