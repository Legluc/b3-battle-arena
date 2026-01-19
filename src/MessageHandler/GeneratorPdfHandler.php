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
        private EntityManagerInterface $entityManager,
        private PdfGenerator $pdfService,
    ) {}

    public function __invoke(GeneratorPdfMessage $message)
    {
        // Récupérer les rencontres depuis la base de données
        $rencontres = $this->entityManager
            ->getRepository('App\Entity\Rencontre')
            ->findAll();

        // Générer le PDF
        $filename = $this->pdfService->generatorPdf($rencontres);

    }
}
