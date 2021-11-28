<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use DatabaseTransactions;

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
}
