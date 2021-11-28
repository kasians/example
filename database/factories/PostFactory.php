<?php

namespace Database\Factories;

use App\Constants\PostStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->text,
            'text' => $this->faker->text,
            'status' => $this->faker->randomElement([PostStatus::DRAFT, PostStatus::ACTIVE, PostStatus::BLOCKED]),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }

    public function draft(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PostStatus::DRAFT,
            ];
        });
    }

    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PostStatus::ACTIVE,
            ];
        });
    }

    public function blocked(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => PostStatus::BLOCKED,
            ];
        });
    }
}
