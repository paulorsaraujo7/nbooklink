<?php
/*
 * @author     : NBookLink Inc.
 * @date       : 03-05-2013
 * @description: Representa uma Action.
 */
class NBL_Action {
    public $action;
        
    /*A intencao eh as acoes terem o mesmo nome que as funcoes da classe de controle para facilitar a chamada*/
    public function __construct ($action = '_NBL_Action_create') {
        if ( $action == '_NBL_Action_create'   ||
             $action == '_NBL_Action_update'   ||
             $action == '_NBL_Action_retrieve' ||
             $action == '_NBL_Action_delete' )
        {
            $this->action = $action;
        }
        else {
            throw new Exception("_NBL_Exception_Invalid_Action");
        }
    }
}


/*
 * Descricao: Classe principal para o tratamento de uma requisição HTTP
 * Autor: Paulo Ricardo Santos e Araujo
 * Data de Criação: 11/02/2013
 * Histórico de atualizacoes: 
 * 03/05/2013 - Criacao
 * 05/06/2013 - Atualizacao do metodo de _NBL_Action_Delete()
 * 16/06/2013 - Criacao do metodo update, criacao da funcao privada que verifica restricao de pertinencia. Atualizacao na documentacao
 * 
 * Motivacao: Contem os metodos essenciais ao controle dos dados enviados por formularios.
 * 
 * DOCUMENTACAO INICIAL:
 * 1 - VERIFICANDO A CONSISTENCIA DE UMA REQUISICAO: UMA REQUISICAO EH CONSISTENTE SE EH VALIDA E ATENDE AS RESTRICOES NOS TERMOS ABAIXO: 
 *   1.1 - A REQUISICAO PRECISA SER VALIDADA. UMA REQUISICAO NAO VALIDA EH AQUELA QUE PARA QUAL:
 *       1.1.1 - A ACAO DEFINIDA DIFERENTE DOS VALORES DA CLASSE _NBL_Action;
 *               SE NAO FOR PASSADO, NO CONSTRUTOR DESTA CLASSE SERA DEFINIDA POR PADRAO COMO _NBL_Action_create 
 *       1.1.2 - O ARRAY QUE CONTEM A REQUISICAO EH VAZIO; NESTE CASO GERA UM ERRO.
 *   1.2 - A REQUISICAO PRECISA ATENDER AS RESTRICOES IMPOSTAS QUE PODEM SER AS SEGUINTES:
 *       1.2.1 - RESTRICAO DE CONEXAO: O USUARIO QUE FEZ A REQUISICAO DEVE ESTAR LOGADO.
 *               POR PADRAO EH RESTRITA. EH VERIFICADA NO CONTRUTOR DA CLASSE.
 *       1.2.2 - RESTRICAO DE PROPRIEDADE:    O USUARIO QUE FEZ A REQUISICAO DEVE SER DONO DA ENTIDADE QUE SERA AFETADA.
 *               POR PADRAO EH RESTRITA. EH VERIFICADA NA CLASSE QUE HERDA DESSA.
 * 
 * FIM - DOCUMENTACAO INICIAL 
 */

abstract class MainController {
   protected $_NBL_Action;                  //OBJETO DO TIPO _NBL_Action                        = _NBL_Action_create por PADRAO
   
   /*$_NBL_Restrict INDICA SE SERA EXIGIDO QUE O USUARIO ESTEJA LOGADO, SERA TRUE por PADRAO - VER ITEM 1 DA DOCUMENTACAO*/
   protected $_NBL_Restrict;                
   
   protected $_NBL_Request = array();       //O ARRAY QUE CONTEM OS DADOS DA REQUISICAO
   protected $_NBL_View;                    //O NOME DA VIEW QUE CHAMOU
   protected $_NBL_Objeto;                  //NOME DO OBJETO ALVO DA ACAO
   protected $_NBL_Container = array();     //IDS HTML DOS CONTAINERS QUE DEVEM SERAO AFETADOS PELA ACAO
   
