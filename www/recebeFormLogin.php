<?php
require_once 'principais.php';
/*
 * RECEBE OS DADOS DO FORMULARIO DE LOGIN E AUTENTICA OU NAO O USUARIO. - VERIFICAR O DOCUMENTO DE CASO DE USO PARA MAIO
 * ENTEDIMENTO.
 *
 * 
 * PENDÊNCIA: (FUNCIONALIDADE) FEITO O LOGIN DIRECIONA PARA A HOME PAGE
 * PENDENCIA: (VISUAL) DEFINIR VISUAL DA DIV DE RESPOSTA NA HOME PAGE QUE RECEBERÁ AS MSGS DE ERRO ENVIADAS POR ESTE SCRIPT
 * 
 * HISTORICO DE ATUALIZACOES
 *  29/07/2012 - Primeiras modificacoes do arquivo antigo do LL para o novo.   
 *  31/07/2012 - Continuacao das primeiras modificacoes e alteracoes no codigo dos arquivos que manipulam o BD
 *  04/08/2012 - Codificacao do que foi definido na documentacao do caso de uso
 *  09/08/2012 - Documentacao, codigo de tratamento de erro.
 *  17/08/2012 - Correção de pendências.
 */


//1 - PREPARA OS DADOS RECEBIDOS PARA EVITAR ATAQUES.
MainController::preparaCampos($_POST);

