<?php 
    require_once "principais.php";
    require_once "verificaRestricao.php";
    $idGrupoHiperLinksUsuario = $_REQUEST['idGrupoHiperLinksUsuario']; //id da entidade
?>
<!doctype html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <?php //echo ViewBase::retornaArquivosDeLigacoes(); ?>
  <style></style>
  <script src="js/formGruposHiperLinksUsuario.js"></script>
  <link   href="http://www.nbooklink.com/css/principal.css"      rel="stylesheet" type="text/css"/>
  <link   href="http://www.nbooklink.com/css/topo.css"           rel="stylesheet" type="text/css"/>
  <link   href="http://www.nbooklink.com/css/ajax.css"           rel="stylesheet" type="text/css"/>
</head>
<body>
    
        <?php 
            /*VALORES PADRAO DOS INPUTS E TITULOS - EH CONSIDERADA A OPERACAO PADRAO A CRIACAO*/
                $nome       = "";
                $descricao  = "";
            
                $tituloForm           = NBLIdioma::getTextoPorIdElemento('tituloFormGruposHiperLinksUsuarioCriar');
                $altImgTituloForm     = NBLIdioma::getTextoPorIdElemento('altImgTituloFormGruposHiperLinksUsuarioCriar');
                $valueBotaoInput      = NBLIdioma::getTextoPorIdElemento('valueBotaoCriarGenerico');
                
                /*DADOS QUE VAO COMO CAMPOS OCULTOS*/
                    $_NBL_Action           = ""; /*ACAO*/
                    $idEntidade            = ""; /*ID DA ENTIDADE A SER AFETADA PELA REQUISICAO*/
                /*FIM - DADOS QUE VAO COMO CAMPOS OCULTOS*/
                
            /*FIM - VALORES PADRAO DOS INPUTS E TITULOS - EH CONSIDERADA A OPERACAO PADRAO A CRIACAO*/
            
            /*SE FOR EDICAO, ENTAO ENTAO PREPARAR OS DADOS QUE SERAO EXIBIDOS NOS INPUTS*/
                $idGrupoHiperLinksUsuario == isset($idGrupoHiperLinksUsuario) ? (int) $idGrupoHiperLinksUsuario : 0; /*id do link recebe inteiro*/ 
                
                if ( $idGrupoHiperLinksUsuario != 0  ) /*EH UMA EDICAO*/
                {
                    $ghu = GruposHiperLinksUsuarioDAO::obterPorId($idGrupoHiperLinksUsuario); /*OBTEM O GRUPO PELO ID PASSADO*/
                    if (is_object($ghu)) /*EXISTE O LINK E FOI RETORNADO UM OBJETO*/
                    {
                            /*VALORES PADRAO DOS INPUTS E TITULOS*/
                                $nome                  = utf8_encode($ghu->getNome());
                                $descricao             = utf8_encode($ghu->getDescricao());
                                
                                $tituloForm           = NBLIdioma::getTextoPorIdElemento('tituloFormGruposHiperLinksUsuarioEditar');
                                $altImgTituloForm     = NBLIdioma::getTextoPorIdElemento('valueBotaoEditarGenerico');
                                
                                $valueBotaoInput      = NBLIdioma::getTextoPorIdElemento('valueInputSalvarAlteracoesGenerico');
                            /*FIM - VALORES PADRAO DOS INPUTS E TITULOS*/
                            
                            /*DEFINIR DADOS DA REQUISICAO*/
                                $_NBL_Action      = " <input id=\"_NBL_Action\" name=\"_NBL_Action\" type=\"hidden\" value=\"_NBL_Action_update\" />"; /*VAI GERAR UM CAMPO OCULTO NO FORMULARIO*/        
                                $idEntidade       = " <input id=\"idEntidade\" name=\"idEntidade\"   type=\"hidden\" value=\"$idGrupoHiperLinksUsuario\" />"; /*VAI GERAR UM CAMPO OCULTO NO FORMULARIO*/        
                            /*FIM - DEFINIR DADOS DA REQUISICAO*/
                    }
                    else { /*FOI PASSADO UM ID DE UM GRUPO QUE N EXISTE NO BD OU NAO VOLTOU OBJETO ALGUM*/
                        $idGrupoHiperLinksUsuario = 0; /*id volta a ser 0, o que faz ser uma insercao*/
                    }
                }
            /*FIM - SE FOR EDICAO, ENTAO ENTAO PREPARAR OS DADOS QUE SERAO EXIBIDOS NOS INPUTS */
        ?>
    
    <div id="divFormGruposHiperLinksUsuario" style="position: relative; top: -12px;">
        <div id="tituloFormGrupoHiperLinksUsuario" class="divTituloForm divComBorda">
            <img src="imagens/imgGrupoFavoritos.png" alt="<?php echo $altImgTituloForm ?>"/>
            <label class="tituloForm"><?php echo $tituloForm  ?></label>
        </div>
        <div id="divRespostaFormGruposHiperLinksUsuario"></div>

        <form id="formGruposHiperLinksUsuario" name="formGruposHiperLinksUsuario"  method="post" >
            <label for="nome" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormGrupoLinkNome'); ?>" class="displayBlock"  ><?php echo NBLIdioma::getTextoPorIdElemento('labelFormGrupoLinkNome'); ?></label>
            <input id="nome" name="nome" type="text" maxlength="100" value="<?php echo $nome ?>"  class="displayBlock texto" />
            <label id="nomeResposta" class="resposta displayBlock"></label>

            <label for="descricao" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormGrupoLinkDescricao'); ?>" class="displayBlock"><?php echo NBLIdioma::getTextoPorIdElemento('labelFormGrupoLinkDescricao'); ?></label>
            <textarea id="descricao" name="descricao" rows="6" class="displayBlock"><?php echo $descricao ?></textarea>
            <label id="descricaoResposta" class="resposta displayBlock"></label>

            <?php echo $_NBL_Action;            //VAI INFORMAR SE FOR UMA ATUALZICAO          - DEFINIDO NO COMECO DESSE ARQUIVO ?>
            <?php echo $idEntidade;             //VAI INFORMAR O ID DA ENTIDADE A SER AFETADA - DEFINIDO NO COMECO DESSE ARQUIVO ?>            

            <input id="inputSubmitFormGruposHiperLinksUsuario" type="submit" value="<?php echo $valueBotaoInput ?>" class="clicavel" />
            <input onclick="javascript:window.location.reload()" type="button" value="<?php echo NBLIdioma::getTextoPorIdElemento('valueInputVoltar'); ?>"  class="clicavel"/>

        </form>
        <div id="divFormGruposHiperLinksUsuarioExplicacao" style="padding: 15px;">
            <img src="imagens/imgExplicacaoMenor.png"/>
            <label id="labelDivRespostaFormGrupoHiperLinksUsuario">
                <?php echo NBLIdioma::getTextoPorIdElemento('msgExplicacaoFormGrupoLinkNovo'); ?>    
            </label>
        </div>
    </div>  
</body>
</html>