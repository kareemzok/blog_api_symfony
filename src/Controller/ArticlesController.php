<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles_api", methods={"GET"})
     */
    public function index(ArticlesRepository $articleRepository, SerializerInterface $serializer): JsonResponse
    {

        $articles = $articleRepository->findAll();

        if (empty($articles)) {
            return new JsonResponse(['error' => 'Articles not found'], Response::HTTP_NOT_FOUND);
        }
        $data = $serializer->serialize($articles, 'json');

        return new JsonResponse(json_decode($data), Response::HTTP_OK);
    }


    /**
     * @Route("/", name="create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $requestData = $request->getContent();

        $article = $serializer->deserialize($requestData, Articles::class, 'json');

        if (!$article->getName() || !$article->getDescription() || !$article->getPrice()) {
            return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($article);
        $entityManager->flush();

        $data = $serializer->serialize($article, 'json');

        return new JsonResponse(['message' => 'Article created!', 'article' => json_decode($data)], Response::HTTP_CREATED);
    }


    /**
     * @Route("/article/{id}", name="articles_api_show", methods={"POST"})
     */
    public function show(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): JsonResponse
    {
        $article = $entityManager->getRepository(Articles::class)->find($id);
      
        if (!$article) {

            return new JsonResponse(['error' => 'Article with id ' . $id . ' not found'], Response::HTTP_NOT_FOUND);
        }
        $data = $serializer->serialize($article, 'json');

        return new JsonResponse(json_decode($data), Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="articles_api_update", methods={"PUT"})
     */
    public function update(Articles $articles, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): JsonResponse
    {
        $requestData = $request->getContent();
        dd($request);
        $updatedProduct = $serializer->deserialize($requestData, Articles::class, 'json');

        $articles->setTitle($updatedProduct->getName());
        $articles->setBody($updatedProduct->getDescription());
        $articles->setStatus($updatedProduct->getPrice());

        $entityManager->flush();

        return new JsonResponse('Article updated!', Response::HTTP_OK);
    }


    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(Articles $article, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse('Article deleted!', Response::HTTP_OK);
    }
}