$email           = $_POST["email"];                                      // VEM DO FORMULARIO DE LOGIN O EMAIL INFORMADO
$senha           = md5($_POST["senha"]);                                 // A SENHA VEM DO FORMULARIO DE LOGIN E JA E CRIPTOGRAFADA AQUI
$manterConectado = ( isset($_POST["manterConectado"]) ) ? TRUE : FALSE;  // O VALOR DO CHECK NO FORMULARIO DE LOGIN PARA MANTER O USUARIO CONECTADO ESTA MARCADO
//2 - VERIFICA EXISTENCIA DO USUARIO
try {
    if (UsuarioDAO::existePorEmail($email)) {
        $Usuario = UsuarioDAO::obterPorEmail($email); //$Usuario AGORA CONTEM O OBJETO USUARIO PREENCHIDO
        if ($Usuario->getSenha() == $senha) { //USUARIO EXISTE E SENHA CONFERE (AS SENHAS TANTO A RECUPERADA QUANTO A QUE VEM JA ESTAO CRIPTOGRAFADAS COM MD5
            
            $Sessao = SessaoDAO::obterPorIdUsuario($Usuario->getIdUsuario()); //RETORNA NULL OU O OBJETO QUE CONTEM A SESSAO ARMAZENADA PARA O USUARIO
            
            
            //---TRATAR A SEGURANCA DA SESSAO RAM (CRIA UMA ASSINATURA COM BASE EM DADOS DO USUARIO E ARMAZENA NA SESSAO RAM (VAI SER UTIL PARA EVITAR SEQUESTRO DE SESSOES COM BASE NO PHPSESSID)
            //OBS: TEM QUE SER TRATADO AQUI POIS ABAIXO SERAO UTILIZADAS AS INFORMACOES SOBRE IP PARA ATUALIZAR OU CRIAR SESSAO NO BD
            $chave  = "1a2cf8gk68gj67gf784kh69fo6";        //CHAVE SECRETA.
            $ip     = $_SERVER["REMOTE_ADDR"];                //IP DO USUARIO.
            $hora   = time();                               //HORA ATUAL.
            
            $assinatura = md5($email . $chave . $ip . $hora);  //ASSINATURA COM ALGUNS DADOS DO USUARIO RECEM AUTENTICADO.
            $_SESSION["_SESSAO_"] = array("chave" => $chave, "ip" => $ip, "hora" => $hora, "assinatura" => $assinatura, "autenticado" => TRUE); //ALGUNS DADOS RELATIVOS AO LOGIN DO USUARIO QUE FICAM ARMAZENADOS NA SESSAO RAM
            //---FIM - TRATA A SEGURANCA DA SESSAO 

            

            // ----- TRATA SESSAO ARMAZENADA NO BD (CRIA OU ATUALIZA)
            if ($Sessao != NULL) { //EXISTE SESSAO ARMAZENADA PARA O USUARIO - ATUALIZA A EXISTENTE
                //----------------------------DADOS QUE DEVEM SER PERSISTIDO NO MOMENTO DO LOGIN.
                //SE O IDIOMA CORRENTE FOR DIFERENTE DO IDIOMA ARMAZENADO NO BD, ENTAO O IDIOMA NO BD PASSA A TER O VALOR DO IDIOMA CORRENTE. (DEVE VERIFICAR NO LOGOUT TAMBEM)
                if ($_SESSION['_IDIOMA_']['idiomaSelecionado'] != $Sessao->getUltimoIdiomaSelecionado())
                    $Sessao->setUltimoIdiomaSelecionado($_SESSION['_IDIOMA_']['idiomaSelecionado']);  //
                    
//ALGUNS DADOS QUE DEVEM SER PERSISTIDO NA SESSAO NO MOMENTO DO LOGIN (O IDIOMA JA FOI DEFINIDO ACIMA)
                $Sessao->setUltimoIPUtilizado($ip); //ULTIMO IP PASSA A SER O IP ATUAL (NAO COLOCO FORA DO IF-ELSE POIS NO ELSE TEM A CRIACAO DA SESSAO QUE DEVE SER FEITA PRIMEIRO)
                //----------------------------FIM - DADOS QUE DEVEM SER PERSISTIDOS NO MOMENTO DO LOGIN.
            }
            else { //NAO EXISTIA SESSAO PARA O USUARIO NO BD - CRIA UMA NOVA E ARMAZENA OS DADOS
                
                $Sessao = new Sessao(); //CRIA NOVO OBJETO SESSAO
                $Sessao->setIdUsuario($Usuario->getIdUsuario());
                $Sessao->setPHPSESSID(session_id()); // O CAMPO PHPSESSID RECEBE O ID DA SESSAO RAM QUE ACABA DE SER CRIADA (SERA UNICA PARA CADA USUARIO)
                //----------------------------DADOS QUE DEVEM SER PERSISTIDO NO MOMENTO DO LOGIN.
                $Sessao->setUltimoIdiomaSelecionado($_SESSION['_IDIOMA_']['idiomaSelecionado']);  //A SESSAO RECEM CRIADA RECEBE O IDIOMA CORRENTE.
                //ALGUNS DADOS QUE DEVEM SER PERSISTIDO NA SESSAO NO MOMENTO DO LOGIN (O IDIOMA JA FOI DEFINIDO ACIMA)
                $Sessao->setUltimoIPUtilizado($ip); //ULTIMO IP PASSA A SER O IP ATUAL (NAO COLOCO FORA DO IF-ELSE POIS NO ELSE TEM A CRIACAO DA SESSAO QUE DEVE SER FEITA PRIMEIRO)
            }
                $Sessao->setUltimoLogin( new NBLDateTime(date('Y-m-d H:i:s')) );    //HORA DO ULTIMO LOGIN PASSA A SER A HORA ATUAL
            
            
            $IUS        = md5( "{$Usuario->getIdUsuario()}" . "$assinatura" );  //IUS = "IDENTIFICADOR DA ULTIMA SESSAO" = (HASH DO ID DO USUARIO COM A ASSINATURA DA SESSAO CORRENTE)
            $Sessao->setHashUltimaSessao($IUS);
            SessaoDAO::persiste($Sessao);
            // ----- FIM - TRATA SESSAO ARMAZENADA NO BD (CRIA OU ATUALIZA)

            $Usuario->setSessao($Sessao);                                     //SETA A SESSAO DO USUARIO (ANTES DE SERIALIZAR O OBJETO USUARIO)
            
            //NO MOMENTO DO LOGIN DEVE SOMAR + 1 NO CONTADOR DE LOGIN DO USUARIO. SE FOR O 1 LOGIN ENTAO MSG DE BOAS VINDAS
            $Usuario->setNumeroDeLogins( $Usuario->getNumeroDeLogins() + 1 ); //INCREMENTA O NUMERO DE LOGINS FEITOS PELO USUARIO
            UsuarioDAO::persiste($Usuario); //ATUALIZA DADOS POR OCASIAO DO LOGIN ATUAL
            
            //Coloco na sessao alguns dados rapidos sobre o usuario que esta logado.
            $_SESSION['_SESSAO_']['_USUARIO_']['idUsuario']       = (int) $Usuario->getIdUsuario();
            $_SESSION['_SESSAO_']['_USUARIO_']['email']           = $Usuario->getEmail();
            $_SESSION['_SESSAO_']['_USUARIO_']['nome']            = $Usuario->getNome();
            $_SESSION['_SESSAO_']['_USUARIO_']['login']           = $Usuario->getLogin();
            $_SESSION['_SESSAO_']['_USUARIO_']['senha']           = $Usuario->getSenha();
            $_SESSION['_SESSAO_']['_USUARIO_']['dataCadastro']    = serialize( $Usuario->getDataCadastro() );
            $_SESSION['_SESSAO_']['_USUARIO_']['temFoto']         = $Usuario->getTemFoto();
            $_SESSION['_SESSAO_']['_USUARIO_']['mensagemInicial'] = $Usuario->getMensagemInicial();
            $_SESSION['_SESSAO_']['_USUARIO_']['anoNascimento']   = $Usuario->getAnoNascimento();
            $_SESSION['_SESSAO_']['_USUARIO_']['mesNascimento']   = $Usuario->getMesNascimento();
            $_SESSION['_SESSAO_']['_USUARIO_']['diaNascimento']   = $Usuario->getDiaNascimento();
            $_SESSION['_SESSAO_']['_USUARIO_']['numeroDeLogins']  = $Usuario->getNumeroDeLogins();
            

            /* ARMAZENA O OBJETO USUARIO NA SESSAO (TEM QUE USAR O SERIALIZE PARA PASSAR PARA SESSAO)
             * LEMBRAR QUE O OBJETO USUARIO JA CONTERA O OBJETO SESSAO AGREGADO A ELE
             */
            $_SESSION['Usuario'] = serialize($Usuario);

            
            // VERIFICAR SE O CHECK "MANTER CONECTADO ESTA MARCADO" SE SIM ARMAZENA UM HASH DA SESSAO DO USUARIO PARA IDENTIFICAR NA PROXIMA ENTRADA
            // NO LOGOUT ESSE NUMERO EH APAGADO.
            /*
             * IUS = "IDENTIFICADOR DA ULTIMA SESSAO" = (HASH DO ID DO USUARIO COM A ASSINATURA DA SESSAO CORRENTE).
             * EH UTILIZADO PARA IDENTIFICAR O USUARIO NA PROX. VISITA SEM QUE  ESSE TENHA QUE AUTENTICAR-SE
             **/
            if ($manterConectado == TRUE) //MATER CONECTADO ESTA MARCADO. (DEVE SER TRATADO AQUI POIS NO MOMENTO DO LOGOUT O CHECK SEQUER ESTARA DISPONIVEL)
            {
                setcookie ("IUS", $Sessao->getHashUltimaSessao(), time()+3600*24*30);  //ARMAZENAR NO COOKIE O ID DA SESSAO GERADA PELO PHP
            }
            
            
            //SE ATINGIR AQUI, ENTAO NAO HOUVE ERRO E REDIRECIONA PARA A HOME PAGE
            header("Location:index.php");
        }
        else { //SENHA NAO CONFERE
            throw new Exception("erroSenhaNaoConfere");
        }
    } else { //USUARIO NAO EXISTE
        throw new Exception("erroUsuarioNaoExiste");
    }
} catch (Exception $e) {
   header("Location:index.php?erro=" . $e->getMessage());
}
?>
