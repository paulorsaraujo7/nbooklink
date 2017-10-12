<?php
if (empty($_REQUEST) || !isset($_REQUEST['_NBL_Action']) || !isset($_REQUEST['_NBL_View'])) //REQUISICAO VAZIA OU ACAO NAO DEFINIDA REDIRECIONA PARA HOME
{
    header("Location:index.php");
    exit();
}
require_once 'principais.php';        //ARQUIVOS COM CLASSES BASES


if ($_REQUEST['_NBL_Action'] == '_NBL_Action_Delete') //EH UMA EXCLUSAO
{
    //Eh MAIS SEGURO o id ser passado pela sessao para nao ficar exposto no scritp da pagina que envia os dados do formulario que contem o botao de exlusao
    UsuarioDAO::excluiPorId($_SESSION['_SESSAO_']['_USUARIO_']['idUsuario']);
    
    unset($_SESSION['_SESSAO_']); 
    unset($_SESSION['Usuario']);
    setcookie("IUS","", time()-1); 
    header("location:index.php?msg=msgExclusaoDeContaDeUsuarioRealizada");              //REDIRECIONA PARA HOMEPAGE
}

$_NBL_Action = $_REQUEST['_NBL_Action']; //TIPO DA ACAO
$_NBL_View   = $_REQUEST['_NBL_View'];   //TIPO DA VISUALIZACAO (SE DA INDEX, SE DE UM CELULAR...)

MainController::preparaCampos($_REQUEST);//TRATA OS DADOS PARA EVITAR ATAQUES


/*
 * RECEBE OS DADOS DO FORMULARIO DE USUARIO.

 * VALIDACOES DOS CAMPOS:
 * 
 * Trata os seguintes possiveis erros (tem no caso de uso):
 * 1 - Para o campo NOME:
 *  1.1 - Nome nao pode ser Vazio.
 * 
 * 2 - Para o campo EMAIL:
 *  2.2 - Email ja existente.
 *  2.3 - Email invalido.
 * 
 * 3 - Para o campos SENHA:
 *  3.2 - Senha menor que quatro caracteres.
 *  3.3 - Senha nao confere com a confirmacao de senha.
 * 
 * 4 - Para o campo de CAPTCHA
 *  4.1 O texto digitado nao confere com o gerado.
 * 
 * PARA O CASO DE SUCESSO:
 *  1 - Envia mensagem de confirmacao
 *  2-  Segue os mesmo passos do caso de uso. 
 */

/*
 * **************PENDENCIAS
  -  AO PASSAR POR TODAS AS RESTRICOES:
  - ENVIAR EMAIL DE BOAS VINDAS PARA O USUARIO
  - FOCO NO CAMPO DE LOGIN (PREENCHER O CAMPO EMAIL COM O EMAIL QUE FOI RECEM CADASTRADO) OU REDIRECIONAR PARA SUA PAGINA PRINCIPAL.
  - MENSAGENS DO BD (TITULO DA PAGINA, TITULO DO FORMULARIO, CAMPOS, MSG DE RESPOSTAS)
  - VERIFICAR NOS OUTROS NAVEGADORES
  - AO SAIR DO CAMPO JA VERIFICAR SUA VALIDADE
  - ERRO NO CAPTCHA ENTAO RECARREGAR A IMAGEM
  - DEFINIR VISUAL DA DIV DE RESPOSTA (DEVE SER IGUAL PARA TODOS OS FORMULARIO)
  - COLOCAR O GIF AJAX DE CARREGANDO
  - OTIMIZAR O CODIGO (COLOCAR NO FRAMEWORK ADEQUADO)
  - VERIFICAR IMPACTO DE MUDANCA EM CAMPO DE ENTIDADE
  - QUANDO O CADASTRO FOR FEITO COM SUCESSO, ENTAO


 *  AO PASSAR POR TODAS AS RESTRICOES:
 * ARMAZENAR NO BD. (CHAMA CLASSE DAO PARA INSERIR)

 * VALIDAR SE JA EXISTE O EMAIL PARA OUTRO USUARIO
 * NAO CONFERE O CAPTCHA no MOZILLA
 * NOME COM SOMENTE ESPACOS (TRIM E VERIFICAR TAMANHO) 
 * DEIXAR O MECANISMO DE ARMAZENAMENTO FUNCIONANDO APOS PASSAR POR TODAS AS RESTRICOES


 * ************HISTORICO DE ATUALIZACOES
 * 02/04/2013 - 05:41 - implementado código para exclusão do usuário
 * 16/07/2012 - 20:00 - 23:00 - Problemas com idiomas. VariÃƒÂ¡veis que vÃƒÂ£o ser exibidas pelo js devem ser escapadas. Exemplo: a palavra " don't ".
 * 12/07/2012 - 13/07/2012 04:50
 * 
 *  

 */

