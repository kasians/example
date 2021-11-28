<?php

namespace App\Commands;

use App\Interfaces\CommandInterface;

class DeletePostCommand implements CommandInterface
{
    public function __construct(private string $id)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }
}
