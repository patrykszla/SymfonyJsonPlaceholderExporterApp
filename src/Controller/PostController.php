<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Helper\FetchJsonPlaceholderHelper;


class PostController extends AbstractController
{
    public function __construct(
        private FetchJsonPlaceholderHelper $jsonHelper,
    )
    {
        
    }
    #[Route('/post', name: 'app_post')]
    public function index(): Response
    {
        $jsonPosts = $this->jsonHelper->fetchPosts();
        dd($jsonPosts);

        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}
