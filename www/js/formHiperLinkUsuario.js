/* SCRIPTS DO FORM DE HIPER LINKS DE USUARIO */

            $(document).ready(function () {
                $('#inputFormHiperLinkUsuarioEndereco').focus();
                
                
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
                
                
                $('#linkFormChamaFormGruposHiperLinksUsuario1').click(function(e){
                    var serializeDados = $('#formChamaFormGruposHiperLinksUsuario1').serializeArray();
                    e.preventDefault();
                    $.ajax({
                        url     : 'formGruposHiperLinksUsuario.php',
                        dataType: 'html',
                        type    : 'POST',
                        data    : serializeDados,
                        timeout : 10000,
                        beforeSend: function(){
                            $('#divScriptFormHiperLinksUsuario').html(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                            $('#divScriptFormHiperLinksUsuario').html('<p>' + data + '<p>');
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divScriptFormHiperLinksUsuario').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divScriptFormHiperLinksUsuario').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                            }
                        }
                    });
                });
                
                $('#inputSubmitFormHiperLinksUsuario').click(function(e){
                    var serializeDados = $('#formHiperLinkUsuario').serializeArray();
                    e.preventDefault();
                    //limpa os labels de resposta
                    $('#labelFormHiperLinkUsuarioEnderecoResposta').empty();
                    $('#labelFormHiperLinkUsuarioNomeResposta').empty();
                    $.ajax({
                        url     : 'recebeHiperLinksUsuario.php',
                        dataType: 'html',
                        type    : 'POST',
                        data    : serializeDados,
                        timeout : 10000,
                        beforeSend: function(){
                            $('#divRespostaFormHiperLinksUsuario').html(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                            $('#divRespostaFormHiperLinksUsuario').hide().html('<p>' + data + '<p>').show('slow'); //exibe resposta
                            setTimeout(function() {
                                $("#divRespostaFormHiperLinksUsuario").hide('slow').html(''); //limpa resposta
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
                                $('#divRespostaFormHiperLinksUsuario').html('<p>Ocorreu um erro</p>')
                            }
                            else
                            {
                                $('#divRespostaFormHiperLinksUsuario').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                            }
                        }
                    });
                });
            });      
