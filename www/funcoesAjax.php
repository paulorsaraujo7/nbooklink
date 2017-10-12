<?php
/*SERA O ARQUIVO CHAVEADOR DE FUNCOES QUE SAO CHAMADAS POR AJAX*/
require_once "principais.php";
require_once "verificaRestricao.php";
$action     = $_REQUEST['action']; /*O ACTION EH O NOME DA FUNCAO A SER INVOCADA POR AJAX*/

$idUsuario  = $_SESSION['_SESSAO_']['_USUARIO_']['idUsuario']; /*O ID DO USUARIO SERA O DA SESSAO*/
$request = array();


if ( $action == 'obterTopLinks' ) /*OBTEM OS TOP LINKS DO USUARIO LOGADO*/
{
    echo ViewHiperLinkUsuario::topLinks($idUsuario);
}   


if ( $action == 'excluirGrupoDeLinks' ) /*EXCLUI UM GRUPO DE LINKS - JA VERIFICA A RESTRICAO DE POSSE DA ENTIDADE A SER EXCLUIDA*/
{
   
    /*No request tem que estar presente _NBL_Action = _NBL_Action_delete */
    $request["_NBL_Action"] = "_NBL_Action_delete";
    $request["idEntidade"]  = $_REQUEST['idEntidade'];
    $controller = new GruposHiperLinksUsuarioController($request);
    $controller->run(); /*Para ser exibida a msg de retorno bastaria usar echo $controller->run()*/
}   

if ( $action == 'excluirLinkParaTodosGrupos' ) /*EXCLUI UM LINK DE UM DETERMINADO GRUPO*/
{
    
    /*JA EH PREENCHIDO AUTOMATICAMENTE NA REQUISICAO*/
    $request["_NBL_Action"] = "_NBL_Action_delete"; 
    $request["idEntidade"]  = $_REQUEST['idEntidade'];
    $controller = new HiperLinkUsuarioController($request);
    $controller->run(); /*Para ser exibida a msg de retorno bastaria usar echo $controller->run()*/
}   

if ( $action == 'excluirLinkParaGrupoAtual' ) /*EXCLUI UM LINK DE UM DETERMINADO GRUPO*/
{

    /*JA EH PREENCHIDO AUTOMATICAMENTE NA REQUISICAO*/
    $request["_NBL_Action"] = "_NBL_Action_delete"; 
    $request["idEntidade"]  = $_REQUEST['idEntidade'];
    $request["idGrupo"]     = $_REQUEST['idGrupo'];
    $controller = new HiperLinkUsuarioController($request);
    $controller->run(); /*Para ser exibida a msg de retorno bastaria usar echo $controller->run()*/
}   




/*Necessita de validacao da requisicao - somente o usuario dono: SOMENTE ACEITA COMO ID O USUARIO LOGADO*/
if ($action == 'obterListaHTMLDeTodosOsLinksDeUmUsuario') /*EXIBE OS GRUPOS DE UM USUARIO EM CHECKS HTML*/
{
    echo ViewHiperLinkUsuario::obterListaHTMLDeTodosOsLinksDeUmUsuario($idUsuario);
}   

/*Necessita de validacao da requisicao - somente o usuario dono: SOMENTE ACEITA COMO ID O USUARIO LOGADO*/
if ($action == 'obterGruposDeHiperLinkDeUsuarioEmChecksHTML') /*EXIBE OS GRUPOS DE UM USUARIO EM CHECKS HTML*/
{
    $t = array(); /*ARRAY QUE VAI COMO ARGUMENTO DA FUNCAO QUE EXIBE OS CHECKS*/
    if ( isset($_REQUEST['grupos']) ) /*SE TIVER ALGUM GRUPO MARCADO PASSA PARA VARIAVEL QUE SERA ENVIADA PARA FUNCAO*/
    {
        $t = $_REQUEST['grupos'];
    }
    echo ViewGruposHiperLinksUsuario::obterGruposDeHiperLinkDeUsuarioEmChecksHTML($idUsuario, $t);
}   

/*Necessita de validacao da requisicao - somente o usuario dono: SOMENTE ACEITA COMO ID O USUARIO LOGADO*/
if ($action == 'obterGruposDeHiperLinkDeUsuarioEmBulletsHTML') /*EXIBE OS GRUPOS DE UM USUARIO EM BULLETS QUE APARECE NA DIV DIREITA*/
{
    echo ViewGruposHiperLinksUsuario::obterGruposDeHiperLinkDeUsuarioEmBulletsHTML($idUsuario);
}

/*Necessita de validacao da requisicao - somente o usuario dono: SOMENTE ACEITA COMO ID O USUARIO LOGADO*/
if ($action == "obterListaDeGruposPorGrupoEUsuario") /*EXIBE OS LINKS DE UM DETERMINADO GRUPO DE USUARIO*/
{
    $idGrupo = (int) $_REQUEST['idGrupo'];
    echo ViewGruposHiperLinksUsuario::obterListaDeGruposPorGrupoEUsuario($idUsuario, $idGrupo);
}

/*Necessita de validacao da requisicao - somente o usuario dono: SOMENTE ACEITA COMO ID O USUARIO LOGADO*/
if ($action == "clickHiperLinkUsuario") /*REGISTRA OS DADOS DE UM CLICK EM UM LINK DE USUARIO*/
{
    $idHiperLinkUsuario = (int) $_REQUEST['idHiperLinkUsuario'];
    HiperLinksUsuarioDAO::registrarAcesso($idHiperLinkUsuario);
}