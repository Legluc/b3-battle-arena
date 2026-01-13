<?php

namespace App\Controller\Api;

use App\Entity\Joueur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/joueurs', name: 'api_joueur_')]
class JoueurApiController extends AbstractController
{
    // Lister tous les joueurs (GET)
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $joueurs = $entityManager->getRepository(Joueur::class)->findAll();
        $json = $serializer->serialize($joueurs, 'json', ['groups' => 'joueur:read']);
        return new JsonResponse($json, 200, [], true);
    }

    // Créer un nouveau joueur (POST)
    #[Route('', name: 'new', methods: ['POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        try {
            $json = $request->getContent();
            /** @var Joueur $joueur */
            $joueur = $serializer->deserialize($json, Joueur::class, 'json');

            // Hachage du mot de passe
            $plainPassword = $request->toArray()['password'] ?? null;
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword($joueur, $plainPassword);
                $joueur->setPassword($hashedPassword);
            }

            $entityManager->persist($joueur);
            $entityManager->flush();

            return new JsonResponse(
                $serializer->serialize($joueur, 'json', ['groups' => 'joueur:read']),
                201,
                [],
                true
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                400
            );
        }
    }

    // Afficher un joueur (GET)
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Joueur $joueur, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($joueur, 'json', ['groups' => 'joueur:read']);
        return new JsonResponse($json, 200, [], true);
    }

    // Modifier un joueur (PUT)
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(Request $request, Joueur $joueur, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $json = $request->getContent();
        $serializer->deserialize($json, Joueur::class, 'json', ['object_to_populate' => $joueur, 'groups' => 'joueur:write']);

        $entityManager->flush();

        return new JsonResponse($serializer->serialize($joueur, 'json', ['groups' => 'joueur:read']), 200, [], true);
    }

    // Supprimer un joueur (DELETE)
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Joueur $joueur, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($joueur);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
