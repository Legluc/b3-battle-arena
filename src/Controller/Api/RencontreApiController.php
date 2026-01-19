<?php

namespace App\Controller\Api;

use App\Entity\Rencontre;
use App\Repository\RencontreRepository;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/rencontre')]
class RencontreApiController extends AbstractController
{
    // Récupérer tous les matchs
    #[Route('/', name: 'api_rencontre_index', methods: ['GET'])]
    public function index(RencontreRepository $rencontreRepository, SerializerInterface $serializer): JsonResponse
    {
        $rencontres = $rencontreRepository->findAll();
        $json = $serializer->serialize($rencontres, 'json', ['groups' => 'rencontre:read']);

        return new JsonResponse($json, 200, [], true);
    }

    // Récupérer un match
    #[Route('/{id}', name: 'api_rencontre_show', methods: ['GET'])]
    public function show(Rencontre $rencontre, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($rencontre, 'json', ['groups' => 'rencontre:read']);

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/', name: 'api_rencontre_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être connecté en tant qu\'administrateur pour effectuer cette action.')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        JoueurRepository $joueurRepository
    ): JsonResponse {
        // Désérialisation de la requête
        $data = json_decode($request->getContent(), true);

        // Récupération des joueurs depuis la base
        $joueur1 = $joueurRepository->find($data['joueur1']);
        $joueur2 = $joueurRepository->find($data['joueur2']);

        if (!$joueur1 || !$joueur2) {
            return new JsonResponse(['error' => 'Un ou plusieurs joueurs n\'existent pas.'], 404);
        }

        $gagnantId = $data['gagnant'] ?? null;
        $gagnant = $gagnantId ? $joueurRepository->find($gagnantId) : null; 

        // Création de la rencontre et association des joueurs
        $rencontre = new Rencontre();
        $rencontre->setJoueur1($joueur1);
        $rencontre->setJoueur2($joueur2);

        // Assignation du gagnant
        if ($gagnant && ($gagnant->getId() === $joueur1->getId() || $gagnant->getId() === $joueur2->getId())) {
            $rencontre->setGagnant($gagnant);
        } else {
            return new JsonResponse(['error' => 'Le gagnant spécifié n\'est pas valide.'], 400);
        }

        // Persist et flush
        $entityManager->persist($rencontre);
        $entityManager->flush();

        // Sérialisation de la réponse
        $json = $serializer->serialize($rencontre, 'json', ['groups' => 'rencontre:read']);

        return new JsonResponse($json, 201, [], true);
    }

    // Mettre à jour un match
    #[Route('/{id}', name: 'api_rencontre_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être connecté en tant qu\'administrateur pour effectuer cette action.')]
    public function update(
        Request $request,
        Rencontre $rencontre,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        JoueurRepository $joueurRepository
    ): JsonResponse {
        // 1. Récupérer les données envoyées
        $data = json_decode($request->getContent(), true);

        // 2. Récupérer les joueurs depuis la base
        $joueur1 = $joueurRepository->find($data['joueur1']);
        $joueur2 = $joueurRepository->find($data['joueur2']);
        $gagnant = isset($data['gagnant']) ? $joueurRepository->find($data['gagnant']) : null;

        // 3. Vérifier que les joueurs existent
        if (!$joueur1 || !$joueur2) {
            return new JsonResponse(['error' => 'Un ou plusieurs joueurs n\'existent pas.'], 404);
        }

        // 4. Mettre à jour la rencontre
        $rencontre->setJoueur1($joueur1);
        $rencontre->setJoueur2($joueur2);
        $rencontre->setGagnant($gagnant);

        // 5. Sauvegarder les modifications
        $entityManager->flush();

        // 6. Sérialiser et retourner la réponse
        $json = $serializer->serialize($rencontre, 'json', ['groups' => 'rencontre:read']);

        return new JsonResponse($json, 200, [], true);
    }

    // Supprimer un match
    #[Route('/{id}', name: 'api_rencontre_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être un connecté en tant que administrateur pour effectué cette action.')]
    public function delete(Rencontre $rencontre, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($rencontre);
        $entityManager->flush();

        return new JsonResponse(null, 204);
    }
}
