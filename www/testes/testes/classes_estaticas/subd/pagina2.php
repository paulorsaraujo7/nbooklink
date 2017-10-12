<?php

include_once '../estatica.php'; //AQUI JÁ NÃO ENXERGA MAIS AS VARIÁVEIS ESTÁTICAS.

estatica::soma();
estatica::soma();
echo estatica::$s; //ERA PRA RETORNAR MAIS QUE O VALOR 6
?>
