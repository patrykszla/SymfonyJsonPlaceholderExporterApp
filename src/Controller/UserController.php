<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\FetchJsonPlaceholderHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
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

        return $this->render('user/users_list.html.twig', [
            'users' => $jsonUsers,
            'retreived_from_db' => false,
            'current_page_from_controller' => $currentRoute
        ]);
    }
    
    #[Route('/show-json-all-users-form', name: 'app_json_all_users_form')]
    public function showAllUsersForm(Request $request): Response 
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
            
            $address = new Address();
            $address->setStreet($userData['address']['street']);
            $address->setSuite($userData['address']['suite']);
            $address->setCity($userData['address']['city']);
            $address->setZipcode($userData['address']['zipcode']);
            $user->setAddress($address);

            $form = $this->createForm(UserType::class, $user, [
                'method' => 'POST',
            ]);

            $forms[] = $form->createView();
        }

        $formAction = $this->generateUrl('app_users_form_handle');
        return $this->render('user/users_form.html.twig', [
            'users_forms' => $forms,
            'form_action' => $formAction,
            'current_page_from_controller' => $request->attributes->get('_route')
        ]);
    }
        

    #[Route('/add_users', name: 'app_users_form_handle')]
    public function handleUsersAdd(Request $request, EntityManagerInterface $entityManager): Response {
        $formData = $request->request->all();
        // dd($formData);
        foreach ($formData['users'] as $userData) {
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['json_id' => $userData['json_id']]);
            
            if (!$existingUser) {
                $user = new User();
                $user->setJsonId($userData['json_id']);
                $user->setName($userData['name']);
                $user->setUsername($userData['username']);
                $user->setEmail($userData['email']);
                $user->setPhone($userData['phone']);
                $user->setWebsite($userData['website']);
                $address = new Address();
                $address->setStreet($userData['address']['street']);
                $address->setSuite($userData['address']['suite']);
                $address->setCity($userData['address']['city']);
                $address->setZipcode($userData['address']['zipcode']);
                $user->setAddress($address);
    
                $entityManager->persist($user);
            } else {
                $userName = $userData['name'];
                $this->addFlash('danger', 'User ' . $userName . ' was already in database!');
            }
            
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
        return $this->render('user/users_list.html.twig', [
            'users' => $users,
            'retreived_from_db' => true,
            'current_page_from_controller' => $currentRoute
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function handleUserEdit(int $id, EntityManagerInterface $entityManager, Request $request): Response
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
            $this->addFlash('success', 'User updated successfully!');

            return $this->redirectToRoute('app_users_list');
        }

        return $this->render('user/user_form.html.twig', [
            'user_form' => $form->createView(),
        ]);
    }

}
