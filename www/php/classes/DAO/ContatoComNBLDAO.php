<?php
/*
 * 1 - CLASSE DE ACESSO AO BD PARA A CLASSE DE ENTIDADE QUE ARMAZENA AS MENSAGENS DE CONTATO DOS INTERNAUTAS E USUARIO COM O NBL
 * 2 - HERDA DA CLASSE MainDAO
 * 
 * DEVE PROVER AS SEGUINTES FUNCIONALIDADES:
 * 1 - CRUD
 * 2 - PESQUISAS POR DIVERSOS CAMPOS QUE RETORNAM OBJETOS.
 *
 * 
 * Description of ContatoComNBLDAO
 *
 * @author Paulo Ricardo Santos e Araújo.
 * Criada em 29/12/2012.
 */
class ContatoComNBLDAO extends MainDAO {
    const objModel = "ContatoComNBL";
    /*Obtem um objeto em questão pelo seu id
     * @IN: id do objeto armazenado no BD;
     * @OUT: Objeto populado ou NULL
     * @OBS: Perceber que o que muda eh o id passado e o nome da coluna no bd.
     * Essa função poderia ficar na classe mae sendo necessario passar o id do objeto e o nome da coluna
     */
    public static function obterPorId($id) {
        $id = (int) $id;
        $query = "SELECT * FROM sac WHERE idContatoComNBL = " . " '$id' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }

    
    /*
     * @PERSISTE O OBJETO NO BD.
     * SE O ID DO OBJETO FOR NULL, ENTAO EH OBJETO NOVO E CRIA UMA NOVA ENTRADA NO BD
     * SE O ID DO OBJETO FOR <> NULL, ENTAO ATUALIZA O EXISTENTE NO BD COM BASE NO ID PASSADO
     */

    public static function persiste(ContatoComNBL $ContatoComNBL) {
        /*
         * LEMBRAR O SEGUINTE:
         * 1 - NA CRIACAO DO OBJETO TODOS OS CAMPOS SAO NULL POR PADRAO, ENTAO O QUE NAO FOR DEFINIDO ESTA COMO NULL
         * 2 - CAMPOS COM VALOR NULL DEVEM ESTAR ENTRE ASPAS SIMPLES
         * 3 - OS VALORES PADRAO SAO SEMPRE DEFINIDOS NO CODIGO CLIENTE - OS CAMPOS NULL DEVEM ESTAR ENTRE ASPAS SIMPLES.
         * 4 - OS VALORE QUE ESTAO NO OBJETO JA FORAM TRATADOS CONTRA POSSIVEIS ATAQUES ANTES DE SEREM ENVIADOS PARA CA
         */

        $idContatoComNBL         = $ContatoComNBL->getIdContatoComNBL(); //ID DO CONTATO
        $idUsuario               = $ContatoComNBL->getIdUsuario();         //ID DO USUARIO QUE ENTROU EM CONTATO
        $tipoMensagem            = $ContatoComNBL->getTipoMensagem();      //O TIPO DA MSG - S->SUGESTAO, E-ERRO, C-CRITICA, O-OUTRO
        $mensagem                = $ContatoComNBL->getMensagem();          //O CONTEUDO DA MSG
        $dataEnvio               = $ContatoComNBL->getDataEnvio();         //A DATA EM QUE A MSG FOI ENVIADA
        $idioma                  = $ContatoComNBL->getIdioma();            //O IDIOMA EM QUE A MSG FOI ESCRITA
        $status                  = $ContatoComNBL->getStatus();            //SE JA FOI RESPONDIDA..., O STATUS DA MSG (SERA DEFINIDO)
        $dataResposta            = $ContatoComNBL->getDataResposta();      //A DATA EM QUE FOI RESPONDIDA AO INTERNAUTA/USUARIO
        $resposta                = $ContatoComNBL->getResposta();           //O CONTEUDO DA RESPOSTA QUE FOI DADA PELA EQUIPE DO NBL
        $nomeRemetente           = $ContatoComNBL->getNomeRemetente();     //SE NAO FOR UM USUARIO CADASTRADO, ENTAO O NOME DE QUEM ENVIOU A MSG
        $emailRemetente          = $ContatoComNBL->getEmailRemetente();    //SE NAO FOR UM USUARIO CADASTRADO, ENTAO O EMAIL DE QUEM ENVIOU A MSG 
        
        
        //PRA SABER SE JA EXISTE NO BD TEM QUE PESQUISA NA TABELA CORRESPONDENTE POIS TODOS OS DADOS JA VEM PREENCHIDOS (E PRECISO QUE VENHAM)
        $s = self::obterPorId($idContatoComNBL); // E PRECISO COLOCAR AQUI POIS NO UPTDATE OS CAMPOS QUE TEM NULL NAO VAO COM ASPAS
        

        //SE OS CAMPOS FOREM NULL, ENTAO DEVEM FICAR SEM ASPAS NO MOMENTO DA UTILIZACAO (SOLUCAO GLOBAL - UMA FUNCAO QUE JA FAZ ESSA PREPARACAO PARA TODOS OS CAMPOS DA CLASSE).
        
        $dataEnvio    = $ContatoComNBL->getDataEnvio()->toStringGravar();
        $dataResposta = $ContatoComNBL->getDataResposta()->toStringGravar();
        //$dataEnvio     = ( $ContatoComNBL->getDataEnvio()    != NULL ) ? "' {$ContatoComNBL->getDataEnvio()->toStringGravar()}  '" : "NULL";  
        //$dataResposta  = ( $ContatoComNBL->getDataResposta() != NULL ) ? "' {$ContatoComNBL->getDataResposta()->toStringGravar()} '" : "NULL";  
        

        $query = "";
        if ($s == NULL) { //NAO EXISTE SESSAO PARA O USUARIO
            $query = "
            
                INSERT INTO `nbooklin_nbl`.`sac` 
                (
                    `idContatoComNBL`, 
                    `idUsuario`, 
                    `tipoMensagem`, 
                    `mensagem`, 
                    `dataEnvio`, 
                    `idioma`, 
                    `status`, 
                    `dataResposta`,
                    `resposta`,
                    `nomeRemetente`,
                    `emailRemetente`
                 )
                 VALUES 
                 (
                    '$idContatoComNBL', 
                    '$idUsuario', 
                    '$tipoMensagem',
                    '$mensagem',
                     $dataEnvio,
                    '$idioma',
                    '$status',
                     $dataResposta,
                    '$resposta',
                    '$nomeRemetente',
                    '$emailRemetente'    
                 )
            ";
        } else { //EXISTE SESSAO E ATUALIZA (OS CAMPOS NULOS DEVEM IR SEM ASPAS
            $query = "
                    UPDATE `nbooklin_nbl`.`sac` SET 

                        idContatoComNBL    = '$idContatoComNBL',
                        idUsuario          = '$idUsuario',  
                        tipoMensagem       = '$tipoMensagem',
                        mensagem           = '$mensagem',
                        dataEnvio          =  $dataEnvio,
                        idioma             = '$idioma',
                        status             = '$status',
                        dataResposta       =  $dataResposta,
                        resposta           = '$resposta',
                        nomeRemetente      = '$nomeRemetente',
                        emailRemetente     = '$emailRemetente'    
                            
                    WHERE idContatoComNBL  = '$idContatoComNBL'        
            ";
        }
        
        if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
            return TRUE;
    }
    
    
    
    
}