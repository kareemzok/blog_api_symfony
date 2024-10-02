<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticlesTest extends WebTestCase
{

    public function testarticles(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/api/articles');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }



    public function testCreateArticleMissingRequiredFields(): void
    {
        $client = static::createClient();

        // Missing body and status
        $content = json_encode([
            'title' => 'Test Article Title'
        ]);

        $client->request(
            Request::METHOD_POST,
            '/api/article/add',
            [],
            [],
            [],
            $content,

        );

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('error', $responseData);
        $this->assertEquals('Missing required fields', $responseData['error']);
    }
}
