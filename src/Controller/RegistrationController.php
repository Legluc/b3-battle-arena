<?php
// src/Controller/RegistrationController.php
namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash du mot de passe
            $joueur->setPassword(
                $passwordHasher->hashPassword($joueur, $form->get('password')->getData())
            );

            $entityManager->persist($joueur);
            $entityManager->flush();

            // Redirection obligatoire pour Turbo
            return $this->redirectToRoute('app_login');
        }

        return $this->render('joueur/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
