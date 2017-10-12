<?php
/**
 * @author Paulo Ricardo Santos e Araújo.
 * Criada em 13/04/2013.
 */
class GruposHiperLinksUsuarioDAO extends MainDAO {
    const objModel = "GruposHiperLinksUsuario";


    
    /*Exclui um Grupo de links pelo ID passado.
     * @IN: id do grupo;
     * @OUT: True se foi excluído ou false se houve erro
     *  Neste caso deve-se observar que se um link pertence exclusivamente ao grupo que deseja-se
     *  exlcuir, entao deve-se exlcui-lo da tabela de links do usuario.
     */
    public static function excluiPorId($id) {
        try {
            /*SABER QUAIS PERTENCEM EXCLUSIVAMENTE AO GRUPO PASSADO (PERTENCEM UNICAMENTE AO GRUPO PASSADO) 
             * E NESTE CASO, APAGA-LOS (LINKS) DA TABELA DE LINKS DO USUARIO*/
                $l = array(); /*$l EH UM ARRAY QUE CONTEM UMA LISTA COM OS IDS DOS LINKS*/
                $linkExclusivos = self::idDosLinksQuePertencemExclusivamenteAoGrupoPassado($id);
                if ( mysql_num_rows($linkExclusivos) > 0 ) /*HA LINKS EXCLUSIVOS AO GRUPO PASSADO*/
                    while ($row = mysql_fetch_array($linkExclusivos))
                        $l[] = $row['idHiperLinkUsuario']; /*armazena em um array a lista de links exclusivos ao grupo passado*/
            /*FIM - SABER QUAIS PERTENCEM EXCLUSIVAMENTE AO GRUPO PASSADO E APAGA-LOS DA TABELA DE LINKS DO USUARIO*/


            /*APAGA DA TABELA DE GRUPOS E O BD JA APAGA DA TABELA DE MUITOS PARA MUITOS*/    
                $id = (int) $id;
                $query = "DELETE FROM gruposhiperlinksusuario WHERE idGrupoHiperLinksUsuario = " . " '$id' ";
                parent::query($query);
            /*FIM - APAGA DA TABELA DE GRUPOS E O BD JA APAGA DA TABELA DE MUITOS PARA MUITOS*/

            /*AGORA APAGAR OS LINKS EXCLUSIVOS - SALVO NO ARRAY CRIADO MAIS ACIMA NESTA FUNCAO*/
                foreach ($l as $key => $value)
                {
                    HiperLinksUsuarioDAO::excluiPorId($value);
                }
            /*FIM - AGORA APAGAR OS LINKS EXCLUSIVOS - SALVO NO ARRAY CRIADO MAIS ACIMA NESTA FUNCAO*/
            return TRUE; /*NAO HOUVE ERRO*/
        } catch (Exception $exc) {
            echo $exc->getTraceAsString(); /*IMPRIME O ERRO*/
            return FALSE; /*HOUVE ERRO*/
        }
    }


     /*Obtem lista dos ids dos links que pertencem a um só grupo
     * @IN: id do grupo
     * @OUT: retorna um resource mysql com os ids dos links que pertencem exclusivamente ao grupo
      *      que tem o id passado como argumento. Usada na exlcusao de um grupo de links por exemplo.
     */
    public static function idDosLinksQuePertencemExclusivamenteAoGrupoPassado($id) {
        $id = (int) $id;
        $query = "
            SELECT m.*, count(idHiperLinkUsuario) as totalDeGrupos FROM m_to_m_grupos_links m 
            GROUP BY m.idHiperLinkUsuario HAVING idGrupoHiperLinksUsuario = $id AND totalDeGrupos = 1";
        return parent::query($query); /*RETORNA UM RESOURCE MYSQL*/
    }
    
    
    
    /*
     * RETORNA O TOTAL DE GRUPOS DE UM USUARIO PASSADO
     * @in:  id do usuario
     * @out: inteiro com o total de grupos que o usuario possui
     */
    public static function totalDeGruposDoUsuario($idUsuario)
    {
        $idUsuario = (int) $idUsuario; //nao foi passado inteiro, retorna null
        $query    = "
                SELECT idUsuario, count(idGrupoHiperLinksUsuario) as totalDeGrupos FROM gruposhiperlinksusuario gu
                WHERE idUsuario = $idUsuario
        ";
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        $row = mysql_fetch_array($resource);
        return $row['totalDeGrupos'];
    }
    

   
    /*
     * RETORNA O TOTAL DE LINKS DE UM DADO USUARIO QUE NAO ESTAO EM GRUPO ALGUM
     * @in:  id do usuario
     * @out: inteiro com o total de links que nao possuem grupo associado
     */
    public static function totalDeLinksSemGrupo($idUsuario)
    {
        if (!is_int($idUsuario)) return null; //nao foi passado inteiro, retorna null
                
        $query    = "
                SELECT idUsuario, count(idHiperLinkUsuario) as totalDeLinks from hiperlinksusuario lu
                WHERE lu.idHiperLinkUsuario NOT IN
                (
                 SELECT idHiperLinkUsuario FROM m_to_m_grupos_links
                ) AND lu.idUsuario = $idUsuario            
        ";
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        $row = mysql_fetch_array($resource);
        return $row['totalDeLinks'];
    }

