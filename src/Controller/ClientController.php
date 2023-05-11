<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    #[Groups(['client'])]
    #[Route('/api/clients', name: 'app_client_list', methods: ['GET'])]
    public function getAll(ClientRepository $clientRepository, SerializerInterface $serializer) //: JsonResponse
    {
        $clients = $clientRepository->findAll();
        $jsonClients = $serializer->serialize($clients, 'json', ['groups' => 'client']);

        return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);
    }

    #[Groups(['client'])]
    #[Route('/api/clients/{id_client}', name: 'app_client_details', methods: ['GET'])]    
    public function getOne(ClientRepository $clientRepository, SerializerInterface $serializer, $id_client): JsonResponse
    {
        $clients = $clientRepository->findOneById($id_client);
        if ($clients) {

            $jsonClients = $serializer->serialize($clients, 'json', ['groups' => 'client']);
            return new JsonResponse($jsonClients, Response::HTTP_OK, [], true);

        } else {

            return new JsonResponse(null, Response::HTTP_NOT_FOUND);

        }
        
    }
}
