<?php

/**
 * @author Paulo Ricardo Santos e Araújo.
 * Criada em 18/04/2013.
 */
class HiperLinksUsuarioDAO extends MainDAO {
    const objModel = "HiperLinksUsuario";
    
    /*Obtem uma entidade pelo id
     * @IN: id da entidade;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorId($id) {
        $id = (int) $id;
        $query = "SELECT * FROM hiperlinksusuario WHERE idHiperLinkUsuario = " . " '$id' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }


    /*RECUPERA OS LINKS MAIS RECENTES CLICADOS PELO USUARIO 
     * @IN: 
     * id do usuario
     * tipoTop informa se sao os mais recentes ou se os mais visitados. 
     * A intencao eh evitar a duplicacao do codigo SQL que eh praticamente igual para as duas consultas.
     * Aceitas dois tipos: RECENTES ou MAIS_VISITADOS
     * 
     * USADA POR: visualizar os top links do usuario que sao exibidos na home
     * Criada em 15-07-13, SEG.
     */
    public static function topLinks($idUsuario, $tipoTop = "RECENTES") {
        $idUsuario = (int) $idUsuario;
        
        if ( $tipoTop == "RECENTES" )
        {
            $sqlAuxiliar = "ORDER BY lu.ultimoAcesso DESC LIMIT 5";
        }
        if ( $tipoTop == "MAIS_ACESSADOS" )
        {
            $sqlAuxiliar = "ORDER BY lu.contadorAcessos DESC, lu.ultimoAcesso DESC LIMIT 5";
        }
        
        $query = "
                SELECT 
                l.idHiperlink,
                l.url,
                l.totalAcessos,

                lu.*,

                mm.totalGrupos
                FROM 
                nbooklin_nbl.hiperlinks AS l
                LEFT JOIN 
                (
                            SELECT * FROM nbooklin_nbl.hiperlinksusuario where idUsuario = $idUsuario /* Diminui o produto cartesiano já filtrar o usuario neste momento */
                ) AS lu 
                ON (l.idHiperLink = lu.idHiperLink) 

                LEFT JOIN
                (
                            SELECT *, count(idHiperLinkUsuario) as totalGrupos FROM nbooklin_nbl.m_to_m_grupos_links
                            GROUP BY idHiperLinkUsuario
                ) AS mm
                ON (mm.idHiperLinkUsuario = lu.idHiperLinkUsuario)
                WHERE idUsuario = $idUsuario
                $sqlAuxiliar";
        return parent::query($query);
    }
    
    
    
    /*EXCLUI UM LINK DE USUARIO DA TABELA DE LINKS DO USUARIO
     * A FUNCAO EH PRIVADAS POIS NAO PODE SER CHAMADA DE FORA DA CLASSE PARA
     * GARANTIR O CUMPRIMENTO DAS REGRAS DE NEGOCIO.
     * @IN: id do link a ser excluido;
     * @OUT: True se foi excluído ou false se houve erro
     * 
     * USADA POR: GruposHiperLinksUsuarioDAO::excluiPorId() para excluir quando o link pertence exclusivamente a um grupo.
     */
    public static function excluiPorId($id) {
        $id = (int) $id;
        $query = "DELETE FROM hiperlinksusuario WHERE idHiperLinkUsuario = " . " '$id' ";
        return parent::query($query);
    }
    
    /*EXLCUI UM LINK DE UM DETERMINADO GRUPO. SE O LINK PERTENCE EXCLUSIVAMENTE AO GRUPO APAGA O LINK TAMBEM
     */
    public static function excluirUmaOcorrencia($idLink, $idGrupo = 0)
    {
        $idLink  = (int) $idLink;
        $idGrupo = (int) $idGrupo;
        
        /*APAGA DA TABELA M-TO-M*/
        M_to_m_grupos_linksDAO::excluiPorIdLinkEIdGrupo($idLink, $idGrupo); /*APAGA DA TABELA M-TO-M*/
            
        /*VERIFICAR SE O LINK AINDA PERTENCE A ALGUM GRUPO POIS SE NAO PERTENCER, ENTAO APAGA DA TABELA DE LINKS DO USUARIO*/    
            if ( !self::pertenceAAlgumGrupo($idLink) )  /*NAO PERTENCE MAIS A NENHUM GRUPO*/
            {
                return self::excluiPorId($idLink); /*EXCLUI DA TABELA DE LINKS DO USUARIO E RETORNA O RESULTADO DO QUERY SQL*/
            }
        /*FIM - VERIFICAR SE O LINK AINDA PERTENCE A ALGUM GRUPO POIS SE NAO APAGA DA TABELA DE LINKS DO USUARIO*/    
        return TRUE;    
    }

