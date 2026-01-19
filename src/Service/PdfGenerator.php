<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use App\Entity\Rencontre;
use Doctrine\ORM\EntityManagerInterface;

class PdfGenerator
{
    private $twig;
    private $pdfDirectory;
    private $entityManager;

    public function __construct(Environment $twig, string $projectDir, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->pdfDirectory = $projectDir . '/public/uploads/pdfs/';
        $this->entityManager = $entityManager;
    }

    public function generatorPdf(array $rencontres): string
    {
        // Créer un nom de fichier unique
        $filename = 'recap_matches_' . time() . '.pdf';
        $filePath = $this->pdfDirectory . $filename;

        // Configurer Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Rendre le template Twig avec les données des rencontres
        $html = $this->twig->render('pdf/matches_recap.html.twig', ['rencontres' => $rencontres]);

        // Générer le PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Sauvegarder le PDF
        file_put_contents($filePath, $dompdf->output());

        return $filename;
    }
}
