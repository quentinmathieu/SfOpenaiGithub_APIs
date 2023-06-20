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
        $repoUrl = 'https://api.github.com/repos/quentinmathieu/Github_API/commits';
        $commitURl = 'https://api.github.com/repos/quentinmathieu/Github_API/commits/a1686c063cbb04d6d576e13fcb98f80f9b0861be';
        $commitContent = $github->getCommitContent($commitURl);
        dd($commitContent);
        
        $commitTest = $github->getRepoContent($repoUrl);
        dd($commitTest);
        
        $commits = $github->getRepoCommits($repoUrl);
        dd($commits);

        return $this->render('github/index.html.twig', [
            'controller_name' => 'GithubController',
        ]);
    }
}