   protected $idEntidade = NULL;            //ID DA ENTIDADE QUE SERA AFETADA. POR EX. NUMA EXCLUSAO (EH OBRIGATORIO DEPENDENDO A ACAO)
   protected $idUsuario = NULL;             //ID DO USUARIO QUE ESTA FAZENDO A REQUISICAO - SE FOR UMA REQUISICAO RESTRITA, ENTAO EH PREENCHIDO AUTOMATICAMENTE

   /*
    * Constroe o Controller
    * @in: $requeste      : contem o array que contem os dados da requisicao
    * @in: $_NBL_Restrict : se vai ser uma acao que exige que o usuario esteja logado. TRUE por padrao.
    * 
    * Se a acao nao for passada pela requisicao assume o valor padrao _NBL_Action_create.
    */
   protected function __construct(array $request, $_NBL_Restrict = TRUE) {
       
       /*VERIFICA A RESTRICAO DE CONEXAO - ESTAR LOGADO*/
            //SE $_NBL_Restrict NAO FOR BOOLEANO GERA EXCECAO
            if ( !is_bool($_NBL_Restrict) )  throw new Exception("_NBL_Exception_Invalid_Restrict"); /*O USUARIO PASSA PROPOSITALMENTE UM VALOR NAO BOOLEANO*/
                 $this->_NBL_Restrict = $_NBL_Restrict;
            //SE FOR UMA REQUISICAO RESTRITA DE ESTAR LOGADO, ENTAO VERIFICA A RESTRICAO
            if ( $this->_NBL_Restrict )
                 $this->verificaRestricao();
       /*FIM - VERIFICA A RESTRICAO DE CONEXAO - ESTAR LOGADO*/
        
       /*VERIFICA A CONSISTENCIA DE VALIDACAO DA REQUISICAO - ARRAY NAO VAZIO...*/
            if ( !isset($request) || empty($request) ) //REQUISICAO VAZIA OU ACAO NAO DEFINIDA REDIRECIONA PARA HOME
                header("Location:index.php?erro=requisicaoInvalida");
            else {
                $this->_NBL_Request = $request; //SE VALIDADA, SETA O CAMPO DE CLASSE
            }
       /*FIM - VERIFICA A CONSISTENCIA DE VALIDACAO DA REQUISICAO - ARRAY NAO VAZIO...*/

       /*DEFININDO A ACAO - SE NAO VEIO NA REQUISICAO (JA VALIDADA ACIMA) DEFINE COMO PADRAO A ACTION _NBL_Action_create
        * SE FOI PASSADO ACTION NAO EXISTENTE, ENTAO GERA EXCECAO NO CONTRUTOR DA CLASSE NBL_Action
        */
            if ( !isset( $this->_NBL_Request['_NBL_Action'] ) )
                $this->_NBL_Action = new NBL_Action("_NBL_Action_create");
            else {
                $action = $this->_NBL_Request['_NBL_Action'];
                $this->_NBL_Action = new NBL_Action($action);
            }
       /*FIM - DEFININDO A ACAO - SE NAO VEIO NA REQUISICAO (JA VALIDADA ACIMA) DEFINE COMO PADRAO A ACTION _NBL_Action_create
       
        
        /*DEFININDO A VIEW - se nao veio na requisicao (ja validada acima) define por padrao a index*/
            if ( !isset( $this->_NBL_Request['_NBL_View'] ) )
                $this->_NBL_View = 'index';
            else {
                $this->_NBL_View = $this->_NBL_Request['_NBL_View'];
            }
        /*FIM - DEFININDO A VIEW - se nao veio na requisicao (ja validada acima) define por padrao a index*/
        
        
        /*DEFININDO O ID DA ENTIDADE - SE FOR O CASO*/
            if ( !isset( $this->_NBL_Request['IdEntidade'] ) )
                $this->idEntidade = $this->_NBL_Request['idEntidade'];
        /*FIM - DEFININDO O ID DA ENTIDADE - SE FOR O CASO*/
        
        return $this;
   }
   
   /*Executa a acao principal*/
   protected function run(){
       /*Descobrir qual eh a Action e executa-la*/
            self::preparaCampos($this->_NBL_Request); //PREPARAR OS CAMPOS DA REQUISICAO;
       /*FIM - Descobrir qual eh a Action e executa-la*/
   }

