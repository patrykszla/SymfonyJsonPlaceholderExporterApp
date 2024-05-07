<?php

namespace App\Helper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;



class FetchJsonPlaceholderHelper 
{
    public function __construct(
        private HttpClientInterface $client,
    )
    {
        
    }

    public function fetchJson(): array 
    {
        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/posts'
        );
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;

    }

    public function fetchUsers(): array 
    {
        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/users'
        );
        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
}