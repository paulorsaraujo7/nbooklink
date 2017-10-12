<?php
/**
 * Encapsula metodos que retornam blocos HTML relacionados aos grupos de favoritos
 * @author Paulo Ricardo
 * @Data de Criacao: 21-04-2013 - domingo
 */
class ViewGruposHiperLinksUsuario extends ViewBase {
    
    /*
     * Retorna o código HTML com campos de formulario do tipo CHECK que contém como valor o id do grupo e 
     * como label do check o nome do grupo
     * @in:  (int)    id do usuario.
     *       (int)    array contendo os ids dos grupos que devem ser marcados.
     * @out: (string) codigo HTML da lista dos check.
     */
    public static function obterGruposDeHiperLinkDeUsuarioEmChecksHTML($idUsuario, array $grupos)
    {
        if (!is_int($idUsuario)) return ""; //idUsuario nao eh inteiro, string vazia
        $result = unserialize( GruposHiperLinksUsuarioDAO::obterTodosOsGruposDeUsuarioPorIdUsuario($idUsuario) );
        if (!$result)    return "";
        
        $resultHTML = ""; //O bloco HTML com os elementos preenchidos

        /*DESENHAR CADA CHECK BOX*/
        foreach ($result as $key => $value) {
            /*PARA CADA CHECKBOX, VERIFICAR QUAL TEM O ID NO ARRAY QUE FOI PASSADO COMO ARGUMENTO DA FUNCAO*/
                $checked = ""; /*EM PRINCIPIO O CHECK ESTA DESMARCADO*/
                $idDoGrupoNoBD = $result[$key]->getIdGrupoHiperLinksUsuario();
                if ( in_array($idDoGrupoNoBD, $grupos)  ) /*VALOR DO ID ESTA NO ARRAY PASSADO?*/
                {
                    $checked = "checked=\"checked\"";
                }
            /*FIM - PARA CADA CHECKBOX, VERIFICAR QUAL TEM O ID NO ARRAY QUE FOI PASSADO COMO ARGUMENTO DA FUNCAO*/
            
            /*OBS: PERCEBER QUE OS CHECKS ESTAO COLOCADOS DENTRO DO FORM DE LINKS PARA PODER PASSAR OS QUE ESTAO MARCADOS PARA OUTRAS FUNCOES
             * USADO QUANDO NA EDICAO DE UM LINK EH CRIADO OUTRO GRUPO ENTAO OS QUE ESTAO MARCADOS DEVEM SER PROPAGADO PARA
             * NAO PERDER A MARCACAO QUANDO CLICAR NO BOTAO DE CRIAR NOVO GRUPO. Usando SerializeArray();
            */
            $resultHTML .= "<div style=\"position: relative; float: left; width: 33%\">";

            $resultHTML .=      "<input id=\"check{$result[$key]->getIdGrupoHiperLinksUsuario()}\"  
                                  type=\"checkbox\" name=\"grupos[]\" 
                                  value=\"{$result[$key]->getIdGrupoHiperLinksUsuario()}\" class=\"checkbox clicavel\" $checked/> 
                                ";
            
            $resultHTML .=      "<label class=\"clicavel\" for=\"check{$result[$key]->getIdGrupoHiperLinksUsuario()}\" 
                                        title=\"{$result[$key]->getDescricao()}\">{$result[$key]->getNome()}
                                </label> ";
                                    
            $resultHTML .= "</div> ";
        } /*FIM - foreach ($result as $key => $value) {*/
        /*FIM - DESENHAR CADA CHECK BOX*/
        return $resultHTML;
    }
    
