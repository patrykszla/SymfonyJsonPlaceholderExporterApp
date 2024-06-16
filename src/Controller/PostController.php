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

    #[Route('/posts', name: 'app_post')]
    public function showJsonPosts(Request $request, UserRepository $userRepository): Response
    {


        
        $jsonPosts = $this->jsonHelper->fetchPosts();
        // dd($jsonPosts);

        $users = $userRepository->findByJsonId(2);
        dd($users);
        foreach ($jsonPosts as $key => $val) {
            
            // $jsonPosts[$key]['user_name'] = 
        }

        return $this->render('post/posts_list.html.twig', [
            'posts' => $jsonPosts,
            'retreived_from_db' => false,
            'current_page_from_controller' => $request->attributes->get('_route'),
        ]);
    }
}