    /*
     * RETORNA UM RESOURSE MYSQL QUE CONTEM UM GRUPO E O TOTAL DE LINKS QUE POSSUI
     * @in:  id do usuario E id do grupo
     * @out: resource MYSQL contendo os seguinte campos (idUsuario, idGrupo, nome, descricao, totalDeLinks)
     * Usada para desenhar as barras dos links, por exemplo.
     * 
     */
    public static function obterGruposETotalDeLinks($idUsuario, $idGrupo)
    {
        if (!is_int($idUsuario)) return null; //nao foi passado inteiro, retorna null
                
        $query    = "
            SELECT 
                g.idUsuario, 
                g.idGrupoHiperLinksUsuario AS idGrupo, 
                g.nome, 
                g.descricao, 

            tg.totalDeLinks FROM gruposhiperlinksusuario AS g
            LEFT JOIN 
            (
                        SELECT m.idGrupoHiperLinksUsuario, COUNT(m.idGrupoHiperLinksUsuario) AS totalDeLinks FROM nbooklin_nbl.m_to_m_grupos_links m 
                        GROUP BY m.idGrupoHiperLinksUsuario HAVING m.idGrupoHiperLinksUsuario = $idGrupo
            ) AS tg 
            ON (tg.idGrupoHiperLinksUsuario = g.idGrupoHiperLinksUsuario) WHERE (idUsuario = $idUsuario AND g.idGrupoHiperLinksUsuario = $idGrupo) ORDER BY g.nome
        ";
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        if (mysql_num_rows($resource) == 0) { //NAO FOI ENCONTRADO OBJETO NO RESOURCE PASSADO (OBTIDO DA CLASSE MAE POR QUEM CHAMOU)
            return NULL; //RETORNA NULL
        } else {
            return $resource;
        }
    }
    
    /*
     * RETORNA UM RESOURSE MYSQL QUE CONTEM OS TOTAIS DE LINKS EM CADA GRUPO DE UM DETERMINADO USUARIO
     * @in:  id do usuario
     * @out: resource MYSQL contendo os seguinte campos (idUsuario, idGrupo, nome, descricao, totalDeLinks)
     * 
     * USADA PARA: desenhar os links na div da direita, por exemplo
     */
    public static function obterGruposETotalDeLinksPorIdUsuario($idUsuario)
    {
        if (!is_int($idUsuario)) return null; //nao foi passado inteiro, retorna null
                
        $query    = "
            SELECT g.idUsuario, g.idGrupoHiperLinksUsuario AS idGrupo, g.nome, g.descricao, tg.totalDeLinks FROM gruposhiperlinksusuario AS g
            LEFT JOIN 
            (
                SELECT m.idGrupoHiperLinksUsuario, COUNT(m.idGrupoHiperLinksUsuario) AS totalDeLinks FROM nbooklin_nbl.m_to_m_grupos_links m 
                GROUP BY m.idGrupoHiperLinksUsuario
            ) AS tg 
            ON (tg.idGrupoHiperLinksUsuario = g.idGrupoHiperLinksUsuario) WHERE idUsuario = $idUsuario ORDER BY g.nome
        ";
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        if (mysql_num_rows($resource) == 0) { //NAO FOI ENCONTRADO OBJETO NO RESOURCE PASSADO (OBTIDO DA CLASSE MAE POR QUEM CHAMOU)
            return NULL; //RETORNA NULL
        } else {
            return $resource;
        }
    }
    

