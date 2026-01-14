<?php

// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Joueur;
use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        JWTTokenManagerInterface $jwtManager,
        LoggerInterface $logger,
        Request $request
    ): Response {
        // Si l'utilisateur est déjà connecté (GET), redirige vers sa page
        if ($this->getUser()) {
            $user = $this->getUser();
            if (!$user instanceof Joueur) {
                throw new \RuntimeException('L\'utilisateur connecté n\'est pas un Joueur.');
            }
            return $this->redirectToRoute('app_joueur_show', ['id' => $user->getId()]);
        }

        // Récupère l'erreur de login s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Si c'est une requête POST (soumission du formulaire)
        if ($request->isMethod('POST')) {
            // Vérifie si l'utilisateur est maintenant connecté
            if ($this->getUser()) {
                $user = $this->getUser();
                if (!$user instanceof Joueur) {
                    throw new \RuntimeException('L\'utilisateur connecté n\'est pas un Joueur.');
                }
                // Génère le JWT
                $token = $jwtManager->create($user);
                $logger->info('JWT généré : ' . $token);
                // Stocke le token dans un cookie
                $response = $this->redirectToRoute('app_joueur_show', ['id' => $user->getId()]);
                $response->headers->setCookie(new Cookie('BEARER', $token, time() + 3600, '/', null, true, false));
                $logger->info('Cookie BEARER ajouté à la réponse.');
                return $response;
            }
        }

        // Affiche le formulaire de login
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de Symfony.');
    }
}
