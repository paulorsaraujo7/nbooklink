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
        //ESS CHAMADA GERA UM ERRO POIS O CONSTRUTOR É PRIVADO 
        //$objetoMIdioma = new MIdioma();
        //Ao carregar o idioma já retorna a instancia da classe.
        //MIdioma::carregarIdioma("PT_BR");
        //echo MIdioma::$ms1;
        //$objeto = MIdioma::getInstance();
        //var_dump($objeto);
        //echo $objeto->getMsg4();
        //$_SESSION['objetoTeste'] = $objeto;

        echo "<br>este  é o valor do idioma: ";
        MIdioma::$idioma;
        echo "<br>este  é o valor do idiomaAnterior: ";
        MIdioma::$idiomaAnterior;


        MIdioma::carregaMensagens('pt_br');
        echo MIdioma::$ms1;

        echo "<br>este  é o valor do idioma: ";
        MIdioma::$idioma;
        echo "<br>este  é o valor do idiomaAnterior: ";
        MIdioma::$idiomaAnterior;


        //MIdioma::carregarIdioma("EN_US");
        //echo MIdioma::$ms1;






        /* SEM USAR SINGLETON
          $MIdioma = new MIdioma();
          $MIdioma->carregarIdioma("PT_BR");
          echo $MIdioma->ms1;
          $_SESSION["classeMIdioma"] = $MIdioma;
         */
        ?>
    </body>
</html>
