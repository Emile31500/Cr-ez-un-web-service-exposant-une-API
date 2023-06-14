<?php

namespace App\Controller;

use App\Entity\Client;
use OpenApi\Annotations as OA;
use App\Repository\ClientRepository;
use JMS\Serializer\Annotation\Since;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Contracts\Cache\ItemInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
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

class ClientController extends AbstractController
{
    /**
     * This method use to delete a client
     * 
     * @OA\Response(
     *     response=200,
     *     description="Delete a client if he belongs to the authenticated client project",
     *     
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="The id of client we want to delete",
     *     @OA\Schema(type="int")
     * )
     *
     * )
     * @OA\Tag(name="Clients")
     *
     * @param ClientRepository $ClientRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
    */
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/api/clients/{id}', name: 'app_Client_delete', methods: ['DELETE'])]
    #[Since("1.0")]
    public function delete(Client $Client, EntityManagerInterface $em): JsonResponse 
    {
        $User = $this->getUser();

        if ($Client->getProject()->getId() === $User->getId()){

            $em->remove($Client);
            $em->flush();

        } else {

            throw new HttpException(JsonResponse::HTTP_BAD_REQUEST, "User invalide");

        }
        
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * This method use to add a new client
     * 
     * @OA\Response(
     *     response=200,
     *     description="Add a client and make it belong to the same group than authenticated clients",
     *
     * )
     * @OA\Tag(name="Clients")
     *
     * @param ClientRepository $ClientRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
    */
    #[IsGranted("ROLE_ADMIN", message: 'Seul les administrateur peuvent exécuter cette requête')]
    #[Route('/api/clients', name:"app_create_Client", methods: ['POST'])]
    #[Since("1.0")]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $hasherInetrface, ValidatorInterface $validator): JsonResponse 
    {

        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        $errors = $validator->validate($client);

        if ($errors->count() > 0){

            throw new HttpException(JsonResponse::HTTP_BAD_REQUEST, "Requête invalide");

        }
        
        $content = $request->toArray();
        $User = $this->getUser();
        $client->setProject($User->getProject());

        $hashedPassword = $hasherInetrface->hashPassword($client, $client->getPassword());
        $client->setPassword($hashedPassword);

        $em->persist($client);
        $em->flush();

        $jsonClient = $serializer->serialize($client, 'json', ['groups' => 'Client']);
        $location = $urlGenerator->generate('app_Client_details', ['id_Client' => $client->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonClient, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * This method use to list all clients in the project than the authenticated client
     * 
     * @OA\Response(
     *     response=200,
     *     description="give an array of client",
     *
     * )
     * @OA\Tag(name="Clients")
     *
     * @param ClientRepository $ClientRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
    */
    #[Groups(['Client'])]
    #[Route('/api/clients', name: 'app_client_list', methods: ['GET'])]
    #[Since("1.0")]
    public function getAll(ClientRepository $ClientRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache): JsonResponse
    {
        
        $project = $this->getUser()->getProject();
        
        $idCache = "getAllClient";
        $jsonClients = $cache->get($idCache, function (ItemInterface $item) use ($ClientRepository, $serializer, $project){
            
            $item->tag("clientsCache");
            $clients = $ClientRepository->findAllInThisProject($project);
            return $serializer->serialize($clients, 'json', ['groups' => 'project']);

        }); 

        return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);
    }

    /**
     * This method use to list a clients according to id if belongs to user authenticated project 
     * 
     * @OA\Response(
     *     response=200,
     *     description="give a client or null",
     *
     * )
     * @OA\Tag(name="Clients")
     *
     * @param ClientRepository $ClientRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
    */
    #[Groups(['Client'])]
    #[Route('/api/clients/{id_Client}', name: 'app_Client_details', methods: ['GET'])]
    #[Since("1.0")]
    public function getOne(ClientRepository $ClientRepository, SerializerInterface $serializer, TagAwareCacheInterface $cache, int $id_Client): JsonResponse
    {

        $project = $this->getUser()->getProject();
        $idCache = "getOneClient";

        $jsonClient = $cache->get($idCache, function (ItemInterface $item) use ($ClientRepository, $id_Client, $serializer, $project){
            
            $item->tag("clientCache");
            $client = $ClientRepository->findOneById($id_Client, $project);

            if ($client) {

                return $serializer->serialize($client, 'json', ['groups' => 'project']);
            
            } else {

                return new JsonResponse(null, Response::HTTP_NOT_FOUND);

            }
        }); 

        return new JsonResponse($jsonClient, Response::HTTP_OK, [], true);
    }
}
