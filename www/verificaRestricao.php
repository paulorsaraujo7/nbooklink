<?php
require_once 'principais.php';

/*
 * SCRIPT QUE GARANTE QUE SOMENTE TEM ACESSO A AREA RESTRITA QUEM ESTIVER LOGADO.
 * SE O USUARIO EXISTIR E A ASSINATURA FOR IGUAL, ENTAO TEM ACESSO. CASO CONTRARIO E REDIRECIONADO PARA HOME PAGE.
 * 
 * 1 - CARACTERISTICAS PRINCIPAIS
 *  1.1 - SE NAO AUTENTICADO LEVA PARA HOME PAGE INDICANDO ERRO.
 *  1.2 - O TRATAMENTO DE ERRO E FEITO NA HOME PAGE.
 *  1.3 - TODA PAGINA DE ACESSO RESTRITO DEVE TER UM INCLUDE PARA ESSE SCRIPT (VERIFICAR MELHORIA PARA ESTA CARACTERISTICA).
 */
try {
    //EXISTE SESSAO PRA O USUARIO?(O OBJETO Usuario ESTA DEFINIDO NA SESSAO - ESTA SERIALIZADO)
    if (isset($_SESSION['Usuario'])) {
        //----VERIFICAR SE A SESSAO E VALIDA.
        $Usuario = new Usuario();
        $Usuario = unserialize($_SESSION['Usuario']);    //CAPTURAR O OBJETO QUE ESTA NA SESSAO


        $email      = $Usuario->getEmail();
        $chave      = $_SESSION['_SESSAO_']['chave'];   //CHAVE SECRETA QUE FOI UTILIZADA NO MOMENTO DO LOGIN
        $ip         = $_SESSION['_SESSAO_']['ip'];      //IP DO USUARIO UTILIZADO NO MOMENTO DO LOGIN
        $hora       = $_SESSION['_SESSAO_']['hora'];    //HORA ARMAZENADA NO MOMENTO DO LOGIN
        $assinatura = md5($email . $chave . $ip . $hora); //ASSINATURA QUE E MD5 DOS ELEMENTOS UTILIZADOS NO MOMENTO DO LOGIN

        if ($assinatura != $_SESSION['_SESSAO_']['assinatura']) {//ASSINATURA GERADA NAO E IGUAL A QUE FOI GERADA NO MOMENTO DO LOGIN
            $_SESSION['_SESSAO_']['autenticado'] = FALSE;
            throw new Exception("erroSessaoInvalida");              //GERA EXCECAO DE SESSAO INVALIDA
        } else {//SESSAO VALIDA - NADA FAZ, POIS ESSE SCRIPT APENAS REDIRECIONA EM CASO DE ERRO.
            $_SESSION['_SESSAO_']['autenticado'] = TRUE;  //FICA NA SESSAO ARMAZENADO QUE O USUARIO ESTA AUTENTICADO.
        }
    }
    else //USUARIO NAO ESTA LOGADO
        throw new Exception("erroAcessoRestrito");
} catch (Exception $e) {
    header("Location:http://www.nbooklink.com/index.php?erro=" . $e->getMessage());
}
?>