    /*
     * Retorna o código HTML com uma lista dos grupos com bullets a esquerda dos links
     * @in:  (int)    id do usuario.
     * @out: (string) codigo HTML da lista dos grupos com bullets.
     */
    public static function obterGruposDeHiperLinkDeUsuarioEmBulletsHTML($idUsuario)
    {
        /*EXIBE OS GRUPO NA DIV DA DIREITA*/
        
        $idUsuario = (int) $idUsuario;
        $resultHTML = ""; //O bloco HTML com os elementos preenchidos
        
        /*DESENHAR O LINK DE COMANDO PARA EXIBICAO DE TODOS OS LINKS DO USUARIO*/
        $linkComandoExibirTodosOsLinks      = NBLIdioma::getTextoPorIdElemento('linkComandoExibirTodosOsLinks');
        $titleLinkComandoExibirTodosOsLinks = NBLIdioma::getTextoPorIdElemento('titleLinkComandoExibirTodosOsLinks');
        $totalDeLinksDoUsuario              = HiperLinksUsuarioDAO::totalDeLinksDoUsuario($idUsuario);
        
        
        
        $resultHTML = "
            <div style=\" position: relative; margin-bottom: 10px;\">
                <a id=\"$idUsuario\" name=\"grupoLink\" style=\"font-style: italic;\" title=\"$titleLinkComandoExibirTodosOsLinks\" href=\"#\"
                   onclick=\"javascritp:todosOsLinks(this)\">
                    <img style=\"position: relative; top:3px;  width: 18px; height: 18px;\" src=\"imagens/imgVerTodosOsLinks.png\"/>
                    $linkComandoExibirTodosOsLinks ($totalDeLinksDoUsuario)
                </a>                                    
            </div>
        ";
        /*FIM - DESENHAR O LINK DE COMANDO PARA EXIBICAO DE TODOS OS LINKS DO USUARIO*/
        
        
        
        /*DESENHAR O LINK DE COMANDO PARA OS LINKS QUE NAO POSSUEM GRUPOS ASSOCIADOS*/
        $linkComandoLinksSemGrupo      = NBLIdioma::getTextoPorIdElemento('linkComandoExibirLinksSemGrupos');
        $titleLinkComandoLinksSemGrupo = NBLIdioma::getTextoPorIdElemento('titleLinkComandoExibirLinksSemGrupos');
        $totalDeLinksSemGrupo          = GruposHiperLinksUsuarioDAO::totalDeLinksSemGrupo($idUsuario);
        
        $resultHTML .= "
            <div style=\" position: relative; margin-bottom: 10px;\">
                <a id=\"0\" name=\"grupoLink\" style=\"font-style: italic;\" title=\"$titleLinkComandoLinksSemGrupo\" href=\"#\"
                   onclick=\"javascritp:gu(this)\">
                    <img style=\"position: relative; top:3px;  width: 18px; height: 18px;\" src=\"imagens/imgLinksSemGrupo.png\"/>
                    $linkComandoLinksSemGrupo ($totalDeLinksSemGrupo)
                </a>                                    
            </div>
        ";
        /*FIM - DESENHAR O LINK DE COMANDO PARA OS LINKS QUE NAO POSSUEM GRUPOS ASSOCIADOS*/
        
        /*$result recebe o resource com a lista de grupos*/
        $result = GruposHiperLinksUsuarioDAO::obterGruposETotalDeLinksPorIdUsuario($idUsuario);
        if ($result) /*SE NAO EXISTIR GRUPO NAO INICIA O WHILE - EVITA UM ERRO*/
        {
            while ($row = mysql_fetch_array($result)) {

                $idGrupo      = $row['idGrupo'];
                $nome         = $row['nome'];
                $descricao    = $row['descricao'];


                $totalDeLinks = !$row['totalDeLinks'] ? 0 : $row['totalDeLinks'];

                $resultHTML .= " <a id=\"$idGrupo\" name=\"grupoLink\" title=\"$descricao\" 
                    style=\"margin-bottom: 10px;\" href=\"#\" onclick=\"javascritp:gu(this)\" 
    class=\"displayBlock\"  > ";
                $resultHTML .= " <img style=\"position: relative; top:2px; margin-right:5px; \"   
                                     src=\"imagens/bulletGrupoLinks.png\" 
                                     title=\"$descricao\" 
                                     alt=\"bullet.png\" >$nome ($totalDeLinks)</a>  ";
            }
        }
        return utf8_encode($resultHTML);
    }
    
    
    
