<?php
require_once 'principais.php';

//SE FECHAR O BROWSER SEM FAZER LOGOUT DESTROE A SESSAO NO PROX. ACESSO.
if ( empty($_SERVER['HTTP_REFERER']) && !isset($_COOKIE['IUS']) )
{
    if (isset($_SESSION['_Usuario_'])) unset($_SESSION['_Usuario_']); 
    if (isset($_SESSION['_SESSAO_'])) unset($_SESSION['_SESSAO_']);
}

//DEFINE IDIOMA A SER EXIBIDO:
if ( isset($_GET['i']) ) //VEIO UMA REQUISICAO EXPLICITA DE MUDANCA DE IDIOMA PELO $_GET
{
    NBLIdioma::mudarIdioma($_GET['i']);
}
 else {
    if ( !isset($_SESSION['_IDIOMA_']['idiomaSelecionado']) ) //NAO HA UM IDIOMA DEFINIDO
    {
        if ( isset($_COOKIE['i']) ) //HA COOKIE DEFINIDO
        {
            NBLIdioma::mudarIdioma($_COOKIE['i']);
        }
        else //NAO HA IDIOMA E NAO HA COOKIE, EH O PRIMEIRO ACESSO. O REDIRECIONAMENTO EH FEITO DIFERENTE (COM BASE NA URL).
        {
            //if ( isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'www.nbooklink.com.br') ) //VAI CARREGAR O PORTUGUES
            //    $idioma = "PT_BR";
           
            //if ( isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'www.nbooklink.com') ) //VAI CARREGAR O INGLES USA
            //{
                $idioma = "EN_US";
                NBLIdioma::mudarIdioma($idioma); //CHAMA A MUDANCA. SE VEIO DO .COM.BR JA FOI MUDADO NO REDIRECIONAMENTO
            //}
        }
    }
}


/* --- TRATAMENTO DOS ERROS QUE SAO DIRECIONADOS PARA HOME PAGE
 * 
 * PENDENCIAS
 * - DEFINIR AS DIVS ONDE SERAO EXIBIDOS ESTES ERROS
 */
//EXIBE CODIGOS DE ERRO VINDO PARA A HOME
if ( isset($_GET["erro"]) ) //SE FOR RETORNADO ERRO PARA HOME ENTAO EXIBA
{
    try {
        $tituloMsg = NBLIdioma::getTextoPorIdElemento('tituloCaixaModalErro');
        $msg       = NBLIdioma::getTextoPorIdElemento($_GET['erro']);
    } catch (Exception $exc) {
        $msg = $_GET["erro"]; /*SE FOR UM ERRO NAO PREVISTO NO BD, ENTAO EXIBE NA CAIXA EM VEZ DE MOSTRAR A TELA LARANJA*/
    }

}

//EXIBE CODIGOS DE ERRO VINDO PARA A HOME
if ( isset($_GET["msg"]) ) //SE FOR RETORNADO ERRO PARA HOME ENTAO EXIBA
{
    $tituloMsg = NBLIdioma::getTextoPorIdElemento('titulo'.$_GET['msg']);
    $msg       = NBLIdioma::getTextoPorIdElemento($_GET['msg']);
}


/*VERIFICA SE EXISTE COOKIE PARA MANTER CONECTADO: 
 * 
 * - SE EXISTIR E AINDA NAO TIVER SIDO DEFINIDA A VARIAVEAL $_SESSION['Usuario'] entao
 *   CARREGA DADOS DO USUARIO DO BD, CRIA UM OBJETO DO TIPO Usuario E ARMAZENA NA SESSAO ($_SESSION['Usuario'])
 *   OBS: TESTAR SE JA NAO ESTA DEFINIDA A VARIAVEL DE USUARIO EVITA CARREGAMENTO DESNECESSARIO.
 * 
 * EH PRECISO AINDA CRIAR OS DADOS DA ASSINATURA DA SESSAO PARA QUE A VERIFICACAO DE RESTRICAO FUNCIONE.
 * CRIAR A ASSINATURA SERA POSSIVEL POIS AS INFORMACOES PARA CRIAR A ASSINATURA 
 * ESTAO NA TABELA DE SESSAO E PODEM SER RECUPERADAS COM O ID DO USUARIO QUE ESTARA ARMAZENADO NO COOKIE
 */
