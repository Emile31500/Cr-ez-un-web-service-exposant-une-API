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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{

    #[Route('/api/users/{id}', name: 'app_user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/users', name:"app_create_user", methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ClientRepository $clientRepository, UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $hasherInetrface): JsonResponse 
    {

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $content = $request->toArray();
        $idClient = $content['idClient'];
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
    public function getAll(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $users = $userRepository->findAll();
        $jsonUsers = $serializer->serialize($users, 'json', ['groups' => 'user']);

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Groups(['user'])]
    #[Route('/api/users/{id_user}', name: 'app_user_details', methods: ['GET'])]    
    public function getOne(UserRepository $userRepository, SerializerInterface $serializer, $id_user): JsonResponse
    {
        $users = $userRepository->findOneById($id_user);
        if ($users) {

            $jsonusers = $serializer->serialize($users, 'json', ['groups' => 'user']);
            return new JsonResponse($jsonusers, Response::HTTP_OK, [], true);

        } else {

            return new JsonResponse(null, Response::HTTP_NOT_FOUND);

        }
        
    }
}
