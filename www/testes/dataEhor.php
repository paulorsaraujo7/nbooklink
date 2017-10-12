<?php

date_default_timezone_set("Brazil/East"); //DEVE FIGURAR NA MUDANÇA DE IDIOMA
echo date("d/m/Y H:i:s", time()) . "<br>";

date_default_timezone_set("US/Central");
echo date("Y/n/d H:i:s", time()) . "<br>";

echo date("Y/n/d H:i:s", time());


?>
