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
            'prompt' => ('FOR A TUTORIAL BASED ON A GITHUB REPO, You have to explain (in a short way, MAX 100 words explainations + code include) what change in a commit and this (your all answer) is actually the '. $stepNumber.'th step in the tuto. Thanks to this message : "'.$commitMsg . '" and to this changes that have been release on this commit :"'.$commitContent . '", For each file explain what happened in the code, and you must quote at least 1 line of the code that you are explaining; format it like : <code class="here put the right prism.js class">code-content</code> for EACH line of code); i repeat : MAX 200 tokens'),
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