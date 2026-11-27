<?php

namespace Tests\Feature;

use App\Models\ItProject;
use App\Models\News;
use App\Models\Publication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->travelTo(now()->startOfDay()->setDate(2026, 1, 15));
    }

    public function test_empty_homepage_exposes_expected_props(): void
    {
        $this->get('/')->assertOk()->assertInertia(fn (Assert $page) => $page
            ->component('Home')
            ->where('about', null)
            ->has('latestNews', 0)
            ->has('latestPublications', 0)
            ->has('latestProjects', 0)
            ->has('companyValues', 0)
            ->has('achievements', 0)
            ->where('siteSettings', [])
            ->where('siteSocialLinks', []));
    }

    public function test_only_published_and_due_content_including_null_dates_is_visible(): void
    {
        foreach ([News::class, Publication::class, ItProject::class] as $model) {
            $this->createItem($model, 'due', 'published', now()->subDay());
            $this->createItem($model, 'no-date', 'published', null);
            $this->createItem($model, 'draft', 'draft', now()->subDay());
            $this->createItem($model, 'future', 'published', now()->addDay());
        }

        $response = $this->get('/')->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
        foreach (['latestNews', 'latestPublications', 'latestProjects'] as $prop) {
            $slugs = array_column($response->inertiaProps($prop), 'slug');
            sort($slugs);
            $this->assertSame(['due', 'no-date'], $slugs);
        }
    }

    public function test_latest_content_is_ordered_and_limited_to_three_per_type(): void
    {
        foreach ([News::class, Publication::class, ItProject::class] as $model) {
            foreach (range(1, 4) as $age) {
                $this->createItem($model, 'item-'.$age, 'published', now()->subDays($age));
            }
        }

        $response = $this->get('/')->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('Home'));
        foreach (['latestNews', 'latestPublications', 'latestProjects'] as $prop) {
            $this->assertSame(['item-1', 'item-2', 'item-3'], array_column($response->inertiaProps($prop), 'slug'));
        }
    }

    private function createItem(string $model, string $slug, string $status, mixed $publishedAt): void
    {
        $attributes = ['title' => 'Contoh '.$slug, 'slug' => $slug, 'status' => $status, 'published_at' => $publishedAt];
        if ($model === News::class) {
            $attributes['content'] = '<p>Konten contoh.</p>';
        }
        $model::create($attributes);
    }
}
