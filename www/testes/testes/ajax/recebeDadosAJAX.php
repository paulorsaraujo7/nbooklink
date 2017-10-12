<?php

if (isset($_GET["nome"]) && isset($_GET["cidade"]) && $_GET["nome"] !== "" && $_GET["cidade"] !== "") {
    $nome = strip_tags($_GET["nome"]);
    $cidade = strip_tags($_GET["cidade"]);


    $html = "<p> Os dados enviados foram: </p>\n";
    $html.= "<ul>";
    $html.= "<li>Nome:$nome </li>\n";
    $html.= "<li>Cidade: $cidade </li>\n";
    $html.= "</ul>";
    echo $html;
} else {

    echo "
         <img src=\"imagens/respostaERRO.png\"></img>
         Por favor, preencha os dois campos.
         ";
}
?>