    /*
     * RETORNA UM ARRAY DE OBJETOS DO TIPO GruposHiperLinkUsuario para um usuario passado.
     * @in:  id do usuaroi
     * @out: array contendo os objetos grupos de favoritos de um usario
     */
    public static function obterTodosOsGruposDeUsuarioPorIdUsuario($idUsuario)
    {
        if (!is_int($idUsuario)) return null; //nao foi passado inteiro, retorna null
        $result = array();
        $query    = "SELECT * FROM gruposhiperlinksusuario WHERE idUsuario = " . " '$idUsuario' order by nome";
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        if (mysql_num_rows($resource) == 0) { //NAO FOI ENCONTRADO OBJETO NO RESOURCE PASSADO (OBTIDO DA CLASSE MAE POR QUEM CHAMOU)
            return NULL; //RETORNA NULL
        } else {
            while ($row = mysql_fetch_array($resource)) {
                //POPULAR O OBJETO.
                $o = new GruposHiperLinksUsuario();
                //PENDENCIA: TRATAR CODIFICAÇÃO AQUI (UTF8_ENCODE)
                //PENDENCIA[REUTILIZACAO] Metodo na classe mae que retorna objeto com base na tabela
                $o->setIdGrupoHiperLinksUsuario($row['idGrupoHiperLinksUsuario']);
                $o->setNome                    (utf8_encode($row['nome']));
                $o->setDescricao               (utf8_encode($row['descricao']));
                $o->setIdUsuario               ($row['idUsuario']);
                $result[$row['idGrupoHiperLinksUsuario']] = $o;
            }
        }
        return serialize($result);
    }

    
    /*
     * RETORNA UM RESOURSE MYSQL QUE CONTEM OS LINKS DE UM GRUPO DE UM DETERMINADO USUARIO
     * @in:  id do usuario, id do grupo. OBS: O id do usuario eh fornecido par agilizar a pesquisa SQL. Se o id do grupo for 0 retorna todos os links sem grupo
     * @out: resource MYSQL contendo os seguinte campos (
     * idHiperLink, url, (TABELA DE HIPERLINKS) 
     * idHiperLinkUsuario, ultimoAcesso, contadorAcesso, ... (TODOS DA TABELA DE HIPERLINKS DE USUARIO
     * totalGrupos, idGrupoHiperLinksUsuario
     */
    public static function obterLinksDeUmGrupo($idUsuario, $idGrupo = 0)
    {
        /*Proteje para evitar injecao de SQL*/
        if (!is_int($idUsuario)) (int) $idUsuario; //nao foi passado inteiro, retorna null
        if (!is_int($idGrupo))   (int) $idGrupo; //nao foi passado inteiro, retorna null
        
        /*PARA OS LINKS QUE NAO POSSUEM GRUPOS MUDA POUCA SINTAXE NO SQL QUE EH MONTADO DINAMICAMENTE*/
        if ($idGrupo == 0)
        {
            $sqlSemGrupo1 = "NOT";
            $sqlComGrupo  = "";
            
        }
        else {
            $sqlSemGrupo1 = "";
            $sqlComGrupo  = "WHERE m_to_m_grupos_links.idGrupoHiperLinksUsuario = $idGrupo";
        }
        /*FIM - PARA OS LINKS QUE NAO POSSUEM GRUPOS MUDA POUCA SINTAXE NO SQL QUE EH MONTADO DINAMICAMENTE*/
        
            
        
        
        $result = array();
        $query    = "
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

                WHERE lu.idHiperLinkUsuario $sqlSemGrupo1 IN
                (
                 SELECT idHiperLinkUsuario FROM nbooklin_nbl.m_to_m_grupos_links $sqlComGrupo
                ) ORDER BY lu.nome, l.url
";
        
        $resource = parent::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        if (mysql_num_rows($resource) == 0) { //NAO FOI ENCONTRADO OBJETO NO RESOURCE PASSADO (OBTIDO DA CLASSE MAE POR QUEM CHAMOU)
            return NULL; //RETORNA NULL
        } else {
            return $resource;
        }
    }
    
    
    /*Obtem uma entidade pelo id
     * @IN: id da entidade;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorId($id) {
        $id = (int) $id;
        $query = "SELECT * FROM gruposhiperlinksusuario WHERE idGrupoHiperLinksUsuario = " . " '$id' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }

    
        public static function persiste(GruposHiperLinksUsuario $GruposHiperLinksUsuario) {
        /*
         * LEMBRAR O SEGUINTE:
         * 1 - NA CRIACAO DO OBJETO TODOS OS CAMPOS SAO NULL POR PADRAO, ENTAO O QUE NAO FOR DEFINIDO ESTA COMO NULL
         * 2 - CAMPOS COM VALOR NULL DEVEM ESTAR ENTRE ASPAS SIMPLES
         * 3 - OS VALORES PADRAO SAO SEMPRE DEFINIDOS NO CODIGO CLIENTE - OS CAMPOS NULL DEVEM ESTAR ENTRE ASPAS SIMPLES.
         */
            
            
            $idGrupoHiperLinksUsuario = $GruposHiperLinksUsuario->getIdGrupoHiperLinksUsuario();
            $nome                     = $GruposHiperLinksUsuario->getNome();
            $descricao                = $GruposHiperLinksUsuario->getDescricao();
            $idUsuario                = $GruposHiperLinksUsuario->getIdUsuario();
            
            $query = "";
            if ($idGrupoHiperLinksUsuario == 0 || $idGrupoHiperLinksUsuario == NULL) { //NAO EXISTE USUARIO E É UMA INSERÇÃO.
                $query = "

                    INSERT INTO `nbooklin_nbl`.`gruposhiperlinksusuario` 
                    (
                        `idGrupoHiperLinksUsuario`, 
                        `nome`, 
                        `descricao`, 
                        `idUsuario`
                     )
                     VALUES 
                     (
                        '$idGrupoHiperLinksUsuario', 
                        '$nome', 
                        '$descricao', 
                        '$idUsuario' 
                     )
                ";
            } else { //EXISTE ENTRADA NO BD E ATUALIZA (OS CAMPOS NULOS DEVEM IR SEM ASPAS
                $query = "

                        UPDATE `nbooklin_nbl`.`gruposhiperlinksusuario` SET 
                            idGrupoHiperLinksUsuario    = '$idGrupoHiperLinksUsuario', 
                            nome                        = '$nome',
                            descricao                   = '$descricao',
                            idUsuario                   = '$idUsuario'
                        WHERE idGrupoHiperLinksUsuario  = '$idGrupoHiperLinksUsuario'
                ";
            }
            
            if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
                return TRUE;
        }
}