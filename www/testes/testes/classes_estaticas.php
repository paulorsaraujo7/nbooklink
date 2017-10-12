<?php

require 'classes_estaticas/estatica.php';

/* O OBJETIVO É SABER SE SERÁ POSSIVEL ACESSAR OS MEMBROS ESTATICOS DE UMA 
 * CLASSE QUE ESTA DESCENDO NA RAIZ DE DIRETÓRIOS, POIS QUANDO O DIRETORIO
 * QUE TENTA ACESSAR INCLUE O ARQUIVO DE CLASSE QUE ESTA ACIMA, ENTAO NAO DA CERTO
 * 
 */
estatica::soma();
estatica::soma();
estatica::soma();
estatica::soma();
estatica::soma();
estatica::soma();
estatica::soma();

echo estatica::$s;
?>
