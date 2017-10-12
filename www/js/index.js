            $(document).ready(function () {
                // PRIMEIRO ELEMENTO A RECEBER O FOCO E O CAMPO EMAIL DO LOGIN
                $('#inputFormLoginEmail').focus();
                $('#imgGifAjax').hide();
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
                    var jImgCAPTCHA      = $("#inputFormCadUsuarioImgCAPTCHA").val();
                    var j_NBL_Action     = $("#_NBL_Action").val();
                    var j_NBL_View       = $("#_NBL_View").val();
        
                    //DEPOIS A CHAMADA DE FUNCAO AQUI
                    $.post('recebeFormCadUsuario.php', 
                    {   nome           : jNome, 
                        email          : jEmail,
                        senha          : jSenha,
                        confirmaSenha  : jConfirmaSenha,
                        mensagemInicial: jMensagemInicial,
                        ImgCAPTCHA     : jImgCAPTCHA,
                        _NBL_Action    : j_NBL_Action,
                        _NBL_View      : j_NBL_View
                    }, 
                    function(data){
                        $("#divRespostaFormCadUsuario").hide();     //ESCONDO A DIV RESPOSTA PARA DESPOIS MOSTRAR COM CONTEUDO.                                                                                                                         
                        $('#divRespostaFormCadUsuario').html(data);
                        $('#divRespostaFormCadUsuario').show('slow');
                        $('#imgGifAjax').hide();
                    },
                    'html');
                    
                    return false;
                });                
            });


            $(function() {
                $( "#dialog-message" ).dialog({
                  modal: true,
                  buttons: {
                    Ok: function() {
                      $( this ).dialog( "close" );
                    }
                  },
                  hide: {
                      effect: "explode",
                      duration: 500
                  },
                  show: {
                      effect: "blind",
                      duration: 500
                  }
                });
              });   


function emBreve(e)
{
    e.preventDefault(); 
    alert("In a bit time / Em breve...");
}

