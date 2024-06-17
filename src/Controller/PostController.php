<?php

namespace App\Controller;
use App\Entity\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Helper\FetchJsonPlaceholderHelper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    public function __construct(
        private FetchJsonPlaceholderHelper $jsonHelper,
    )
    {
        
    }

    #[Route('/fetch-json-posts', name: 'app_post_page_fetch')]
    public function showJsonPosts(Request $request, UserRepository $userRepository): Response
    {
        $jsonPosts = $this->jsonHelper->fetchPosts();

        $newPostsArray = [];
        foreach ($jsonPosts as $key => $val) {
            $users = $userRepository->findByJsonId($val['userId']);
            
            $jsonPosts[$key]['user_name'] = $users[0]->getName(); 
        }


        return $this->render('post/posts_list.html.twig', [
            'posts' => $jsonPosts,
            'retreived_from_db' => false,
            'current_page_from_controller' => $request->attributes->get('_route'),
        ]);
    }

    #[Route('/posts', name: 'app_posts_handle_json_add')]
    public function hanldeAddJsonPosts(Request $request, UserRepository $userRepository): Response
    {
        dd('Posts add!');
        $jsonPosts = $this->jsonHelper->fetchPosts();

        $newPostsArray = [];
        foreach ($jsonPosts as $key => $val) {
            
        }


        return $this->render('post/posts_list.html.twig', [
            'posts' => $jsonPosts,
            'retreived_from_db' => false,
            'current_page_from_controller' => $request->attributes->get('_route'),
        ]);
    }
}
