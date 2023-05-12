<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/api/users/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[IsGranted("ROLE_ADMIN", message: 'Seul les administrateur peuvent exécuter cette requête')]
    #[Route('/api/users', name:"app_create_user", methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ClientRepository $clientRepository, UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $hasherInetrface, ValidatorInterface $validator): JsonResponse 
    {

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        $errors = $validator->validate($user);

        if ($errors->count() > 0){

            throw new HttpException(JsonResponse::HTTP_BAD_REQUEST, "requête ivalide");

        }

        $token = $jwtManager->decode($tokenStorage->getToken());
        $user = $userRepository->getByUsername($token["username"]);
        $clientId = $user->getClient()->getId();
        
        $content = $request->toArray();
        $hashedPassword = $hasherInetrface->hashPassword($user, $user->getPassword());
        
        $user->setClient($clientRepository->findOneById($idClient));
        $user->setPassword($hashedPassword);
        $em->persist($user);
        $em->flush();

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'user']);
        $location = $urlGenerator->generate('app_user_details', ['id_user' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }


    #[Groups(['user'])]
    #[Route('/api/users', name: 'app_user_list', methods: ['GET'])]
    public function getAll(UserRepository $userRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache, JWTTokenManagerInterface $jwtManager, TokenStorageInterface $tokenStorage): JsonResponse
    {

        $token = $jwtManager->decode($tokenStorage->getToken());
        $user = $userRepository->getByUsername($token["username"]);
        $clientId = $user->getClient();

        $users = $userRepository->findAllOfThisClient($clientId);
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'user']);
        
        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Groups(['user'])]
    #[Route('/api/users/{id_user}', name: 'app_user_details', methods: ['GET'])]    
    public function getOne(UserRepository $userRepository, SerializerInterface $serializer, $id_user): JsonResponse
    {
        $token = $jwtManager->decode($tokenStorage->getToken());
        $user = $userRepository->getByUsername($token["username"]);
        $clientId = $user->getClient();
        
        $users = $userRepository->findOneById($id_user, $clientId = 5);
        if ($users) {

            $jsonusers = $serializer->serialize($users, 'json', ['groups' => 'user']);
            return new JsonResponse($jsonusers, Response::HTTP_OK, [], true);

        } else {

            return new JsonResponse(null, Response::HTTP_NOT_FOUND);

        }
        
    }

}
