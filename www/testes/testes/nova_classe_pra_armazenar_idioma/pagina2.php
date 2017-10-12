<?php
require_once 'MIdioma.php';
session_start();
?>

<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        echo MIdioma::$ms1; //NAO EXIBE NADA MESMO QUE A VARIÁVEL JÁ TENHA SIDO DEFINIDA, INCLUSIVE NO CONSTRUTOR

        $objetoTestePagina2 = $_SESSION['objetoTeste'];
        $objetoTestePagina2->getMsg4();
        ?>
    </body>
</html>
