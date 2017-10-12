<?php 
    require_once "principais.php";
    require_once "verificaRestricao.php";
    $idHiperLinkUsuario  = $_REQUEST['idHiperLinkUsuario']; //id da entidade
    //$_NBL_View           = $_REQUEST['_NBL_View'];                //quem chamou o form (para fins de atualizacao)
    //$_NBL_Action         = $_REQUEST['_NBL_Action'];              //qual a acao
    //$_NBL_Container      = $_REQUEST['_NBL_Container'];           //Sem uso, por enquanto - Indica em qual container o form deve ser exibido se for chamado por AJAX
?>
<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php //echo ViewBase::retornaArquivosDeLigacoes(); ?>
        <title><?php echo NBLIdioma::getTextoPorIdElemento('titlePaginaFormLink'); ?></title>
        <script src="js/formHiperLinkUsuario.js">
        </script>
        <link   href="http://www.nbooklink.com/css/principal.css"      rel="stylesheet" type="text/css"/>
        <link   href="http://www.nbooklink.com/css/topo.css"           rel="stylesheet" type="text/css"/>
        <link   href="http://www.nbooklink.com/css/ajax.css"           rel="stylesheet" type="text/css"/>
        
        
    </head>
    <body>
        <?php 
            /*VALORES PADRAO DOS INPUTS E TITULOS - EH CONSIDERADA A OPERACAO PADRAO A CRIACAO*/
                $endereco              = "";
                $nome                  = "";
                $descricao             = "";
                $nivelCompartilhamento = 2;
                $grupos = array();
            
                $tituloFormLink             = NBLIdioma::getTextoPorIdElemento('tituloFormLinkCriar');
                $altImgTituloFormLink       = NBLIdioma::getTextoPorIdElemento('altImgTituloFormLinkCriar');
                $valueBotaoInput            = NBLIdioma::getTextoPorIdElemento('valueBotaoCriarGenerico');
                
                
                /*DADOS QUE VAO COMO CAMPOS OCULTOS*/
                    $_NBL_Action           = ""; /*ACAO*/
                    $idEntidade            = ""; /*ID DA ENTIDADE A SER AFETADA PELA REQUISICAO*/
                    /*INFORMACOES NO MOMENTO DA ABERTURA DO FORM - SERAO UTEIS PARA COMPARAR NA REQUISICAO - EX. SABER SE A URL FOI ALTERADA*/
                        $gruposAntes           = ""; /*O ARRAY DOS GRUPOS QUE ESTAVAM MARCADOS NA ABERTURA DO FORM*/
                        $idHiperLinkAntes      = "";
                    /*FIM - INFORMACOES NO MOMENTO DA ABERTURA DO FORM - SERAO UTEIS PARA COMPARAR NA REQUISICAO - EX. SABER SE A URL FOI ALTERADA*/
                /*FIM - DADOS QUE VAO COMO CAMPOS OCULTOS*/
                
            /*FIM - VALORES PADRAO DOS INPUTS E TITULOS - EH CONSIDERADA A OPERACAO PADRAO A CRIACAO*/
            
            /*SE FOR EDICAO, ENTAO ENTAO PREPARAR OS DADOS QUE SERAO EXIBIDOS NOS INPUTS*/
                $idHiperLinkUsuario == isset($idHiperLinkUsuario) ? (int) $idHiperLinkUsuario : 0; /*id do link recebe inteiro*/ 
                
                if ( $idHiperLinkUsuario != 0  ) /*EH UMA EDICAO*/
                {
                    $hu          = HiperLinksUsuarioDAO::obterPorId($idHiperLinkUsuario); /*OBTEM O LINK DO USUARIO PELO ID PASSSADO*/
                    if (is_object($hu)) /*EXISTE O LINK E FOI RETORNADO UM OBJETO*/
                    {
                        $idl         = $hu->getIdHiperLink();
                        $hl          = HiperLinkDAO::obterPorId( $idl );     /*OBTEM O LINK GLOBAL */
                            /*VALORES PADRAO DOS INPUTS E TITULOS*/
                                $endereco              = $hl->getUrl();
                                $nome                  = $hu->getNome();
                                $descricao             = $hu->getDescricao();
                                $nivelCompartilhamento = $hu->getNivelCompartilhamento();
                                
                                $tituloFormLink        = NBLIdioma::getTextoPorIdElemento('tituloFormLinkEditar'); 
                                $altImgTituloFormLink  = NBLIdioma::getTextoPorIdElemento('valueBotaoEditarGenerico'); 
                                $valueBotaoInput       = NBLIdioma::getTextoPorIdElemento('valueInputSalvarAlteracoesGenerico');
                                
                                /*AGORA OBTER A LISTA DOS GRUPOS AOS QUAIS O LINK PERTENCE - PARA MARCAR NO FORM OS CHECK AOS QUAIS O LINK JA PERTENCE*/
                                    $grupos = HiperLinksUsuarioDAO::obterListaDosIdsDosGruposAosQuaisOLinkPertence($idHiperLinkUsuario);
                                /*FIM - AGORA OBTER A LISTA DOS GRUPOS AOS QUAIS O LINK PERTENCE - PARA MARCAR NO FORM OS CHECK AOS QUAIS O LINK JA PERTENCE*/
                            /*FIM - VALORES PADRAO DOS INPUTS E TITULOS*/
                            
                            /*DEFINIR DADOS DA REQUISICAO*/
                                $_NBL_Action      = " <input id=\"_NBL_Action\" name=\"_NBL_Action\" type=\"hidden\" value=\"_NBL_Action_update\" />"; /*VAI GERAR UM CAMPO OCULTO NO FORMULARIO*/        
                                $idEntidade       = " <input id=\"idEntidade\" name=\"idEntidade\"   type=\"hidden\" value=\"$idHiperLinkUsuario\" />"; /*VAI GERAR UM CAMPO OCULTO NO FORMULARIO*/        
                                
                                $temp = $hl->getIdHiperLink();
                                $idHiperLinkAntes = "<input id=\"idHiperLinkAntes\" name=\"idHiperLinkAntes\" type=\"hidden\" value=\"$temp\" />"; /*VAI GERAR UM CAMPO OCULTO NO FORMULARIO*/
                            /*FIM - DEFINIR DADOS DA REQUISICAO*/
                                    
                    }
                    else { /*FOI PASSADO UM ID DE UM LINK QUE N EXISTE NO BD OU NAO VOLTOU OBJETO ALGUM*/
                        $idHiperLinkUsuario = 0; /*id volta a ser 0, o que faz ser uma insercao*/
                    }
                }
            /*FIM - SE FOR EDICAO, ENTAO ENTAO PREPARAR OS DADOS QUE SERAO EXIBIDOS NOS INPUTS */
        ?>
        <div id="divFormHiperLinkUsuario" style="position: relative; top: -12px;">
            <div id="tituloFormHiperLinkUsuario" class="divTituloForm divComBorda">
                <img src="imagens/imgFavorito.png" alt="<?php echo $altImgTituloFormLink ?>"/>
                <label class="tituloForm"><?php echo $tituloFormLink ?></label>
            </div>
            <div id="divRespostaFormHiperLinksUsuario">
            </div>
            <form id="formHiperLinkUsuario" name="formHiperLinkUsuario" method="post">
                <fieldset class="divComBorda">
                    <legend title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLegendFieldsetDadosPrincipaisFormLink'); ?>"><?php echo NBLIdioma::getTextoPorIdElemento('legendFieldsetDadosPrincipaisFormLink'); ?></legend>
                        <label for="inputFormHiperLinkUsuarioEndereco" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelEnderecoFormLink'); ?>" class="displayBlock"><?php echo NBLIdioma::getTextoPorIdElemento('labelEnderecoFormLink'); ?></label>                        
                        <input id="inputFormHiperLinkUsuarioEndereco" value="<?php echo $endereco ?>" name="url"  type="text" maxlength="1024" class="texto displayBlock">
                        <label id="labelFormHiperLinkUsuarioEnderecoResposta" class="resposta displayBlock"></label>

                        <label for="inputFormHiperLinkUsuarioNome" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelNomeFormLink'); ?>" class="displayBlock"><?php echo NBLIdioma::getTextoPorIdElemento('labelNomeFormLink'); ?></label>                        
                        <input id="inputFormHiperLinkUsuarioNome" value="<?php echo $nome ?>" name="nome" type="text" maxlength="100" class="texto displayBlock">
                        <label id="labelFormHiperLinkUsuarioNomeResposta" class="resposta displayBlock"></label>

                        <label for="textareaFormHiperLinkUsuarioDescricao" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelDescricaoFormLink'); ?>">
                            <?php echo NBLIdioma::getTextoPorIdElemento('labelDescricaoFormLink'); ?>
                        </label><br>                        
                        <textarea id="textareaFormHiperLinkUsuarioDescricao" name="descricao" class="texto" rows="4"><?php echo $descricao ?></textarea>
                </fieldset>
                <fieldset class="divComBorda">
                    <legend title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLegendFieldsetDadosDePrivacidadeFormLink'); ?>">
                        <?php echo NBLIdioma::getTextoPorIdElemento('legendFieldsetDadosDePrivacidadeFormLink'); ?>
                    </legend>
                    <input id="inputNivelCompartilhamento1" type="radio" name="nivelCompartilhamento" value="1" <?php if ($nivelCompartilhamento == 1) echo "checked=\"checked\""; ?>/>
                    <label for="inputNivelCompartilhamento1" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelRadioPublicoFormLink'); ?>">
                        <?php echo NBLIdioma::getTextoPorIdElemento('labelRadioPublicoFormLink'); ?>
                    </label>
                    <input id="inputNivelCompartilhamento2" type="radio" name="nivelCompartilhamento" value="2" <?php if ($nivelCompartilhamento == 2) echo "checked=\"checked\""; ?> />
                    <label for="inputNivelCompartilhamento2" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelRadioPrivadoFormLink'); ?>">
                        <?php echo NBLIdioma::getTextoPorIdElemento('labelRadioPrivadoFormLink'); ?>
                    </label>
                </fieldset>
                <fieldset class="divComBorda">
                    <legend title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLegendFieldsetGruposFormLink'); ?>">
                        <?php echo NBLIdioma::getTextoPorIdElemento('legendFieldsetGruposFormLink'); ?>
                    </legend>
                    <?php // NAO PODE UM FORM DENTRO DE OUTRO echo ViewGruposHiperLinksUsuario::obterLinkDeComandoParaFormGruposLink(0, "formHiperLinkUsuario", "_NBL_Action_Create", ""); ?> 
                    <div id="divGrupos">
                        <?php //DESENHA OS CHECK BOXES
                             echo ViewGruposHiperLinksUsuario::obterGruposDeHiperLinkDeUsuarioEmChecksHTML($_SESSION['_SESSAO_']['_USUARIO_']['idUsuario'], $grupos); 
                        ?>
                    </div>    
                    <!--campo auxiliar para atual. da div de checks -->
                    <input id="action" name="action" value="obterGruposDeHiperLinkDeUsuarioEmChecksHTML" type="hidden"/>
                </fieldset>
                
                <?php echo $_NBL_Action;            //VAI INFORMAR SE FOR UMA ATUALZICAO          - DEFINIDO NO COMECO DESSE ARQUIVO ?>
                <?php echo $idEntidade;             //VAI INFORMAR O ID DA ENTIDADE A SER AFETADA - DEFINIDO NO COMECO DESSE ARQUIVO ?>
                <?php echo $idHiperLinkAntes;       /*O ID DO LINK GLOBAL - VAI SER USADO NO CONTROLLER*/                            ?>


                <input id="inputSubmitFormHiperLinksUsuario" type="submit" value="<?php echo $valueBotaoInput ?>" class="clicavel" />
                <input type="button" onclick="javascript:window.location.reload()" value="<?php echo NBLIdioma::getTextoPorIdElemento('valueInputVoltar'); ?>" class="clicavel"/>
            </form>
            <?php echo ViewGruposHiperLinksUsuario::obterLinkDeComandoParaFormGruposLink(0, "formHiperLinkUsuario", "_NBL_Action_Create", ""); ?> 
        </div>
        <div id="divScriptFormHiperLinksUsuario">
        </div>
    </body>
</html>