    /*INDICA SE UM LINK PASSADO PERTENCE A ALGUM GRUPO
     * IN  : id do link
     * OUT : TRUE - pertence a algum grupo, FALSE - nao pertece a grupo algum.
     */
    public static function pertenceAAlgumGrupo ($idLink)
    {
        $idLink = (int) $idLink;
        $query = "
            SELECT idGrupoHiperLinksUsuario FROM m_to_m_grupos_links WHERE
            idHiperLinkUsuario = $idLink LIMIT 1
        ";
        $result = mysql_num_rows( MainDAO::query($query) ); /*EXECUTA A PESQUISA - LINK NAO PERTENCE A GRUPO ALGUM*/
        if ( $result == 0 ) {                 /*PESQUISA RETORNOU NENHUMA LINHA */
            return FALSE;
        } 
        else { /*SE N RETORNOU NULL EH PORQUE RETORNOU UMA LINHA - LINK PERTENCE A ALGUM GRUPO*/
            return TRUE;
        }
    }
    
    /*Registra informaees decorrentes de um acesso a um HiperLink,
     * como por exemplo, incrementar contadores de acesso, data de ultimo acesso.
     * Alterar informacoes do Link global ao qual esta assossiado.
     * Para maiores informacoes: ver caso de uso 'CLICAR EM LINK'
     * @IN: id do hiper link de usuario (com essa informacao eh possivel acessar o
     * o objeto que contem informacoes do hiperlik global
     * global;
     * @OUT: True se foram executadas todos os registros de informacoes definidos
     *       no caso de uso mencionado. FALSE, caso contrario.
     */
    public static function registrarAcesso ($id) {
        $id = (int) $id;                                /*SE NAO FOR INTEIRO FAZ COERCAO DE TIPO*/
        $lu = HiperLinksUsuarioDAO::obterPorId($id);    /*OBTEM POR ID DO LINK DE USUARIO*/
        
        /*REGISTRA INFORMACOE PARA O HIPER LINK DE USUARIO*/
        
        $duv = date("Y-m-d H:i:s");                       /*CRIA NOVA DATA DE ACESSO COM A DATA ATUAL*/
        $duv = new NBLDateTime($duv);
        
        $lu->setUltimoAcesso($duv);   /*SETA A DATA DE ACESSO*/
        $lu->setContadorAcessos( $lu->getContadorAcessos() + 1 ); /*INCREMENTA O CONTADOR DE ACESSO*/
        
        /*REGISTRA INFORMACOES PARA O HIPERLINK GLOBAL*/
        $nivelCompartilhamento = $lu->getNivelCompartilhamento();       /*NIVEL DE COMPAR. DO LINK GLOBAL*/
        $idhl = $lu->getIdHiperLink(); /*OBTEM O ID DO HIPER LINK GLOBAL*/
        $hl   = HiperLinkDAO::obterPorId($idhl);                        /*OBTEM O OBJETO HIPERLINK GLOBAL*/
        
        $total = $hl->getTotalAcessos();
        $total++;
        $hl->setTotalAcessos($total);               /*INCREMENTA O CONTADO DE ACESSOS*/
        
        /*AGORA GRAVA AS INFORMACOES DO LINK DE USUARIO E LINK GLOBAL*/
        HiperLinksUsuarioDAO::persiste($lu);
        HiperLinkDAO::persiste($hl);
    }
    
    
     /*
     * RETORNA O TOTAL DE LINKS DE UM DADO USUARIO
     * @in:  id do usuario
     * @out: inteiro com o total de links que o usuario possui
     */
    public static function totalDeLinksDoUsuario($idUsuario)
    {
        $idUsuario = (int) $idUsuario; //nao foi passado inteiro, retorna null
        $query    = "
                SELECT idUsuario, count(idHiperLinkUsuario) as totalDeLinks FROM hiperlinksusuario lu
                WHERE idUsuario = $idUsuario
        ";
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        $row = mysql_fetch_array($resource);
        return $row['totalDeLinks'];
    }

