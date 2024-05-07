<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JsonPlaceholderPostsController extends AbstractController
{
    #[Route('/json/placeholder/posts', name: 'app_json_placeholder_posts')]
    public function index(): Response
    {
        return $this->render('json_placeholder_posts/index.html.twig', [
            'controller_name' => 'JsonPlaceholderPostsController',
        ]);
    }
}
