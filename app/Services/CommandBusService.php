<?php

namespace App\Services;

use App\Commands\CreatePostCommand;
use App\Commands\Handlers\CreatePostHandler;
use App\Commands\UpdatePostCommand;
use App\Commands\Handlers\UpdatePostHandler;
use App\Exceptions\CommandHandlerNotFoundException;
use App\Interfaces\CommandInterface;
use App\Interfaces\CommandBusInterface;
use App\Interfaces\CommandHandlerInterface;

class CommandBusService implements CommandBusInterface
{
    private const HANDLERS = [
        CreatePostCommand::class => CreatePostHandler::class,
        UpdatePostCommand::class => UpdatePostHandler::class,
    ];

    /**
     * @throws CommandHandlerNotFoundException
     */
    public function handle(CommandInterface $command): void
    {
        $handler = $this->resolveHandler($command);

        $handler->handle($command);
    }

    /**
     * @throws CommandHandlerNotFoundException
     */
    private function resolveHandler($command): CommandHandlerInterface
    {
        if (!isset(self::HANDLERS[get_class($command)])) {
            throw new CommandHandlerNotFoundException();
        }

        return app(self::HANDLERS[get_class($command)]);
    }
}
