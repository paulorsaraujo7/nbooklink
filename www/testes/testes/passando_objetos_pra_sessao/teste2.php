<?php

require_once ("classeTeste.php");
session_start();
$c = $_SESSION["teste"];
echo $c::$s; // ACESSOU A VARIÁVEL ESTÁTICA MESMO RECEBENDO UMA VARIÁVEL DE CLASSE!
?>
