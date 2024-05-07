<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Helper\FetchJsonPlaceholderHelper;

class TestController extends AbstractController 
{
    public function __construct(
        private HttpClientInterface $client,
        private FetchJsonPlaceholderHelper $jsonHelper,
    )
    {
        
    }

    #[Route('/test', 'test-index')]
    public function index(): Response 
    {
        $response = $this->jsonHelper->fetchJson();
        // $response = $this->fetchJson();
        dd($response);
        return $this->render('index.html.twig');
        // return new Response('test');
    }
    
    protected function fetchJson(): array 
    {
        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/posts'
        );
        $statusCode = $response->getStatusCode();
        
        if($statusCode == 200) {
            $contentType = $response->getHeaders()['content-type'][0];
            $content = $response->getContent();
            $content = $response->toArray();

        } else {
            $content = false;
        }
        // $statusCode = 200
        // $contentType = 'application/json'
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;

    }
    
}