if ( isset($_COOKIE["IUS"]) && !isset($_SESSION['Usuario']) ) //EXISTE COOKIE E AINDA NAO ESTA DEFINIDA A VARIAVEL (OCORRE QUANDO O USUARIO FECHA E ABRE O NAVEGADOR)
{
    //COM BASE NO IUS CAPTURAR OS DADOS NECESSARIOS PARA CRIAR ASSINATURA DA SESSAO
    
    /*O BLOCO A SEGUIR RECUPERA OS DADOS DO USUARIO NO BD, CRIA O OBJETO E ARMAZENA NA SESSAO*/
    
    
    $IUS = $_COOKIE["IUS"];                              //RECUPERA O ID DA ULTIMA SESSAO DO USUARIO  

    $Sessao = SessaoDAO::obterPorHashUltimaSessao($IUS); //RETORNA NULL OU O OBJETO QUE CONTEM A SESSAO ARMAZENADA PARA O USUARIO NO ULTIMO PROCESSO DE LOGIN

    
    /*OBTEM OBJETO USUARIO E ARMAZENA NA SESSAO RAM*/
    
    $Usuario   = UsuarioDAO::obterPorId($Sessao->getIdUsuario());    
    $Usuario->setSessao($Sessao);
    $_SESSION['_SESSAO_']['_USUARIO_']['idUsuario']       = (int) $Usuario->getIdUsuario();
    $_SESSION['_SESSAO_']['_USUARIO_']['email']           = $Usuario->getEmail();
    $_SESSION['_SESSAO_']['_USUARIO_']['nome']            = $Usuario->getNome();
    $_SESSION['_SESSAO_']['_USUARIO_']['login']           = $Usuario->getLogin();
    $_SESSION['_SESSAO_']['_USUARIO_']['senha']           = $Usuario->getSenha();
    $_SESSION['_SESSAO_']['_USUARIO_']['dataCadastro']    = serialize( $Usuario->getDataCadastro() );
    $_SESSION['_SESSAO_']['_USUARIO_']['temFoto']         = $Usuario->getTemFoto();
    $_SESSION['_SESSAO_']['_USUARIO_']['mensagemInicial'] = $Usuario->getMensagemInicial();
    $_SESSION['_SESSAO_']['_USUARIO_']['anoNascimento']   = $Usuario->getAnoNascimento();
    $_SESSION['_SESSAO_']['_USUARIO_']['mesNascimento']   = $Usuario->getMesNascimento();
    $_SESSION['_SESSAO_']['_USUARIO_']['diaNascimento']   = $Usuario->getDiaNascimento();
    $_SESSION['_SESSAO_']['_USUARIO_']['numeroDeLogins']  = $Usuario->getNumeroDeLogins();
    $_SESSION['Usuario'] = serialize($Usuario);               //ARMAZENA O OBJETO RECUPERADO NA SESSAO (MESMO PROCEDIMENTO QUE EH FEITO NO LOGIN)
    /*O BLOCO A SEGUIR CRIA A ASSINATURA DA ULTIMA SESSAO*/

    //---TRATAR A SEGURANCA DA SESSAO RAM (CRIA UMA ASSINATURA COM BASE EM DADOS DO USUARIO E ARMAZENA NA SESSAO RAM (VAI SER UTIL PARA EVITAR SEQUESTRO DE SESSOES COM BASE NO PHPSESSID)
    //OBS: TEM QUE SER TRATADO AQUI POIS ABAIXO SERAO UTILIZADAS AS INFORMACOES SOBRE IP PARA ATUALIZAR OU CRIAR SESSAO NO BD
    $chave      = "1a2cf8gk68gj67gf784kh69fo6";        //CHAVE SECRETA (EH A MESMA UTILIZADA NO MOMENTO DO LOGIN).
    $ip         = $Sessao->getUltimoIPUtilizado();     //IP UTILIZADO PELO USUARIO DA ULTIMA VEZ QUE ELE FEZ LOGIN
    
    //NA RECUPERAÇÃO DA HORA NA LINHA ABAIXO EH PRECISO TRANSFORMAR PARA TIME POIS O CALCULO PARA ASSINATURA NA AREA RESTRITA EH FEITO COM TIME QUE EH INT E NAO STRING
    $hora       = strtotime($Sessao->getUltimoLogin()->toStringGravar());           //HORA DE QUANDO O USUARIO FEZ O ULTIMO LOGIN 
    $email      = $Usuario->getEmail();
    $assinatura = md5($email . $chave . $ip . $hora);  //ASSINATURA COM ALGUNS DADOS DO USUARIO RECEM AUTENTICADO.
    $_SESSION["_SESSAO_"] = array("chave" => $chave, "ip" => $ip, "hora" => $hora, "assinatura" => $assinatura); //ALGUNS DADOS RELATIVOS AO LOGIN DO USUARIO QUE FICAM ARMAZENADOS NA SESSAO RAM
    $_SESSION["_SESSAO_"]["autenticado"] = TRUE;
    
    //NESTE PONTO, ESTAO DEFINIDAS AS MESMAS VARIAVEIS DE COMO SE O USUARIO ACABASSE DE FAZER LOGIN
}

 $t = new ViewTopo();
 if ( $t->isLogado() ) /*SE O USUARIO ESTIVER LOGADO, REDIRECIONA PARA PAGINA DE USUARIO LOGADO.*/
 {
     header("Location:usuarioLogado.php");
     exit();    /*SAI DO SCRIPT PARA EVITAR O ENVIO DO CODIGO HTML DO INDEX*/
 }


