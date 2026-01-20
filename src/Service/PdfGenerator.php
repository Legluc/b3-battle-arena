<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class PdfGenerator
{
    private string $pdfDirectory;

    public function __construct(private Environment $twig, string $projectDir)
    {
        $this->pdfDirectory = $projectDir . '/public/uploads/pdfs/';
    }

    

    public function generatorPdf(array  $rencontres): string
    {
            $path = $this->pdfDirectory . '/' . 'matches_recap_' . time() . '.pdf';
            $options = new Options();
            $options->set('defaultFont', 'Arial');
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            
            
        try {
            $dompdf = new Dompdf($options);
            $html = $this->twig->render('pdf/matches_recap.html.twig', ['rencontres' => $rencontres]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            file_put_contents($path, $dompdf->output());
            return $path;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return "ça n'a pas marché";
        }
    }
}
        


