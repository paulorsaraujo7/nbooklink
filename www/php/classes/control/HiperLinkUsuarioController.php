<?php
/*
 * @author Paulo Ricardo.
 * @data 03-06-13 - 02:42
 * Processa uma requisicao que afeta um HiperLinkDeUsuario
 */
class HiperLinkUsuarioController extends MainController {
    
    private $objeto;

    public function __construct(array $request, $_NBL_Restrict = TRUE) {
        parent::__construct($request, $_NBL_Restrict);
    }
    
    /*Executa a acao principal*/
    public function run() {
       parent::run();
       $funcaoAcao = $this->_NBL_Action->action;
       $this->$funcaoAcao();
       
       /*CHAMAR UM METODO FINALIZADOR DA TAREFA*/
    }

    protected function _NBL_Action_retrieve() {
        parent::_NBL_Action_retrieve();
    }
    
    
    /*Acao de criar nova entidade*/
    protected function _NBL_Action_create() {
        parent::_NBL_Action_create();
                /*1.1 - VALIDAR URL:*/
                    $this->_NBL_Request['url'] = Uteis::validarURL( $this->_NBL_Request['url'] ); //RECEBE FALSE OU A URL TRATADA
                    if ( !$this->_NBL_Request['url'] ) { //URL INVALIDA
                        $msgResposta        = NBLIdioma::getTextoPorIdElemento('msgErroLabelDivRespostaGenerica');
                        $msgErroUrlInvalida = NBLIdioma::getTextoPorIdElemento('msgErroUrlInvalida');
                        $resposta = "
                              <script type=\"text/javascript\"> 
                                $('#divRespostaFormHiperLinksUsuario').empty();
                                $('#divRespostaFormHiperLinksUsuario').append('<img src=\"imagens/imgRespostaErro.png\"/>');
                                $('#divRespostaFormHiperLinksUsuario').append('<label id=\"labelDivRespostaFormHiperLinkUsuario\"> $msgResposta </label>');

                                $('#labelFormHiperLinkUsuarioEnderecoResposta').empty();
                                $('#labelFormHiperLinkUsuarioEnderecoResposta').append('$msgErroUrlInvalida');
                                $('#labelFormHiperLinkUsuarioEnderecoResposta').hide();
                                $('#labelFormHiperLinkUsuarioEnderecoResposta').show('slow');
                                $('#inputFormHiperLinkUsuarioEndereco').focus();

                              </script>";
                        echo $resposta;
                        return false;
                    }//URL VALIDADA OU PROCESSO INTERROMPIDO
                /*1.1 - FIM - VALIDAR URL:*/

                /*VERIFICA SE A URL JA EXISTE NA TABELA GLOBAL*/
                    $idHiperLink = HiperLinkDAO::obterIdPorUrl( $this->_NBL_Request['url'] ); /*BUSCA ID NA TABELA GLOBAL PELA URL PASSADA*/
                    if ( !$idHiperLink )                                                      /*NAO EXISTE LINK GLOBAL COM A URL PASSADA*/
                    {
                        $h = New HiperLink();                                                 /*CRIA NOVO OBJETO*/
                        $h->setUrl ( $this->_NBL_Request['url'] );                            /*DEFINE A URL COMO SENDO A QUE FOI PASSADA PELA REQUISICAO*/
                        HiperLinkDAO::persiste($h);                                           /*GRAVA O OBJETO NO BD*/
                        
                        /*O ID GLOBAL PASSA A SER O ID ATRIBUIDO PARA O LINK GLOBAL QUE ACABOU DE SER CRIADO */
                        $idHiperLink = HiperLinkDAO::obterIdPorUrl( $this->_NBL_Request['url'] );  
                    }
                    /*OBS: PERCEBER QUE $idHiperLink OU SAI COM O ID QUE EXISTIA OU COM O NOVO ID - SERA USADO COMO FK NA TABELA DE LINK DO USUARIO*/
                /*FIM - VERIFICA SE A URL JA EXISTE NA TABELA GLOBAL*/
                
                
                /* AGORA REFLETIR AS MUDANCAS PARA O LINK QUE ESTA SENDO ALTERADO*/
                    /*TABELA HIPERLINKSUSUARIO - DADOS PROPRIOS DO USUARIO PARA O LINK CADASTRADO*/
                    $hu = new HiperLinksUsuario();
                    $hu->setNivelCompartilhamento ( (int) $this->_NBL_Request['nivelCompartilhamento'] );
                    $hu->setNome                  ( $this->_NBL_Request['nome'] );
                    $hu->setDescricao             ( $this->_NBL_Request['descricao']);
                    $hu->setIdUsuario             ( $_SESSION['_SESSAO_']['_USUARIO_']['idUsuario']);
                    $hu->setIdHiperLink( $idHiperLink ); /*$idHiperLink FOI DEFINIDO ACIMA - EVITA UMA 'IDA' DESNECESSARIA NO BD*/
                    
                    $dataTemp = date("Y-m-d H:i:s");
                    $hu->setDataCadastro(new NBLDateTime($dataTemp)); /*A DATA DE CRIACAO EH UMA NOVA DATA*/
                    HiperLinksUsuarioDAO::persiste($hu);
                /* FIM - AGORA REFLETIR AS MUDANCAS PARA O LINK QUE ESTA SENDO ALTERADO*/


        /*AGORA PREENCHER A TABELA DE MUITOS PARA MUITOS DE GRUPOS E LINKS DO USUARIO*/
        if ( isset( $this->_NBL_Request['grupos'] ) ) {  //VIERAM GRUPOS MARCADOS NA REQUISICAO 
            $grupos = $this->_NBL_Request['grupos']; //$grupos contem o array com os checkboxes marcados

            //Eh PRECISO CAPTURAR O ID DO ULIMO HIPER LINK DE USUARIO QUE FOI 
            //ACABADO DE SER CADASTRADO PARA INSERIR NA TABELA M TO M. 
            //O ID DO ULTIMO HIPER LINK DE USUARIO CADASTRADO SERA O PROX AUTO INCREMET DA TABELA
            //DE HIPER LINKS - 1
            $query = "SHOW TABLE STATUS LIKE 'hiperlinksusuario'"; //RETORNA INFORMACOES SOBRE A TABELA
            $r = MainDAO::query($query);
            $r = mysql_fetch_array($r);
            $r = $r['Auto_increment'] - 1; //O CAMPO DA QUERY ACIMA QUE CONTEM O VALOR DO PROX AUTOINCREMENTO

            /*PARA CADA ID RECUPERADO ACRESCENTA NA TABELA M-M GRUPOS ID DO GRUPO ID DO LINK ($r acima)*/
            for ($i=0; $i<count($grupos); $i++) { //$i EH VARIAVEL AUXILIAR DO FOR
                /*A CHAVE DO CHECK BOX EH UM INTEIRO
                 * OS CHECKS MARCADOS VEM COMO ARRAY ASSIM (0 => 138, 1 = 145...)
                 */
                $m_to_m = new M_to_m_grupos_links($r, $grupos[$i]);
                M_to_m_grupos_linksDAO::persiste($m_to_m);
            }
        }
        
        $msgResposta = NBLIdioma::getTextoPorIdElemento('msgGenericaSalvoComSucesso');
        $resposta = "
              <script type=\"text/javascript\"> 
                $('#divRespostaFormHiperLinksUsuario').empty();
                $('#divRespostaFormHiperLinksUsuario').append('<img src=\"imagens/imgRespostaOK.png\"/>');
                $('#divRespostaFormHiperLinksUsuario').append('<label id=\"labelDivRespostaFormHiperLinkUsuario\"> $msgResposta </label>');
                $('#inputFormHiperLinkUsuarioEndereco').focus();
                setTimeout(function() {
                      $('#formHiperLinkUsuario')[0].reset();
                }, 2000);
              </script>";
        echo $resposta;
        
    }/*FIM DA ACAO CREATE*/    
    
    
    /*
     * METODO PARA ATUALZIACAO DE UM LINK
     * - ESSA REQUISICAO EH RESTRITA PARA USUARIO LOGADO
     * - ESSA REQUISICAO EH RESTRITA PARA USUARIO DONO DONO DA ENTIDADE AFETADA
     */
    public function _NBL_Action_update($restric = TRUE) {
        parent::_NBL_Action_update($restric);
        /*VERIFICAR SE O USUARIO LOGADO EH DONO DA ENTIDADE AFETADA PELA REQUISICAO*/
        try {
            /* BUSQUE O OBJETO COM O ID PASSADO - EVITA ERRO DE ACESSAR OBJETO QUE NAO EXISTE 
             * - PODE OCORRER EM UMA REQUISICAO MAL FEITA ONDE EH ENVIADO ID DE UM OBJETO QUE SEQUER EXISTE 
             * - RECEBE NULL OU OBJETO PREENCHIDO
             */
            if ( ($HiperLinkUsuario = HiperLinksUsuarioDAO::obterPorId($this->idEntidade)) != NULL) /*EXISTE OBJETO COM ID PASSADO?*/
            {
                if ( $HiperLinkUsuario->getIdUsuario() != $this->idUsuario) /*QUEM FEZ A REQUISICAO EH DONO DA ENTIDADE?*/
                {    
                    throw new Exception ("erroUsuarioNaoEDonoDaEntidadeAfetadaPelaRequisicao"); /*QUEM FEZ A REQ. NAO EH DONO*/
                }
                /*SE PASSOU PELO IF ACIMA SEM GERAR EXCECAO ENTAO O USUARIO EH DONO DA ENTIDADE*/

                /*PARA ESSA REQUISICAO SERAO IMPORTANTES OS SEGUINTES DADOS:
                 * $this->_NBL_Request['idHiperLink'] - CONTEM O ID DO LINK GLOBAL POIS SE NAO HOUVER MUDANCA NA URL MANTEM ESSE (EVITA IDA AO BD NA TAB. DE LINKS GLOBAIS)
                 * 
                 * SAO UTEIS PARA SABER SE HOUVE MUDANCA NA URL E NOS GRUPOS E EVITAR ACESSO AO BD DESNECESSARIAMENTE
                 * TANTO PARA ESCRITA DE URL COMO PARA ESCRITA DOS GRUPOS
                 */
                 /*1.1 - VALIDAR URL:*/
                     $this->_NBL_Request['url'] = Uteis::validarURL( $this->_NBL_Request['url'] ); //RECEBE FALSE OU A URL TRATADA
                     if ( !$this->_NBL_Request['url'] ) { //URL INVALIDA
                         $msgResposta        = NBLIdioma::getTextoPorIdElemento('msgErroLabelDivRespostaGenerica');
                         $msgErroUrlInvalida = NBLIdioma::getTextoPorIdElemento('msgErroUrlInvalida');
                         $resposta = "
                               <script type=\"text/javascript\"> 
                                 $('#divRespostaFormHiperLinksUsuario').empty();
                                 $('#divRespostaFormHiperLinksUsuario').append('<img src=\"imagens/imgRespostaErro.png\"/>');
                                 $('#divRespostaFormHiperLinksUsuario').append('<label id=\"labelDivRespostaFormHiperLinkUsuario\"> $msgResposta </label>');
 
                                 $('#labelFormHiperLinkUsuarioEnderecoResposta').empty();
                                 $('#labelFormHiperLinkUsuarioEnderecoResposta').append('$msgErroUrlInvalida');
                                 $('#labelFormHiperLinkUsuarioEnderecoResposta').hide();
                                 $('#labelFormHiperLinkUsuarioEnderecoResposta').show('slow');
                                 $('#inputFormHiperLinkUsuarioEndereco').focus();
 
                               </script>";
                         echo $resposta;
                         return false;
                     }//URL VALIDADA OU PROCESSO INTERROMPIDO
                 /*1.1 - FIM - VALIDAR URL:*/

                 /*VERIFICA SE A URL JA EXISTE NA TABELA GLOBAL E SE NAO EXISTE CRIA*/
                     $idHiperLink = HiperLinkDAO::obterIdPorUrl( $this->_NBL_Request['url'] ); /*BUSCA ID NA TABELA GLOBAL PELA URL PASSADA*/
                     if ( !$idHiperLink ) /*NAO EXISTE LINK GLOBAL COM A URL PASSADA*/
                     {
                         $h = New HiperLink();                       /*CRIA NOVO OBJETO*/
                         $h->setUrl ( $this->_NBL_Request['url'] );  /*DEFINE A URL COMO SENDO A QUE FOI PASSADA PELA REQUISICAO*/
                         HiperLinkDAO::persiste($h);                 /*GRAVA O OBJETO NO BD*/
                         /*O ID GLOBAL PASSA A SER O ID ATRIBUIDO PARA O LINK GLOBAL QUE ACABOU DE SER CRIADO */
                         $idHiperLink = HiperLinkDAO::obterIdPorUrl( $this->_NBL_Request['url'] );  
                     }
                     /*OBS: PERCEBER QUE $idHiperLink OU SAI COM O ID QUE EXISTIA OU COM O NOVO ID - SERA USADO COMO FK NA TABELA DE LINK DO USUARIO*/
                 /*FIM - VERIFICA SE A URL JA EXISTE NA TABELA GLOBAL*/

                 /* AGORA REFLETIR AS MUDANCAS PARA O LINK QUE ESTA SENDO ALTERADO*/
                    /*TABELA HIPERLINKSUSUARIO - DADOS PROPRIOS DO USUARIO PARA O LINK CADASTRADO*/
                    $hu = HiperLinksUsuarioDAO::obterPorId( $this->idEntidade );
                    $hu->setNivelCompartilhamento ( (int) $this->_NBL_Request['nivelCompartilhamento'] );
                    $hu->setNome                  ( $this->_NBL_Request['nome'] );
                    $hu->setDescricao             ( $this->_NBL_Request['descricao']);
                    $hu->setIdHiperLink( $idHiperLink ); /*$idHiperLink FOI DEFINIDO ACIMA NOS CODIGOS ACIMA - EH PRECISO DEIXAR SETADO SENAO O BD CRIA NO LINK*/
                    
                    HiperLinksUsuarioDAO::persiste($hu);
                /* FIM - AGORA REFLETIR AS MUDANCAS PARA O LINK QUE ESTA SENDO ALTERADO*/
                
                /*AGORA VERIFICAR OS GRUPOS - ABORDAGEM ADOTADA: APAGA TODOS E INCLUI OS DA REQUISICAO*/
                    $R  = $this->_NBL_Request['grupos'];                   /*$R  - Array com os ids de grupos da requisicao de alteracao, R*/
                    M_to_m_grupos_linksDAO::excluiPorIdLink($this->idEntidade); /*EXCLUI TODOS AS ENTRADAS NA TABELA M-TO-M PARA O LINK PASSADO*/
                    /*INCLUIR OS ELEMENTOS DO CONJUTO R (REQUISICAO)*/
                    for ($i=0; $i<count($R); $i++) { //$i EH VARIAVEL AUXILIAR DO FOR
                        $m_to_m = new M_to_m_grupos_links($this->idEntidade, $R[$i]);
                        M_to_m_grupos_linksDAO::persiste($m_to_m);
                    }
                /*AGORA VERIFICAR OS GRUPOS*/                    
                $msgResposta = NBLIdioma::getTextoPorIdElemento('msgGenericaSalvoComSucesso');
                $resposta = "
                      <script type=\"text/javascript\"> 
                        $('#divRespostaFormHiperLinksUsuario').empty();
                        $('#divRespostaFormHiperLinksUsuario').append('<img src=\"imagens/imgRespostaOK.png\"/>');
                        $('#divRespostaFormHiperLinksUsuario').append('<label id=\"labelDivRespostaFormHiperLinkUsuario\"> $msgResposta </label>');
                        $('#inputFormHiperLinkUsuarioEndereco').focus();
                      </script>";
                echo $resposta;
            }
            else /* if ( ($HiperLinkUsuario = HiperLinksUsuarioDAO::obterPorId($this->idEntidade)) != NULL) /*EXISTE OBJETO COM ID PASSADO?*/
            { /*POR ENQUANTO, NADA FAZ - APENAS A REQUISICAO NAO FARA EFEITO ALGUM*/ }
        } catch (Exception $exc) {
            header("Location:http://www.nbooklink.com/index.php?erro=" . $exc->getMessage());
            die();//PARA ESSE SCRIPT POR AQUI
        }
    }

    
    /*Acao de excluir nova entidade*/
    protected function _NBL_Action_delete($restrict = TRUE){
        parent::_NBL_Action_delete($restrict); /*JA ALGUMAS RESTRICOES DE BASE*/
        /*VERIFICAR SE O USUARIO LOGADO EH DONO DA ENTIDADE A SER EXCLUIDA*/
        try {
            /*BUSQUE O OBJETO COM O ID PASSADO - $Link RECEBE NULL OU OBJETO PREENCHIDO*/
            if ( ($HiperLinkUsuario = HiperLinksUsuarioDAO::obterPorId($this->idEntidade)) != NULL) 
            {
                if ( $HiperLinkUsuario->getIdUsuario() != $this->idUsuario) /*QUEM FEZ A REQUISICAO NAO EH DONO DO GRUPO*/
                {    
                    throw new Exception ("erroUsuarioNaoEDonoDaEntidadeAfetadaPelaRequisicao");
                }
                /*SE HA NA REQUISICAO ENVIADA UM ID DE GRUPO EH PORQUE A EXCLUSAO EH DE UM LINK EM PARTICULAR*/
                if ( isset($this->_NBL_Request['idGrupo']) && (int)($this->_NBL_Request['idGrupo']) > 0 )
                {
                    HiperLinksUsuarioDAO::excluirUmaOcorrencia($this->idEntidade, $this->_NBL_Request['idGrupo']);
                }
                else { /*NAO HA UM ID DE GRUPO ENTAO EXCLUI O LINK POR COMPLETO*/
                    HiperLinksUsuarioDAO::excluiPorId($this->idEntidade);
                }
                return NBLIdioma::getTextoPorIdElemento('msgGenericaExcluidoComSucesso');
            }
            else { /*POR ENQUANTO, NADA FAZ - APENAS A REQUISICAO NAO FARA EFEITO ALGUM*/ }
        } catch (Exception $exc) {
            header("Location:http://www.nbooklink.com/index.php?erro=" . $exc->getMessage());
            die();//PARA ESSE SCRIPT POR AQUI
        }
    }
}