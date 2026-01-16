<?php

namespace App\Controller;

use App\Entity\Rencontre;
use App\Form\RencontreType;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/rencontre')]
class RencontreController extends AbstractController
{
    // Liste des matchs
    #[Route('/', name: 'rencontre_index', methods: ['GET'])]
    public function index(RencontreRepository $rencontreRepository): Response
    {
        return $this->render('rencontre/index.html.twig', [
            'rencontres' => $rencontreRepository->findAll(),
        ]);
    }

    // Afficher un match
    #[Route('/{id}', name: 'rencontre_show', methods: ['GET'])]
    public function show(Rencontre $rencontre): Response
    {
        return $this->render('rencontre/show.html.twig', [
            'rencontre' => $rencontre,
        ]);
    }

    // Créer un match
    #[Route('/new', name: 'rencontre_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être un connecté en tant que administrateur pour effectué cette action.')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rencontre = new Rencontre();
        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rencontre);
            $entityManager->flush();

            return $this->redirectToRoute('rencontre_index');
        }

        return $this->render('rencontre/new.html.twig', [
            'rencontre' => $rencontre,
            'form' => $form->createView(),
        ]);
    }

    // Modifier un match
    #[Route('/{id}/edit', name: 'rencontre_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être un connecté en tant que administrateur pour effectué cette action.')]
    public function edit(Request $request, Rencontre $rencontre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('rencontre_index');
        }

        return $this->render('rencontre/edit.html.twig', [
            'rencontre' => $rencontre,
            'form' => $form->createView(),
        ]);
    }

    // Supprimer un match
    #[Route('/{id}', name: 'rencontre_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être un connecté en tant que administrateur pour effectué cette action.')]
    public function delete(Request $request, Rencontre $rencontre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rencontre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rencontre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rencontre_index');
    }
}
