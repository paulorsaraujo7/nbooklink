<?php

/*
 * Cuidará de tratar a sessão
 * No decorrer da navegação $_SESSION terá as seguintes variáveis
 *
 * $_SESSION['idioma']
 * $_SESSION['usuario']
 * $_SESSION['sessao']
 * $_SESSION['elementos']
 *
 *
 *
 */
session_start();
if (!isset($_SESSION['sessao'])) { //

    session_cache_limiter("private"); //DADOS DA SESSÃO SÃO PRIVADOS
    session_cache_expire(1); // POR PADRÃO DURA UM MINUTO
}
?>
