<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Faker\Factory;
use Faker\Generator;
use Tests\TestCase;

class CommentsApiTest extends TestCase
{
    protected Generator $faker;

    protected static ?User $user = null;

    protected static ?Post $post = null;

    protected static ?int $commentId = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function test_comments_create(): void
    {
        static::$user = User::factory()->create();

        static::$post = Post::factory()->create([
            'title' => $this->faker->text(),
            'content' => $this->faker->realText(1000),
            'author_id' => static::$user->id,
        ]);

        $commentData = [
            'comment' => $this->faker->realText(1000),
            'post_id' => static::$post->id,
            'author_id' => static::$user->id,
        ];

        // Test not auth user
        $response = $this->postJson(sprintf('/api/posts/%d/comments', static::$post->id), $commentData);
        $this->assertEquals($response->getStatusCode(), 401);

        // Test Request validation
        $response = $this
            ->actingAs(static::$user)
            ->postJson(
                sprintf('/api/posts/%d/comments', static::$post->id),
                array_merge($commentData, ['author_id' => 11000])
            );

        $this->assertEquals($response->getStatusCode(), 422);
        $this->assertArrayHasKey('errors', $response->json());

        // Test save Comment
        $response = $this
            ->actingAs(static::$user)
            ->postJson(sprintf('/api/posts/%d/comments', static::$post->id), $commentData);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', $response->json());

        static::$commentId = (int) $response->json('data')['id'];
    }

    /**
     * @depends test_comments_create
     */
    public function test_comments_list(): void
    {
        // Test not auth user
        $response = $this->getJson(sprintf('/api/posts/%d/comments', static::$post->id));
        $this->assertEquals($response->getStatusCode(), 401);

        // Test auth user
        $response = $this
            ->actingAs(static::$user)
            ->getJson(sprintf('/api/posts/%d/comments', static::$post->id));

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('meta', $response->json());
    }

    /**
     * @depends test_comments_create
     */
    public function test_comments_update(): void
    {
        $commentData = [
            'comment' => $this->faker->realText(1000),
            'post_id' => static::$post->id,
            'author_id' => static::$user->id,
        ];

        // Test not auth user
        $response = $this->postJson('/api/comments/' . static::$commentId, $commentData);
        $this->assertEquals($response->getStatusCode(), 401);

        // Test Request validation
        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/comments/' . static::$commentId, array_merge($commentData, ['author_id' => 11000]));

        $this->assertEquals($response->getStatusCode(), 422);
        $this->assertArrayHasKey('errors', $response->json());

        // Test save Comment
        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/comments/' . static::$commentId, $commentData);

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
        $this->assertArrayHasKey('data', $response->json());

        // Test not found
        $response = $this
            ->actingAs(static::$user)
            ->postJson('/api/comments/' . 10000, $commentData);

        $this->assertEquals($response->getStatusCode(), 404);

        // Test Update for not author
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/comments/' . static::$commentId, $commentData);

        $this->assertEquals($response->getStatusCode(), 403);

        $user->delete();

    }

    /**
     * @depends test_comments_create
     */
    public function test_comments_delete(): void
    {
        // Test not auth user
        $response = $this->deleteJson('/api/comments/' . static::$commentId);
        $this->assertEquals($response->getStatusCode(), 401);

        // Test for not author
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->deleteJson('/api/comments/' . static::$commentId);

        $this->assertEquals($response->getStatusCode(), 403);

        $user->delete();

        // Test auth user
        $response = $this
            ->actingAs(static::$user)
            ->deleteJson('/api/comments/' . static::$commentId);
        $this->assertEquals($response->getStatusCode(), 204);
    }
}
