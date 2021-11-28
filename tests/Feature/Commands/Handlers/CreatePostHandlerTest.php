<?php

namespace Tests\Feature\Commands\Handlers;

use App\Commands\CreatePostCommand;
use App\Commands\Handlers\CreatePostHandler;
use App\Constants\PostStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class CreatePostHandlerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpFaker();
    }

    public function testPostCreated(): void
    {
        $command = new CreatePostCommand(
            Uuid::uuid4(),
            $this->faker->text,
            $this->faker->text
        );

        $handler = new CreatePostHandler();
        $handler->handle($command);

        $this->assertDatabaseHas('posts', ['id' => $command->getId()]);
    }

    /**
     * @depends testPostCreated
     */
    public function testPostCreatedWithGivenAttributes(): void
    {
        $command = new CreatePostCommand(
            Uuid::uuid4(),
            $this->faker->text,
            $this->faker->text
        );

        $handler = new CreatePostHandler();
        $handler->handle($command);

        $this->assertDatabaseHas('posts', [
            'id' => $command->getId(),
            'name' => $command->getName(),
            'text' => $command->getText(),
        ]);
    }

    /**
     * @depends testPostCreated
     */
    public function testPostCreatedWithDraftStatus(): void
    {
        $command = new CreatePostCommand(
            Uuid::uuid4(),
            $this->faker->text,
            $this->faker->text
        );

        $handler = new CreatePostHandler();
        $handler->handle($command);

        $this->assertDatabaseHas('posts', [
            'id' => $command->getId(),
            'status' => PostStatus::DRAFT,
        ]);
    }
}
