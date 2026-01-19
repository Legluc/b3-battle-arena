<?php

namespace App\Message;

class GeneratorPdfMessage
{
    public function __construct(
        public readonly int $joueurId,
        public readonly string $joueurEmail
    ) {}
}
