<?php

namespace Tests\Feature\Commands\Handlers;

use App\Commands\Handlers\UpdatePostHandler;
use App\Commands\UpdatePostCommand;
use App\Constants\PostStatus;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdatePostCommandTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();
    }

    public function testPostUpdatedWithGivenAttributes(): void
    {
        /** @var Post $post */
        $post = Post::factory()->create();

        $command = new UpdatePostCommand(
            $post->id,
            $this->faker->text,
            $this->faker->text,
            PostStatus::ACTIVE
        );

        $handler = new UpdatePostHandler();
        $handler->handle($command);

        $this->assertDatabaseHas('posts', [
            'id' => $command->getId(),
            'name' => $command->getName(),
            'text' => $command->getText(),
            'status' => $command->getStatus(),
        ]);
    }
}
