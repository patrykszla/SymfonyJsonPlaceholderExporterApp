<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Helper\FetchJsonPlaceholderHelper;

class MainPageController extends AbstractController
{
    public function __construct(
        private FetchJsonPlaceholderHelper $jsonHelper,
    )
    {
        
    }

    #[Route('/', name: 'app_main_page')]
    public function index(): Response
    {
        // $response = $this->jsonHelper->fetchJson();
        // $response = $this->fetchJson();
        // dd($response);
        return $this->render('index.html.twig');

    }
}
