<?php

namespace App\Commands\Handlers;

use App\Commands\CreatePostCommand;
use App\Constants\PostStatus;
use App\Interfaces\CommandHandlerInterface;
use App\Models\Post;

class CreatePostHandler implements CommandHandlerInterface
{
    public function handle(CreatePostCommand $command): void
    {
        /** @var Post $post */
        $post = Post::query()->make([
            'name' => $command->getName(),
            'text' => $command->getText(),
            'status' => PostStatus::DRAFT,
        ]);

        $post->id = $command->getId();

        $post->save();
    }
}
