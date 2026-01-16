<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Joueur;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $authenticationUtils,
        LoggerInterface $logger,
        Request $request,
        HttpClientInterface $httpClient
    ): Response {

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

                // Appelle la route API /api/login pour générer le token JWT
                try {
                    $response = $httpClient->request('POST', '/api/login', [
                        'json' => [
                            'mail' => $user->getUserIdentifier(),
                            'password' => $request->request->get('password'), // Attention : à utiliser avec précaution
                        ],
                    ]);

                    $data = $response->toArray();
                    $token = $data['token'];

                    $logger->info('JWT généré via /api/login : ' . $token);

                    // Crée une réponse de redirection avec le cookie
                    $response = $this->redirectToRoute('app_joueur_show', ['id' => $user->getId()]);
                    $response->headers->setCookie(new Cookie(
                        'BEARER',
                        $token,
                        time() + 3600, // 1 heure
                        '/',
                        null,
                        true,  // HttpOnly
                        true   // Secure (nécessite HTTPS)
                    ));

                    $logger->info('Cookie BEARER ajouté à la réponse.');
                    return $response;
                } catch (\Exception $e) {
                    $logger->error('Erreur lors de la génération du token JWT : ' . $e->getMessage());
                    // Gère l'erreur (ex : affiche un message à l'utilisateur)
                }
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
