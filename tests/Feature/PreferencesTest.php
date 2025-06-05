<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PreferencesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private array $categories;

    private array $authors;

    private array $sources;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create();

        // Create test data
        $this->categories = Category::factory()->count(3)->create()->toArray();
        $this->authors = Author::factory()->count(3)->create()->toArray();
        $this->sources = Source::factory()->count(3)->create()->toArray();
    }

    public function test_user_can_get_preferences(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson('/api/v1/preferences');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'categories',
                    'authors',
                    'sources'
                ]
            ]);
    }

    public function test_user_can_update_preferences(): void
    {
        $payload = [
            'categories' => array_column($this->categories, 'id'),
            'authors' => array_column($this->authors, 'id'),
            'sources' => array_column($this->sources, 'id'),
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/preferences', $payload);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'categories',
                    'authors',
                    'sources'
                ]
            ]);

        // Verify preferences were saved
        $this->assertDatabaseHas('preferences', [
            'user_id' => $this->user->id,
            'preferable_id' => $this->categories[0]['id'],
            'preferable_type' => Category::class,
        ]);

        $this->assertDatabaseHas('preferences', [
            'user_id' => $this->user->id,
            'preferable_id' => $this->authors[0]['id'],
            'preferable_type' => Author::class,
        ]);

        $this->assertDatabaseHas('preferences', [
            'user_id' => $this->user->id,
            'preferable_id' => $this->sources[0]['id'],
            'preferable_type' => Source::class,
        ]);
    }

    public function test_user_cannot_update_preferences_with_invalid_category(): void
    {
        $payload = [
            'categories' => [999], // Non-existent category
            'authors' => array_column($this->authors, 'id'),
            'sources' => array_column($this->sources, 'id'),
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/preferences', $payload);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['categories.0']);
    }

    public function test_unauthenticated_user_cannot_access_preferences(): void
    {
        $response = $this->getJson('/api/v1/preferences');
        $response->assertUnauthorized();

        $response = $this->postJson('/api/v1/preferences', []);
        $response->assertUnauthorized();
    }

    public function test_user_can_update_preferences_with_empty_arrays(): void
    {
        $payload = [
            'categories' => [],
            'authors' => [],
            'sources' => [],
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/v1/preferences', $payload);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'categories',
                    'authors',
                    'sources'
                ]
            ]);

        // Verify no preferences exist
        $this->assertDatabaseMissing('preferences', [
            'user_id' => $this->user->id,
        ]);
    }
}
