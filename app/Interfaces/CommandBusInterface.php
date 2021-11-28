<?php

namespace App\Interfaces;

use App\Exceptions\CommandHandlerNotFoundException;

interface CommandBusInterface
{
    /**
     * @throws CommandHandlerNotFoundException
     */
    public function handle(CommandInterface $command): void;
}
