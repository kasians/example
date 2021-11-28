<?php

namespace App\Services;

use App\Exceptions\CommandHandlerNotFoundException;
use App\Interfaces\CommandInterface;
use App\Interfaces\CommandBusInterface;
use App\Interfaces\CommandHandlerInterface;

class CommandBusService implements CommandBusInterface
{
    private const HANDLERS = [];

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
