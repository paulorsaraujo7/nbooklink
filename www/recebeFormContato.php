<?php
require_once 'principais.php';

/*
 * RECEBE OS DADOS DO FORMULARIO DE CONTATO (VERIFICAR OS QUADROS DE CASO DE USO - ENTRAR EM CONTATO)
 * 
 * HISTORICO DE ATUALIZACOES
 *  04/02/2013 - Criacao com base no arquivo recebeFormLogin.php
 *  06/02/2013 - Mudanca na forma de recebimento (por Ajax)
 *  11/02/2013 - Mudancas para respeitar o idioma selecionado no envio da mensagem
 * 
 * 
 * 1 - PROTEJE DADOS RECEBIDOS CONTRA ATAQUES XSS (FUNCAO STRIP_TAGS PARA OS CAMPOS NAO OCULTOS)
 * 1.1 - ARMAZENAR OS DADOS RECEBIDOS EM VARIAVEIS
 *     (CONTRA ATAQUES SQL EH FEITO NA CLASSE DAO)
 *  
 * 2 - VALIDAR AUTENTICACAO / CAMPOS (REGRAS DO NEGOCIO)
 *     (SEGUIR A SEQUENCIA DO FORMULARIO)
 *     (CADA FORMULARIO DEVE TER LABELS DE RESPOSTA ESPECIFICO PARA CAMPOS ESPECIFICOS)
 *     (ESCAPAR AS POSSIVEIS MENSAGENS DE ERRO - EH PRECISO ESCAPAR PARA NAO GERAR ERRO QUANDO RETORNAR POR JS. EX: THE EMAIL DON'T... )
 *     (AS MENSAGENS DEVEM VIR DO BD SEMPRE RESPEITANDO O IDIOMA SELECIONADO)
 *     (NA CRIACAO DE MENSAGENS NO BD UTILIZAR OS PREFIXOS ERRO, ALERTA... E UTILIZAR MENSAGENS GENERICAS PARA O MESMO TIPO DE ERRO)
 *  2.1 - NOME NAO PODE SER VAZIO
 *  2.2 - EMAIL DEVE SER VALIDO
 *  2.3 - MENSAGEM NAO PODE SER VAZIA E NEM CONTER ESPACOS SOMENTE
 *  2.1 - CAPTCHA
 *  
 * 
 * 3 - PREPARAR DADOS QUE NAO FORAM ENVIADOS MAS QUE SERAO ARMAZENADOS NO OBJETO
 *  3.1 - ID DO USUARIO = NULL SE NAO LOGADO
 *  3.2 - DATA DO ENVIO = DATA ATUAL
 * 
 * 4 - PREENCHER OS DADOS DO OBJETO
 *  4.1 - ENVIR PARA O EMAIL DO:
 *      NBL - DADOS DO USUARIO E DA MSG
 *      DO INTERNAUTA - DADOS DE CONFIRMACAO DO RECEBIMENTO DA MSG, PRAZO PARA RESPOSTA ETC...
 *  4.2 - ARMAZENAR OBJETO NO BD SOMENTE SE FOI ENVIADO COM SUCESSO
 *
 * 5 - EXIBIR FEEDBACK DO PROCESSO PARA O INTERNAUTA COM UM RESUMO DA MSG 
 *     (RESPEITAR O IDIOMA)
 *     (O FORMULARIO DEVE TER UM CONTEINER PAR RECEBER O CONTEUDO DE RETORNO)
 *  5.1 - PARA ESTE CASO O USUARIO SERA REDIRECIONADO PARA OUTRA PAGINA (CONFIRMACAO DE CONTATO)
 */


//echo  "<script type=\"text/javascript\">
//        location.href=\"contactReceived.php?idMessage=10 \" 
//      </script>";        
//
//$p = 10;
//        $RESPOSTA = "<script type=\"text/javascript\">
//                        location.href = \"contactError.php?idMessage=". $p ." \"</script>";        
//
//        echo $RESPOSTA;
//
//die();

