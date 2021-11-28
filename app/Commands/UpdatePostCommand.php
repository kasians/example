<?php

namespace App\Commands;

use App\Interfaces\CommandInterface;

class UpdatePostCommand implements CommandInterface
{
    public function __construct(
        private string $id,
        private string $name,
        private string $text,
        private string $status
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