   /*Acao para criar uma nova entidade no BD*/
   protected function _NBL_Action_create()
   {
   }
   
   /*Acao para atualizar uma nova entidade no BD
    * 
    * @in: $restricaoDePropriedade = TRUE, indica se a requisicao somente pode ser feita pelo usuario dono da entidade afetada.
    * 
    */
   protected function _NBL_Action_update($restrict = TRUE)
   {
       if ($restrict) /*SOMENTE PODE EXECUTAR A ACAO O USUARIO QUE ESTIVER LOGADO*/
       {
           /*AGORA SE A ACAO FOR RESTRITA CONFERIR SE O USUARIO QUE ESTA REQUISITANDO EH DONO DA ENTIDADE
            * - FICA POR CONTA DA CLASSE HERDADA POIS VARIA DE ENTIDADE PARA ENTIDADE (ESSA REGRA PODE SER ENCAPSULADA)
            */

           /* SE A REQUISICAO REQUER QUE ESTEJA LOGADO (PARAMENTRO PADRAO) E A REQUISICAO 
            * FOI FEITA POR UM USUARIO QUE NAO ESTA, ENTAO GERA ERRO.
            * 
            * NESTE CASO A RESTRICAO JA FOI VERIFICADA NO CONSTRUTOR QUE GERA UM ERRO SE FALHAR
            */
           if (!$this->_NBL_Restrict) /*A ACAO EH RESTRITA (ENTROU NO IF) E A REQUISICAO NAO EH*/ 
           {
               throw new Exception("erroRequisicaoRestritaParaUsuarioLogado");
           }
           
           /*PASSOU PELO IF ACIMA ENTAO O USARIO ESTA LOGADO, POIS FOI DEFINIDO $this->_NBL_Restrict JA NO CONSTRUTOR PRINCIPAL DA CLASSE */
           if (!$this->idEntidade) /*NAO FOI PASSADA A CHAVE PRIMARIA DA ENTIDADE A SER AFETADA DO BD*/
           {
               throw new Exception("erroFaltaIdDaEntidadeAfetadaPelaRequisicao");
           }
           /*SE CHEGOU ATE AQUI EH PORQUE NAO FALHOU NAS RESTRICOES ACIMA*/
       }
       return $this; /*RETORNA O OBJETO*/
   }

   /*Acao para recuperar por padrao*/
   protected function _NBL_Action_retrieve()
   {
       echo "recuperando...";
   }

   /*recuperar para deletar
    * 
    * $restrict = A restricao indica que a requisicao somente pode afetar uma entidade que pertenca ao
    * usuario que esta logado.
    * 
    * /*POR PADRAO $restrict EH TRUE. EH BOM DEIXAR A POSSIBILIDADE DE NAO SER POIS ESSA FUNCAO PODE SER CHAMADA POR OUTRA
    */
   protected function _NBL_Action_delete($restrict = TRUE) 
   {
       if ($restrict) /*SOMENTE PODE EXECUTAR A ACAO O USUARIO QUE ESTIVER LOGADO*/
       {
           /*AGORA SE A ACAO FOR RESTRITA CONFERIR SE O USUARIO QUE ESTA REQUISITANDO EH DONO DA ENTIDADE
            * - FICA POR CONTA DA CLASSE HERDADA POIS VARIA DE ENTIDADE PARA ENTIDADE (ESSA REGRA PODE SER ENCAPSULADA)
            */

           /* SE A REQUISICAO REQUER QUE ESTEJA LOGADO (PARAMENTRO PADRAO) E A REQUISICAO 
            * FOI FEITA POR UM USUARIO QUE NAO ESTA, ENTAO GERA ERRO.
            * 
            * NESTE CASO A RESTRICAO JA FOI VERIFICADA NO CONSTRUTOR QUE GERA UM ERRO SE FALHAR
            */
           if (!$this->_NBL_Restrict) /*A ACAO EH RESTRITA (ENTROU NO IF) E A REQUISICAO NAO EH*/ 
           {
               throw new Exception("erroRequisicaoRestritaParaUsuarioLogado");
           }
           
           /*PASSOU PELO IF ACIMA ENTAO O USARIO ESTA LOGADO, POIS FOI DEFINIDO $this->_NBL_Restrict JA NO CONSTRUTOR PRINCIPAL DA CLASSE */
           if (!$this->idEntidade) /*NAO FOI PASSADA A CHAVE PRIMARIA DA ENTIDADE A SER AFETADA DO BD*/
           {
               throw new Exception("erroFaltaIdDaEntidadeAfetadaPelaRequisicao");
           }
           /*SE CHEGOU ATE AQUI EH PORQUE NAO FALHOU NAS RESTRICOES ACIMA*/
       }
       return $this; /*RETORNA O OBJETO*/
   }
   

