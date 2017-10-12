<?php

include_once 'DAO/MainDAO.php';
/*
 * Classe que encapsulará e manipulará as funções relativas a FUNCIONALIDADE
 * MULTI-IDIOMA.
 * 
 */

/**
 * Descrição da classe Idioma
 *
 * @author Paulo Ricardo - Iniciada implementaÃ§Ã£o em 23/06/2012
 * 1 - CARREGA DE ACORDO COM O DOMÍNIO
 * 2 - CARREGA O CONTEÚDO DO BD
 * 3 - SOMENTE CARREGA UMA VEZ, A MENOS QUE MUDE O IDIOMA
 * 
 */
class Idioma {

    public $teste = "oi";
    public static $altImgCadUsuario;
    public static $altLinkIdiomaInglesUS;
    public static $altLinkIdiomaPortuguesBrasil;
    public static $inputSubmitFormCadUsuario;
    public static $inputSubmitFormLogin;
    public static $labelCheckManterConectado;
    public static $labelFormCadUsuarioConfirmaSenha;
    public static $labelFormCadUsuarioEmail;
    public static $labelFormCadUsuarioImgCAPTCHA;
    public static $labelFormCadUsuarioMensagemInicial;
    public static $labelFormCadUsuarioNome;
    public static $labelFormCadUsuarioSenha;
    public static $labelFormLoginEmail;
    public static $labelFormLoginSenha;
    public static $labelTituloFormCadUsuario;
    public static $linkIdiomaPortuguesBrasil;
    public static $linkRecuperarSenha;
    public static $msgErroCadUsuarioCAPTCHANaoConfere;
    public static $msgErroCadUsuarioEmailInvalido;
    public static $msgErroCadUsuarioEmailJaExistente;
    public static $msgErroCadUsuarioEmailVazio;
    public static $msgErroCadUsuarioNomeNaoPreenchido = "teste";
    public static $msgErroCadUsuarioNomeSomenteComEspacos;
    public static $msgErroCadUsuarioSenhaInvalida;
    public static $msgErroCadUsuarioSenhaNaoConfereComConfirmacao;
    public static $msgErroCadUsuarioSenhaVazia;
    public static $msgErroLoginOuSenhaInvalidos;
    public static $titleImgRecarregaCAPTCHA;
    public static $titleLabelCheckManterConectado;
    public static $titleLabelFormCadUsuarioEmail;
    public static $titleLabelFormCadUsuarioImgCAPTCHA;
    public static $titleLabelFormCadUsuarioMensagemInicial;
    public static $titleLabelFormCadUsuarioNome;
    public static $titleLabelFormCadUsuarioSenha;
    public static $titleLabelFormLoginEmail;
    public static $titleLabelFormLoginSenha;
    public static $titleLabelTituloFormCadUsuario;
    public static $titleLinkRecuperarSenha;
    public static $titleImgLogoMarca;
    public static $titleLinkIdiomaPortuguesBrasil;
    public static $titleLinkIdiomaInglesEUA;
    public static $titleLabelFormCadUsuarioConfirmaSenha;
    public static $altImgRecarregaCAPTCHA;
    public static $msgOkLabelDivRespostaFormCadUsuario;
    public static $msgErroLabelDivRespostaFormCadUsuario;
    private static $instance;

    private function __contructor() {
        session_start();
    }

    private function __clone() {
        
    }

    /*
     * CARREGA O IDIOMA DO BD E ARMAZENA EM $_SESSION
     * Inicialmente o idioma padrao sera o portugues. Depois sera com base na URL solicitada
     * @in: o idioma a ser selecionado
     * 

      public static function cIdioma(string $idioma)
      {
      if ( !isset(self::$instance) )
      self::$instance = new self;


      //JA ESTA DEFINIDO?
      if ( !isset($_SESSION['idioma'] )




      )
      $_SESSION['idioma'] = $idioma;
      else //JA ESTA DEFINIDO










      //CARREGA O CONTEUDO
      if($idioma == 'PT_BR')
      $colunaConteudo = "conteudoPT_BR";
      else
      $colunaConteudo = "conteudoEN_US";


      $query = "select idElemento, " . $colunaConteudo . " from elementos";

      $sql = MainDAO::query($query);
      while ( $row = mysql_fetch_array($sql) )
      {
      $idElemento = $row['idElemento'];

      //***ALGO SOBRE REGISTRO NA SESSÃƒO TEM QUE SER COLOCADO AQUI PARA EVITAR ERRO NO IE E NO MOZILA QUANDO CHAMAR O IDIOMA (NAO ESTAO VENDO A SESSAO
      //$_INTERACAO['elementos'][$idElemento] = array ( 'conteudo' => utf8_encode($row["$colunaConteudo"]), 'ordemCriacao' => ($row['ordemCriacao']) );
      $_INTERACAO['elementos'][$idElemento] = array ( 'conteudo' => utf8_encode($row["$colunaConteudo"]) );


      }








      $_SESSION['idiomaAnterior'] = $_SESSION['idioma'];


      //O idioma passado é diferente que o que está definido?

















      return self::$instance; //O RETURN DEVE FICAR NO FINAL PARA NÃO INTERROMPER A FUNCIOALIDADE DA FUNCAO
      }







      /*Carrega na sessÃ£o o idioma de acordo com a url de onde foi originada a
     * requisiÃ§Ã£o;
     */

