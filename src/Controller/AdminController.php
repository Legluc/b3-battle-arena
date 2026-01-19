<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Message\GeneratorPdfMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin/matches/recap/pdf', name: 'admin_matches_recap_pdf')]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être connecté en tant qu\'administrateur pour effectuer cette action.')]
    public function generatePdf(MessageBusInterface $bus): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer cette action.');
        }

        if (!$user instanceof Joueur) {
            throw new \RuntimeException('L\'utilisateur connecté n\'est pas un Joueur.');
        }

        $matches = $this->getMatches();

        $bus->dispatch(new GeneratorPdfMessage($user->getId(), $matches));

        $this->addFlash('success', 'La génération du PDF est en cours. Vous serez notifié une fois terminé.');
        return $this->redirectToRoute('resultat_index');
    }


    #[Route('/admin/matches/recap/pdf/download/{filename}', name: 'admin_matches_recap_pdf_download')]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être connecté en tant qu\'administrateur pour effectuer cette action.')]
    public function downloadPdf(string $filename): Response
    {
        $filePath = $this->getParameter('pdf_directory').'/'.$filename;
        return $this->file($filePath);
    }


    private function getMatches(): array
    {
        // Logique pour récupérer les matchs
        return [];
    }
}