   /*
    * Verifica a restricao de o usuario estar logado ou nao.
    * Se for uma requisicao restrita, entao redireciona para home com o erro.
    * Se for uma requisicao nao restrita, retorna TRUE;
    */ 
   private function verificaRestricao()
   {
       if ( $this->_NBL_Restrict ) {
           try {
               //EXISTE SESSAO PRA O USUARIO?(O OBJETO Usuario ESTA DEFINIDO NA SESSAO - ESTA SERIALIZADO)
               if (isset($_SESSION['Usuario'])) {
                   //----VERIFICAR SE A SESSAO E VALIDA.
                   $Usuario = new Usuario();
                   $Usuario = unserialize($_SESSION['Usuario']);    //CAPTURAR O OBJETO QUE ESTA NA SESSAO


                   $email      = $Usuario->getEmail();
                   $chave      = $_SESSION['_SESSAO_']['chave'];   //CHAVE SECRETA QUE FOI UTILIZADA NO MOMENTO DO LOGIN
                   $ip         = $_SESSION['_SESSAO_']['ip'];      //IP DO USUARIO UTILIZADO NO MOMENTO DO LOGIN
                   $hora       = $_SESSION['_SESSAO_']['hora'];    //HORA ARMAZENADA NO MOMENTO DO LOGIN
                   $assinatura = md5($email . $chave . $ip . $hora); //ASSINATURA QUE E MD5 DOS ELEMENTOS UTILIZADOS NO MOMENTO DO LOGIN

                   if ($assinatura != $_SESSION['_SESSAO_']['assinatura']) {//ASSINATURA GERADA NAO E IGUAL A QUE FOI GERADA NO MOMENTO DO LOGIN
                       $_SESSION['_SESSAO_']['autenticado'] = FALSE;
                       throw new Exception("erroSessaoInvalida");              //GERA EXCECAO DE SESSAO INVALIDA
                   } else {//SESSAO VALIDA - NADA FAZ, POIS ESSE SCRIPT APENAS REDIRECIONA EM CASO DE ERRO.
                       $_SESSION['_SESSAO_']['autenticado'] = TRUE;  //FICA NA SESSAO ARMAZENADO QUE O USUARIO ESTA AUTENTICADO.
                       $this->idUsuario = $Usuario->getIdUsuario();  //ARMAZENA NO CAMPO DE CLASSE O ID DO USUARIO QUE ESTA FAZENDO A REQUISICAO
                   }
               }
               else //USUARIO NAO ESTA LOGADO
                   throw new Exception("erroAcessoRestrito");
            } catch (Exception $e) {
                header("Location:http://www.nbooklink.com/index.php?erro=" . $e->getMessage());
                die();//PARA ESSE SCRIPT POR AQUI
            }
       }
       else {
           return TRUE;
       }
   }
   
   public static function preparaCabecalhos() {
        
        return <<<EOF
                require_once '../../../principais.php';
EOF;
    }

   /*Retorna um array com os elementos tratados por funcoes de seguranca para todas as requisicoes
     * @in: referencia para o array contendo os campo a serem tratados
     * @out: false em caso de falha, ou array com os campos tratados (retira tags HTML e espaços do começo e fim)
     */
   public static function preparaCampos (array &$requisicao)
   {
        if (!is_array($requisicao)) return false;
        
        foreach ($requisicao as $key => &$value) {
          if (is_string($value)) //Se valor do tipo string, entao trata
          {
              $value = trim(strip_tags($value));
          }
        }
    }
}
?>
