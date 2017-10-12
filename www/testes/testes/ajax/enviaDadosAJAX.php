<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">


        <link href="cssAJAX.css" rel="stylesheet" type="text/css"/>

        <script type="text/javascript" src="jquery-1.5.1.min.js"></script>        


        <!-- Script para o click do batão enviar. Mostra o resultado no container indicado.        -->
        <script>

            $(document).ready(function()
            {
                $('#botaoEnvia').click(
                
                function(){
                    $('#gifAJAX').removeClass('naoVisivel');
                    $('#gifAJAX').addClass('visivel');
                      
                        
                    var seuNome = $('input[name="nome"]').val();
                    var suaCidade = $('input[name="cidade"]').val();
                        
                    $.get('recebeDadosAJAX.php', 
                    { nome: seuNome, cidade: suaCidade }, 
                    function(data){
                        $('#divResposta').html(data);
                    },
                    'html');
                              

                    $('#gifAJAX').removeClass('visivel');
                    $('#gifAJAX').addClass('naoVisivel');
                    return false;
                });                
            });
        </script>


        <title>Teste de AJAX</title>
    </head>
    <body>

        <img id="gifAJAX" class="naoVisivel" src="gifAJAX.gif" alt="Carregando... PHP"></img>
        <img src="/imagens/respostaERRO.png"></img>


        <!-- Conteiner que armazena a resposta AJAX. O conteúdo é definido no JS       -->
        <div id="divResposta">

        </div>

        <form id="formEnvioAJAX" action="recebeDadosAJAX.php">
            <label for="nome">nome: </label>
            <input id="nome" name="nome"></input>
            <br>
            <br>
            <label for="cidade">cidade: </label>
            <input id="cidade" name="cidade"></input>

            <button type="button" id="botaoEnvia">Enviar</button> 
        </form>



    </body>
</html>