//DADOS PASSADOS PELA REQUISICAO
$nome               = $_POST["nome"];
$email              = $_POST["email"];
$senha              = $_POST["senha"];
$confirmaSenha      = $_POST["confirmaSenha"];
$mensagemInicial    = $_POST["mensagemInicial"];






if ( $_NBL_Action == '_NBL_Action_Create') //EH A CRICAO DE UMA ENTIDADE
 $ImgCAPTCHA         = $_POST["ImgCAPTCHA"];

//POSSIVEIS MENSAGENS DE ERRO - DEVEM SER ESCAPADAS COM A FUNCAO DO MYSQL PARA NAO GERAR ERRO NO SCRIPT JS QUANDO TEM ASPAS

$msgErroCadUsuarioNomeNaoPreenchido             = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroCadUsuarioNomeNaoPreenchido'));
$msgErroCadUsuarioEmailInvalido                 = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroCadUsuarioEmailInvalido'));
$msgErroCadUsuarioEmailJaExistente              = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroCadUsuarioEmailJaExistente'));
$msgErroCadUsuarioSenhaInvalida                 = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroCadUsuarioSenhaInvalida'));
$msgErroCadUsuarioSenhaNaoConfereComConfirmacao = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroCadUsuarioSenhaNaoConfereComConfirmacao'));
$msgErroCadUsuarioCAPTCHANaoConfere             = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroCadUsuarioCAPTCHANaoConfere'));
$msgErroLabelDivRespostaFormCadUsuario          = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroLabelDivRespostaGenerica'));
$msgOkLabelDivRespostaFormCadUsuario            = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgOkLabelDivRespostaFormCadUsuario'));
$msgGenericaSalvoComSucesso                     = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgGenericaSalvoComSucesso'));



$ERRO = FALSE; //EM PRICIPIO NAO HA IMPEDIEMTOS
$RESPOSTA = ""; // E O CONTEUDO DA DIV DE RESPOSTA
$SCRIPT_RESPOSTA_NOME = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO NOME
$SCRIPT_RESPOSTA_EMAIL = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO NOME
$SCRIPT_RESPOSTA_SENHA = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO NOME
$SCRIPT_RESPOSTA_CAPTCHA = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO NOME
//NOME
if (!isset($nome) || $nome == "") { //NOME - IMPEDIMENTOS
    $ERRO = TRUE;

    $SCRIPT_RESPOSTA_NOME = "
                               
                                $('#labelRespostaFormCadUsuarioNome').empty();
                                $('#labelRespostaFormCadUsuarioNome').append('$msgErroCadUsuarioNomeNaoPreenchido');
                                $('#labelRespostaFormCadUsuarioNome').hide();
                                $('#labelRespostaFormCadUsuarioNome').show('fast');
                                $('#inputFormCadUsuarioNome').focus();

                               ";
}
else
    $SCRIPT_RESPOSTA_NOME =
            " 
       $('#labelRespostaFormCadUsuarioNome').empty();
       
   ";

