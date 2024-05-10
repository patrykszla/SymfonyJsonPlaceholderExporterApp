<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Helper\FetchJsonPlaceholderHelper;
use Symfony\Component\HttpFoundation\Request;

class UsersPageController extends AbstractController
{
    public function __construct(
        private FetchJsonPlaceholderHelper $jsonHelper,
    )
    {
        
    }

    #[Route('/fetch-users', name: 'app_users_page')]
    public function index(): Response
    {
        $response = $this->jsonHelper->fetchUsers();
        return $this->render('json_placeholder_users/index.html.twig', [
            'json_users' => $response,
        ]);
    }

    #[Route('/show-form', name: 'app_users_page')]
    public function showForm(Request $request): Response
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
            ->add('task', TextType::class)
            ->getForm();

        // $response = $this->jsonHelper->fetchUsers();
        return $this->render('json_placeholder_users/index.html.twig', [
            'user_form' => $form,
        ]);
    }

}
