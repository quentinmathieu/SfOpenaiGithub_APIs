<?php

namespace App\Controller;

use App\Service\GithubService;
use App\Service\OpenAiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GithubController extends AbstractController
{
    #[Route('/github', name: 'app_github')]
    public function index(Request $request, GithubService $github, OpenAiService $openAi): Response
    {
        $repoUrl = 'https://api.github.com/repos/quentinmathieu/SfOpenaiGithub_APIs/commits';

        $stepsAnswer = [];
        $commits = $github->getRepoContent($repoUrl);
        $stepNumber = 1;
        foreach($commits as $commitMsg => $commitContent){
            if($stepNumber > 6){
                // break;
            }
            if($stepNumber > 14){
                
                $stepsAnswer[$stepNumber.".".$commitMsg] = $openAi->getCommitExplaination($commitMsg, $commitContent, $stepNumber);
                // break;
            }
                $stepNumber++;
            
            
        }


        return $this->render('github/index.html.twig', [
            'controller_name' => 'GithubController',
            'steps' => $stepsAnswer,
            'commits' => $commits,
        ]);
    }
}