//SE O USUARIO DIGITAR A URL E NAO FOREM DADOS O USUARIO EH REDIRECINADO PARA HOME. EVITA QUE GERE ERRO E SEJAM EXIBIDOS DETALHES DA IMPLEMENTACAO
if (empty($_POST))
    header ("Location:index.php");


//1 - PREPARA OS DADOS RECEBIDOS PARA EVITAR ATAQUES XSS.
MainController::preparaCampos($_POST);

//1 - ARMAZENAR OS DADOS RECEBIDOS
$nome           = $_POST['nome'];
$email          = $_POST['email'];
$tipoMensagem   = $_POST['tipoMensagem']; //sugestao, erro, critica, outro
$mensagem       = $_POST['mensagem'];
$imgCAPTCHA     = $_POST['imgCAPTCHA'];


//2 - ESCAPAR AS POSSIVEIS MENSAGENS DE ERRO / VALIDAÇÃO
$msgErroNomeNaoPreenchido           = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('erroNomeNaoPreenchido'));
$msgErroEmailInvalido               = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('erroEmailInvalido'));
$msgErroEmailVazio                  = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('erroEmailVazio'));
$msgErroMensagemVazia               = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('erroMensagemVazia'));
$msgErroCAPTCHANaoConfere           = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('erroCAPTCHANaoConfere'));
$msgErroLabelDivRespostaGenerica    = Uteis::escapeString(NBLIdioma::getTextoPorIdElemento('msgErroLabelDivRespostaGenerica'));


$ERRO = FALSE;                  //EM PRICIPIO NAO HA IMPEDIEMTOS
$RESPOSTA = "";                 //E O CONTEUDO DA DIV DE RESPOSTA
$SCRIPT_RESPOSTA_NOME     = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO NOME
$SCRIPT_RESPOSTA_EMAIL    = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO EMAIL
$SCRIPT_RESPOSTA_MENSAGEM = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO MENSAGEM
$SCRIPT_RESPOSTA_CAPTCHA  = ""; //E O SCRIPT DE RESPOSTA PARA O CAMPO CAPTCHA


/*ABAIXO AS VALIDACOES DOS CAMPOS*/
//NOME 
if (!isset($nome) || $nome == "") { //NOME - IMPEDIMENTOS
    $ERRO = TRUE;

    $SCRIPT_RESPOSTA_NOME = "
                               
                                $('#labelRespostaFormContatoNome').empty();
                                $('#labelRespostaFormContatoNome').append('$msgErroNomeNaoPreenchido');
                                $('#labelRespostaFormContatoNome').hide();
                                $('#labelRespostaFormContatoNome').show('fast');
                                $('#inputFormContatoNome').focus();

                               ";
}
else
    $SCRIPT_RESPOSTA_NOME =
   " 
       $('#labelRespostaFormContatoNome').empty();
   ";

//EMAIL
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //EMAIL - IMPEDIMENTOS
    $ERRO = TRUE;
    $SCRIPT_RESPOSTA_EMAIL =
            "
                    $('#labelRespostaFormContatoEmail').empty();
                    $('#labelRespostaFormContatoEmail').text('$msgErroEmailInvalido');
                    $('#labelRespostaFormContatoEmail').hide();
                    $('#labelRespostaFormContatoEmail').show('slow');
                    $('#inputFormContatoEmail').focus();
            ";
}
else
    $SCRIPT_RESPOSTA_EMAIL = " $('#labelRespostaFormContatoEmail').empty(); ";

//MENSAGEM 
if (!isset($mensagem) || $mensagem == "") { //NOME - IMPEDIMENTOS
    $ERRO = TRUE;

    $SCRIPT_RESPOSTA_MENSAGEM = "
                               
                                $('#labelRespostaFormContatoMensagem').empty();
                                $('#labelRespostaFormContatoMensagem').append('$msgErroMensagemVazia');
                                $('#labelRespostaFormContatoMensagem').hide();
                                $('#labelRespostaFormContatoMensagem').show('fast');
                                $('#inputFormContatoMensagem').focus();

                               ";
}
else
    $SCRIPT_RESPOSTA_MENSAGEM =
   " 
       $('#labelRespostaFormContatoMensagem').empty();
   ";


