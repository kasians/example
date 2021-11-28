<?php

namespace Tests\Feature\Api;

use App\Constants\PostStatus;
use App\Models\Post;
use Generator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();
    }

    public function testGetList(): void
    {
        Post::factory()->create();

        $response = $this->json('GET', '/api/posts');

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'text',
                'status',
                'createdAt',
                'updatedAt',
            ],
        ]);
    }

    public function testGet(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->json('GET', '/api/posts/' . $post->id);

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'name',
            'text',
            'status',
            'createdAt',
            'updatedAt',
        ]);
    }

    public function testGetValidation(): void
    {
        foreach ($this->postGetRequestValidationProvider() as [$id, $validationErrors]) {
            $response = $this->json('GET', '/api/posts/' . $id);

            $response->assertUnprocessable();
            $response->assertJsonValidationErrors($validationErrors);
        }
    }

    private function postGetRequestValidationProvider(): Generator
    {
        yield [
            'invalid-uuid',
            ['id' => 'The id must be a valid UUID.'],
        ];

        yield [
            $this->faker->uuid,
            ['id' => 'The selected id is invalid.'],
        ];
    }

    public function testCreate(): void
    {
        $response = $this->json('POST', '/api/posts', [
            'name' => $this->faker->text,
            'text' => $this->faker->text,
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'id',
            'name',
            'text',
            'status',
            'createdAt',
            'updatedAt',
        ]);
    }

    public function testCreateValidation(): void
    {
        foreach ($this->postCreateRequestValidationProvider() as [$data, $validationErrors]) {
            $response = $this->json('POST', '/api/posts', $data);

            $response->assertUnprocessable();
            $response->assertJsonValidationErrors($validationErrors);
        }
    }

    private function postCreateRequestValidationProvider(): Generator
    {
        yield [
            [],
            [
                'name' => 'The name field is required.',
                'text' => 'The text field is required.',
            ],
        ];

        yield [
            [
                'name' => $this->faker->randomNumber(),
                'text' => $this->faker->randomNumber(),
            ],
            [
                'name' => 'The name must be a string.',
                'text' => 'The text must be a string.',
            ],
        ];
    }

    public function testUpdate(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->json('PUT', '/api/posts/' . $post->id, [
            'name' => $this->faker->text,
            'text' => $this->faker->text,
            'status' => PostStatus::ACTIVE,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'name',
            'text',
            'status',
            'createdAt',
            'updatedAt',
        ]);
    }

    public function testUpdateValidation(): void
    {
        foreach ($this->postUpdateRequestValidationProvider() as [$id, $data, $validationErrors]) {
            $response = $this->json('PUT', '/api/posts/' . $id, $data);

            $response->assertUnprocessable();
            $response->assertJsonValidationErrors($validationErrors);
        }
    }

    private function postUpdateRequestValidationProvider(): Generator
    {
        yield [
            'invalid-uuid',
            [],
            [
                'id' => 'The id must be a valid UUID.',
                'name' => 'The name field is required.',
                'text' => 'The text field is required.',
                'status' => 'The status field is required.',
            ],
        ];

        yield [
            $this->faker->uuid,
            [],
            [
                'id' => 'The selected id is invalid.',
                'name' => 'The name field is required.',
                'text' => 'The text field is required.',
                'status' => 'The status field is required.',
            ],
        ];

        /** @var Post $post */
        $post = Post::factory()->create();

        yield [
            $post->id,
            [],
            [
                'name' => 'The name field is required.',
                'text' => 'The text field is required.',
                'status' => 'The status field is required.',
            ],
        ];

        yield [
            $post->id,
            [
                'name' => $this->faker->randomNumber(),
                'text' => $this->faker->randomNumber(),
                'status' => $this->faker->randomNumber(),
            ],
            [
                'name' => 'The name must be a string.',
                'text' => 'The text must be a string.',
                'status' => 'The status must be a string.',
            ],
        ];

        yield [
            $post->id,
            ['status' => 'invalid-status'],
            [
                'name' => 'The name field is required.',
                'text' => 'The text field is required.',
                'status' => 'The selected status is invalid.',
            ],
        ];
    }

    public function testDelete(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $response = $this->json('DELETE', '/api/posts/' . $post->id);

        $response->assertOk();
        $response->assertExactJson(['ok']);
    }

    public function testDeleteValidation(): void
    {
        foreach ($this->postDeleteRequestValidationProvider() as [$id, $validationErrors]) {
            $response = $this->json('DELETE', '/api/posts/' . $id);

            $response->assertUnprocessable();
            $response->assertJsonValidationErrors($validationErrors);
        }
    }

    private function postDeleteRequestValidationProvider(): Generator
    {
        yield [
            'invalid-uuid',
            ['id' => 'The id must be a valid UUID.'],
        ];

        yield [
            $this->faker->uuid,
            ['id' => 'The selected id is invalid.'],
        ];
    }
}
