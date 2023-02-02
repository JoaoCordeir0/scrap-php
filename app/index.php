<?php 

use HeadlessChromium\BrowserFactory;

require_once "../vendor/autoload.php";

$browserFactory = new BrowserFactory();

$browser = $browserFactory->createBrowser(['customFlags' => ['--lang=pt-BR']]);

try 
{
    $page = $browser->createPage();
    $page->navigate('https://github.com/JoaoCordeir0')->waitForNavigation();
    
    echo $page->evaluate('document.getElementsByClassName("p-name")')->getReturnValue();
} 
finally 
{
    $browser->close();
}