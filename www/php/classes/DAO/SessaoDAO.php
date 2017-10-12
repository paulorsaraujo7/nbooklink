<?php
/*
 * 1 - CLASSE DE ACESSO AO BD PARA A CLASSE DE ENTIDADE SESSAO
 * 2 - HERDA DA CLASSE MAINDB.
 * 
 * DEVE PROVER AS SEGUINTES FUNCIONALIDADES:
 * 1 - CRUD
 * 2 - PESQUISAS POR DIVERSOS CAMPOS QUE RETORNAM OBJETOS.

  /**
 * Description of SessaoDAO
 *
 * @author Paulo Ricardo Santos e Araujo.
 * Criada em 04/08/2012.
 * HISTORICO DE ATUALIZACOES
 * 09/08/2012 - metodo persiste (campos NULL) e outros
 */

class SessaoDAO extends MainDAO {
    const objModel = "Sessao";

    /*
     * RETORNA UM OBJETO DO TIPO SESSAO OU NULL
     * @in:  ID DO USUARIO DONO DA SESSAO ARMAZENADA;
     * @out: SESSAO POPULADA ou NULL caso nÃ£o exista;
     */

    public static function obterPorIdUsuario($idUsuario) {
        //SE O ARGUMENTO PASSADO NAO FOR INTEIRO, ENTAO TRANSFORMA EM INTEIRO
        if (!is_int($idUsuario))
            $idUsuario = (int) $idUsuario;
        $query = "SELECT * FROM sessoes WHERE idUsuario = $idUsuario";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }

