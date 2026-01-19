<?php

// src/Controller/Api/ResultatApiController.php
namespace App\Controller\Api;

use App\Entity\Resultat;
use App\Repository\ResultatRepository;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\DTO\ResultatDTO;

#[Route('/api/resultat')]
class ResultatApiController extends AbstractController
{
    #[Route('/', name: 'api_resultat_index', methods: ['GET'])]
    public function index(ResultatRepository $resultatRepository, SerializerInterface $serializer): JsonResponse
    {
        $resultats = $resultatRepository->findAll();
        $data = $serializer->serialize($resultats, 'json', ['groups' => 'resultat:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_resultat_show', methods: ['GET'])]
    public function show(Resultat $resultat, SerializerInterface $serializer): JsonResponse
    {
        $data = $serializer->serialize($resultat, 'json', ['groups' => 'resultat:read']);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }


    #[Route('/', name: 'api_resultat_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        RencontreRepository $rencontreRepository
    ): JsonResponse {
        $dto = $serializer->deserialize($request->getContent(), ResultatDTO::class, 'json');

        // Validation
        $errors = $validator->validate($dto);
        if (count($errors) > 0) {
            // Gérer les erreurs...
        }

        // Conversion DTO → Entité
        $resultat = new Resultat();
        $resultat->setScoreJoueur1($dto->scoreJoueur1);
        $resultat->setScoreJoueur2($dto->scoreJoueur2);
        $rencontre = $rencontreRepository->find($dto->rencontreId);
        if (!$rencontre) {
            return new JsonResponse(['error' => 'La rencontre spécifiée n\'existe pas.'], Response::HTTP_NOT_FOUND);
        }
        $resultat->setRencontre($rencontre);

        // Persistance
        $entityManager->persist($resultat);
        $entityManager->flush();

        // Réponse
        $responseData = $serializer->serialize($resultat, 'json', ['groups' => 'resultat:read']);
        return new JsonResponse($responseData, Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}', name: 'api_resultat_update', methods: ['PUT'])]
    public function update(Request $request, Resultat $resultat, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Resultat::class, 'json', ['object_to_populate' => $resultat, 'groups' => 'resultat:write']);

        $entityManager->flush();

        $responseData = $serializer->serialize($resultat, 'json', ['groups' => 'resultat:read']);

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_resultat_delete', methods: ['DELETE'])]
    public function delete(Resultat $resultat, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($resultat);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
