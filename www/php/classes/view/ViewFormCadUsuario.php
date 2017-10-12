<?php 
require_once "../../../principais.php";
$Usuario = UsuarioDAO::obterPorId($_SESSION['_SESSAO_']['_USUARIO_']['idUsuario']);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php echo ViewBase::retornaArquivosDeLigacoes(); //Prepara os arquivos de ligacoes comuns a todas as pgs.?>
        
        <link href="css/formCadUsuario.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript">
            $(document).ready(function () {
                // PRIMEIRO ELEMENTO A RECEBER O FOCO E O CAMPO EMAIL DO LOGIN
                $('#imgGifAjax').hide();
                $('#imgGifAjax').remove();
                //ENVIO DO FORMULARIO DE CADASTRO DE USUARIO.  
                $('#inputSubmitFormCadUsuario').click(
                function(){
                    $('#imgGifAjax').show();
                    //PRIMEIRO PREENCHE AQUI
                    var jNome            = $("#inputFormCadUsuarioNome").val();
                    var jEmail           = $("#inputFormCadUsuarioEmail").val();
                    var jSenha           = $("#inputFormCadUsuarioSenha").val();
                    var jConfirmaSenha   = $("#inputFormCadUsuarioConfirmaSenha").val();
                    var jMensagemInicial = $("#textAreaFormCadUsuarioMensagemInicial").val();
                    var j_NBL_Action     = $("#_NBL_Action").val();
                    var j_NBL_View       = $("#_NBL_View").val();
        
                    //DEPOIS A CHAMADA DE FUNCAO AQUI
                    $.post('recebeFormCadUsuario.php', 
                    {   nome           : jNome, 
                        email          : jEmail,
                        senha          : jSenha,
                        confirmaSenha  : jConfirmaSenha,
                        mensagemInicial: jMensagemInicial,
                        _NBL_Action    : j_NBL_Action,
                        _NBL_View      : j_NBL_View
                    }, 
                    function(data){
                        $("#divRespostaFormCadUsuario").hide();     //ESCONDO A DIV RESPOSTA PARA DESPOIS MOSTRAR COM CONTEUDO.                                                                                                                         
                        $('#divRespostaFormCadUsuario').html(data);
                        $('#divRespostaFormCadUsuario').show('slow');
                    },
                    'html');
                    $('#imgGifAjax').hide();
                    return false;
                });                
                
                $("#inputSubmitFormExcluiUsuario").click(function(){  
                    $(function() {
                        
                      $("#dialog-confirm").dialog({
                        resizable: false,
                        height:200,
                        title: "<?php echo NBLIdioma::getTextoPorIdElemento('titulomsgConfirmaExclusaoDeContaDeUsuario');?>",
                        modal: true,
                        show:
                        {
                            effect: "blind",
                            duration: 500
                        },
                        close:
                        {
                            effect: "blind",
                            duration: 1000
                        },
                        buttons: {
                          "<?php echo NBLIdioma::getTextoPorIdElemento('valueBotaoExcluirGenerico');?>": function() {
                            $( this ).dialog( "close" );
                            document.forms["formExcluiUsuario"].submit();
                          },
                          "<?php echo NBLIdioma::getTextoPorIdElemento('valueBotaoCancelarGenerico');?>": function() {
                            $( this ).dialog( "close" );
                          }
                        }
                     });
                     
                    });
                });                  
            });
            
  </script>
