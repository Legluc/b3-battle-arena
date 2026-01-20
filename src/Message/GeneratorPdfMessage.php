<?php

namespace App\Message;

class GeneratorPdfMessage
{
    public function __construct(
        public readonly string $pdfLink
    ) {}
}
