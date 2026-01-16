<?php

namespace App\Controller\Api;

use App\Entity\Rencontre;
use App\Repository\RencontreRepository;
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

    // Créer un match
    #[Route('/', name: 'api_rencontre_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être un connecté en tant que administrateur pour effectué cette action.')]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $rencontre = $serializer->deserialize($request->getContent(), Rencontre::class, 'json');
        $entityManager->persist($rencontre);
        $entityManager->flush();

        $json = $serializer->serialize($rencontre, 'json', ['groups' => 'rencontre:read']);

        return new JsonResponse($json, 201, [], true);
    }

    // Mettre à jour un match
    #[Route('/{id}', name: 'api_rencontre_update', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous devez être un connecté en tant que administrateur pour effectué cette action.')]
    public function update(Request $request, Rencontre $rencontre, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $rencontre = $serializer->deserialize($request->getContent(), Rencontre::class, 'json', ['object_to_populate' => $rencontre]);
        $entityManager->flush();

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
