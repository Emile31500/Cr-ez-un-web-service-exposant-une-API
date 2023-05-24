<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use JMS\Serializer\Annotation\Since;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PhoneController extends AbstractController
{
    /**
     * This method list all phones in catalog
     */
    #[Route('/api/phones', name: 'app_phone_list', methods: ['GET'])]
    #[Since("1.0")]
    public function getAll(PhoneRepository $phoneRepository, SerializerInterface $serializer): JsonResponse
    {
        $phones = $phoneRepository->findAll();
        $jsonPhones = $serializer->serialize($phones, 'json');

        return new JsonResponse($jsonPhones, Response::HTTP_OK, [], true);
    }

    /**
     * This method list a phone according to his id 
     */
    #[Route('/api/phones/{id_phone}', name: 'app_phone_details', methods: ['GET'])]
    #[Since("1.0")]
    public function getOne(PhoneRepository $phoneRepository, SerializerInterface $serializer, $id_phone): JsonResponse
    {
        $phones = $phoneRepository->findOneById($id_phone);
        if ($phones) {

            $jsonPhones = $serializer->serialize($phones, 'json');
            return new JsonResponse($jsonPhones, Response::HTTP_OK, [], true);

        } else {

            return new JsonResponse(null, Response::HTTP_NOT_FOUND);

        }
        
    }
}
