<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Repository\ArticlesRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Serializer\SerializerInterface;

class ArticlesController extends AbstractController
{
    /**
     * @Route("/", name="articles_api", methods={"GET"})
     */
    public function index(Request $request, ArticlesRepository $articleRepository, SerializerInterface $serializer, RateLimiterFactory $anonymousApiLimiter): JsonResponse
    {

        // create a limiter based on a unique identifier of the client
        $limiter = $anonymousApiLimiter->create($request->getClientIp());

        // the argument of consume() is the number of tokens to consume
        // and returns an object of type Limit
        if (false === $limiter->consume(3)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }


        $articles = $articleRepository->findAll();

        if (empty($articles)) {
            return new JsonResponse(['error' => 'Articles not found'], Response::HTTP_NOT_FOUND);
        }
        $data = $serializer->serialize($articles, 'json');

        return new JsonResponse(json_decode($data), Response::HTTP_OK);
    }


    /**
     * @Route("/", name="articles_api_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, RateLimiterFactory $anonymousApiLimiter): Response
    {
        
         // create a limiter based on a unique identifier of the client
         $limiter = $anonymousApiLimiter->create($request->getClientIp());

         // the argument of consume() is the number of tokens to consume
         // and returns an object of type Limit
         if (false === $limiter->consume(3)->isAccepted()) {
             throw new TooManyRequestsHttpException();
         }


        $requestData = $request->getContent();
        // Missing body and status

        $article = $serializer->deserialize($requestData, Articles::class, 'json');

        if (!$article->getTitle() || !$article->getBody() || !$article->isStatus()) {
            return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }
        $article->setPostDate(new DateTime());
        $article->setDeleted(false);
        $entityManager->persist($article);
        $entityManager->flush();

        $data = $serializer->serialize($article, 'json');

        return new JsonResponse(['message' => 'Article created!', 'article' => json_decode($data)], Response::HTTP_CREATED);
    }


    /**
     * @Route("/article/{id}", name="articles_api_show", methods={"POST"})
     */
    public function show(EntityManagerInterface $entityManager, SerializerInterface $serializer, RateLimiterFactory $anonymousApiLimiter, int $id): JsonResponse
    {

         // create a limiter based on a unique identifier of the client
         $limiter = $anonymousApiLimiter->create($request->getClientIp());

         // the argument of consume() is the number of tokens to consume
         // and returns an object of type Limit
         if (false === $limiter->consume(3)->isAccepted()) {
             throw new TooManyRequestsHttpException();
         }

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
    public function update(Articles $article, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, RateLimiterFactory $anonymousApiLimiter, int $id): JsonResponse
    {

        $requestData = $request->getContent();
        $updatedArticle = $serializer->deserialize($requestData, Articles::class, 'json');

        $article->setTitle($updatedArticle->getTitle());
        $article->setBody($updatedArticle->getBody());
        $article->setStatus($updatedArticle->isStatus());

        $entityManager->flush();

        return new JsonResponse('Article with id ' . $id . ' is updated!', Response::HTTP_OK);
    }


    /**
     * @Route("/{id}", name="articles_api_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, RateLimiterFactory $anonymousApiLimiter, int $id): JsonResponse
    {

         // create a limiter based on a unique identifier of the client
         $limiter = $anonymousApiLimiter->create($request->getClientIp());

         // the argument of consume() is the number of tokens to consume
         // and returns an object of type Limit
         if (false === $limiter->consume(3)->isAccepted()) {
             throw new TooManyRequestsHttpException();
         }

        $article = $entityManager->getRepository(Articles::class)->find($id);

        if (empty($article)) {
            return new JsonResponse(['error' => 'Article not found'], Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($article);
        $entityManager->flush();

        return new JsonResponse('Article deleted!', Response::HTTP_OK);
    }

    /**
     * @Route("/article/search", name="product_api_search", methods={"GET"})
     */
    public function search(Request $request, ArticlesRepository $articleRepository, SerializerInterface $serializer, RateLimiterFactory $anonymousApiLimiter,string $keyword): JsonResponse
    {

         // create a limiter based on a unique identifier of the client
         $limiter = $anonymousApiLimiter->create($request->getClientIp());

         // the argument of consume() is the number of tokens to consume
         // and returns an object of type Limit
         if (false === $limiter->consume(3)->isAccepted()) {
             throw new TooManyRequestsHttpException();
         }


        $articles = $articleRepository->searchForArticles($keyword);

        if (empty($articles)) {
            return new JsonResponse(['error' => 'No result fond'], Response::HTTP_NOT_FOUND);
        }

        $data = $serializer->serialize($articles, 'json');

        return new JsonResponse(json_decode($data), Response::HTTP_OK);
    }
}
