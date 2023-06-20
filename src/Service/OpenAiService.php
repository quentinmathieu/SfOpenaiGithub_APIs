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
            'prompt' => ('thanks to this message : '.$commitMsg . "\n and to this changes that have been release on this commit :". $commitContent . "\n , For each file (if it's pertinent) explain what happens in the code , and quote the code with '<code></code>' (it's for a tutorial);the actuel step in this tutorial is :" . $stepNumber),
            'frequency_penalty' => 0.5,
            'presence_penalty' => 0,
        ]);
        $json = json_decode($complete, true);
        // dd($json);
    
        
        return $json['choices'][0]['text'];
    }
}