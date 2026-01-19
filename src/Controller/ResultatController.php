<?php

namespace App\Controller;

use App\Entity\Resultat;
use App\Form\ResultatType;
use App\Repository\ResultatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/resultat')]
class ResultatController extends AbstractController
{
    #[Route('/', name: 'resultat_index', methods: ['GET'])]
    public function index(ResultatRepository $resultatRepository): Response
    {
        return $this->render('resultat/index.html.twig', [
            'resultats' => $resultatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'resultat_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être connecté en tant qu\'administrateur pour effectuer cette action.')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $resultat = new Resultat();
        $form = $this->createForm(ResultatType::class, $resultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($resultat);
            $entityManager->flush();

            return $this->redirectToRoute('resultat_show', ['id' => $resultat->getId()]);
        }

        return $this->render('resultat/new.html.twig', [
            'resultat' => $resultat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'resultat_show', methods: ['GET'])]
    public function show(Resultat $resultat): Response
    {
        return $this->render('resultat/show.html.twig', [
            'resultat' => $resultat,
        ]);
    }

    #[Route('/{id}/edit', name: 'resultat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Resultat $resultat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResultatType::class, $resultat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('resultat_show', ['id' => $resultat->getId()]);
        }

        return $this->render('resultat/edit.html.twig', [
            'resultat' => $resultat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'resultat_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être connecté en tant qu\'administrateur pour effectuer cette action.')]
    public function delete(Request $request, Resultat $resultat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$resultat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($resultat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('resultat_index', [], Response::HTTP_SEE_OTHER);
    }
}
