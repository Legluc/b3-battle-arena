<?php
namespace App\Controller\Api;

use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

#[Route('/api/token/refresh', name: 'api_token_refresh', methods: ['POST'])]
class RefreshTokenController extends AbstractController
{
    public function __construct(
        private RefreshTokenManagerInterface $refreshTokenManager,
        private JWTTokenManagerInterface $jwtManager,
        private UserProviderInterface $userProvider
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        // Récupérer le refresh_token depuis le body JSON ou form-data
        $refreshTokenString = $request->request->get('refresh_token') ?? json_decode($request->getContent(), true)['refresh_token'] ?? null;

        if (!$refreshTokenString) {
            return $this->json(['error' => 'Refresh token missing.'], 400);
        }

        // Récupérer le refresh token depuis la base de données
        $refreshToken = $this->refreshTokenManager->get($refreshTokenString);
        if (!$refreshToken || !$refreshToken->isValid()) {
            return $this->json(['error' => 'Invalid refresh token.'], 401);
        }

        // Récupérer l'utilisateur
        $username = $refreshToken->getUsername();
        $user = $this->userProvider->loadUserByIdentifier($username);

        // Générer un nouveau JWT
        $newToken = $this->jwtManager->create($user);

        return $this->json(['token' => $newToken]);
    }
}