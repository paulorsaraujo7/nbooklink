<?php
/*
 * Iniciada criação em 04/06/2013
 * Autor: NBL Inc.
 */

/**
 *
 * @author NBL Inc.
 */
class GruposHiperLinksUsuarioController extends MainController {
    
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

                /* AGORA REFLETIR AS MUDANCAS PARA ENTIDADE QUE ESTA SENDO ALTERADO*/
                    $O = new GruposHiperLinksUsuario();
                    $O->setIdGrupoHiperLinksUsuario ($this->_NBL_Request['idEntidade']);
                    $O->setNome                     ($this->_NBL_Request['nome']);
                    $O->setDescricao                ($this->_NBL_Request['descricao']);
                    $O->setIdUsuario                ($this->idUsuario);
                    
                    GruposHiperLinksUsuarioDAO::persiste($O);
                /* FIM - AGORA REFLETIR AS MUDANCAS PARA O ENTIDADE QUE ESTA SENDO ALTERADO*/
                
                $msgResposta = NBLIdioma::getTextoPorIdElemento('msgGenericaSalvoComSucesso');
                $resposta = "
                    <script type=\"text/javascript\"> 
                      $('#divRespostaFormGruposHiperLinksUsuario').empty();
                      $('#divRespostaFormGruposHiperLinksUsuario').append('<img src=\"imagens/imgRespostaOK.png\"/>');
                      $('#divRespostaFormGruposHiperLinksUsuario').append('<label id=\"labelDivRespostaFormGrupoHiperLinksUsuario\"> $msgResposta </label>');
                      $('#nome').val('');
                      $('#descricao').val('');
                      $('#nome').focus();
                    </script>";
                echo $resposta;
    }/*FIM DA ACAO CREATE*/
    
    /*
     * METODO PARA ATUALZIACAO DE UM GRUPO
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
            if ( ($GrupoHiperLinksUsuario = GruposHiperLinksUsuarioDAO::obterPorId($this->idEntidade)) != NULL) /*EXISTE OBJETO COM ID PASSADO?*/
            {
                if ( $GrupoHiperLinksUsuario->getIdUsuario() != $this->idUsuario) /*QUEM FEZ A REQUISICAO EH DONO DA ENTIDADE?*/
                {    
                    throw new Exception ("erroUsuarioNaoEDonoDaEntidadeAfetadaPelaRequisicao"); /*QUEM FEZ A REQ. NAO EH DONO*/
                }
                /*SE PASSOU PELO IF ACIMA SEM GERAR EXCECAO ENTAO O USUARIO EH DONO DA ENTIDADE*/

                /* AGORA REFLETIR AS MUDANCAS PARA ENTIDADE QUE ESTA SENDO ALTERADO*/
                    $O = new GruposHiperLinksUsuario();
                    $O->setIdGrupoHiperLinksUsuario ($this->_NBL_Request['idEntidade']);
                    $O->setNome                     ($this->_NBL_Request['nome']);
                    $O->setDescricao                ($this->_NBL_Request['descricao']);
                    $O->setIdUsuario                ($this->idUsuario);
                    
                    GruposHiperLinksUsuarioDAO::persiste($O);
                /* FIM - AGORA REFLETIR AS MUDANCAS PARA O ENTIDADE QUE ESTA SENDO ALTERADO*/
                
                $msgResposta = NBLIdioma::getTextoPorIdElemento('msgGenericaSalvoComSucesso');
                $resposta = "
                    <script type=\"text/javascript\"> 
                      $('#divRespostaFormGruposHiperLinksUsuario').empty();
                      $('#divRespostaFormGruposHiperLinksUsuario').append('<img src=\"imagens/imgRespostaOK.png\"/>');
                      $('#divRespostaFormGruposHiperLinksUsuario').append('<label id=\"labelDivRespostaFormGrupoHiperLinksUsuario\"> $msgResposta </label>');
                      $('#nome').focus();
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
            /*BUSQUE O OBJETO COM O ID PASSADO - $Grupo RECEBE NULL OU OBJETO PREENCHIDO*/
            if ( ($Grupo = GruposHiperLinksUsuarioDAO::obterPorId($this->idEntidade)) != NULL) 
            {
                if ( $Grupo->getIdUsuario() != $this->idUsuario) /*QUEM FEZ A REQUISICAO NAO EH DONO DO GRUPO*/
                {    
                    throw new Exception ("erroUsuarioNaoEDonoDaEntidadeAfetadaPelaRequisicao");
                }
                if ( GruposHiperLinksUsuarioDAO::excluiPorId($this->idEntidade) )
                {
                    return NBLIdioma::getTextoPorIdElemento('msgGenericaExcluidoComSucesso');
                }
            }
            else 
            { /*POR ENQUANTO, NADA FAZ - APENAS A REQUISICAO NAO FARA EFEITO ALGUM*/ }
        } catch (Exception $exc) {
            header("Location:http://www.nbooklink.com/index.php?erro=" . $exc->getMessage());
            die();//PARA ESSE SCRIPT POR AQUI
        }
    }
}

?>
