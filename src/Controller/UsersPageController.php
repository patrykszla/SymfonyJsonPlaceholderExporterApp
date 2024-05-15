<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;

use App\Helper\FetchJsonPlaceholderHelper;
use App\Entity\User;
use App\Form\UserType;

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
    public function showAllUsersForm(Request $request): Response 
    {
        // Pobierz dane użytkowników
        $jsonUsers = $this->jsonHelper->fetchUsers();
        $forms = [];

        // Tworzenie formularzy dla każdego użytkownika
        foreach ($jsonUsers as $index => $userData) {
            $user = new User(); // Załóżmy, że masz encję User

            // Ustawienie danych użytkownika w formularzu
            $user->setName($userData['name']);
            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);
            $user->setPhone($userData['phone']);
            $user->setWebsite($userData['website']);

            // Utworzenie formularza dla użytkownika
            $form = $this->createForm(UserType::class, $user, [
                // 'action' => $this->generateUrl('app_users_page_submit'),
                'method' => 'POST',
            ]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // Obsługa przesłanego formularza
                // Możesz tutaj dodać logikę zapisu użytkownika do bazy danych lub inną operację
            }

            // Dodanie utworzonego formularza do tablicy formularzy
            $forms[] = $form->createView();
        }
        // dd($forms);
        return $this->render('users_page/save_users.html.twig', [
            'user_forms' => $forms,
        ]);
    }    


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
