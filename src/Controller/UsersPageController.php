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
    public function showJsonUsers(): Response 
    {
        $jsonUsers = $this->jsonHelper->fetchUsers();
        return $this->render('json_placeholder_users/index.html.twig', [
            'json_users' => $jsonUsers,
        ]);
    }
    
    #[Route('/show-json-all-users-form', name: 'app_json_all_users_form')]
    public function showAllUsersForm(): Response 
    {
        $jsonUsers = $this->jsonHelper->fetchUsers();
        $forms = [];

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
        // dd($formData);

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

            // $userRepository = $entityManager->getRepository(User::class);
            // $users = $userRepository->findAll();

            // dd($users);
            return $this->redirectToRoute('app_users_list');
    }



    //test
    #[Route('/show-form', name: 'app_users_page')]
    public function showForm(): Response
    {
        $user = new User();
        $user->setId(2);
        $user->setJsonId(2333);
        $user->setName('Patryk');
        $user->setUsername('TESTOWE USERNAME');
        $user->setEmail('test@email.com');
        $user->setPhone('222222');
        $user->setWebsite('testowa website');

        $form = $this->createFormBuilder($user)
            ->setMethod('POST')
            ->add('name', TextType::class)
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TextType::class)
            ->add('website', TextType::class)
            ->getForm();

        // $response = $this->jsonHelper->fetchUsers();
        return $this->render('users_page/index.html.twig', [
            'user_form' => $form,
        ]);
    }
    
    #[Route('/users', name: 'app_users_list')]
    public function showUsersList(EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        // $response = $this->jsonHelper->fetchUsers();
        $tableHead = array('#', 'name', 'email', 'actions');
        return $this->render('users_page/users_list.html.twig', [
            // 'table_headers' => $tableHead,
            'users' => $users
        ]);
    }
    
    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function showUserForm(int $id, EntityManagerInterface $entityManager): Response
    {
        echo ini_get('memory_limit');
        ini_set('memory_limit', '512M');

        // die();
        $userRepository = $entityManager->getRepository(User::class);
        dd($userRepository);
        $userData = $userRepository->find($id);
        if (!$userData) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }

        $user = new User();
        $user->setJsonId($userData->getJsonId());
        $user->setName($userData->getName());
        $user->setUsername($userData->getUsername());
        $user->setEmail($userData->getEmail());
        $user->setPhone($userData->getPhone());
        $user->setWebsite($userData->getWebsite());

        $form = $this->createForm(UserType::class, $user, [
            'method' => 'POST',
        ]);

        $forms = $form->createView();


       
        // dd($user);

        

        // $form = $this->createForm(UserType::class, $user, [
        //     'method' => 'POST',
        // ]);

        // $form = $form->createView();

        $formAction = $this->generateUrl('app_users_form_handle');

        return $this->render('users_page/users_form.html.twig', [
            'users_forms' => $forms,
            'form_action' => $formAction
        ]);



        // $response = $this->jsonHelper->fetchUsers();
        // $tableHead = array('#', 'name', 'email', 'actions');
        // return $this->render('users_page/users_list.html.twig', [
        //     // 'table_headers' => $tableHead,
        //     'users' => $users
        // ]);
    }


    //to delete
    #[Route('/submit-form', name: 'app_submit_user_form')]
    public function submitUserForm(Request $request): Response
    {
        dd($request);
        // $response = $this->jsonHelper->fetchUsers();
        // return $this->render('json_placeholder_users/index.html.twig', [
        //     'json_users' => $response,
        // ]);
    }

    

}
