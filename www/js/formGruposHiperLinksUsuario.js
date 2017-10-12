/*JS do Formulario de Grupos de Hiper Links*/
            $(document).ready(function () {
                
                //DEFININDO A CLASSE DOS ELEMENTOS QUE CONTEM O FOCO
                $('input[type="text"]').focus(function () {
                    $(this).addClass("ui-state-focus");
                });
                $('input[type="password"]').focus(function () {
                    $(this).addClass("ui-state-focus");
                });
                $('textarea').focus(function () {
                    $(this).addClass("ui-state-focus");
                });


                $('input[type="text"]').blur(function () {
                    $(this).removeClass("ui-state-focus");
                });
                $('input[type="password"]').blur(function () {
                    $(this).removeClass("ui-state-focus");
                });
                $('textarea').blur(function () {
                    $(this).removeClass("ui-state-focus");
                });
                
                
                $('#nome').focus();
                $('#inputSubmitFormGruposHiperLinksUsuario').click(function(e){
                    e.preventDefault();
                    var serializeDados = $('#formGruposHiperLinksUsuario').serializeArray();
                    $.ajax({
                        url     : 'recebeFormGruposHiperLinksUsuario.php',
                        dataType: 'html',
                        data    : serializeDados,
                        type    : 'post',
                        timeout : 10000,
                        beforeSend: function(){
                            $('#divRespostaFormGruposHiperLinksUsuario').html(iconCarregandoNivel2);
                        },
                        complete: function() {
                            $(iconCarregandoNivel2).remove();
                        },
                        success: function(data, textStatus){
                            $("#divRespostaFormGruposHiperLinksUsuario").hide().html('<p>' + data + '<p>').show('slow'); //exibe resposta
                            /*Atualiza a div grupos*/
                            setTimeout(function() {
                                $("#divRespostaFormGruposHiperLinksUsuario").hide('slow').html(''); //limpa resposta
                                //atualiza o combo de grupos
                                var dadosFormChecksGruposLinks = $('#formHiperLinkUsuario').serializeArray();
                                $.ajax({
                                    url     : 'funcoesAjax.php',
                                    dataType: 'HTML',
                                    type    : 'POST',
                                    data    : dadosFormChecksGruposLinks,
                                    timeout : 10000,
                                    beforeSend: function(){
                                        $('#divGrupos').html(iconCarregandoNivel2);
                                    },
                                    complete: function() {
                                        $(iconCarregandoNivel2).remove();
                                    },
                                    success: function(data, textStatus){
                                        $('#divGrupos').html(data);
                                    },
                                    error: function(xhr, er) {
                                        if (er == 'timeout') {
                                            $('#divGrupos').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                                        }
                                        else
                                        {
                                            $('#divGrupos').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                                        }
                                    }
                                });

                                /*Atualiza a div da direita*/
                                $.ajax({
                                    url     : 'funcoesAjax.php?action=obterGruposDeHiperLinkDeUsuarioEmBulletsHTML',
                                    dataType: 'html',
                                    type    : 'get',
                                    timeout : 10000,
                                    beforeSend: function(){
                                        $('#divGruposLinksDireita').html(iconCarregando);
                                    },
                                    complete: function() {
                                        $(iconCarregando).remove();
                                    },
                                    success: function(data, textStatus){
                                        $('#divGruposLinksDireita').html(data);
                                    },
                                    error: function(xhr, er) {
                                        if (er == 'timeout') {
                                            $('#divGruposLinksDireita').html('<p>Ocorreu um erro</p>')
                                        }
                                        else
                                        {
                                            $('#divGruposLinksDireita').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')

                                        }
                                    }
                                });
                                
                                
                            }, 2000);
                            
                            
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divRespostaFormGruposHiperLinksUsuario').html('<p>Ocorreu um erro</p>')
                            }
                            else
                            {
                                $('#divRespostaFormGruposHiperLinksUsuario').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')

                            }
                        }
                    });
                });
            });