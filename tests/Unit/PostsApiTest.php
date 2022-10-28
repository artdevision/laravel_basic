<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Tests\TestCase;

class PostsApiTest extends TestCase
{
    protected Generator $faker;

    private static ?int $postId = null;

    private static ?User $user = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function test_posts_create(): void
    {
        static::$user = User::factory()->create();

        $postData = [
            'title' => $this->faker->text(),
            'content' => $this->faker->realText(1000),
            'author_id' => static::$user->id,
        ];

        // Test not auth user
        $response = $this->postJson('/api/posts/create', $postData);
        $this->assertEquals($response->getStatusCode(), 401);

        // Test auth user
        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/posts/create', $postData);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', $response->json());

        static::$postId = (int) $response->json('data')['id'];
    }

    public function test_posts_update(): void
    {
        $postData = [
            'title' => $this->faker->text(),
            'content' => $this->faker->realText(1000),
            'author_id' => static::$user->id,
        ];

        // Test not auth user
        $response = $this->postJson('/api/posts/' . static::$postId, $postData);
        $this->assertEquals($response->getStatusCode(), 401);

        // Test Request validation
        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/posts/' . static::$postId, array_merge($postData, ['author_id' => 11000]));

        $this->assertEquals($response->getStatusCode(), 422);
        $this->assertArrayHasKey('errors', $response->json());

        // Test save Post
        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/posts/' . static::$postId, $postData);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', $response->json());

        // Test not found
        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/posts/' . 10000, $postData);

        $this->assertEquals($response->getStatusCode(), 404);

        // Test Update for not author
        $user = User::factory()->create();

        /** @var Post $post */
        $post = Post::create([
            'title' => $this->faker->text(),
            'content' => $this->faker->realText(1000),
            'author_id' => $user->id,
        ]);

        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/posts/' . $post->id, $postData);

        $this->assertEquals($response->getStatusCode(), 403);

        $post->delete();
        $user->delete();

    }

    public function test_posts_list(): void
    {
        // Test not auth user
        $response = $this->getJson('/api/posts');
        $this->assertEquals($response->getStatusCode(), 401);

        // Test auth user
        $response = $this
            ->actingAs(static::$user)
            ->getJson('/api/posts');

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('meta', $response->json());
    }

    public function test_posts_delete(): void
    {
        // Test not auth user
        $response = $this->deleteJson('/api/posts/' . static::$postId);
        $this->assertEquals($response->getStatusCode(), 401);

        // Test auth user
        $response = $this
            ->actingAs(static::$user)
            ->deleteJson('/api/posts/' . static::$postId);
        $this->assertEquals($response->getStatusCode(), 204);

        // Test for not author
        $user = User::factory()->create();

        /** @var Post $post */
        $post = Post::create([
            'title' => $this->faker->text(),
            'content' => $this->faker->realText(1000),
            'author_id' => $user->id,
        ]);

        $response = $this
            ->actingAs(static::$user)
            ->deleteJson('/api/posts/' . $post->id);

        $this->assertEquals($response->getStatusCode(), 403);

        $post->delete();
        $user->delete();
    }
}