    /*
     * Retorna o SCRIPT necessario para envio dos dados do form para o controlador
     * @in:  (int)    id da entidade a qual se refere o form.
     * @in:  (string) tipo da acao que sera passada para o controlador.
     * @in:  (string) quem chamou o formulario. Eh importante para fins de atualizacao de alguma lista no chamador.
     * @in:  (string) O Container HTML que recebera o codigo HTML do formulario por AJAX.
     * 
     * @out: (string) codigo JavaScript para exibicao do formulario.
     */
    public static function obterScritpDeExibicaoDoForm($idEntidade, $_NBL_View, $_NBL_Container, $_NBL_Action = "_NBL_Action_Create")
    {
        $result = "
            function exibeFormGruposFavoritos () {
                $.post('forms/formGruposFavoritos.php', {
                    id : $idEntidade, 
                    _NBL_Action : \"$_NBL_Action\", 
                    _NBL_View   : \"$_NBL_View\"   
                 }, 
                 function (data) { 
                        $(\"#$_NBL_Container\").fadeIn(3).html(data)}, 'html');
                 return false;
            }
        ";
        return $result;
    }
    

    /*
     * Retorna o código HTML que exibe o link de comando que chama o form de grupo de favoritos
     * @in:  (int)    id do grupo - util para a view saber se se trata de uma edicao ou criacao por padrao.
     * @out: (string) codigo HTML do link de comando para abertura  do form de novo grupo
     */
    public static function obterLinkDeComandoParaFormGruposLink($idGrupoHiperLinkUsuario, $_NBL_View, $_NBL_Action, $_NBL_Container)
    {
        /*Eh preciso tratar o nome do link para nao ter dois com o mesmo nome pois os scripts nao funcionariam*/
        $i = "";
        $f = "";
        if ($_NBL_View == 'formHiperLinkUsuario')
        {
            $f = "formChamaFormGruposHiperLinksUsuario1";
            $i = "linkFormChamaFormGruposHiperLinksUsuario1";
        }
        else {
            $f = "formChamaFormGruposHiperLinksUsuario";
            $i = "linkFormChamaFormGruposHiperLinksUsuario";
        }
        
        if (!is_int($idGrupoHiperLinkUsuario)) return ""; //idGrupo nao eh inteiro, string vazia

        $t = NBLIdioma::getTextoPorIdElemento('titleImgNovoGrupoLinkMenuComando');
        $a = NBLIdioma::getTextoPorIdElemento('linkMenuComandoNovoGrupoLink');
        
        /*Estou usando um form para cada link para uso da funcao $.ajax que utilizo data = serializeArray(form)*/
        $result = <<<EOT
        <form id="$f" action="formGruposHiperLinksUsuario.php" method="post" style="margin-bottom: 10px;">
            <img id="imgNovoFavoritos"       style="position: relative; top: 5px;" 
                                 src="imagens/imgNovoGrupoFavoritos.png" 
                                 title="$t" 
                                 alt="$a">

            <input name="idGrupoHiperLinksUsuario" type="hidden" value="$idGrupoHiperLinkUsuario">
            <input name="_NBL_View"                type="hidden" value="$_NBL_View">
            <input name="_NBL_Action"              type="hidden" value="$_NBL_Action">
            <input name="_NBL_Container"           type="hidden" value="$_NBL_Container">
                
            <input id="$i"  type="submit" value="$a" style="border-style: none; font-size:12px; margin-left:-5px; color:#0066FF; background-color: #ffffff; cursor: pointer;">
        </form>
EOT;
        return $result;
        
    }
    

    /*
     * Retorna o código HTML que exibe a lista de links pertencente a um grupo de links de um usuário especifico
     * @in:  (int)    id do grupo (Se for igual a 0 entao exibe os links que nao pertencem a grupo algum)
     * @in:  (int)    id do usuario
     * @out: (string) codigo HTML da lista de links que pertence ao grupo do usuario passado
     * 
     * OBS: Perceber que os links tem nome e id bem definidos para fins de chamada as funcoes js executadas no click
     * tanto pelo nome como pela url (incrementar contador, por exemplo)
     */
    public static function obterListaDeGruposPorGrupoEUsuario($idUsuario, $idGrupo = 0)
    {
        
        $resultHTML = "";
        
        /*SE O USUARIO NAO POSSUE LINK, ENTAO EXIBE UMA MSG*/
            $totalDeLinks = HiperLinksUsuarioDAO::totalDeLinksDoUsuario($idUsuario);
            if ( $totalDeLinks == 0 && $idGrupo == 0 )
            {
                    /*SE O USUARIO NAO POSSUI LINK ENTAO EXIBE UMA MENSAGEM NO CENTRO DA TELA*/
                    $array  = explode (".",NBLIdioma::getTextoPorIdElemento('labelMsgUsuarioNaoPossuiLink')) ; //DIVIDE A STRING EM UM ARRAY SEPARANDO PELO PONTO
                    $string = implode(".</br>", $array); 
                    $resultHTML = "
                          <img src=\"imagens/imgExplicacao.png\">
                          <div id=\"divExplicacaoUsuarioNaoPossuiLink\">
                          $string
                          </div>
                    ";
                    return $resultHTML;
            }
        /*FIM - SE O USUARIO NAO POSSUE LINK, ENTAO EXIBE UMA MSG*/
        
        
        
        /*MENSAGENS DE IDIOMA*/
        $titleImgLinkPublico                  = NBLIdioma::getTextoPorIdElemento('titleImgLinkPublico');
        $titleImgLinkPrivado                  = NBLIdioma::getTextoPorIdElemento('titleImgLinkPrivado');
        $titleImgEditar                       = NBLIdioma::getTextoPorIdElemento('valueBotaoEditarGenerico');
        $titleImgExcluir                      = NBLIdioma::getTextoPorIdElemento('valueBotaoExcluirGenerico');
        
        $titleImgExcluirLinkParaGrupoAtual    = NBLIdioma::getTextoPorIdElemento('titleImgExcluirLinkParaGrupoAtual');
        $titleImgExcluirLinkParaTodosOsGrupos = NBLIdioma::getTextoPorIdElemento('titleImgExcluirLinkParaTodosOsGrupos');
        
        
        $labelLinkUltimoAcesso          = NBLIdioma::getTextoPorIdElemento('labelLinkUltimoAcesso');
        $labelLinkTotalDeGrupos         = NBLIdioma::getTextoPorIdElemento('labelLinkTotalDeGrupos');
        $labelLinkNumeroDeAcessos       = NBLIdioma::getTextoPorIdElemento('labelLinkNumeroDeAcessos');
        $labelLinkDataCad               = NBLIdioma::getTextoPorIdElemento('labelLinkDataCad');
        $titleLabelLinkUltimoAcesso     = NBLIdioma::getTextoPorIdElemento('titleLabelLinkUltimoAcesso');
        $titleLabelLinkTotalDeGrupos    = NBLIdioma::getTextoPorIdElemento('titleLabelLinkTotalDeGrupos');
        $titleLabelLinkNumeroDeAcessos  = NBLIdioma::getTextoPorIdElemento('titleLabelLinkNumeroDeAcessos');
        $titleLabelLinkDataCad          = NBLIdioma::getTextoPorIdElemento('titleLabelLinkDataCad');
        /*FIM - MENSAGENS DE IDIOMA*/
        
        /*PREPARA DADOS PARA O TITULO DO GRUPO - SE EXISTIR GRUPO*/
        $tituloGrupoHTML = "";
        if ($idGrupo) {
            $info_grupo = GruposHiperLinksUsuarioDAO::obterGruposETotalDeLinks($idUsuario, $idGrupo);
            if ($info_grupo != null)
            {
                $row = mysql_fetch_array($info_grupo);
                $idGrupo       = $row['idGrupo'];
                $nomeGrupo     = utf8_encode( $row['nome'] );      /*PARA EXIBICAO DO NOME*/
                $tituloDoGrupo = utf8_encode( $row['descricao'] ); /*PARA EXIBICAO DO HINT COM A DESCRICAO DO LINK*/
                $totalDeLinks  = !$row['totalDeLinks'] ? 0 : $row['totalDeLinks'];  /*SE O GRUPO NAO POSSUIR LINK COLOCA O N 0 DO LADO DO NOME*/

                
                /*SE O GRUPO NAO POSSUIR LINK COLOCA UMA MENSAGEM A SER EXIBIDA
                 * EH NECESSARIO FAZER NESTE MOMENTO POIS NA PESQUISA ABAIXO SOMENTE SERA MONTADO O HTML SE EXISTIR LINK PARA UM GRUPO DADO
                 */
                $msgSemLinks = "";
                if (!$row['totalDeLinks']) { /*GRUPO NAO POSSUI LINK*/
                    $msgGrupoNaoPossuiLink = NBLIdioma::getTextoPorIdElemento('msgGrupoNaoPossuiLink'); /*MSG INDICATIVA QUE O GRUPO NAO POSSUI LINK*/
                    /*MONTA A MESMA DIV QUE CONTERIA LINKS SO QUE COM UMA MSG INDICANDO QUE N TEM LINK
                     * - APENAS PARA NAO FICAR A BARRA SOLTA DO GRUPO NA EXIBICAO
                     * - PERCEBER QUE NAO TEM BORDA
                     */
                    $msgSemLinks = 
                    "<div id=\"divMsgGrupoLink$idGrupo\" style=\"position: relative; width: 100%; height: auto; margin-bottom: 5px; color:#666666;
                                text-align:center; font-style:italic\">
                                $msgGrupoNaoPossuiLink
                            </div>
                    ";
                }
                
                $tituloGrupoHTML = 
                "<div id=\"divTituloGrupoLink$idGrupo\" class=\"divComBorda\" 
                            style=\"position: relative; 
                            width: 550px; 
                            height: auto; 
                            min-height:17px;
                            margin-bottom: 5px; 
                            color:#FFF; 
                            padding: 5px;
                            background: #CBDEFE url('http://www.nbooklink.com/imagens/imgFundoTituloForm.png') 50% 50% repeat-x;
                            \"> <label title=\"$tituloDoGrupo\">$nomeGrupo ($totalDeLinks)</label>
                            <span style=\"position: relative; float: right\">
                                                    <img id=\"$idGrupo\" class=\"clicavel\" onclick=\"javascript:editarGrupoHiperLinksUsuario(this)\" src=\"imagens/imgEditar.png\" width=\"20\" height=\"20\" alt=\"\" title=\"$titleImgEditar\"/>
                                                    <img id=\"$idGrupo\" class=\"clicavel\" name=\"$nomeGrupo\" onclick=\"javascript:excluirGrupoDeLinks(this)\"  src=\"imagens/imgDelete.png\" width=\"20\" height=\"20\" alt=\"\" title=\"$titleImgExcluir\"/>
                            </span>
                            $msgSemLinks
                            </div>
                ";
            }
            /*ACRESCENTA O INICIO DA DIV QUE CONTERA TODO O GRUPO DE LINKS - SOMENTE SE EXISTIR TITULO DE GRUPO - VER O IF ONDE ESTA CONTIDO ESTE CODIGO*/
            $resultHTML = "<div id=\"divGrupoLinks$idGrupo\" style=\"position:relative; margin-bottom:20px;\" >" . $tituloGrupoHTML; 
        }
        /*FIM - PREPARA DADOS PARA O TITULO DO GRUPO*/
        

        
        
        $result = GruposHiperLinksUsuarioDAO::obterLinksDeUmGrupo($idUsuario, $idGrupo);
        if ($result) /*HÁ LINKS NO GRUPO*/
        {
                
                $i = 0;           //VARIAVEL DE CONTROLE PARA DEFINIR A COR
                while ($row = mysql_fetch_array($result)) {

                    /*VARIAVEIS DO BD*/
                    $idHiperLink                = $row['idHiperLink'];
                    $url                        = $row['url'];
                    $idHiperLinkUsuario         = $row['idHiperLinkUsuario'];


                    $ultimoAcesso = "";
                    if ($row['ultimoAcesso']) /*JA FOI ACESSADO UMA PRIMEIRA VEZ. CAMPO EH <> DE NULL*/
                    {
                        $ultimoAcesso               = new NBLDateTime($row['ultimoAcesso']);
                        $ultimoAcesso               = $ultimoAcesso->toStringExibir();
                    }

                    $dataCadastro = new NBLDateTime($row['dataCadastro']);
                    $dataCadastro = $dataCadastro->toStringExibir();


                    $contadorAcessos            = $row['contadorAcessos'];
                    $nivelCompartilhamento      = $row['nivelCompartilhamento'];
                    $nota                       = $row['nota'];
                    $nome                       = utf8_encode($row['nome']);
                    $descricao                  = utf8_encode($row['descricao']);
                    $contadorImportacao         = $row['contadorImportacao'];
                    $idUsuario                  = $row['idUsuario'];
                    $totalGrupos                = $row['totalGrupos'];

                    /*DEFININDO A FORMATACAO*/
                    $color = ($i & 1) ? "#EAF2FF" : "#FFF"; //EFEITO ZEBRA PARA AS LINHAS
                    $idioma = "Respeitar o idioama";

                    $parseURL = parse_url($url);                                                  
                    $favicon = $parseURL['scheme'] . '://' . $parseURL['host'] . '/favicon.ico'; //CAPTURAR O ICONE DA URL
                    if ($favicon)
                    {
                        $icone = "<img height=\"20\" width=\"20\" src=\"$favicon\" title=\"\"/>";
                    }
                    else {
                        $icone = "";
                    }

                    if ($nivelCompartilhamento == 1){
                        $imgCompartilhamento = "imgPublico.png";
                        $titleImgCompartilhamento = $titleImgLinkPublico;
                    }
                    else {
                        $imgCompartilhamento = "imgPrivado.png";
                        $titleImgCompartilhamento = $titleImgLinkPrivado;
                    }

                    $botoes = "
                                        <span style=\"position: relative; float: right\">
                                            <img src=\"imagens/$imgCompartilhamento\" alt=\"imgPublico\" title=\"$titleImgCompartilhamento\"/>
                                            <img id=\"$idHiperLinkUsuario\" name=\"$url\" onclick=\"javascript:editarLink(this)\" class=\"clicavel\" src=\"imagens/imgEditar.png\" width=\"20\" height=\"20\" alt=\"\" title=\"$titleImgEditar\"/>
                                            <img id=\"$idHiperLinkUsuario\" name=\"$url\" onclick=\"javascript:excluirLinkParaTodosGrupos(this)\" class=\"clicavel\" src=\"imagens/imgDeleteDeTodosGrupos.png\" width=\"20\" height=\"20\" alt=\"\" title=\"$titleImgExcluirLinkParaTodosOsGrupos\"/>
                                            <img id=\"$idHiperLinkUsuario\" name=\"$url\" onclick=\"javascript:excluirLinkParaGrupoAtual(this, $idGrupo)\"  class=\"clicavel\" src=\"imagens/imgDelete.png\" width=\"20\" height=\"20\" alt=\"\" title=\"$titleImgExcluirLinkParaGrupoAtual\"/>
                                        </span>
                    ";

                    if (!$nome)
                    {
                        $nome = "";
                        $htmlURL = "
                                    <div  style=\"padding: 5px; word-break: break-all;\">
                                        <a id=\"$idHiperLinkUsuario\" onclick=\"javascritp:clickHiperLinkUsuario(this)\" style=\"color:#82B0FF\" href=\"$url\" target=\"blank\">$icone $url</a>
                                        $botoes
                                    </div>    
                        ";

                    }
                    else {
                        $nome = "
                                    <div  style=\"padding: 5px;\">
                                        <a id=\"$idHiperLinkUsuario\" onclick=\"javascritp:clickHiperLinkUsuario(this)\" href=\"$url\" target=\"blank\">$icone $nome</a>
                                        $botoes    
                                    </div>    
                        ";
                        $htmlURL = "
                                    <div style=\"padding: 5px; word-break: break-all;\">
                                        <a id=\"$idHiperLinkUsuario\" onclick=\"javascritp:clickHiperLinkUsuario(this)\" style=\"color:#A4C6FF\" href=\"$url\" target=\"blank\">$url</a>
                                    </div>    
                        ";
                    }

                    if (!$descricao)                                                            //DESCRICAO VAZIA NAO APARECE
                        $descricao = "";
                    else {
                      $descricao = "<div  style=\"padding: 5px;\">$descricao</div>";  
                    }

                    $totalGrupos = !$row['totalGrupos'] ? 0 : $row['totalGrupos'];       //GRUPO NULL FICA NUMERO 0
                    $ultimoAcesso = NULL ? "" : $ultimoAcesso;                           //NAO ACESSADO APARECE MSG

                    /*INICIA O DESENHO HTML DA DIV QUE CONTERA UM LINK ESPECIFICO
                     * 
                     * <div id=\"divLinkGlobal$idHiperLinkUsuario\" .... - IDENTIFICA UMA DIV GLOBAL QUE CONTEM UM LINK  - PRA APAGAR O LINK DE TODOS OS GRUPOS NO DOM BASTA APAGAR AS QUE CONTEM ESTE ID  
                     * <div id=\"divLinkIdLink$idHiperLinkUsuario\" .... - IDENTIFICA UM LINK ESPECIFICO                 - PRA APAGAR DO DOM BASTA LIMPAR ESTA DIV
                     * 
                     */
                    $resultHTML .= "
                            <div id=\"divLinkIdLink$idHiperLinkUsuario\" class=\"divComBorda\" 
                                 style=\"position: relative; width: 100%; height: auto; margin-bottom: 5px; color:#666666; 
                                 background-color:$color \">
                                    $nome
                                    $htmlURL
                                    $descricao
                                    <div style=\"padding: 5px; min-height: 16px;\">

                                        <img style=\"position: relative; float: left; margin-left: 0px;\" src=\"imagens/imgCalendario.png\" width=\"18\" height=\"18\"/>
                                        <label title=\"$titleLabelLinkDataCad\" 
                                           style=\"position: relative; float: left; margin-left: 5px; top:2px;  min-width: 100px;\">$dataCadastro</label>                                    

                                        <img style=\"position: relative; float: left; margin-left: 10px;\" src=\"imagens/imgRelogio.png\" width=\"18\" height=\"18\"/>
                                        <label title=\"$titleLabelLinkUltimoAcesso\" 
                                           style=\"position: relative; float: left; margin-left: 5px; top:2px; min-width: 100px;\">$ultimoAcesso</label>

                                        <label title=\"$titleLabelLinkNumeroDeAcessos\" style=\"position: relative; float: right; margin-left: 20px;\">$labelLinkNumeroDeAcessos $contadorAcessos</label>                                    
                                        <label title=\"$titleLabelLinkTotalDeGrupos\"   style=\"position: relative; float: right; margin-left: 20px;\">$labelLinkTotalDeGrupos   $totalGrupos </label>

                                    </div>    
                            </div>
                        ";
                    $i++;
                }
        } /*FIM - if ($result) /*HÁ LINKS NO GRUPO*/
        
        if ($idGrupo) /*FOI PASSADO UM ID DE GRUPO - E POR ISSO FOI ACRESCENTADA UMA DIV MAE MAIS ACIMA*/
            $resultHTML .= "</div>"; /*FECHA A DIV QUE CONTEM O TITULO DO GRUPO E OS SEUS LINKS*/

            
        
        return $resultHTML;
        
    }

    

    public function display(){
        echo "";
    }
}