</head>
 
    <body>
                    <!--AS RESPOSTAS PRINCIPAIS VEM PARA ESSA DIV. -->
                    <div style="height: 300px;">
                        <div id="divRespostaFormCadUsuario">
                        </div>
                        <form id="formCadUsuario" action="recebeFormCadUsuario.php" method="post" >
                            <label id="labelFormCadUsuarioNome" class="labelEntradaForm"  for="inputFormCadUsuarioNome" 
                                   title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioNome'); ?>">*
                                    <?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioNome'); ?>
                            </label>
                            <input id="inputFormCadUsuarioNome" name="nome" type="text" maxlength="100" tabindex="0" value ="<?php echo $Usuario->getNome(); ?>">
                            <label id="labelRespostaFormCadUsuarioNome"></label> 
                            
                            <label id="labelFormCadUsuarioEmail" class="labelEntradaForm"  for="inputFormCadUsuarioEmail" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioEmail'); ?>"><?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioEmail'); ?></label>
                            <input id="inputFormCadUsuarioEmail" name="email" type="text" maxlength="100" tabindex="0" value ="<?php echo $Usuario->getEmail(); ?>">
                            <label id="labelRespostaFormCadUsuarioEmail" ></label>
                            
                            <label id="labelFormCadUsuarioSenha" class="labelEntradaForm"  for="inputFormCadUsuarioSenha" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioSenha'); ?>"><?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioSenha'); ?></label>
                            <input id="inputFormCadUsuarioSenha" name="senha" type="password" maxlength="45" tabindex="0" value ="" >
                            <label id="labelRespostaFormCadUsuarioSenha" ></label>
                            
                            <label id="labelFormCadUsuarioConfirmaSenha" class="labelEntradaForm"  for="inputFormCadUsuarioConfirmaSenha" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioConfirmaSenha'); ?>"><?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioConfirmaSenha'); ?></label>
                            <input id="inputFormCadUsuarioConfirmaSenha" name="confirmaSenha" type="password" tabindex="0" value ="" >
                            <label id="labelFormCadUsuarioMensagemInicial" class="labelEntradaForm"  for="textAreaFormCadUsuarioMensagemInicial" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioMensagemInicial'); ?>"><?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioMensagemInicial'); ?></label>

                            <input id="_NBL_Action" type="hidden" name="_NBL_Action" value="_NBL_Action_Update" />
                            <input id="_NBL_View"   type="hidden" name="_NBL_View"   value="_NBL_View_Abas" />
                                                       
                            
                            <textarea id="textAreaFormCadUsuarioMensagemInicial" name="mensagemInicial" type="" tabindex="0" ><?php echo $Usuario->getMensagemInicial(); ?></textarea>
                            <input id="inputSubmitFormCadUsuario" style="position: absolute; width: auto; left: 10px; top: 320px" class="clicavel" type="submit" value="<?php echo NBLIdioma::getTextoPorIdElemento('valueInputSalvarAlteracoesGenerico'); ?>">
                            <input style="position: absolute; width: auto; left: 160px; top: 320px" class="clicavel" type="button" value="<?php echo NBLIdioma::getTextoPorIdElemento('valueInputVoltar'); ?>" onclick="document.location='index.php'">
                        </form>

                        
                        
                        <div id="dialog-confirm" style="display: none">
                            <p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
                                <?php 
                                     //VAI DIVIDIR A STRING DA MENSAGEM PARA A CADA PONTO DE INTERROGAÇÃO SEJA INSERIDA UMA QUEBRA DE LINHA
                                    $array  = explode ("?",NBLIdioma::getTextoPorIdElemento('msgConfirmaExclusaoDeContaDeUsuario')) ;
                                    $string = implode("?</br>", $array); 
                                    echo $string;
                                ?>
                            </p>
                        </div>            
                        
                        
                        <form id="formExcluiUsuario" action="recebeFormCadUsuario.php" method="post" >
                            <input id="_NBL_Action" type="hidden" name="_NBL_Action" value="_NBL_Action_Delete" />
                            <input id="_NBL_View"   type="hidden" name="_NBL_View"   value="_NBL_View_Abas" />
                            <input id="inputSubmitFormExcluiUsuario" class="clicavel" type="button" style="position: absolute; width: auto; left: 400px; top: 320px"  value="<?php echo NBLIdioma::getTextoPorIdElemento('valueInputSubmitFormExcluiUsuario'); ?>">                            
                        </form>                        
                    </div>

                    
                    
                    <div id="divExplicacaoFormUsuario" style="position:absolute; left:350px; top:40px; width:200px;">
                        <img id="imgExplicacao" src="imagens/imgExplicacao.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgExplicacao');?>" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altImgExplicacao');?>" >
                        <div id="divTextoExplicacaoFormUsuario" style="position:absolute; left:10px; top:80px; width:180px;"> 
                            <?php 
                                $array  = explode (".",NBLIdioma::getTextoPorIdElemento('msgExplicacaoAlterarDadosPrincipaisDoUsuario')) ; //DIVIDE A STRING EM UM ARRAY SEPARANDO PELO PONTO
                                $string = implode(".</br></br>", $array); 
                                echo $string;
                            ?>
                        </div>
                    </div>

    </body>
</html>
