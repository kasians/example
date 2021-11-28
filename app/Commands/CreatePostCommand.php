<?php

namespace App\Commands;

use App\Interfaces\CommandInterface;

class CreatePostCommand implements CommandInterface
{
    public function __construct(
        private string $id,
        private string $name,
        private string $text
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
}