    public function carregaIdioma($idioma) {
        //Anilisar se o idioma jÃ¡ estÃ¡ definino na sessÃ£o.
        //if ( !isset($_SESSION['idioma']) || ($_SESSION['idioma'])== '' )       // IDIOMA NÃƒO DEFINIDO
        //{
        //QUAL URL DA REQUISIÃ‡ÃƒO?
        //if ( $_SERVER['REQUEST_URI'] == 'http://nbooklink.com.br' ) //PORTUGUÃŠS
        //{    
        //    $_SESSION['idioma'] = 'PT_BR'; // DEFINE A VARIÃ�VEL DE IDIOMA NA SESSÃƒO.
        //}
        //elseif ( $_SERVER['REQUEST_URI'] == 'http://nbooklink.com' ) 
        //{
        //   $_SESSION['idioma'] = 'EN_US';
        //}
        //----ENQUANTO NÃƒO FAZ UPLOAD
        //$_SESSION['idioma'] = 'EN_US';
        //DEFINIR A QUERY A SER FEITA COM BASE NA REQUISIÃ‡ÃƒO
        //  if ($_SESSION['idioma'] == 'PT_BR')
        //{
        //    $query = 'SELECT idElemento, conteudoPT_BR FROM elementos';
        //    $colunaConteudo = 'conteudoPT_BR';
        //}    
        //elseif ($_SESSION['idioma'] == 'EN_US') 
        //{
        //$query = 'SELECT idElemento, conteudoPT_BR FROM elementos';
        //$colunaConteudo = 'conteudoPT_BR';
        //}
        //ARMAZENAR NA VARIAVEL DE SESSAO O CONTEUDO CARREGADO
        if ($idioma == 'PT_BR')
            $colunaConteudo = "conteudoPT_BR";
        else
            $colunaConteudo = "conteudoEN_US";


        $query = "select idElemento, " . $colunaConteudo . " from elementos";

        $sql = MainDAO::query($query);
        while ($row = mysql_fetch_array($sql)) {
            $idElemento = $row['idElemento'];

            //***ALGO SOBRE REGISTRO NA SESSÃƒO TEM QUE SER COLOCADO AQUI PARA EVITAR ERRO NO IE E NO MOZILA QUANDO CHAMAR O IDIOMA (NAO ESTAO VENDO A SESSAO
            //$_INTERACAO['elementos'][$idElemento] = array ( 'conteudo' => utf8_encode($row["$colunaConteudo"]), 'ordemCriacao' => ($row['ordemCriacao']) );
            $_INTERACAO['elementos'][$idElemento] = array('conteudo' => utf8_encode($row["$colunaConteudo"]));
        }


        self::$altImgCadUsuario = $_INTERACAO['elementos']['altImgCadUsuario']['conteudo'];
        self::$altLinkIdiomaInglesUS = $_INTERACAO['elementos']['altLinkIdiomaInglesUS']['conteudo'];
        self::$altLinkIdiomaPortuguesBrasil = $_INTERACAO['elementos']['altLinkIdiomaPortuguesBrasil']['conteudo'];
        self::$inputSubmitFormCadUsuario = $_INTERACAO['elementos']['inputSubmitFormCadUsuario']['conteudo'];
        self::$inputSubmitFormLogin = $_INTERACAO['elementos']['inputSubmitFormLogin']['conteudo'];
        self::$labelCheckManterConectado = $_INTERACAO['elementos']['labelCheckManterConectado']['conteudo'];
        self::$labelFormCadUsuarioConfirmaSenha = $_INTERACAO['elementos']['labelFormCadUsuarioConfirmaSenha']['conteudo'];
        self::$labelFormCadUsuarioEmail = $_INTERACAO['elementos']['labelFormCadUsuarioEmail']['conteudo'];
        self::$labelFormCadUsuarioImgCAPTCHA = $_INTERACAO['elementos']['labelFormCadUsuarioImgCAPTCHA']['conteudo'];
        self::$labelFormCadUsuarioMensagemInicial = $_INTERACAO['elementos']['labelFormCadUsuarioMensagemInicial']['conteudo'];
        self::$labelFormCadUsuarioNome = $_INTERACAO['elementos']['labelFormCadUsuarioNome']['conteudo'];
        self::$labelFormCadUsuarioSenha = $_INTERACAO['elementos']['labelFormCadUsuarioSenha']['conteudo'];
        self::$labelFormLoginEmail = $_INTERACAO['elementos']['labelFormLoginEmail']['conteudo'];
        self::$labelFormLoginSenha = $_INTERACAO['elementos']['labelFormLoginSenha']['conteudo'];
        self::$labelTituloFormCadUsuario = $_INTERACAO['elementos']['labelTituloFormCadUsuario']['conteudo'];
        self::$linkIdiomaPortuguesBrasil = $_INTERACAO['elementos']['linkIdiomaPortuguesBrasil']['conteudo'];
        self::$linkRecuperarSenha = $_INTERACAO['elementos']['linkRecuperarSenha']['conteudo'];
        self::$msgErroCadUsuarioCAPTCHANaoConfere = $_INTERACAO['elementos']['msgErroCadUsuarioCAPTCHANaoConfere']['conteudo'];
        self::$msgErroCadUsuarioEmailInvalido = $_INTERACAO['elementos']['msgErroCadUsuarioEmailInvalido']['conteudo'];
        self::$msgErroCadUsuarioEmailJaExistente = $_INTERACAO['elementos']['msgErroCadUsuarioEmailJaExistente']['conteudo'];
        self::$msgErroCadUsuarioEmailVazio = $_INTERACAO['elementos']['msgErroCadUsuarioEmailVazio']['conteudo'];
        self::$msgErroCadUsuarioNomeNaoPreenchido = $_INTERACAO['elementos']['msgErroCadUsuarioNomeNaoPreenchido']['conteudo'];
        self::$msgErroCadUsuarioNomeSomenteComEspacos = $_INTERACAO['elementos']['msgErroCadUsuarioNomeSomenteComEspacos']['conteudo'];
        self::$msgErroCadUsuarioSenhaInvalida = $_INTERACAO['elementos']['msgErroCadUsuarioSenhaInvalida']['conteudo'];
        self::$msgErroCadUsuarioSenhaNaoConfereComConfirmacao = $_INTERACAO['elementos']['msgErroCadUsuarioSenhaNaoConfereComConfirmacao']['conteudo'];
        self::$msgErroCadUsuarioSenhaVazia = $_INTERACAO['elementos']['msgErroCadUsuarioSenhaVazia']['conteudo'];
        self::$msgErroLoginOuSenhaInvalidos = $_INTERACAO['elementos']['msgErroLoginOuSenhaInvalidos']['conteudo'];
        self::$titleImgRecarregaCAPTCHA = $_INTERACAO['elementos']['titleImgRecarregaCAPTCHA']['conteudo'];
        self::$titleLabelCheckManterConectado = $_INTERACAO['elementos']['titleLabelCheckManterConectado']['conteudo'];
        self::$titleLabelFormCadUsuarioEmail = $_INTERACAO['elementos']['titleLabelFormCadUsuarioEmail']['conteudo'];
        self::$titleLabelFormCadUsuarioImgCAPTCHA = $_INTERACAO['elementos']['titleLabelFormCadUsuarioImgCAPTCHA']['conteudo'];
        self::$titleLabelFormCadUsuarioMensagemInicial = $_INTERACAO['elementos']['titleLabelFormCadUsuarioMensagemInicial']['conteudo'];
        self::$titleLabelFormCadUsuarioNome = $_INTERACAO['elementos']['titleLabelFormCadUsuarioNome']['conteudo'];
        self::$titleLabelFormCadUsuarioSenha = $_INTERACAO['elementos']['titleLabelFormCadUsuarioSenha']['conteudo'];
        self::$titleLabelFormLoginEmail = $_INTERACAO['elementos']['titleLabelFormLoginEmail']['conteudo'];
        self::$titleLabelFormLoginSenha = $_INTERACAO['elementos']['titleLabelFormLoginSenha']['conteudo'];
        self::$titleLabelTituloFormCadUsuario = $_INTERACAO['elementos']['titleLabelTituloFormCadUsuario']['conteudo'];
        self::$titleLinkRecuperarSenha = $_INTERACAO['elementos']['titleLinkRecuperarSenha']['conteudo'];
        self::$titleImgLogoMarca = $_INTERACAO['elementos']['titleImgLogoMarca']['conteudo'];
        self::$titleLinkRecuperarSenha = $_INTERACAO['elementos']['titleLinkRecuperarSenha']['conteudo'];
        self::$titleLinkIdiomaPortuguesBrasil = $_INTERACAO['elementos']['titleLinkIdiomaPortuguesBrasil']['conteudo'];
        self::$titleLinkIdiomaInglesEUA = $_INTERACAO['elementos']['titleLinkIdiomaInglesEUA']['conteudo'];
        self::$titleLabelFormCadUsuarioConfirmaSenha = $_INTERACAO['elementos']['titleLabelFormCadUsuarioConfirmaSenha']['conteudo'];
        self::$altImgRecarregaCAPTCHA = $_INTERACAO['elementos']['altImgRecarregaCAPTCHA']['conteudo'];
        self::$msgOkLabelDivRespostaFormCadUsuario = $_INTERACAO['elementos']['msgOkLabelDivRespostaFormCadUsuario']['conteudo'];
        self::$msgErroLabelDivRespostaFormCadUsuario = $_INTERACAO['elementos']['msgErroLabelDivRespostaFormCadUsuario']['conteudo'];







        //}// FIM if ( !isset($_SESSION['idioma']) || ($_SESSION['idioma'])== '' )       // IDIOMA NÃƒO DEFINIDO
    }

//FIM CARREGAR IDIOMA
}

// FIM DA CLASSE
?>
