<?php

namespace App\Controller;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Helper\FetchJsonPlaceholderHelper;
use App\Form\PostType;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    #[Route('/post/edit/{id}', name: 'app_post_edit')]
    public function handlePostEdit(int $id,EntityManagerInterface $entityManager, ValidatorInterface $validator, Request $request): Response 
    {
        $postRepository = $entityManager->getRepository(Post::class);
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException('No post found for id ' . $id);
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        // $errors = $validator->validate($request);
        if ($form->isSubmitted() && $form->isValid()) {
        dd($form->isValid());
            
            

            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', "Post {$id} updated successfully!");

            return $this->redirectToRoute('app_posts_list');
        }

        return $this->render('post/post_form.html.twig', [
            'post_form' => $form->createView(),
        ]);
    }

}
