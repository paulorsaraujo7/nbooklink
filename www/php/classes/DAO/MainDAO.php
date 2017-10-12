<?php
/*
  Prover mecanismos de acesso ao BD usando o padrão Singleton.
 * Perceber que para sessões diferentes, serão feitas conexões diferentes.
 * Para a mesma sessão, então será usado a mesma conexão. O que alivia bastante 
 * para o servidor.
 * 
 * 
 * Vantagens desta classe;
 * 1 - Uma só conexão para a mesma sessão.
 * 2 - As outras classes apenas enviam a string de consulta
 * 3 - Evita a chamada para scape de string mais de uma vez pois é feita uma única
 *     vez nesta classe, no método query que executa procedimentos padrão para todas as 
 *     pesquisas a serem feitas no BD
 * ;
 * 
 * PENDENCIAS:
 * 1 - USAR TRATAMENTO DE ERRO PARA CODIGO DE INCIO DE CONEXAO COM O BD. 
 *  1.1 - CASO HAJA ERRO ENTAO ENVIAR MENSAGEM AMIGAVEL E NO IDIOMA DO USUARIO.
 *      - ENVIAR MENSAGEM PARA O ADMINISTRADOR DO SITE.
 * 
 *
 */

/**
 * Em: 23 de maio de 2012
 * @author Paulo Ricardo
 *  
 */
class MainDAO {

    // Guarda uma instância da classe
    static private $_instance;
    protected $tabela;




    /*METODO QUE TODA CLASSE FILHA DEVE IMPLEMENTAR
     * IN@ STRING DE PESQUISA SQL
     * OUT@ OBJETO POPULADO OU NULL CASO NAO EXISTA CORRESPONDENCIA NO BD
     */

    // Um construtor privado: evita que a classe seja instanciada publicamente
    private function __construct() {
        
            $link = mysql_connect('127.0.0.1:3306', 'nbooklin_userBD', 'usuario'); //Evita que para mesma sessão sejam abertas várias conexões.
            if (!$link) {
                throw new mysqli_sql_exception();
                //die('Não foi possível conectar: ' . mysql_error() . "<br><br>");
            }

            $db_selected = mysql_select_db('nbooklin_nbl', $link);
            if (!$db_selected) {
                throw new mysqli_sql_exception();
                //die('Não foi possível selecionar : ' . mysql_error());
            }
            
            
            
    }

    // Evita que a classe seja clonada
    private function __clone() {
        
    }

    // O método singleton 
    static public function conectarMySQL() {
        if (!isset(self::$_instance)) { // Testa se há instância definifa na propriedade, caso sim, a classe não será instanciada novamente.
            self::$_instance = new self; // o new self cria uma instância da própria classe à própria classe.
        }
        return self::$_instance;
    }
    

    /* Método genérico para consulta ao BD;
     * @in  $query  String  string da consulta a ser feita (perceber a utilização de dica de tipo).
     * @out O mesmo retorno da função mysql_query (na classe que chama é que deve ser tratado o retorno. Exemplo: construir o objeto...);
     * 
     * For SELECT, SHOW, DESCRIBE, EXPLAIN and other statements returning resultset, mysql_query returns a resource on success, or false on error.
     * For INSERT, UPDATE, DELETE, DROP, etc, mysql_query returns true on success or false on error.
     * 
     * OBS: Essa é a função que será chamadas por todas as classes que precisem
     * fazer uma consulta ao BD;
     */

