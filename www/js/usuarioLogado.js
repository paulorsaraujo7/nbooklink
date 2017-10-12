/*Scripts para quando o usuário está logado.*/
            $(document).ready(function() {
                
                
                
                
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
                
                $('#linkMenuComandoMinhaConta').bind('click', t);
                $('#linkMenuComandoTopLinks').bind('click', topLinks);
                
                /*CHAMA O FORM DE HIPER LINK*/
                $('#linkFormChamaFormGruposHiperLinksUsuario').click(function(e){
                    var serializeDados = $('#formChamaFormGruposHiperLinksUsuario').serializeArray();
                    e.preventDefault();
                    $.ajax({
                        url     : 'formGruposHiperLinksUsuario.php',
                        dataType: 'html',
                        type    : 'POST',
                        data    : serializeDados,
                        timeout : 10000,
                        beforeSend: function(){
                            $('#divConteudoCentro').html(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                            $('#divConteudoCentro').html('<p>' + data + '<p>');

                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                                    
                            }
                        }
                    });
                });

                $('#linkFormChamaFormHiperLinkUsuario').click(function(e){
                    var serializeDados = $('#formChamaFormHiperLinkUsuario').serializeArray();
                    e.preventDefault();
                    $.ajax({
                        url     : 'formHiperLinkUsuario.php',
                        dataType: 'html',
                        type    : 'POST',
                        data    : serializeDados,
                        timeout : 10000,
                        beforeSend: function(){
                            $('#divConteudoCentro').html(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                            $('#inputFormHiperLinkUsuarioEndereco').focus();
                        },
                        success: function(data, textStatus){
                            $('#divConteudoCentro').html('<p>' + data + '<p>');
                            $('#inputFormHiperLinkUsuarioEndereco').focus();
                            
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                                    
                            }
                        }
                    });
                });
                
                function t () {
                    $.get('php/classes/view/ViewAbasContaUsuario.php', {id : 0}, function (data) { $('#divConteudoCentro').fadeIn(3).html(data) }, 'html');
                        return false;
                }
                
            })/*ready*/

                
    function gu(e){
                    $('#divConteudoCentro').empty();
                    $.ajax({
                        url     : 'funcoesAjax.php?action=obterListaDeGruposPorGrupoEUsuario&idGrupo=' + e.id,
                        dataType: 'html',
                        type    : 'GET',
                        data    : 'html',
                        timeout : 10000,
                        beforeSend: function(){
                            $('#divConteudoCentro').html(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                            $('#divConteudoCentro').hide().html(data).show('slow'); //exibe resposta
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                            }
                        }
                    });
                }


    function todosOsLinks(){
                    $('#divConteudoCentro').empty();
                    $.ajax({
                        url     : 'funcoesAjax.php?action=obterListaHTMLDeTodosOsLinksDeUmUsuario',
                        dataType: 'html',
                        type    : 'GET',
                        data    : 'html',
                        beforeSend: function(){
                            $('#divConteudoCentro').html(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                            $('#divConteudoCentro').hide().html(data).show('slow'); //exibe resposta
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                            }
                        }
                    });
                }



    function clickHiperLinkUsuario(e){
                    $.ajax({
                        url     : 'funcoesAjax.php?action=clickHiperLinkUsuario&idHiperLinkUsuario=' + e.id,
                        dataType: 'html',
                        type    : 'GET',
                        data    : 'html'
                    });
                }




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
    
    
    function excluirGrupoDeLinks(e){
        if ( confirm(msgConfirmaExclusaoDeGrupoDeLinks + '\n' + e.name) )
        {
                    $.ajax({
                        url     : 'funcoesAjax.php?action=excluirGrupoDeLinks&idEntidade='+e.id,
                        dataType: 'html',
                        type    : 'GET',
                        data    : 'html',
                        beforeSend: function(){
                            $("#divGrupoLinks" + e.id).append(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                           $("#divGrupoLinks" + e.id).hide('slow');
                           atualizaDivDireita();
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                            }
                        }
                    });
        }
    }
    


    function excluirLinkParaTodosGrupos(e){
        if ( confirm(msgConfirmaExclusaoDeLinkParaTodosOsGrupos + '\n' + e.name) )
        {
                    $.ajax({
                        url     : 'funcoesAjax.php?action=excluirLinkParaTodosGrupos&idEntidade='+e.id,
                        dataType: 'html',
                        type    : 'GET',
                        data    : 'html',
                        beforeSend: function(){
                            $('#divConteudoCentro').prepend(iconCarregando);
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                            atualizaDivDireita();
                            todosOsLinks(e);
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                            }
                        }
                    });
        }
    }
    
    
    function atualizaDivDireita()
    {
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
                        $('#divGruposLinksDireita').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                    }
                    else
                    {
                        $('#divGruposLinksDireita').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')

                    }
                }
            });
    }

    function excluirLinkParaGrupoAtual(e, idGrupo){

        if ( confirm(msgConfirmaExclusaoDeLinkParaGrupoAtual + '\n' + e.name) )
        {
                    $.ajax({
                        url     : 'funcoesAjax.php?action=excluirLinkParaGrupoAtual&idEntidade='+e.id+"&idGrupo="+idGrupo,
                        dataType: 'html',
                        type    : 'GET',
                        data    : 'html',
                        beforeSend: function(){
                            if (idGrupo == 0) /*link sem grupo*/
                            {
                                $("#divLinkIdLink" + e.id).html(iconCarregando); /*atualiza a div somente do link*/
                            }
                            else
                            {
                                if (idGrupo > 0)
                                {
                                    $("#divGrupoLinks" + idGrupo).html(iconCarregando);
                                }
                            }
                        },
                        complete: function() {
                            $(iconCarregando).remove();
                        },
                        success: function(data, textStatus){
                            atualizaDivDireita();
                            if (idGrupo == 0) /*link sem grupo*/
                            {
                                $("#divLinkIdLink" + e.id).hide('slow'); /*atualiza a div somente a div do link -  exclui o conteudo*/
                            }
                            else
                            {
                                if (idGrupo > 0) /*atualiza a div do grupo - chamda do servidor o grupo atualizado*/
                                {
                                    
                                    $.ajax({
                                        url     : 'funcoesAjax.php?action=obterListaDeGruposPorGrupoEUsuario&idGrupo=' + idGrupo,
                                        dataType: 'html',
                                        type    : 'GET',
                                        data    : 'html',
                                        timeout : 5000,
                                        beforeSend: function(){
                                            $("#divGrupoLinks" + idGrupo).html(iconCarregandoNivel2);
                                        },
                                        complete: function() {
                                            $(iconCarregandoNivel2).remove();
                                        },
                                        success: function(data, textStatus){
                                            $("#divGrupoLinks" + idGrupo).html(data).show('slow'); //exibe resposta
                                        },
                                        error: function(xhr, er) {
                                            if (er == 'timeout') {
                                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                                            }
                                            else
                                            {
                                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                                            }
                                        }
                                    });
                                }
                            }
                        },
                        error: function(xhr, er) {
                            if (er == 'timeout') {
                                $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                            }
                            else
                            {
                                $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                            }
                        }
                    });
        }
    }

    function editarLink(e)
    {
        $.ajax({
            url     : 'formHiperLinkUsuario.php?idHiperLinkUsuario='+e.id,
            dataType: 'html',
            type    : 'GET',
            timeout : 10000,
            beforeSend: function(){
                $('#divConteudoCentro').html(iconCarregando);
            },
            complete: function() {
                $(iconCarregando).remove();
            },
            success: function(data, textStatus){
                $('#divConteudoCentro').html('<p>' + data + '<p>');
            },
            error: function(xhr, er) {
                if (er == 'timeout') {
                    $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                }
                else
                {
                    $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')

                }
            }
        });
    }
    
    function editarGrupoHiperLinksUsuario(e)
    {
        $.ajax({
            url     : 'formGruposHiperLinksUsuario.php?idGrupoHiperLinksUsuario='+e.id,
            dataType: 'html',
            type    : 'GET',
            timeout : 10000,
            beforeSend: function(){
                $('#divConteudoCentro').html(iconCarregando);
            },
            complete: function() {
                $(iconCarregando).remove();
            },
            success: function(data, textStatus){
                $('#divConteudoCentro').html('<p>' + data + '<p>');
            },
            error: function(xhr, er) {
                if (er == 'timeout') {
                    $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                }
                else
                {
                    $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')

                }
            }
        });
    }
   
    /*CHAMA OS TOP LINKS*/
    function topLinks(){
            $('#divConteudoCentro').empty();
            $.ajax({
                url     : 'funcoesAjax.php?action=obterTopLinks',
                dataType: 'html',
                type    : 'GET',
                data    : 'html',
                timeout : 10000,
                beforeSend: function(){
                    $('#divConteudoCentro').html(iconCarregando);
                },
                complete: function() {
                    $(iconCarregando).remove();
                },
                success: function(data, textStatus){
                    $('#divConteudoCentro').hide().html(data).show('slow'); //exibe resposta
                },
                error: function(xhr, er) {
                    if (er == 'timeout') {
                        $('#divConteudoCentro').html('<p>Sorry, try again... Desculpe-nos, tente novamente...</p>')
                    }
                    else
                    {
                        $('#divConteudoCentro').html('<p>Erro: ' + xhr.status + ' - ' + xhr.statusText + '<br />Tipo do erro: ' + er + '</p>')
                    }
                }
            });
    }
