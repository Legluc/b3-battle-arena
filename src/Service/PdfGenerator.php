<?php 

namespace App\Service;

use Dompdf\Dompdf;
use Twig\Environment;

class PdfGenerator
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdf(array $matches): string
    {
        $html = $this->twig->render('resultat/index.html.twig', ['matches' => $matches]);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }
}