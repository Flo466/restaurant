<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Config\Framework\FormConfig;

use function PHPSTORM_META\type;

#[Route('api/restaurant', name: 'app_api_restaurant_')]
final class RestaurantController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    #[Route(methods: ['POST'])]
    public function new(Request $request): JsonResponse
    {
        $restaurant = $this->serializer->deserialize(
            $request->getContent(),
            type: Restaurant::class,
            format: 'json');
        $restaurant->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($restaurant);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($restaurant, format:'json');
        $location = $this->urlGenerator->generate(
            name: 'app_api_restaurant_show',
            parameters: ['id' => $restaurant->getId()],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL
        );
        
        return new JsonResponse(
            data: $responseData,
            status: Response::HTTP_CREATED,
            headers: ["Location" => $location],
            json: true
        );
        
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if ($restaurant) {

            $respondeData = $this->serializer->serialize($restaurant, format:'json');

            return new JsonResponse($respondeData, status: Response::HTTP_OK);
        }

        return new JsonResponse(data: null, status: Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);
        if ($restaurant) {

            $restaurant = $this->serializer->deserialize(
                $request->getContent(),
                Restaurant::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant]
            );

            $restaurant->setUpdatedAt(new DateTimeImmutable());
            $this->manager->flush();

            $responseData = $this->serializer->serialize($restaurant, format:'json');
            $location = $this->urlGenerator->generate(
                name: 'app_api_restaurant_show',
                parameters: ['id' => $restaurant->getId()],
                referenceType: UrlGeneratorInterface::ABSOLUTE_URL
            );
            
            return new JsonResponse(
                data: $responseData,
                status: Response::HTTP_CREATED,
                headers: ["Location" => $location],
                json: true
            );
        }

        return new JsonResponse(null, status: Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $restaurant = $this->repository->findOneBy(['id' => $id]);

        if ($restaurant) {
            $this->manager->remove($restaurant);
            $this->manager->flush();

            return new JsonResponse(null, status: Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, status: Response::HTTP_NOT_FOUND);
    }
}
