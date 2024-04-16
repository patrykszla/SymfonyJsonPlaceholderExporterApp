<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController 
{
    #[Route('/test', 'test-index')]
    public function index(): Response 
    {
        return $this->render('index.html.twig');
        // return new Response('test');
    } 
}