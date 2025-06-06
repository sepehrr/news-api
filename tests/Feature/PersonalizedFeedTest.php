<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersonalizedFeedTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category1;

    private Category $category2;

    private Author $author1;

    private Author $author2;

    private Source $source1;

    private Source $source2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->user = User::factory()->create();
        $this->category1 = Category::factory()->create();
        $this->category2 = Category::factory()->create();
        $this->author1 = Author::factory()->create();
        $this->author2 = Author::factory()->create();
        $this->source1 = Source::factory()->create();
        $this->source2 = Source::factory()->create();

        // Create articles with different combinations
        Article::factory()->create([
            'category_id' => $this->category1->id,
            'author_id' => $this->author1->id,
            'source_id' => $this->source1->id,
        ]);

        Article::factory()->create([
            'category_id' => $this->category2->id,
            'author_id' => $this->author2->id,
            'source_id' => $this->source2->id,
        ]);

        Article::factory()->create([
            'category_id' => $this->category1->id,
            'author_id' => $this->author2->id,
            'source_id' => $this->source1->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_access_personalized_feed()
    {
        $response = $this->getJson('/api/v1/personalized-feed');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_without_preferences_gets_all_articles()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/personalized-feed');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_authenticated_user_with_category_preference_gets_filtered_articles()
    {
        // Set category preference
        $this->user->preferences()->create([
            'preferable_type' => Category::class,
            'preferable_id' => $this->category1->id,
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/personalized-feed');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'published_at',
                        'category',
                        'author',
                        'source',
                    ]
                ],
                'links',
                'meta'
            ]);
    }

    public function test_authenticated_user_with_multiple_preferences_gets_filtered_articles()
    {
        // Set multiple preferences
        $this->user->preferences()->createMany([
            [
                'preferable_type' => Category::class,
                'preferable_id' => $this->category1->id,
            ],
            [
                'preferable_type' => Author::class,
                'preferable_id' => $this->author1->id,
            ],
            [
                'preferable_type' => Source::class,
                'preferable_id' => $this->source1->id,
            ],
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/personalized-feed');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    public function test_personalized_feed_respects_additional_filters()
    {
        // Set category preference
        $this->user->preferences()->create([
            'preferable_type' => Category::class,
            'preferable_id' => $this->category1->id,
        ]);
        Article::query()->delete();

        // Create articles in 10 days ago, 2 days ago, and 1 day ago
        $article = Article::factory()->create([
            'id' => 999,
            'category_id' => $this->category1->id,
            'published_at' => now()->subDays(10),
        ]);
        $article2 = Article::factory()->create([
            'category_id' => $this->category1->id,
            'published_at' => now()->subDays(2),
        ]);
        $article3 = Article::factory()->create([
            'category_id' => $this->category1->id,
            'published_at' => now()->subDays(1),
        ]);

        // Filter articles published in the last 5 days
        // Only article2 and article3 should be returned
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/personalized-feed?start_date=' . now()->subDays(5)->format('Y-m-d'));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonMissing(['id' => $article->id]);
    }

    public function test_personalized_feed_with_empty_preferences()
    {
        // Set empty preferences
        $this->user->preferences()->createMany([
            [
                'preferable_type' => Category::class,
                'preferable_id' => $this->category1->id,
            ],
            [
                'preferable_type' => Author::class,
                'preferable_id' => $this->author1->id,
            ],
        ]);

        // Update preferences to empty arrays
        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/preferences', [
                'categories' => [],
                'authors' => [],
                'sources' => [],
            ]);

        $response->assertStatus(200);

        // Check personalized feed
        $feedResponse = $this->actingAs($this->user)
            ->getJson('/api/v1/personalized-feed');

        $feedResponse->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
