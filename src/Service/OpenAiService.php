<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Orhanerday\OpenAi\OpenAi;

class OpenAiService
{
    public function __construct(private ParameterBagInterface $param)
    {
        
    }

    public function getCommitExplaination(string $commitMsg, string $commitContent, int $stepNumber) : string
    {
        $open_ai_key = $this->param->get('OPENAI_API_KEY');

        $open_ai = new OpenAi($open_ai_key);

        //completion of openai
        
        $complete =  $open_ai->completion([
            'model' => 'text-davinci-003',
            'prompt' => ('FOR A TUTORIAL BASED ON A GITHUB REPO, You have to explain (in a short way, MAX 150 words explainations + code included) what change in a commit and this (your all answer) is actually the '. $stepNumber.'th step in the tuto. Thanks to this message : "'.$commitMsg . '" and to this changes that have been release on this commit :"'.$commitContent . '", You MUST quote the code : format it for prism.css with pre, code etc tags; use the <code> tag for a short quote or file\'s names; i repeat : MAX 300 tokens'),
            'temperature' => 0,
            'max_tokens'=> 250,
            'top_p'=> 1.0,
            'frequency_penalty'=> 0.0,
            'presence_penalty'=> 0.0,
        ]);
        $json = json_decode($complete, true);
        // dd($json);
    
        // dd($json['choices'][0]['text']);
        return $json['choices'][0]['text'];
    }
}