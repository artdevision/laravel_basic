<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    public function definition(): array
    {
        return [
            'title' => fake()->text(),
            'content' => fake()->realText(),
            'author_id' => User::factory()->create()->id,
        ];
    }
}
