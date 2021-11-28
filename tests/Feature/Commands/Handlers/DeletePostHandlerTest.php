<?php

namespace Tests\Feature\Commands\Handlers;

use App\Commands\DeletePostCommand;
use App\Commands\Handlers\DeletePostHandler;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePostHandlerTest extends TestCase
{
    use RefreshDatabase;

    public function testPostDeleted(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $command = new DeletePostCommand(
            $post->id,
        );

        $handler = new DeletePostHandler();
        $handler->handle($command);

        $this->assertDatabaseMissing('posts', ['id' => $command->getId()]);
    }
}