//EMAIL INFORMADO EH VALIDO?
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //EMAIL - IMPEDIMENTOS
    $ERRO = TRUE;
    $SCRIPT_RESPOSTA_EMAIL =
            "
                    $('#labelRespostaFormCadUsuarioEmail').empty();
                    $('#labelRespostaFormCadUsuarioEmail').text('$msgErroCadUsuarioEmailInvalido');
                    $('#labelRespostaFormCadUsuarioEmail').hide();
                    $('#labelRespostaFormCadUsuarioEmail').show('slow');
                    $('#inputFormCadUsuarioEmail').focus();
                ";
} else
/*
 * CADASTRO NOVO
 *  1 - VERIFICAR SE EXISTE NO BD O EMAIL CADASTRADO
 * UPDATE 
 *  1 - INFORMADO EMAIL NOVO (DIFERENTE DO QUE ESTA NA SESSAO) E QUE JA EXISTE NO BD
 * 
 */    
    if ( $_NBL_Action == '_NBL_Action_Create'    
         || 
        ($_NBL_Action == '_NBL_Action_Update' && $email != $_SESSION['_SESSAO_']['_USUARIO_']['email'] )   
    ) 
    {
            if (UsuarioDAO::existePorEmail($email)) {
            $ERRO = TRUE;
            $SCRIPT_RESPOSTA_EMAIL =
                    "
                            $('#labelRespostaFormCadUsuarioEmail').empty();
                            $('#labelRespostaFormCadUsuarioEmail').text('$msgErroCadUsuarioEmailJaExistente');
                            $('#labelRespostaFormCadUsuarioEmail').hide();    
                            $('#labelRespostaFormCadUsuarioEmail').show('slow');    
                            $('#inputFormCadUsuarioEmail').focus();
                        ";
            }
            else
            $SCRIPT_RESPOSTA_EMAIL = " $('#labelRespostaFormCadUsuarioEmail').empty(); ";
    
    }    


