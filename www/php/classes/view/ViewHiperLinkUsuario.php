<?php

/*
 * @FUNCIONALIDADE: Provê blocos HTML contendo informações sobre links favoritos.
 * @Autor: Paulo Ricardo
 * @Criacao: 19-04-2013, sexta-feira.
 */

class ViewHiperLinkUsuario {



    
    /*
     * Retorna o código HTML que exibe a lista dos 10 LINKS MAIS RECENTEMENTE VISITADOS E DOS 10 MAIS ACESSADOS DO USUARIO
     * @in:  (int)    id do usuario
     * 
     * 
     * @out: (string) codigo HTML da lista dos LINKS MAIS RECENTES DO USUARIO
     */ 
    public static function topLinks($idUsuario)
    {
        $idUsuario = (int) $idUsuario; //idUsuario nao eh inteiro, string vazia
        $resultHTML = ""; /*$resultHTML contem o codigo HTML que sera retornado*/

        /*BUSCA PELOS ACESSADOS MAIS RECENTEMENTE*/
        $result = HiperLinksUsuarioDAO::topLinks($idUsuario, "RECENTES");
        if (mysql_num_rows($result) > 0) /*HÁ LINKS NA BUSCA FEITA*/
        {
            
                /*CARREGAR AS MSGS DE IDIOMA*/
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
            
            
                    //MENSAGENS DOS TITULOS - MAIS RECENTES
                    $titleImgTopLinksMaisRecentesDoUsuario          = NBLIdioma::getTextoPorIdElemento('titleImgTopLinksMaisRecentesDoUsuario');
                    $altImgTopLinksMaisRecentesDoUsuario            = NBLIdioma::getTextoPorIdElemento('altImgTopLinksMaisRecentesDoUsuario');
                    $labelTituloTopLinksMaisRecentesDoUsuario       = NBLIdioma::getTextoPorIdElemento('labelTituloTopLinksMaisRecentesDoUsuario');
                    $titleLabelTituloTopLinksMaisRecentesDoUsuario  = NBLIdioma::getTextoPorIdElemento('titleLabelTituloTopLinksMaisRecentesDoUsuario');
                    
                /*FIM - CARREGAR AS MSGS DE IDIOMA*/
            
                /*DESENHAR A BARRA DE TITULO DOS MAIS RECENTES*/
                $tituloHTML = 
                "<div id=\"divTituloMaisRecentes\" class=\"divComBorda\" 
                            style=\"position: relative; 
                            width: 550px; 
                            height: auto; 
                            min-height:25px;
                            margin-bottom: 5px; 
                            color:#FFF; 
                            padding: 5px;
                            background: #CBDEFE url('http://www.nbooklink.com/imagens/imgFundoTituloForm.png') 50% 50% repeat-x;
                            \"> 
                                <img src=\"http://www.nbooklink.com/imagens/topLinksMaisRecentes.png\"  alt=\"$altImgTopLinksMaisRecentesDoUsuario\" title=\"$titleImgTopLinksMaisRecentesDoUsuario\"/>
                                <label style=\"position:relative; margin-left:10px; top:-5px;\" title=\"$titleLabelTituloTopLinksMaisRecentesDoUsuario\">
                                    $labelTituloTopLinksMaisRecentesDoUsuario
                                </label>
                            </div>
                ";
                $resultHTML .= $tituloHTML;
            
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
                                           style=\"position: relative; float: left; margin-left: 5px; top:2px; min-width: 100px; font-weight: bold;\">$ultimoAcesso</label>

                                        <label title=\"$titleLabelLinkNumeroDeAcessos\" style=\"position: relative; float: right; margin-left: 20px;\">$labelLinkNumeroDeAcessos $contadorAcessos</label>                                    
                                        <label title=\"$titleLabelLinkTotalDeGrupos\"   style=\"position: relative; float: right; margin-left: 20px;\">$labelLinkTotalDeGrupos   $totalGrupos </label>

                                    </div>    
                            </div>
                        ";
                    $i++;
                }
        } //FIM - if (mysql_num_rows($result) > 0) /*HÁ LINKS NA BUSCA FEITA PELOS MAIS RECENTES*/
        

