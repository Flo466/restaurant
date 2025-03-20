<?php

namespace App\Controller;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/food', name: 'app_api_food_')]
final class FoodController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private FoodRepository $repository
    ) {
    }

    #[Route(name: 'new', methods: ['POST'])]
    public function new(): Response
    {
        $food = new Food();
        $food->setTitle('Poulet braisé');
        $food->setDescription('Un délicieux poulet cuit au feu de bois.');
        $food->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($food);
        $this->manager->flush();

        return $this->json(
            ['message' => "food resource created with ID: {$food->getId()}"],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

        if (!$food) {
            throw new \Exception("No food found for ID: {$id}");
        }

        return $this->json([
            'id' => $food->getId(),
            'name' => $food->getTitle(),
            'description' => $food->getDescription()
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id): Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

        if (!$food) {
            throw new \Exception("No food found for ID: {$id}");
        }

        $food->setTitle('food name updated');
        $this->manager->flush();

        return $this->json([
            'message' => "food updated",
            'id' => $food->getId(),
            'name' => $food->getTitle()
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $food = $this->repository->findOneBy(['id' => $id]);

        if (!$food) {
            throw new \Exception("No food found for ID: {$id}");
        }

        $this->manager->remove($food);
        $this->manager->flush();

        return $this->json(
            ['message' => "food resource deleted"],
            Response::HTTP_NO_CONTENT
        );
    }
}
