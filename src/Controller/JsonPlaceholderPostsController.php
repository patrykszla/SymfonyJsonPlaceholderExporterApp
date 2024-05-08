<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Helper\FetchJsonPlaceholderHelper;

class JsonPlaceholderPostsController extends AbstractController
{
    public function __construct(
        private FetchJsonPlaceholderHelper $jsonHelper,
    )
    {
        
    }

    #[Route('/fetch-posts', name: 'app_json_placeholder_posts')]
    public function index(): Response
    {
        return $this->render('json_placeholder_posts/index.html.twig', [
            'controller_name' => 'JsonPlaceholderPostsController',
        ]);
    }

    #[Route('/fetch-user-posts/{userId}', name: 'app_json_placeholder_posts')]
    public function showUserPosts($userId): Response
    {
        $response = $this->jsonHelper->fetchPosts();
        foreach($response as $key => $value) {
            if($value['userId'] != $userId) {
                unset($response[$key]);
            }
        }
        // dd($response);
        return $this->render('json_placeholder_posts/display_user_posts.html.twig', [
            'user_posts' => $response,
        ]);
    }

}
