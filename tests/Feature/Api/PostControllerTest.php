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
}
