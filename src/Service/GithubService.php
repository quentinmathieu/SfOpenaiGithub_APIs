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


    // function that get all commits from a repo
    public function getRepoCommits(string $commitUrl) : array
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

        $commits = $response->toArray();

        return $commits;
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


    // function that get all commits with msg & diff code from a repo
    public function getRepoContent(string $repoUrl) : array
    {
        

        //get all (SHA) commits from a repo
        $repoCommits = $this->getRepoCommits($repoUrl);


        $allCommitsMsgContent = [];
        //get all commit content & message from each commit
        foreach(array_reverse($repoCommits) as $commit){

            //get commit content (diff code & msg)
            $commit = $this->getCommitContent($repoUrl . "/".$commit['sha']);

            $filesDiff = "";

            //foreach file that has been modified/added, get the diff code in a range limit of 3000 characters
            foreach($commit['files'] as $key=>$file){
                if (isset($file['patch']) && strlen(str_replace("\n", "", $file['patch'])) < 3000){
                    $filesDiff.= $file['filename'] . " (". $file['status'] . ") : ".str_replace("\n\n", "", $file['patch']) . "\n";
                }
                else{
                    $filesDiff.= $file['filename'] . " (". $file['status'] . ") : " . "..." . "\n";
                }
                
            }

            //if the diff code is too long, cut it to 600 characters
            $filesDiff = (strlen($filesDiff) > 6000) ? substr($filesDiff, 0, 6000) . "..." : $filesDiff;
           
            //add the commit message & diff code to an array
            $allCommitsMsgContent[$commit['commit']['message']] = $filesDiff;
        }


        return $allCommitsMsgContent;
    }
}