            $(document).ready(function () {
                $('#imgGifAjax').hide();
                //ENVIO DO FORMULARIO.  
                $('#inputSubmitFormContato').click(
                function(){
                    $('#imgGifAjax').show();
                    //PRIMEIRO PREENCHE AQUI
                    var jNome            = $("#inputFormContatoNome").val();
                    var jEmail           = $("#inputFormContatoEmail").val();
                    var jTipoMensagem    = $("input[name='tipoMensagem']:checked").val();
                    var jMensagem        = $("#textAreaFormContatoMensagem").val();
                    var jImgCAPTCHA      = $("#inputFormContatoImgCAPTCHA").val();
                    
                    //DEPOIS A CHAMADA DE FUNCAO AQUI
                    $.post('recebeFormContato.php', 
                    {   nome           : jNome, 
                        email          : jEmail,
                        tipoMensagem   : jTipoMensagem,
                        mensagem       : jMensagem,
                        imgCAPTCHA     : jImgCAPTCHA
                    }, 
                    function(data){
                        $("#divRespostaFormContato").hide();     //ESCONDO A DIV RESPOSTA PARA DESPOIS MOSTRAR COM CONTEUDO.                                                                                                                         
                        $("#divRespostaFormContato").html(data);
                        $("#divRespostaFormContato").show('slow');
                        $('#imgGifAjax').hide();
                    },
                    'html');
                    return false;
                });                
            });