//IMAGEM CAPTCHA
if ($imgCAPTCHA != $_SESSION['ImgCAPTCHA']) { //CAPTCHA NAO CONFERE COM O DIGITADO  
    $ERRO = TRUE;
    $SCRIPT_RESPOSTA_CAPTCHA =
            "
                    $('#labelRespostaFormContatoImgCAPTCHA').empty();
                    $('#labelRespostaFormContatoImgCAPTCHA').append('$msgErroCAPTCHANaoConfere');
                    $('#labelRespostaFormContatoImgCAPTCHA').hide();
                    $('#labelRespostaFormContatoImgCAPTCHA').show('slow');
                    $('#inputFormContatoImgCAPTCHA').focus();
                ";
}
else
    $SCRIPT_RESPOSTA_CAPTCHA = "
         $('#labelRespostaFormContatoImgCAPTCHA').empty();
         
";

//HOUVE ERRO NA VALIDACAO DOS CAMPOS
if ($ERRO) {
    $RESPOSTA = "
                    <img   id=\"imgDivRespostaFormContato\" src=\"imagens/respostaErro.png\"></img>
                    <label id=\"labelDivRespostaFormContato\">$msgErroLabelDivRespostaGenerica</label>
                    
                ";

echo $RESPOSTA
 . " <script type=\"text/javascript\"> "
 . "$('#imgGifAjax').hide();"        
 . $SCRIPT_RESPOSTA_CAPTCHA
 . $SCRIPT_RESPOSTA_MENSAGEM
 . $SCRIPT_RESPOSTA_EMAIL
 . $SCRIPT_RESPOSTA_NOME
 . "</script>";
    
    
} else { //PASSOU POR TODAS AS VALIDACOES DOS CAMPOS
// 3 - PREPARAR DADOS QUE NAO FORAM ENVIADOS MAS QUE SERAO ARMAZENADOS NO OBJETO
    
    
    $v = new ViewContact(); //Sera utilizado para saber se o usuario esta logado
    
    /*CAMPOS DO OBJETO ContatoComNBL ABAIXO (Na sequencia em que aparecem no objeto: */
    //$idContatoComNBL = null; //DEFINIDO NA CLASSE
    if ($v->isLogado()) //Usuario logado
    {
        $Usuario   = unserialize($_SESSION['Usuario']); //Deserializa o objeto que esta na sessao
        $idUsuario = $Usuario->getIdUsuario();          //Recupera o ID do usuario logado
    }
    else {//Usuario nao logado
        $idUsuario       = null;
    }
    //$tipoMensagem  = JA FOI RECEBIDO PELO $_POST
    //$mensagem      = JA FOI RECEBIDO PELO $_POST
    $dataEnvio       = new NBLDateTime(date('Y-m-d H:i:s'));
    $idioma          = $_SESSION['_IDIOMA_']['idiomaSelecionado'];
    //$status          = 'N';  //DEFINIDO POR PADRAO
    //$dataResposta    = null; //DEFINIDO POR PADRAO
    //$resposta        = null; //DEFINIDO POR PADRAO
    $nomeRemetente   = $nome;
    $emailRemetente  = $email;
    /*FIM DOS CAMPOS do objeto ContatoComNBL*/

    
// 4 - PREENCHER OS DADOS DO OBJETO:
    $m = new ContatoComNBL(); //Novo objeto criado
    
    
    
    //$m->setIdUsuario($idUsuario);
    $m->setIdUsuario($idUsuario);
    $m->setTipoMensagem($tipoMensagem);
    $m->setMensagem($mensagem);
    $m->setDataEnvio($dataEnvio);
    $m->setIdioma($idioma);
    $m->setNomeRemetente($nomeRemetente);
    $m->setEmailRemetente($emailRemetente);
    
    //PREPARA O ENVIO DE EMAIL - PODE SER UTILIZADO POR UM USUARIO OU PARA ENVIO DE UM ERRO PARA O EMAIL TECNICO
    $mail = new PHPMailer(true); 
    $mail->IsSMTP();
    $mail->SMTPAuth = TRUE;
    $mail->Password = "nbl2012";                   
    $mail->Username = "support@nbooklink.com";    
    $mail->Host     = "smtp.nbooklink.com";   
    $mail->Port = 587;
    $mail->SetFrom('support@nbooklink.com');
    $mail->AddCC("nbooklink@yahoo.com.br");
    $mail->AddAddress("support@nbooklink.com");   
    
    
    if (isset($_SESSION['_IDIOMA_']['idiomaSelecionado'])) /*EVITA UM ERRO GERADO POR EXEMPO SE A INDEX NAO TIVER SIDO ACESSADA E FOR DIGITADA A URL DESTA PAGINA*/
    {
        /*VAI SELECIONAR O IDIOMA DO SITE COM BASE NO IDIOMA SELECIONADO*/
        if ($_SESSION['_IDIOMA_']['idiomaSelecionado'] == 'PT_BR')
            $mail->SetLanguage("br");
        if ($_SESSION['_IDIOMA_']['idiomaSelecionado'] == 'EN_US')
            $mail->SetLanguage("en");
        
    }
    else /*SE NAO TIVER IDIOMA SELECIONADO, ENTAO SETA COMO PADRAO O INGLES*/
    {
            $mail->SetLanguage("en");
    }
    
    
    $mail->CharSet = 'utf-8';
    


    //CODIGO PARA DEFINIR NA LINGUAGEM ESCOLHIDA O TIPO DA MENSAGEM - NECESSARIO POIS VAI FIGURAR NO EMAIL ENVIADO E NA RESPOSTA
    switch ($tipoMensagem) {
        case "e":
            $tipoMensagem = NBLIdioma::getTextoPorIdElemento('tipoMensagemDeContatoComNBLErro');
            break;
        case "s":
            $tipoMensagem = NBLIdioma::getTextoPorIdElemento('tipoMensagemDeContatoComNBLSugestao');
            break;
        case "c":
            $tipoMensagem = NBLIdioma::getTextoPorIdElemento('tipoMensagemDeContatoComNBLCritica');
            break;
        case "o":
            $tipoMensagem = NBLIdioma::getTextoPorIdElemento('tipoMensagemDeContatoComNBLOutro');
            break;
    }
    $tipoMensagem = ucfirst($tipoMensagem);
    
    // 4.2 ENVIA MENSAGEM PARA O EMAIL DO NBL
    try {
        $mensagem = nl2br($mensagem); //TRANFORMA QUEBRA DE LINHA EM </BR>
        $mail->AddReplyTo($m->getEmailRemetente());   //EMAIL DE RESPOSTA EH O DO REMETENTE
        $mail->Subject = NBLIdioma::getTextoPorIdElemento('campoAssuntoEmailEntrarEmContatoComNBL') . " " . $m->getNomeRemetente() . " - " . $tipoMensagem; 
        $mail->MsgHTML("<html><body> $mensagem  </body></html>");
        $mail->Send();
        
        //  4.2 - ARMAZENAR OBJETO NO BD SOMENTE SE FOI ENVIADO COM SUCESSO
        try {
            ContatoComNBLDAO::persiste($m);
        }
        catch (mysqli_sql_exception $e) { //ERRO NO BD - NAO PRECISA COMUNICAR AO USUARIO POIS O QUE IMPORTA EH QUE A MSG VA PELO EMAIL
            $mail->Subject = 'Erro no BD - Contato com o NBookLink. De ' . $m->getNomeRemetente() ; //CAMPO ASSUNTO - VERIFICAR IDIOMA PARA FINS DA RESPOSTA
            $mensagemDeErro = $e->getMessage();
            $mail->MsgHTML("<html><body> $mensagemDeErro </body></html>");
            $mail->Send();
        }
        
        //REDIRECIONA PARA PAGINA DE SUCESSO PASSANDO O ID DA MENSAGEM CRIADO NO BD (SO CHEGA AQUI SE FOI ENVIADA E GRAVADA NO BD)
        $RESPOSTA = "<script type=\"text/javascript\">
                        $('#imgGifAjax').hide();
                        location.href = \"contactReceived.php?from=". $m->getNomeRemetente() .
                    " \"</script>";  
        echo $RESPOSTA;
    } catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    } catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
}