?>
<!DOCTYPE html>
<html>
    <head>
<!--ESSE CODIDO DE LIGACAO PARA OUTROS ARQUIVOS VAI PAR UM METODO NA CLASSE VIEW BASE        -->

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="content-language" content="en-us">
        <meta name="keywords" content="bookmarks favoritos">
        <meta name="description" content="Bookmarks online favoritos online">
        <meta name="robots" content="noindex,nofollow">
        <meta name="robots" content="noarchive">
        <meta name="author" content="NBookLink team">
        <meta name="reply-to" content="support@nbooklink.com">
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
        <link rel="shortcut icon" href="favicon.gif">
<!--        <meta content=”IE=EmulateIE9″ http-equiv=”X-UA-Compatible”></meta>-->
        
<!--    <meta name="robots" content="index,follow">
        <meta name="robots" content="noindex,follow">
        <meta name="robots" content="index,nofollow">
        <meta name="generator" content="Microsoft FrontPage 5.0">
        <meta http-equiv="refresh" content=" 5 ;url=http://www.novosite.com/">
-->
        
        <?php
            echo ViewBase::retornaArquivosDeLigacoes(); //Prepara os arquivos de ligacoes comuns a todas as pgs.
        ?>
        <link href="css/formCadUsuario.css" rel="stylesheet" type="text/css"/>
        <title><?php echo NBLIdioma::getTextoPorIdElemento('titleIndex'); ?></title>
        
        <script type="text/javascript" src="js/index.js"></script>
    </head>
    <body>
        <?php 
            if ( isset($_GET["msg"]) ) {
        ?>
                <div id="dialog-message" title="<?php echo $tituloMsg ?>">
                  <p>
                    <span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
                        <?php echo $msg ?>
                  </p>
                </div>
        <?php } ?>
        
        <?php 
            if ( isset($_GET['erro']) ) {
        ?>
                <div id="dialog-message" title="<?php echo $tituloMsg ?>">
                  <p>
                    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 50px 0;"></span>
                        <?php echo $msg ?>
                  </p>
                </div>
        <?php } ?>

        <div id="divTudo">
            <div id="imgGifAjax">
              <img src="imagens/imgGifAjax.png" class="icon" /> <span class="destaque">Carregando...</span>
            </div>
            
           <?php
                $t = new ViewTopo();
                $t->display();
           ?>
             <div id="divIndexCorpo">
                 <div id="divEsquerda" class="divComBorda">
                    <div style="position: relative; left: 15px; top: 15px; width: 590px" id="divOqueENBL">
                        <img src="imagens/imgLivroAzul.png" alt="<?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgOqueEoNBL'); ?>"/>
                        <span style="position:absolute;  left: 90px; top: 15px; line-height: 15px;">
                            <span style="color:#00a; font-weight: bold"><?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgOqueEoNBL'); ?></span> <br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgOqueEoNBL1'); ?><br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgOqueEoNBL2'); ?><br>
                        </span>
                    </div>
                    <div style="position: relative; left: 15px; margin-top: 40px; width: 590px " id="divFacilidades">
                        <img src="imagens/imgFacilidades.png" alt="<?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgFacilidadesDoNBL'); ?>"/>
                        <span style="position:absolute;  left: 90px; top: 5px; line-height: 15px;">
                            <span style="color:#00a; font-weight: bold"><?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgFacilidadesDoNBL'); ?></span> <br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgFacilidadesDoNBL1'); ?><br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgFacilidadesDoNBL2'); ?><br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgFacilidadesDoNBL3'); ?><br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgFacilidadesDoNBL4'); ?><br>
                        </span>
                    </div>
                    <div style="position: relative; left: 15px; margin-top: 40px; width: 590px " id="divPrivacidade">
                        <img src="imagens/imgCadeado.png" alt="<?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgPrivacidadeNoNBL'); ?>"/>
                        <span style="position:absolute;  left: 90px; top: 5px; line-height: 15px;">
                            <span style="color:#00a; font-weight: bold"><?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgPrivacidadeNoNBL'); ?></span> <br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgPrivacidadeNoNBL1'); ?><br>
                            <?php echo NBLIdioma::getTextoPorIdElemento('subMsgPrivacidadeNoNBL2'); ?><br>
                            <a href="privacyPolicy.php"><?php echo NBLIdioma::getTextoPorIdElemento('subMsgPrivacidadeNoNBL3'); ?></a>
                        </span>
                    </div>
                    <div style="position: relative; left: 15px; margin-top: 20px; width: 590px" id="divEntrarEmContato">
                        <img src="imagens/imgEntrarEmContato.png" alt="<?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgEntrarEmContato'); ?>"/>
                        <span style="position:absolute;  left: 90px; top: 15px; line-height: 15px;">
                            <span style="color:#00a; font-weight: bold"><?php echo NBLIdioma::getTextoPorIdElemento('tituloMsgEntrarEmContato'); ?></span> <br>
                            <a href="contact.php"><?php echo NBLIdioma::getTextoPorIdElemento('linkMsgEntrarEmContato'); ?></a>                            
                        </span>
                     </div>

                </div>
                 <div id="divFormCadUsuario" class="divFormulario divComBorda" >
                    <label id="labelTituloFormCadUsuario" class="labelTituloForm" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelTituloFormCadUsuario'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelTituloFormCadUsuario'); ?> </label>
                    <img id="imgCadUsuario" src="imagens/imgCadUsuario.png" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altImgCadUsuario'); ?>" >
                    <!--AS RESPOSTAS PRINCIPAIS VEM PARA ESSA DIV. -->
                    <div id="divRespostaFormCadUsuario">
                        
                    </div>
                    <form id="formCadUsuario" name="formCadUsuario" action="recebeFormCadUsuario.php" method="post" autocomplete="off" >
                        <label id="labelFormCadUsuarioNome" class="labelEntradaForm"  for="inputFormCadUsuarioNome" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioNome'); ?>" > *<?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioNome'); ?> </label>
                        <input id="inputFormCadUsuarioNome" name="nome" type="text" maxlength="100" tabindex="0">
                        <label id="labelRespostaFormCadUsuarioNome"></label> 
                        <label id="labelFormCadUsuarioEmail" class="labelEntradaForm"  for="inputFormCadUsuarioEmail" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioEmail'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioEmail'); ?> </label>
                        <input id="inputFormCadUsuarioEmail" name="email" type="text" maxlength="100" tabindex="0" >
                        <label id="labelRespostaFormCadUsuarioEmail" ></label>
                        <label id="labelFormCadUsuarioSenha" class="labelEntradaForm"  for="inputFormCadUsuarioSenha" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioSenha'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioSenha'); ?> </label>
                        <input id="inputFormCadUsuarioSenha" name="senha" type="password" maxlength="45" tabindex="0" >
                        <label id="labelRespostaFormCadUsuarioSenha" ></label>
                        <label id="labelFormCadUsuarioConfirmaSenha" class="labelEntradaForm"  for="inputFormCadUsuarioConfirmaSenha" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioConfirmaSenha'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioConfirmaSenha'); ?> </label>
                        <input id="inputFormCadUsuarioConfirmaSenha" name="confirmaSenha" type="password" tabindex="0" >
                        <label id="labelFormCadUsuarioMensagemInicial" class="labelEntradaForm"  for="textAreaFormCadUsuarioMensagemInicial" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioMensagemInicial'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioMensagemInicial'); ?> </label>
                        <textarea id="textAreaFormCadUsuarioMensagemInicial" name="mensagemInicial" type="" tabindex="0" ></textarea>
                        <!-- CAPTCHA  -->
                        <label id="labelFormCadUsuarioImgCAPTCHA" class="labelEntradaForm"  for="inputFormCadUsuarioImgCAPTCHA" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioImgCAPTCHA'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioImgCAPTCHA'); ?> </label>
                        <input id="inputFormCadUsuarioImgCAPTCHA" name="ImgCAPTCHA" type="text" tabindex="0">
                        <label id="labelRespostaFormCadUsuarioImgCAPTCHA"></label>


                        <input id="_NBL_Action" type="hidden" name="_NBL_Action" value="_NBL_Action_Create" />
                        <input id="_NBL_View"   type="hidden" name="_NBL_View"   value="_NBL_View_Index" />

                        <input id="inputSubmitFormCadUsuario" class="clicavel" type="submit" value="<?php echo NBLIdioma::getTextoPorIdElemento('inputSubmitFormCadUsuario'); ?> ">
                        <img id="imgCAPTCHA" src="imagens/imgCAPTCHA.php"</img>
                        <a   id="imgRecarregaCAPTCHA" href="">
                            <img src="imagens/imgRecarregaCAPTCHA.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgRecarregaCAPTCHA'); ?>" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altImgRecarregaCAPTCHA'); ?>" >
                        </a>
                    </form>
                </div>
             </div>
           <?php
            $r = new ViewRodape();
            $r->display();
           ?>
        </div>
    </body>
</html>

