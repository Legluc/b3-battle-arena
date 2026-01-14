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
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): Response {
        $joueur = new Joueur();
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $joueur->setPassword(
                $passwordHasher->hashPassword($joueur, $form->get('password')->getData())
            );

            $entityManager->persist($joueur);
            $entityManager->flush();

            $token = $jwtManager->create($joueur);

            if ($request->isXmlHttpRequest() || $request->headers->get('Accept') === 'application/json') {
                return new JsonResponse(['token' => $token]);
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('joueur/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