    public static function query($query) {
        
        
        /*
         * A chamada estática abaixo poderia ser feita por cada classe que quisesse
         * conectar ao BD, contudo, evita a repetição desta chamada, bastando
         * a classe já enviar a string de consulta que deseja;
         * REGRAS:
         * 1 - As CONSULTAS QUE GERAM ERRO são tratadas aqui com die e a exibição do número do erro.
         *     Isto evita replicação de código de verificação de erro nas classe filhas.
         * 2 - As CONSULTAS QUE NÃO GERAM ERRO são tratadas na classe que chamou pois vai depender do tipo de consulta. 
         * 
         */
        if (!is_string($query)) //ARGUMENTO PASSADO NÃO É STRING
            $query = (string) $query; //CONVERTE PARA STRING
        
        $query = utf8_decode($query); //A STRING TODA É DECODIFICADA PARA MULTILIGUAGEM
        
        self::conectarMySQL(); //Se não existir, então cria conexão única com o BD

        try {
            $result = mysql_query($query); // O ESCAPAMENTO EH FEITO INDIVIDUALMENTE, VET O TOP. 8 DO ARQ. DE APRENDIZADOS.
        } catch (Exception $exc) {
            header("Location:http://nbooklink.com/index.php?erro=erroBD");
        }

        

        //NÃO DEU ERRO - PARA OS TIPOS SELECT.. RETORNA RESOURCE OU NULL. PARA OS TIPOS CRUD - RETORNA TRUE SE SUCESSO
        return $result; //Retorna um resource MySQL ou TRUE (DEPENDENDO DO TIPO DA CONSULTA - EXPLICADO ACIMA) - VAI SER TRATADO NA CLASSE QUE CHAMOU
        
    }
    
    
    /*
     * @in : $query eh uma string SQL
     * @in : $o eh uma string contendo o nome do objeto do modelo a ser retornado
     * @out: um objeto populado
     * @Melhoria: varre o objeto e já preencher com base na tabela.
     */    
    protected static function retornaObjetoPorQuery ($query, $o)
    {
        $resource = self::query($query); //RECEBE O QUE VEM DA CLASSE MAE
        if (mysql_num_rows($resource) == 0) { //NAO FOI ENCONTRADO OBJETO NO RESOURCE PASSADO (OBTIDO DA CLASSE MAE POR QUEM CHAMOU)
            return NULL; //RETORNA NULL
        } 
        else {
            //POPULAR O OBJETO.
            $o = new $o(); //O parametro que veio com string eh instanciado como objeto
            $row = mysql_fetch_array($resource);
            if ($o instanceof Usuario)
            {

                //PENDENCIA: TRATAR CODIFICAÇÃO AQUI (UTF8_ENCODE)
                $o->setIdUsuario($row['idUsuario']);
                $o->setEmail($row['email']);
                $o->setNome(utf8_encode($row['nome']));
                $o->setLogin($row['login']);
                $o->setSenha($row['senha']);

                //EM BREVE FICARA NO PADRAO FACTORY

                //ERRO: QUANDO JA EXISTE DATA NO BD, ENTAO A DATA DE CADASTRO EH ATUALIZADA COM A DATA ATUAL POR CAUSA DO CONSTRUTOR DA CLASSE.
                $data = new NBLDateTime($row['dataCadastro']);
                $o->setDataCadastro ( $data );
                $o->setTemFoto($row['temFoto']); //ARMAZENA O CAMINHO DA FOTO PRINCIPAL DO PERFIL
                $o->setTotalVisitas($row['totalVisitas']);
                $o->setMensagemInicial(utf8_encode($row['mensagemInicial']));
                $o->setGenero($row['genero']);
                $o->setAnoNascimento($row['anoNascimento']);
                $o->setMesNascimento($row['mesNascimento']);
                $o->setDiaNascimento($row['diaNascimento']);
                $o->setNumeroDeLogins($row['numeroDeLogins']);
                

            }
            if ($o instanceof Sessao)
            {
                $o->setPHPSESSID($row['PHPSESSID']);
                $o->setIdUsuario($row['idUsuario']);
                $o->setExpiraEm(new NBLDateTime(date($row['expiraEm'])));


                //EM BREVE FICARA NO PADRAO FACTORY
                $data = new NBLDateTime($row['ultimoLogin']); //EH PRECISO CRIAR PARA DEFINIR O TIMEZONE
                $o->setUltimoLogin($data);

                $data = new NBLDateTime($row['ultimoLogout']); //EH PRECISO CRIAR PARA DEFINIR O TIMEZONE
                $o->setUltimoLogout($data);



                $o->setUltimoIPUtilizado($row['ultimoIPUtilizado']);
                $o->setUltimoIdiomaSelecionado($row['ultimoIdiomaSelecionado']);
                $o->setHashUltimaSessao($row['hashUltimaSessao']);
            }
            if ($o instanceof HiperLinksUsuario)
            {
                //PENDENCIA: TRATAR CODIFICAÇÃO AQUI (UTF8_ENCODE)
                $o->setIdHiperLinkUsuario($row['idHiperLinkUsuario']);

                $data = new NBLDateTime($row['ultimoAcesso']); //EH PRECISO CRIAR PARA DEFINIR O TIMEZONE
                $o->setUltimoAcesso($data);
                
                $data = new NBLDateTime($row['dataCadastro']); //EH PRECISO CRIAR PARA DEFINIR O TIMEZONE
                $o->setDataCadastro($data);
                

                $o->setContadorAcessos($row['contadorAcessos']);
                $o->setNivelCompartilhamento($row['nivelCompartilhamento']);
                $o->setNota($row['nota']);
                $o->setNome(utf8_encode($row['nome']));
                $o->setDescricao(utf8_encode($row['descricao']));
                $o->setContadorImportacao($row['contadorImportacao']);
                $o->setIdHiperLink($row['idHiperLink']);
                $o->setIdUsuario($row['idUsuario']);

            }

            if ($o instanceof HiperLink)
            {
                $o->setIdHiperLink($row['idHiperLink']);
                $o->setTotalAcessos($row['totalAcessos']);
                $o->setUrl($row['url']);
            }

            if ($o instanceof GruposHiperLinksUsuario)
            {
                //PENDENCIA: TRATAR CODIFICAÇÃO AQUI (UTF8_ENCODE)
                $o->setIdGrupoHiperLinksUsuario($row['idGrupoHiperLinksUsuario']);
                $o->setNome($row['nome']);
                $o->setDescricao($row['descricao']);
                $o->setIdUsuario($row['idUsuario']);
            }
            
            if ($o instanceof Elemento)
            {                
                //PENDENCIA: TRATAR CODIFICAÇÃO AQUI (UTF8_ENCODE)
                $o->setIdElemento   ($row['idElemento']);
                $o->setConteudoPT_BR($row['conteudoPT_BR']);
                $o->setConteudoEN_US($row['conteudoEN_US']);
                $o->setOrdemCriacao ($row['ordemCriacao']);
            }
            
            if ($o instanceof ContatoComNBL)
            {
                $o->setIdContatoComNBL($row['idContatoComNBL']);
                $o->setIdUsuario($row['idUsuario']);
                $o->setTipoMensagem($row['tipoMensagem']);
                $o->setMensagem($row['mensagem']);

                $data = new NBLDateTime($row['dataEnvio']);
                $o->setDataEnvio( $data );

                $o->setIdioma($row['idioma']);
                $o->setStatus($row['status']);

                $data = new NBLDateTime($row['dataResposta']);
                $o->setDataResposta( $data );
                $o->setReposta($row['resposta']);
                $o->setEmailRemetente($row['emailRemetente']);
                $o->setNomeRemetente($row['nomeRemetente']);
            }
            
            if ($o instanceof M_to_m_grupos_links)
            {
                //PENDENCIA: TRATAR CODIFICAÇÃO AQUI (UTF8_ENCODE)
                $o->setIdHiperLinkUsuario($row['idHiperLinkUsuario']);
                $o->setIdGrupoHiperLinksUsuario($row['idGrupoHiperLinksUsuario']);
            }
            return $o;
        }//fim else
    }

}