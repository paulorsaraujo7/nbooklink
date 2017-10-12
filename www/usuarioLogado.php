<?php
require_once 'principais.php';
require_once 'verificaRestricao.php'; //SE NAO ESTIVER CONECTADO VAI PRA HOME
$t = new ViewTopo();
$r = new ViewRodape();
/*ALGUMAS VARIAVEIS DE PREPARACAO*/
 /*$totalDeLinks - utilizada para se o usuario nao possuir link algum, entao exibir uma msg*/
 $Usuario = new Usuario();
 $Usuario = unserialize($_SESSION['Usuario']);
 $totalDeLinks = HiperLinksUsuarioDAO::totalDeLinksDoUsuario($Usuario->getIdUsuario());
/*FIM - ALGUMAS VARIAVEIS DE PREPARACAO*/
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="content-language" content="pt-br">
        <meta name="keywords" content="bookmarks, social network socialBookmarks">
        <meta name="description" content="Bookmarks online">
        <meta name="robots" content="noindex,nofollow">
        <meta name="robots" content="noarchive">
        <meta name="author" content="NBookLink team">
        <meta name="reply-to" content="nbooklink@nbooklink.com">
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
        <link rel="shortcut icon" href="favicon.gif">
        <?php echo ViewBase::retornaArquivosDeLigacoes(); //Prepara os arquivos de ligacoes comuns a todas as pgs.?>
        <script>
            var iconCarregando                                  = $(<?php echo ViewBase::retornaGifAjax() ?>);
            var iconCarregandoNivel2                            = $(<?php echo ViewBase::retornaGifAjax() ?>); /*FUNCAO AJAX DENTRO DE OUTRA DEVE USAR ESTE PARA SEGUNDA REQUISICAO*/
            var msgConfirmaExclusaoDeGrupoDeLinks               = "<?php echo NBLIdioma::getTextoPorIdElemento('msgConfirmaExclusaoDeGrupoDeLinks') ?>";
            var titulomsgConfirmaExclusaoDeGrupoDeLinks         = "<?php echo NBLIdioma::getTextoPorIdElemento('titulomsgConfirmaExclusaoDeGrupoDeLinks') ?>";
            var valueBotaoExcluirGenerico                       = "<?php echo NBLIdioma::getTextoPorIdElemento('valueBotaoExcluirGenerico') ?>";
            var valueBotaoCancelarGenerico                      = "<?php echo NBLIdioma::getTextoPorIdElemento('valueBotaoCancelarGenerico') ?>";  
            var msgConfirmaExclusaoDeLinkParaTodosOsGrupos      = "<?php echo NBLIdioma::getTextoPorIdElemento('msgConfirmaExclusaoDeLinkParaTodosOsGrupos') ?>";
            var msgConfirmaExclusaoDeLinkParaGrupoAtual         = "<?php echo NBLIdioma::getTextoPorIdElemento('msgConfirmaExclusaoDeLinkParaGrupoAtual') ?>";
            
            
        </script>
        <script src="js/usuarioLogado.js"></script>
        <title><?php echo NBLIdioma::getTextoPorIdElemento('titleIndex'); ?></title>
    </head>
    <body>
        <div id="divTudo">
        <?php
        $t->display();
        ?>
            <?php 
                //MSG DE BOAS VINDAS - SE FOR O PRIMEIRO LOGIN
                $tituloMsg = NBLIdioma::getTextoPorIdElemento('tituloCaixaModalBoasVindas');;
                $msg       = NBLIdioma::getTextoPorIdElemento('msgBoasVindas');;
                //$_SESSION['_SESSAO_']['boas_vindas'] eh setada para nao repetir a msg quando for o primeiro login e atualizar a pg
                if ( $Usuario->getNumeroDeLogins() == 1 && !isset($_SESSION['_SESSAO_']['boas_vindas']) ) {
            ?>
                    <div id="dialog-message" title="<?php echo $tituloMsg ?>">
                      <p>
                        <span class="ui-icon ui-icon-circle-check" style="float: left; margin: 0 7px 50px 0;"></span>
                            <?php 
                                $_SESSION['_SESSAO_']['boas_vindas'] = 1;
                                echo $msg 
                            ?>
                      </p>
                    </div>
            <?php } ?>
             <div id="divIndexCorpo">
                <div id ="divMenusDeComando">
                    <div id="divMenuDeComando1" class="divComBorda">

                        <?php echo ViewHiperLinkUsuario::obterLinkDeComandoParaFormLink             (0, "usuarioLogado", "_NBL_Action_Create", ""); ?>                        
                        <?php echo ViewGruposHiperLinksUsuario::obterLinkDeComandoParaFormGruposLink(0, "usuarioLogado", "_NBL_Action_Create", ""); ?>
                        
                        <a id="linkMenuComandoTopLinks" style="position:relative; margin-bottom: 10px; " href="#" class="displayBlock">
                        <img id="imgTopLinks"  style="position: relative; top: 5px;"   
                                 src="imagens/imgTopLinks.png" 
                                 title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgLinkMenuComandoTopLinks'); ?>" 
                                 alt="<?php   echo NBLIdioma::getTextoPorIdElemento('linkMenuComandoTopLinks'); ?>" >                        
                                 <?php        echo NBLIdioma::getTextoPorIdElemento('linkMenuComandoTopLinks'); ?>
                        </a>  
                    </div>

                    <div id="divMenuDeComando2" class="divComBorda">
                        

                        <a id="linkMenuComandoMinhaConta" style="position:relative; margin-bottom: 10px; " href="" class="displayBlock">
                        <img id="imgEntrarEmContatoMenu"  style="position: relative; top: 5px;"   
                                 src="imagens/imgMinhaContaMenu.png" 
                                 title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgContaUsuarioMenuComando'); ?>" 
                                 alt="<?php   echo NBLIdioma::getTextoPorIdElemento('linkMenuComandoContaUsuario'); ?>" >                        
                                 <?php        echo NBLIdioma::getTextoPorIdElemento('linkMenuComandoContaUsuario'); ?>
                            
                            
                            
                        </a>  
                        
                        
                        <a   style="position: absolute; top:50px; left: 35px;" href="contact.php"><?php echo NBLIdioma::getTextoPorIdElemento('linkMenuComandoEntrarEmContato'); ?></a>  
                        <img style="position: absolute; top:45px; left: 10px;" id="imgEntrarEmContatoMenu" src="imagens/imgEntrarEmContatoMenu.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgEntrarEmContatoMenuComando'); ?>" alt="<?php echo NBLIdioma::getTextoPorIdElemento('linkMenuComandoEntrarEmContato'); ?>" >
                    </div>

                </div>    
                <div id="divConteudoCentro"> 
                    
                    <div id="dialogConfirmaExclusaoDeGrupoDeLinks" style="display: none">
                        <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                            <?php 
                                 //VAI DIVIDIR A STRING DA MENSAGEM PARA A CADA PONTO DE INTERROGAÇÃO SEJA INSERIDA UMA QUEBRA DE LINHA
                                $array  = explode ("?",NBLIdioma::getTextoPorIdElemento('msgConfirmaExclusaoDeGrupoDeLinks')) ;
                                $string = implode("?</br>", $array); 
                                echo $string;
                            ?>
                        </p>
                    </div>            
