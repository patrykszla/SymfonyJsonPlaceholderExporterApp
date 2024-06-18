<?php

namespace App\Controller;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Helper\FetchJsonPlaceholderHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

        foreach ($jsonPosts as $key => $val) {
            $users = $userRepository->findByJsonId($val['userId']);
            $jsonPosts[$key]['user'] = $users[0];
        }

        return $this->render('post/posts_list.html.twig', [
            'posts' => $jsonPosts,
            'retreived_from_db' => false,
            'current_page_from_controller' => $request->attributes->get('_route'),
        ]);
    }

    #[Route('/posts', name: 'app_posts_list')]
    public function hanldeAddJsonPosts(Request $request, EntityManagerInterface $entityManager): Response
    {
        $postRepository = $entityManager->getRepository(Post::class);
        $posts = $postRepository->findAll();
        // dd($posts);


        return $this->render('post/posts_list.html.twig', [
            'posts' => $posts,
            'retreived_from_db' => true,
            'current_page_from_controller' => $request->attributes->get('_route'),
        ]);
    }

    #[Route('/add_posts', name: 'app_posts_handle_json_add')]
    public function handleAddJsonPosts(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // dd('Posts add!');
        $jsonPosts = $this->jsonHelper->fetchPosts();
        // dd($jsonPosts);
        foreach ($jsonPosts as $key => $val) {
            $users = $userRepository->findByJsonId($val['userId']);
            foreach ($users as $user) {
                $post = new Post();
                $post->setUser($user);
                $post->setTitle($val['title']);
                $post->setBody($val['body']);
                $entityManager->persist($post);

            }
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_posts_list');
       
    }


}
