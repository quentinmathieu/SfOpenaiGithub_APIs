<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OpenAIController extends AbstractController
{
    #[Route('/openai', name: 'app_open_a_i')]
    public function index(): Response
    {
        return $this->render('open_ai/index.html.twig', [
            'controller_name' => 'OpenAIController',
        ]);
    }
}
