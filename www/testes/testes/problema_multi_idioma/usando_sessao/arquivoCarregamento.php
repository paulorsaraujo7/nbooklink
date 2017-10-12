<?php

session_start();

/* FALTA DEFINIR O PRIMEIRO CARREGAMENTO E A MUDANÇA DE IDIOMA.
 * OBEDECER AS REGRAS DO ARQUIVO QUE DEFINE A FUNÇÃO DE MULTIIDIOMA
 */

if (!isset($_SESSION['idioma'])) {
    echo "passei por aqui";
    $idioma = array(
        "msg1" => "mensagem1",
        "msg2" => "mensagem2",
        "msg3" => "mensagem3"
    );



    $_SESSION['idioma'] = $idioma;
} else {
    echo "não vou ter que carregar novamente";
}
?>
