<?php 

use HeadlessChromium\BrowserFactory;

require_once "../vendor/autoload.php";

if (isset($_GET['token'], $_GET['lang']))
{
    $browserFactory = new BrowserFactory();

    $browser = $browserFactory->createBrowser(['customFlags' => ['--lang=pt-BR']]);

    try 
    {    
        $page = $browser->createPage();
        $page->navigate('https://www.scielo.br/j/rlae/a/' . $_GET['token'] . '/?lang=' . $_GET['lang'])->waitForNavigation();
            
        // título do artigo;
        $title = $page->evaluate('document.getElementsByClassName("article-title")[0].innerText')->getReturnValue();

        // Quantidade de autores;
        $qtdAutor = $page->evaluate('document.getElementsByClassName("contribGroup")[0].childElementCount')->getReturnValue();

        // Laço de repetição para trazer os autores com base na quantidade obtida acima;
        $autor = "";
        for ($i = 1; $i < $qtdAutor; $i++)
        {
            $autor .= $page->evaluate('document.getElementById("contribGroupTutor' . $i . '").textContent')->getReturnValue() . ', ';
        }

        $body_article = $page->evaluate('document.getElementsByClassName("col-sm-offset-0")[0].innerHTML')->getReturnValue();
            
        $arrArticle = [
            'title_article' => $title,
            'autor_article' => str_replace(' ,', ',', $autor),
            'body_article'  => $body_article,
        ];

        echo "<p> <b>Título: </b>" . $arrArticle['title_article'] . "</p>";
        echo "<p> <b>Autores: </b>" . $arrArticle['autor_article'] . "</p>";
        echo $arrArticle['body_article'];        
    } 
    finally 
    {
        $browser->close();
    }
}
else
{
    echo "Não é possível processar sem parâmetros";
    exit();
}