<?php 

use HeadlessChromium\BrowserFactory;

require_once "../vendor/autoload.php";

$browserFactory = new BrowserFactory();

$browser = $browserFactory->createBrowser(['customFlags' => ['--lang=pt-BR']]);

try 
{
    $page = $browser->createPage();
    $page->navigate('https://www.scielo.br/j/rlae/i/2023.v31/')->waitForNavigation();
    
    // título do artigo;
    $qtdArticle = $page->evaluate('document.getElementsByClassName("articles")[0].childElementCount')->getReturnValue();

    // Laço de repetição que percorre os artigos para pegar o token
    $array = [];
    $i = 0;    
    do
    {
        for($j = 0; $j < 3; $j++)
        {
            $dado = $page->evaluate('document.getElementsByClassName("articles")[0].
                                     getElementsByTagName("li")[1].                                              
                                     getElementsByTagName("a")[' . $j . '].
                                     getAttribute("href")')->getReturnValue() . "<br><br>";        
            switch($j)
            {
                case 0:
                    array_push($array, ['abstract_article1_en' => $dado]);
                    break;
                case 1:
                    array_push($array, ['abstract_article1_es' => $dado]);
                    break;
                case 2:
                    array_push($array, ['abstract_article1_pt' => $dado]);
                    break;
            }                                                
        }
        $i++;

    } while($i < 1);

    print_r($array);
} 
finally 
{
    $browser->close();
}