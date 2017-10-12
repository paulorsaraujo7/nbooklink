<?php
require_once 'principais.php';        //ARQUIVOS COM CLASSES BASES
$recebe = new GruposHiperLinksUsuarioController($_REQUEST); /*O ARRAY DA REQUISICAO EH PASSADO NO CONSTRUTOR*/
$recebe->run();                                      /*RODA A REQUISIACAO*/

