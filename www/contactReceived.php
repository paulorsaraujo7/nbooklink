<?php
require_once 'principais.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="css/contactReceived.css" rel="stylesheet" type="text/css"/>
        
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
        <link rel="shortcut icon" href="favicon.gif">

        <?php
            $viewTopo = new ViewTopo();
            $viewRodape = new ViewRodape();
            echo ViewBase::retornaArquivosDeLigacoes();
        ?>
        <title><?php echo NBLIdioma::getTextoPorIdElemento('titleIndex'); ?></title>
    </head>
    <body>
        <div id="divTudo">
        <?php
             $viewTopo->display();
         ?>
         <?php
            $array  = explode (".",NBLIdioma::getTextoPorIdElemento('msgOKContatoComNBLEnviado')); //DIVIDE A STRING EM UM ARRAY SEPARANDO PELO PONTO
            $string = implode(".</br>", $array); 
         ?>
        <div id="divConteudoUnicoContactReceived" class="divComBorda">
            <img id="imgRespostaOKContactReceived" src="imagens/imgRespostaOK.png">
            <div id="divTextoRespostaContactReceived"> 
                <?php
                  echo $_GET['from'] . ",</br>" . $string;
                ?>
            </div>
            <a id="linkEnviarOutraMensagemContactReceived" href="contact.php"><?php echo NBLIdioma::getTextoPorIdElemento('linkEnviarOutraMensagem'); ?></a>  
            <a id="linkIrParaHomePageContactReceived" href="index.php"><?php echo NBLIdioma::getTextoPorIdElemento('linkIrParaHomePage'); ?></a>  
        </div>
       <?php    
            $viewRodape->display();
        ?>
        </div>
    </body>
</html>
