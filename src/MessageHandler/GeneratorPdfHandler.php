<?php

namespace App\MessageHandler;

use App\Message\GeneratorPdfMessage;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GeneratorPdfHandler
{
    public function __construct(
        private PdfGenerator $pdfService,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(GeneratorPdfMessage $message): void
    {
        // Récupère le lien du PDF depuis le message
        $pdfLink = $message->pdfLink;


    }
}
