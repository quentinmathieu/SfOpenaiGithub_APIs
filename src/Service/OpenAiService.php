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
            'prompt' => ('In this tutorial, we are documenting changes made in a GitHub repository commit. You are currently on step ' . $stepNumber . '. Your objective is to provide a detailed explanation, including code snippets, of what changes were introduced in this specific commit. Please make sure your response adheres to the following guidelines:\n\n' .
        
            '1. Explanation (Maximum 200 tokens):\n' .
            '   - Clearly describe the purpose and impact of this commit.\n' .
            
            '2. Code Inclusion (Maximum 200 tokens):\n' .
            '   - Include relevant code snippets that demonstrate the changes.\n' .
            '   - Use Prism.css code blocks with <pre> and <code> tags for all code snippets.\n' .
            '   - When referencing short code snippets or file names, wrap them in <code> tags.\n' .
            '   - Ensure that all code blocks are properly closed to maintain correct formatting.\n\n' .
            
            'Refer to the commit message for this step: "' . $commitMsg . '" and provide a comprehensive explanation of the changes made in this commit: "' . $commitContent . '".\n\n' .
            
            'Your total response, including explanations and code, should not exceed 400 tokens. Be concise, clear, and informative. Your contribution to this tutorial is invaluable.'),
            'temperature' => 0,
            'max_tokens'=> 500,
            'top_p'=> 1.0,
            'frequency_penalty'=> 0.0,
            'presence_penalty'=> 0.0,
        ]);
        $json = json_decode($complete, true);
        $string = str_replace('Explanation (200 tokens):', '', $json['choices'][0]['text']);
        $string = str_replace('Code Inclusion (200 tokens):', '', $string);


        //close unclosed tags
        $dom = new \DOMDocument();

        // @ is avoids warnings
        @$dom->loadHTML($string, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $result = $dom->saveHTML();  

        return $result;
    }
}