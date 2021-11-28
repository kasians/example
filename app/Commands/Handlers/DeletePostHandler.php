<?php

namespace App\Commands\Handlers;

use App\Commands\DeletePostCommand;
use App\Interfaces\CommandHandlerInterface;
use App\Models\Post;

class DeletePostHandler implements CommandHandlerInterface
{
    public function handle(DeletePostCommand $command): void
    {
        Post::destroy($command->getId());
    }
}