//SENHA MENOR QUE QUATRO CARACTERES
if (strlen($senha) < 4) { //MENOR QUE QUATRO CARACTERES
    $ERRO = TRUE;
    $SCRIPT_RESPOSTA_SENHA =
            "
                    $('#labelRespostaFormCadUsuarioSenha').empty();
                    $('#labelRespostaFormCadUsuarioSenha').append('$msgErroCadUsuarioSenhaInvalida');
                    $('#labelRespostaFormCadUsuarioSenha').hide();
                    $('#labelRespostaFormCadUsuarioSenha').show('slow');    
                    $('#inputFormCadUsuarioSenha').focus();
                ";
} elseif ($senha != $confirmaSenha) { //SENHA NAO CONFERE COM A CONFIRMACAO
    $ERRO = TRUE;
    $SCRIPT_RESPOSTA_SENHA =
            "
                    $('#labelRespostaFormCadUsuarioSenha').empty(); 
                    $('#labelRespostaFormCadUsuarioConfirmaSenha').empty();
                    $('#inputFormCadUsuarioSenha').val(\"\");
                    $('#inputFormCadUsuarioConfirmaSenha').val(\"\");
                    $('#labelRespostaFormCadUsuarioSenha').append('$msgErroCadUsuarioSenhaNaoConfereComConfirmacao');
                    $('#labelRespostaFormCadUsuarioSenha').hide();
                    $('#labelRespostaFormCadUsuarioSenha').show('slow');    
                    $('#inputFormCadUsuarioSenha').focus();
                ";
}
else
    $SCRIPT_RESPOSTA_SENHA = "$('#labelRespostaFormCadUsuarioSenha').empty();";


if ($_NBL_Action == '_NBL_Action_Create') //SOMENTE SOLICITA CAPTCHA SE FOR CRIACAO
{
        if ($ImgCAPTCHA != $_SESSION['ImgCAPTCHA']) { //CAPTCHA NAO CONFERE COM O DIGITADO  
            $ERRO = TRUE;
            $SCRIPT_RESPOSTA_CAPTCHA =
                    "
                            $('#labelRespostaFormCadUsuarioImgCAPTCHA').empty();
                            $('#labelRespostaFormCadUsuarioImgCAPTCHA').append('$msgErroCadUsuarioCAPTCHANaoConfere');
                            $('#labelRespostaFormCadUsuarioImgCAPTCHA').hide();
                            $('#labelRespostaFormCadUsuarioImgCAPTCHA').show('slow');
                            $('#inputFormCadUsuarioImgCAPTCHA').focus();
                        ";
        }
        else
            $SCRIPT_RESPOSTA_CAPTCHA = "
                 $('#labelRespostaFormCadUsuarioImgCAPTCHA').empty();

        ";
}
else
{
    $SCRIPT_RESPOSTA_CAPTCHA = "";

}

//HOUVE ERRO
if ($ERRO) {

    $RESPOSTA = "
                    <img   id=\"imgDivRespostaFormCadUsuario\" src=\"imagens/respostaErro.png\"></img>
                    <label id=\"labelDivRespostaFormCadUsuario\">$msgErroLabelDivRespostaFormCadUsuario</label>
    ";
    
} else { //PASSOU POR TODAS AS RESTRICOES
   
    $mensagemSucesso = "";
    if ($_NBL_Action == '_NBL_Action_Create' || $_NBL_Action == '_NBL_Action_Update'  )
    {
        $Usuario = new Usuario();
        if ($_NBL_Action == '_NBL_Action_Create') //EH NOVO USUARIO
        {
            $Usuario->setIdUsuario(null);
            $Usuario->setDataCadastro(new NBLDateTime(date('Y-m-d H:i:s'))); 
            $mensagemSucesso = $msgOkLabelDivRespostaFormCadUsuario; //MENSAGEM A SER EXIBIDA NA DIV DE RESPOSTA
            
        }
        else //EH UPDATE
        {
            
            $Usuario = UsuarioDAO::obterPorId($_SESSION['_SESSAO_']['_USUARIO_']['idUsuario']); //RECUPERA DO BD OS DADOS DO USUARIO LOGADO
            $mensagemSucesso = $msgGenericaSalvoComSucesso; //MENSAGEM A SER EXIBIDA NA DIV DE RESPOSTA
        }
        //OBS: PARA UM NOVO USUARIO, TODOS OS CAMPOS NAO DEFINIDOS JA SAO NULL POR PADRAO O QUE SERA TRATADO NA HORA DE GRAVAR NO BD
        $Usuario->setNome($nome);
        $Usuario->setEmail($email);
        $Usuario->setSenha(md5($senha));
        $Usuario->setMensagemInicial($mensagemInicial);
        $Usuario->setSessao(SessaoDAO::obterPorIdUsuario($Usuario->getIdUsuario()));
        
        //ATUALIZA O OBJETO ARMAZENADO NA SESSAO
        $_SESSION['Usuario'] = serialize($Usuario);

        
        //ARMAZENA NO BD
        UsuarioDAO::persiste($Usuario);

        
        
        if ($_NBL_Action == '_NBL_Action_Create' && $_NBL_View == '_NBL_View_Index')
            $foco = "$('#inputFormLoginEmail').focus();";
    }

    if (!isset($foco)) $foco = ""; 
    //LEMBRAR QUE SE FOR ACRECENTAR SCRIPT NA RESPOSTA TEM QUE ACRESCENTAR AS TAGS JAVA SCRIPT ANTES E DEPOIS SOMENTE DO SCRIPT
    $RESPOSTA = "<img src=\"http://www.nbooklink.com/imagens/respostaOK.png \"></img>" . $mensagemSucesso .
                "<script type=\"text/javascript\">" . $foco . "</script>";   

    //SE FOR NOVO E NÃO FOR USUARIO NOVO REDIRECIONA PARA PÁGINA PRINCIPAL DO USUARIO
    if ($_NBL_Action == '_NBL_Action_Create')
    {
        $email = $Usuario->getEmail(); //VARIAVEL JA SETADA NO USUARIO CRIADO
        $senha = $_POST['senha'];
        $RESPOSTA = "<script type=\"text/javascript\"> " . 
                        "$('#inputFormLoginEmail').attr('value', '$email' ); " .  //PREENCHE O CAMPO LOGIN
                        "$('#inputFormLoginSenha').attr('value', '$senha' ); " .  // PREENCHE O CAMPO SENHA
                        "document.forms['formLogin'].submit(); " . //ENVIA O FORMULARIO
                    "</script>";    
        echo $RESPOSTA;
        exit(); 
    }
}

// AS VARIAVEIES DE SCRIPT DE RESPOSTA DEVEM SER COLOCADAS DA ULTIMA PARA PRIMEIRA PARA O FOCO DO COMPO  NAO FICAR NA ULTIMA
echo $RESPOSTA
 . " <script type=\"text/javascript\"> "
 . $SCRIPT_RESPOSTA_CAPTCHA
 . $SCRIPT_RESPOSTA_SENHA
 . $SCRIPT_RESPOSTA_SENHA
 . $SCRIPT_RESPOSTA_EMAIL
 . $SCRIPT_RESPOSTA_NOME
 . "</script>";


?>
