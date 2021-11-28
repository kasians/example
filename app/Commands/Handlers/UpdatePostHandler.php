<?php

namespace App\Commands\Handlers;

use App\Commands\UpdatePostCommand;
use App\Interfaces\CommandHandlerInterface;
use App\Models\Post;

class UpdatePostHandler implements CommandHandlerInterface
{
    public function handle(UpdatePostCommand $command): void
    {
        /** @var Post $post */
        $post = Post::query()->find($command->getId());

        $post->name = $command->getName();
        $post->text = $command->getText();
        $post->status = $command->getStatus();

        $post->save();
    }
}
