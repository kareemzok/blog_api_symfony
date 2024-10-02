<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class AuthController extends AbstractController
{
    /**
     * @Route("/user/register", name="api_user_register", methods={"POST"})
     */
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        UserPasswordHasherInterface $hasher
    ): Response {

        $requestData = $request->getContent();


        $userData = $serializer->deserialize($requestData, User::class, 'json');
     
        if (!$userData->getUsername() || !$userData->getPassword() || !$userData->getEmail()) {
            return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }
   
        $user = new User();
        $user->setUsername($userData->getUsername());
        $user->setPassword($hasher->hashPassword($user, $userData->getPassword()));
        $user->setEmail($userData->getEmail());

        $entityManager->persist($user);
        $entityManager->flush();

        $data = $serializer->serialize($user, 'json');

        return new JsonResponse(['message' => 'User created!', 'user' => json_decode($data)], Response::HTTP_CREATED);
    }

 
}
