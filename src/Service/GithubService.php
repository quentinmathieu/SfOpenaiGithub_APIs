<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class GithubService
{
    private $client;
    private $param;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $param)
    {
        $this->client = $client;
        $this->param = $param;
    }

    public function getCommitContent(string $commitUrl) : array
    {
        $token = $this->param->get('GITHUB_API_KEY');
        $response = $this->client->request(
            'GET',
            $commitUrl,
            [
                'headers' => [
                'Authorization' => 'token ' . $token,
                ],
            ]
        );

        $content = $response->toArray();

        return $content;
    }

    // public function getRepoCommits(string $repoUrl) : array
    // {
    //     $token = $this->getParameter('GITHUB_API_KEY');

    //     $response = $this->client->request(
    //         'GET',
    //         $repoUrl
    //     );

    //     $commits = $response->toArray();

    //     return $commits;
    // }

    // public function getRepoContent(string $repoUrl) : array
    // {
        

    //     //get all (SHA) commits from a repo
    //     $repoCommits = $this->getRepoCommits($repoUrl);

    //     dd($repoCommits);

    //     $allCommitsMsgContent = [];
    //     //get all commit content & message from each commit
    //     foreach(array_reverse($repoCommits) as $commit){
    //         $commit = $this->getCommitContent($repoUrl . "/".$commit['sha']);

    //         $allCommitsMsgContent[] = [
    //             $commit,
    //         ];
    //     }
    //     dd($allCommitsMsgContent);


    //     return $allCommitsMsgContent;
    // }
}