<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UsersApiTest extends TestCase
{
    public static User $user;

    public function test_get_current_user(): void
    {
        $response = $this->getJson('/api/user');
        $this->assertEquals($response->getStatusCode(), 401);

        static::$user = User::factory()->create();
        $response = $this->actingAs(static::$user)->getJson('/api/user');

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
    }

    public function test_get_users_list(): void
    {
        $response = $this->getJson('/api/users');
        $this->assertEquals($response->getStatusCode(), 401);

        $response = $this->actingAs(static::$user)
            ->json('GET', '/api/users');

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertJson($response->content());
    }
}