    /*
     * RETORNA UM OBJETO DO TIPO SESSAO OU NULL
     * @in:  ID DA SESAO QUE E NA VERDADE O PHPSESSID;
     * @out: SESSAO POPULADA ou NULL caso nÃ£o exista;
     */
    public static function obterPorPHPSESSID($PHPSESSID) {
        //LEBRAR QUE O USUARIO PODE ESCREVER NO COOKIE UM CODIGO MALICIOSO, ENTAO O ARGUMENTO PASSADO DEVE SER ESCAPADO (PHPSESSID)
        $PHPSESSID = $PHPSESSID;
        $query = "SELECT * FROM sessoes WHERE PHPSESSID = " . " '$PHPSESSID' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }


    /*
     * RETORNA UM OBJETO DO TIPO SESSAO OU NULL
     * @in:  HASH DA ULTIMA SESSAO QUE E GERADO NO MOMENTO DO LOGIN;
     * @out: SESSAO POPULADA ou NULL caso nao exista;
     */
    public static function obterPorHashUltimaSessao($hashUltimaSessao) {
        //LEBRAR QUE O USUARIO PODE ESCREVER NO COOKIE UM CODIGO MALICIOSO, ENTAO O ARGUMENTO PASSADO DEVE SER ESCAPADO (PHPSESSID)
        $hashUltimaSessao = $hashUltimaSessao;
        $query = "SELECT * FROM sessoes WHERE hashUltimaSessao = " . " '$hashUltimaSessao' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }
  
    
    /*
     * @PERSISTE O OBJETO NO BD.
     * SE O ID DO USUARIO FOR NULL, ENTAO EH OBJETO NOVO E CRIA UMA NOVA ENTRADA NO BD
     * SE O ID DO USUARIO FOR <> NULL, ENTAO ATUALIZA
     */

    public static function persiste(Sessao $Sessao) {
        /*
         * LEMBRAR O SEGUINTE:
         * 1 - NA CRIAÃ‡ÃƒO DO OBJETO TODOS OS CAMPOS SÃƒO NULL POR PADRÃƒO, ENTÃƒO O QUE NAO FOR DEFINIDO ESTA COMO NULL
         * 2 - CAMPOS COM VALOR NULL DEVEM ESTAR ENTRE ASPAS SIMPLES
         * 3 - OS VALORES PADRAO SAO SEMPRE DEFINIDOS NO CODIGO CLIENTE - OS CAMPOS NULL DEVEM ESTAR ENTRE ASPAS SIMPLES.
         */

        $PHPSESSID                  = $Sessao->getPHPSESSID();                          //ID DA SESSAO PHP
        $idUsuario                  = $Sessao->getIdUsuario();                          //ID DO USUARIO DONO DA SESSAO
        $expiraEm                   = $Sessao->getExpiraEm()->toStringGravar();         //DATA E HORA QUE EXPIRA A SESSAO  
        $ultimoLogin                = $Sessao->getUltimoLogin()->toStringGravar();      //DATA E HORA DO ULTIMO LOGIN (DEIXAR O CODIGO CLIENTE DEFINIR O VALOR DESTE CAMPO) 
        $ultimoLogout               = $Sessao->getUltimoLogout()->toStringGravar();     //DATA E HORA DO ULTIMO LOGOUT
        $ultimoIPUtilizado          = $Sessao->getUltimoIPUtilizado();                  //ULTIMO IP UTLIZADO ANTES DO LOGOUT
        $ultimoIdiomaSelecionado    = $Sessao->getUltimoIdiomaSelecionado();            //ULTIMO IDIOMA SELECIONADO ANTES DE FAZER O LOGOUT
        //PRA SABER SE JA EXISTE NO BD TEM QUE PESQUISA NA TABELA DE SESSOES POIS TODOS OS DADOS JA VEM PREENCHIDOS (E PRECISO QUE VENHAM)
        $s = self::obterPorIdUsuario($idUsuario); // E PRECISO COLOCAR AQUI POIS NO UPTDATE OS CAMPOS QUE TEM NULL NAO VAO COM ASPAS
        //SE OS CAMPOS FOREM NULL, ENTAO DEVEM FICAR SEM ASPAS NO MOMENTO DA UTILIZACAO (SOLUCAO GLOBAL - UMA FUNCAO QUE JA FAZ ESSA PREPARACAO PARA TODOS OS CAMPOS DA CLASSE).
//        $expiraEm = ($expiraEm != NULL) ? "'$expiraEm'" : "NULL";
//        $ultimoLogin   = ( $Sessao->getUltimoLogin()  != NULL ) ? "' {$Sessao->getUltimoLogin()->toStringGravar()}  '" : "NULL";  
//        $ultimoLogout  = ( $Sessao->getUltimoLogout() != NULL ) ? "' {$Sessao->getUltimoLogout()->toStringGravar()} '" : "NULL";  
        
        $hashUltimaSessao = $Sessao->getHashUltimaSessao();

        $query = "";
        if ($s == NULL) { //NAO EXISTE SESSAO PARA O USUARIO
            $query = "
            
                INSERT INTO `nbooklin_nbl`.`sessoes` 
                (
                    `PHPSESSID`, 
                    `idUsuario`, 
                    `expiraEm`, 
                    `ultimoLogin`, 
                    `ultimoLogout`, 
                    `ultimoIPUtilizado`, 
                    `ultimoIdiomaSelecionado`,
                    `hashUltimaSessao`
                 )
                 VALUES 
                 (
                    '$PHPSESSID', 
                    '$idUsuario', 
                     $expiraEm, 
                     $ultimoLogin,  
                     $ultimoLogout, 
                    '$ultimoIPUtilizado',  
                    '$ultimoIdiomaSelecionado',
                    '$hashUltimaSessao'    
                 )
            ";
        } else { //EXISTE SESSAO E ATUALIZA (OS CAMPOS NULOS DEVEM IR SEM ASPAS
            $query = "
            
                    UPDATE `nbooklin_nbl`.`sessoes` SET 
                        PHPSESSID                   = '$PHPSESSID', 
                        idUsuario                   = '$idUsuario',
                        expiraEm                    =  $expiraEm,
                        ultimoLogin                 =  $ultimoLogin,
                        ultimoLogout                =  $ultimoLogout,
                        ultimoIPUtilizado           = '$ultimoIPUtilizado',
                        ultimoIdiomaSelecionado     = '$ultimoIdiomaSelecionado',
                        hashUltimaSessao            = '$hashUltimaSessao'    
                    WHERE idUsuario = '$idUsuario'
            ";
        }
        
        if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
            return TRUE;
    }

    /*
     * @DELETA DO BD
     * @IN: OBJETO SESSAO A SER EXCLUIDO.
     */

    public static function delete(Sessao $Sessao) {
        $idUsuario = $Sessao->getIdUsuario(); //VAI APAGAR PELO ID DO USUARIO
        $query = " DELETE FROM `nbooklin_nbl.sessoes` WHERE idUsuaro = '$idUsuario' ";
        if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
            return TRUE;
    }

}