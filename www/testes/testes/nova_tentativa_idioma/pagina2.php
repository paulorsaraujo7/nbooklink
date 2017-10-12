<?php

session_start();

$i = $_SESSION["idioma"] = '';
if ($i == '') {
    $ob = new MIdioma();
    $ob->carregaMensagens($idioma);
    $_SESSION["idioma"] = 'd';
}
?>