<!--                    <div id="divConteudoCentroResposta" style="position: relative; top: 0px;"></div>-->
                    <?php
                        /*O USUARIO NAO POSSUI LINK ALGUM CADASTRADO - EXIBE MSG NA DIV DO CENTRO*/
                        if ( $totalDeLinks == 0 )
                        {
                                /*SE O USUARIO NAO POSSUI LINK ENTAO EXIBE UMA MENSAGEM NO CENTRO DA TELA*/
                                $array  = explode (".",NBLIdioma::getTextoPorIdElemento('labelMsgUsuarioNaoPossuiLink')) ; //DIVIDE A STRING EM UM ARRAY SEPARANDO PELO PONTO
                                $string = implode(".</br>", $array); 
                                echo "
                                      <img src=\"imagens/imgExplicacao.png\">
                                      <div id=\"divExplicacaoUsuarioNaoPossuiLink\">
                                      $string
                                      </div>
                                ";
                        }
                        else /*EXIBE OS 10 LINKS MAIS RECENTES E OS 10 MAIS VISITADOS*/
                        {
                            echo ViewHiperLinkUsuario::topLinks($Usuario->getIdUsuario());
                        }
                        /*FIM -O USUARIO NAO POSSUI LINK ALGUM CADASTRADO - EXIBE MSG NA DIV DO CENTRO*/
                    ?>

                    
                    <div id="divConteudoCentroAbaixo" style="position: relative; top: 0px;">
                    </div>
                </div>
                 <div id="divConteudoDireita" class="divComBorda" >
                     <div id="divGruposLinksDireita" style="padding: 10px;">
                         
                        <?php 
                            echo ViewGruposHiperLinksUsuario::obterGruposDeHiperLinkDeUsuarioEmBulletsHTML($Usuario->getIdUsuario()); 
                                    
                            /*O USUARIO NAO POSSUI LINK ALGUM CADASTRADO - EXIBE MSG NA DIV DO CENTRO*/
                            $totalDeGrupos = GruposHiperLinksUsuarioDAO::totalDeGruposDoUsuario($Usuario->getIdUsuario());
                            if ( $totalDeGrupos == 0 )
                            {
                                
                                    /*SE O USUARIO NAO POSSUI LINK ENTAO EXIBE UMA MENSAGEM NO CENTRO DA TELA*/
                                    $array  = explode (".",NBLIdioma::getTextoPorIdElemento('labelMsgUsuarioNaoPossuiLinkDivDireita')) ; //DIVIDE A STRING EM UM ARRAY SEPARANDO PELO PONTO
                                    $string = implode(".</br>", $array); 
                                    echo "
                                        <div id=\"divExplicacaoNaDireitaUsuarioNaoPossuiLink\">
                                          $string
                                        </div>
                                    ";
                            }
                        ?>
                     </div>
                </div>
             </div>
<?php
$r->display();
?>
        </div>
    </body>
</html>
