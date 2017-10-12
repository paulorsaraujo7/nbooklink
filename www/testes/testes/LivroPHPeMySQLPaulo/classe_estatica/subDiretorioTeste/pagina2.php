<?php

require_once '../Visitor.php';

$visits4 = new Visitor();
$visits5 = new Visitor();
$visits6 = new Visitor();
$visits7 = new Visitor();



echo Visitor::getVisitors() . "<br>"; //ERA PRA EXIBIR 4
//COM REQUIRE NÃO FUNCIONA.
//COM INCLUDE TAMBÉM NÃO
//NA CRIAÇÃO COLOCANDO NA SESSAO
?>
