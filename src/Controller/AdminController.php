<?php

namespace App\Controller;

use App\Entity\Rencontre;
use App\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\GeneratorPdfMessage;
use App\Repository\RencontreRepository;

class AdminController extends AbstractController
{
    #[Route('/admin/recap/pdf', name: 'admin_recap_pdf')]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être connecté en tant qu\'administrateur pour effectuer cette action.')]
    public function generatePdf(
        PdfGenerator $pdfService,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
        RencontreRepository $rencontreRepository
    ): Response {
        $rencontres = $rencontreRepository->findAll();

        $pdfPath = $pdfService->generatorPdf($rencontres);

        if ($pdfPath) {

            $this->addFlash('success', 'PDF généré avec succès');
        } else {
            $this->addFlash('error', 'Échec lors de la génération du PDF');
        }

        return $this->redirectToRoute('rencontre_index');
    }
}
