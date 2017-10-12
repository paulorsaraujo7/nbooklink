<?php

$var1 = "teste1";
$$var1 = "teste2"; //A PARTIR DAQUI teste1 passa a ser uma variável.

echo $var1 . "<br>";
echo $teste1 . "<br>";
echo $$var1;

//NÃO SEI EM QUE PODERIA SER ÚTIL.
?>
