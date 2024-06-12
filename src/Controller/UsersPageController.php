<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\FetchJsonPlaceholderHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersPageController extends AbstractController
{
    public function __construct(
        private FetchJsonPlaceholderHelper $jsonHelper,
    )
    {
        
    }

    #[Route('/fetch-json-users', name: 'app_users_page_fetch')]
    public function showJsonUsers(Request $request): Response 
    {
        $jsonUsers = $this->jsonHelper->fetchUsers();
        $currentRoute = $request->attributes->get('_route');

        return $this->render('users_page/users_list.html.twig', [
            'users' => $jsonUsers,
            'retreived_from_db' => false,
            'current_page_from_controller' => $currentRoute
        ]);
    }
    
    #[Route('/show-json-all-users-form', name: 'app_json_all_users_form')]
    public function showAllUsersForm(): Response 
    {
        $jsonUsers = $this->jsonHelper->fetchUsers();
        $forms = [];
        // dd($jsonUsers);
        foreach ($jsonUsers as $index => $userData) {
            $user = new User();
            $user->setJsonId($userData['id']);
            $user->setName($userData['name']);
            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);
            $user->setPhone($userData['phone']);
            $user->setWebsite($userData['website']);

            $form = $this->createForm(UserType::class, $user, [
                'method' => 'POST',
            ]);

            $forms[] = $form->createView();
        }

        $formAction = $this->generateUrl('app_users_form_handle');

        return $this->render('users_page/users_form.html.twig', [
            'users_forms' => $forms,
            'form_action' => $formAction
        ]);
    }
        

    #[Route('/add_users', name: 'app_users_form_handle')]
    public function handleUsersAdd(Request $request, EntityManagerInterface $entityManager): Response {
        $formData = $request->request->all();
        foreach ($formData['users'] as $userData) {
            $user = new User();
            $user->setJsonId($userData['json_id']);
            $user->setName($userData['name']);
            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);
            $user->setPhone($userData['phone']);
            $user->setWebsite($userData['website']);

            $entityManager->persist($user);
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_users_list');
    }

    #[Route('/users', name: 'app_users_list')]
    public function showUsersList(EntityManagerInterface $entityManager, Request $request): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();
        $currentRoute = $request->attributes->get('_route');
        // $response = $this->jsonHelper->fetchUsers();
        $tableHead = array('#', 'name', 'email', 'actions');
        return $this->render('users_page/users_list.html.twig', [
            // 'table_headers' => $tableHead,
            'users' => $users,
            'retreived_from_db' => true,
            'current_page_from_controller' => $currentRoute
        ]);
    }
    
    #[Route('/OLDuser/edit/{id}', name: 'app_user_edit',  methods: ['GET', 'POST'])]
    public function OldshowUserForm(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {

        $userRepository = $entityManager->getRepository(User::class);
        $userData = $userRepository->find($id);
        if (!$userData) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }


        $formAction = $this->generateUrl('app_user_edit', ['id' => $id]);
        $form = $this->createForm(UserType::class, $userData, [
            'method' => 'POST',
            // 'action' => $this->generateUrl('app_user_edit_handle', ['id' => $id])
        ]);

        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $data = $form->getData();
            dd($userData);

        }

        if ($form->isSubmitted()) {
            dd('JEST SUBMITed');
        }
        $forms[] = $form->createView();

        return $this->render('users_page/users_form.html.twig', [
            'form_type' => 'one',
            'users_forms' => $forms,
            'form_action' => $formAction,
            'current_page_from_controller' => 'test'
        ]);

    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function showUserForm(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('No user found for id ' . $id);
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_users_list');
        }

        return $this->render('users_page/user_form.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }



    // #[Route('/user/handle-edit/{id}', name: 'app_user_edit_handle', methods: ['POST'])]
    // public function handleUserForm(int $id, Request $request): Response
    // {
    //     // add handle user edit!
    //     // dd("HANDLE USER EDIT!!");
    //     dd($request);
    //     return $this->render('users_page/users_form.html.twig', [
    //         'users_forms' => "test",
    //         'form_action' => 'TEST',
    //         'current_page_from_controller' => 'test'
    //     ]);
    // }
    


    

}
