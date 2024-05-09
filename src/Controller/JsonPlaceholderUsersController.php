<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Helper\FetchJsonPlaceholderHelper;
use App\Entity\JsonPlaceholderUser;

class JsonPlaceholderUsersController extends AbstractController
{
    public function __construct(
        private FetchJsonPlaceholderHelper $jsonHelper,
        private JsonPlaceholderUser $user
    )
    {
        
    }

    
    #[Route('/fetch-users', name: 'app_json_placeholder_users')]
    public function index(JsonPlaceholderUser $jsonPlaceholderUser): Response
    {
        $response = $this->jsonHelper->fetchUsers();
        return $this->render('json_placeholder_users/index.html.twig', [
            'json_users' => $response,
        ]);
    }
}
