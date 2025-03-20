<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('api/category', name: 'app_api_category_')]
final class CategoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private CategoryRepository $repository
    ) {
    }

    #[Route(name: 'new', methods: ['POST'])]
    public function new(): Response
    {
        $category = new Category();
        $category->setTitle('something');
        $category->setCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($category);
        $this->manager->flush();

        return $this->json(
            ['message' => "category resource created with ID: {$category->getId()}"],
            Response::HTTP_CREATED
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $category = $this->repository->findOneBy(['id' => $id]);

        if (!$category) {
            throw new \Exception("No category found for ID: {$id}");
        }

        return $this->json([
            'id' => $category->getId(),
            'name' => $category->getTitle(),
        ]);
    }

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id): Response
    {
        $category = $this->repository->findOneBy(['id' => $id]);

        if (!$category) {
            throw new \Exception("No category found for ID: {$id}");
        }

        $category->setTitle('category name updated');
        $this->manager->flush();

        return $this->json([
            'message' => "category updated",
            'id' => $category->getId(),
            'name' => $category->getTitle()
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        $category = $this->repository->findOneBy(['id' => $id]);

        if (!$category) {
            throw new \Exception("No category found for ID: {$id}");
        }

        $this->manager->remove($category);
        $this->manager->flush();

        return $this->json(
            ['message' => "category resource deleted"],
            Response::HTTP_NO_CONTENT
        );
    }
}