        /*BUSCA PELOS MAIS ACESSADOS*/
        $result = HiperLinksUsuarioDAO::topLinks($idUsuario, "MAIS_ACESSADOS");
        if (mysql_num_rows($result) > 0) /*HÁ LINKS NA BUSCA FEITA*/
        {
            
                /*CARREGAR AS MSGS DE IDIOMA*/
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
            
            
                    //MENSAGENS DOS TITULOS - MAIS ACESSADOS
                    $titleImgTopLinksMaisAcessadosDoUsuario          = NBLIdioma::getTextoPorIdElemento('titleImgTopLinksMaisAcessadosDoUsuario');
                    $altImgTopLinksMaisAcessadosDoUsuario            = NBLIdioma::getTextoPorIdElemento('altImgTopLinksMaisAcessadosDoUsuario');
                    $labelTituloTopLinksMaisAcessadosDoUsuario       = NBLIdioma::getTextoPorIdElemento('labelTituloTopLinksMaisAcessadosDoUsuario');
                    $titleLabelTituloTopLinksMaisAcessadosDoUsuario  = NBLIdioma::getTextoPorIdElemento('titleLabelTituloTopLinksMaisAcessadosDoUsuario');
                /*FIM - CARREGAR AS MSGS DE IDIOMA*/
            
                /*DESENHAR A BARRA DE TITULO DOS MAIS ACESSADOS*/
                $tituloHTML = 
                "<div id=\"divTituloMaisAcessados\" class=\"divComBorda\" 
                            style=\"position: relative; 
                            width: 550px; 
                            height: auto; 
                            min-height:25px;
                            margin-bottom: 5px;
                            margin-top: 20px;
                            color:#FFF; 
                            padding: 5px;
                            background: #CBDEFE url('http://www.nbooklink.com/imagens/imgFundoTituloForm.png') 50% 50% repeat-x;
                            \"> 
                                <img src=\"http://www.nbooklink.com/imagens/topLinksMaisAcessados.png\"  alt=\"$altImgTopLinksMaisAcessadosDoUsuario\" title=\"$titleImgTopLinksMaisAcessadosDoUsuario\"/>
                                <label style=\"position:relative; margin-left:10px; top:-2px;\" title=\"$titleLabelTituloTopLinksMaisAcessadosDoUsuario\">
                                    $labelTituloTopLinksMaisAcessadosDoUsuario
                                </label>
                            </div>
                ";
                $resultHTML .= $tituloHTML;
            
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

                                        <label title=\"$titleLabelLinkNumeroDeAcessos\" style=\"position: relative; float: right; margin-left: 20px; font-weight: bold;\">$labelLinkNumeroDeAcessos $contadorAcessos</label>                                    
                                        <label title=\"$titleLabelLinkTotalDeGrupos\"   style=\"position: relative; float: right; margin-left: 20px;\">$labelLinkTotalDeGrupos   $totalGrupos </label>

                                    </div>    
                            </div>
                        ";
                    $i++;
                }
        } //FIM - if (mysql_num_rows($result) > 0) /*HÁ LINKS NA BUSCA FEITA PELOS MAIS ACESSADOS*/
        
        
        
        
        
        return $resultHTML;
    }
   
    
    
    
    
    
    
    /*
     * Retorna o código HTML que exibe a lista HTML de de TODOS OS LINKS de um usuario
     * @in:  (int)    id do usuario
     * @out: (string) codigo HTML da lista de TODOS os LINKS de um USUARIO
     * 
     * @OBS: Essa funcao chamara duas vezes a funcao obterListaDeGruposPorGrupoEUsuario.
     *       Na primeira chamada com o id do grupo = 0 para vir os links sem grupo
     *       Na segunda  chamada um for com todos os grupos do usuario
     */
    public static function obterListaHTMLDeTodosOsLinksDeUmUsuario($idUsuario)
    {
        $idUsuario = (int) $idUsuario; //idUsuario nao eh inteiro, string vazia
        $resultHTML = ""; /*O RESULTADO DA FUNCAO*/

        /*SE O USUARIO NAO POSSUE LINK, ENTAO EXIBE UMA MSG*/
            $totalDeLinks = HiperLinksUsuarioDAO::totalDeLinksDoUsuario($idUsuario);
            if ( $totalDeLinks == 0 )
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
        
        /*PRIMEIRO CHAMAR OS QUE NAO POSSUEM GRUPOS*/
            $resultHTML .= ViewGruposHiperLinksUsuario::obterListaDeGruposPorGrupoEUsuario($idUsuario, 0); /*LINKS SEM GRUPO*/

            if ($resultHTML != "") /*HA LINKS SEM GRUPO DESENHADOS - MARGEM DE 20PX PARA BAIXO*/
                $resultHTML .= "<div style=\"position:relative; margin-top:20px;\" ></div>";
        /*FIM - PRIMEIRO CHAMAR OS QUE NAO POSSUEM GRUPOS*/
        
        
        /*OBTEM TODOS OS GRUPOS DE USUARIO - PARA CADA UM EH UMA CHAMADA A FUNCAO QUE EXIBE OS LINKS DE UM GRUPO
         * EM $result CONTERA UM ARRAY COM OS IDS DOS GRUPOS DO USUARIO
         */
            $result = unserialize(GruposHiperLinksUsuarioDAO::obterTodosOsGruposDeUsuarioPorIdUsuario($idUsuario));
              
            if ($result) { /*SE TEM GRUPOS DE LINKS*/
                foreach ($result as $key => $value) {
                        $resultHTML .= ViewGruposHiperLinksUsuario::obterListaDeGruposPorGrupoEUsuario($idUsuario, $result[$key]->getIdGrupoHiperLinksUsuario());
                }
            }
         /*FIM - OBTEM TODOS OS GRUPOS DE USUARIO - PARA CADA UM EH UMA CHAMADA A FUNCAO QUE EXIBE OS LINKS DE UM GRUPO*/
        return $resultHTML;
    }
    
    
    
    /*
     * Retorna o código HTML que exibe o link de comando que chama o form de links
     * @in:  (int)    id do link - util para a view saber se se trata de uma edicao ou criacao por padrao.
     * @out: (string) codigo HTML do link de comando para abertura  do form de novo grupo
     */
    public static function obterLinkDeComandoParaFormLink($idHiperLinkUsuario, $_NBL_View, $_NBL_Action, $_NBL_Container)
    {
        

        if (!is_int($idHiperLinkUsuario)) return ""; //idGrupo nao eh inteiro, string vazia

        $t = NBLIdioma::getTextoPorIdElemento('titleImgAbrirFormLink');
        $a = NBLIdioma::getTextoPorIdElemento('altImgAbrirFormLink');
        $v = NBLIdioma::getTextoPorIdElemento('linkMenuComandoNovoLink');

        $result = <<<EOT
        <form id="formChamaFormHiperLinkUsuario" action="formHiperLinkUsuario.php" method="post" style="margin-bottom: 5px;">
            <img id="imgNovoFavoritos"       style="position: relative; top: 5px;" 
                                 src="imagens/imgNovoFavorito.png" 
                                 title="$t" 
                                 alt="$a" >

            <input name="idHiperLinkUsuario"      type="hidden" value="$idHiperLinkUsuario">
            <input name="_NBL_View"                type="hidden" value="$_NBL_View">
            <input name="_NBL_Action"              type="hidden" value="$_NBL_Action">
            <input name="_NBL_Container"           type="hidden" value="$_NBL_Container">


            <input id="linkFormChamaFormHiperLinkUsuario" style="border-style: none; font-size:12px; margin-left:-5px; color:#0066FF; background-color: #ffffff; cursor: pointer;"  type="submit" value="$v">
        </form>
EOT;
        return $result;
    }
}