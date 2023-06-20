<?php

namespace App\Controller;

use App\Service\GithubService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GithubController extends AbstractController
{
    #[Route('/github', name: 'app_github')]
    public function index(Request $request, GithubService $github): Response
    {
        $repoUrl = 'https://api.github.com/repos/quentinmathieu/Projet4/commits';

        
        
        $commits = $github->getRepoContent($repoUrl);
        dd($commits);

        return $this->render('github/index.html.twig', [
            'controller_name' => 'GithubController',
        ]);
    }
}