     /*
     * RETORNA UM ARRA COM A LISTA DOS IDS DOS GRUPO AOS QUAIS O LINK COM ID PASSADO PERTENCE
     * @in:  id link de usuario
     * @out: array VAZIO ou array de inteiros COM IDS dos grupos aos quais o link pertence;
     */
    public static function obterListaDosIdsDosGruposAosQuaisOLinkPertence($idHiperLinkUsuario)
    {
        $idHiperLinkUsuario = (int) $idHiperLinkUsuario;
        $query    = "
            SELECT idGrupoHiperLinksUsuario FROM m_to_m_grupos_links WHERE idHiperLinkUsuario = $idHiperLinkUsuario
        ";
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        $result = array();
            while ( $row = mysql_fetch_array($resource) ) {
                $result[] = (int) $row['idGrupoHiperLinksUsuario'];
            }
        return $result;
    }
    
    
    
    
    public static function persiste(HiperLinksUsuario $HiperLinksUsuario) {
            $idHiperLinkUsuario	    = $HiperLinksUsuario->getIdHiperLinkUsuario();
//            $ultimoAcesso           = ( $HiperLinksUsuario->getUltimoAcesso()  != NULL ) ? "'{$HiperLinksUsuario->getUltimoAcesso()->toStringGravar()}'" : "NULL";
//            $dataCadastro           = ( $HiperLinksUsuario->getDataCadastro()  != NULL ) ? "'{$HiperLinksUsuario->getDataCadastro()->toStringGravar()}'" : "NULL";
            $ultimoAcesso           = $HiperLinksUsuario->getUltimoAcesso()->toStringGravar();
            $dataCadastro           = $HiperLinksUsuario->getDataCadastro()->toStringGravar();
            
            $contadorAcessos	    = $HiperLinksUsuario->getContadorAcessos();
            $nivelCompartilhamento  = $HiperLinksUsuario->getNivelCompartilhamento();	 
            $nota                   = $HiperLinksUsuario->getNota();
            $nome                   = $HiperLinksUsuario->getNome();
            $descricao              = $HiperLinksUsuario->getDescricao(); 
            $contadorImportacao     = $HiperLinksUsuario->getContadorImportacao();
            $idHiperLink            = $HiperLinksUsuario->getIdHiperLink();  	 
            $idUsuario              = $HiperLinksUsuario->getIdUsuario();
            
            
            
            
            $query = "";
            if ($idHiperLinkUsuario == 0 || $idHiperLinkUsuario == NULL) { //NAO EXISTE USUARIO E É UMA INSERÇÃO.
                $query = "

                    INSERT INTO `nbooklin_nbl`.`hiperlinksusuario` 
                    (
                        `idHiperLinkUsuario`,
                        `ultimoAcesso`,
                        `dataCadastro`,
                        `contadorAcessos`,
                        `nivelCompartilhamento`,
                        `nota`,
                        `nome`,
                        `descricao`,
                        `contadorImportacao`,
                        `idHiperLink`,
                        `idUsuario`
                     )
                     VALUES 
                     (
                        '$idHiperLinkUsuario',
                         $ultimoAcesso,
                         $dataCadastro,    
                        '$contadorAcessos',
                        '$nivelCompartilhamento',
                        '$nota',
                        '$nome',
                        '$descricao',
                        '$contadorImportacao',
                        '$idHiperLink',
                        '$idUsuario'
                     )
                ";
            } else { //EXISTE ENTRADA NO BD E ATUALIZA (OS CAMPOS NULOS DEVEM IR SEM ASPAS
                
                $query = "

                        UPDATE `nbooklin_nbl`.`hiperlinksusuario` SET 


                        idHiperLinkUsuario      = '$idHiperLinkUsuario',
                        ultimoAcesso            = $ultimoAcesso,
                        dataCadastro            = $dataCadastro,
                        contadorAcessos         = '$contadorAcessos',
                        nivelCompartilhamento   = '$nivelCompartilhamento',
                        nota                    = '$nota',
                        nome                    = '$nome',
                        descricao               = '$descricao',
                        contadorImportacao      = '$contadorImportacao',
                        idHiperLink             = '$idHiperLink',
                        idUsuario               = '$idUsuario'
                        WHERE idHiperLinkUsuario  = '$idHiperLinkUsuario'
                ";
            }
            
            //print_r($query);            die();
            if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
                return TRUE;
        }
}