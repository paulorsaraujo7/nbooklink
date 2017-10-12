<?php 
require_once '../../../principais.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <script>

                $(function() {
                    $( "#tabs" ).tabs({
                        beforeLoad: function( event, ui ) {
                            ui.jqXHR.error(function() {
                            ui.panel.html(
                            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                            "If this wouldn't be a demo." );
                        });
                        }
                    });
                });
        </script>
  
    </head>
    <body>
                          <div id="tabs">
                            <ul>
                                <li><a href="php/classes/view/ViewFormCadUsuario.php"><?php echo NBLIdioma::getTextoPorIdElemento('tituloAbaAlterarCadastro'); ?></a></li>
<!--                                <li><a href="php/classes/view/ViewFormUsuarioOutrosDados.php"</a></li>-->
<!--                            <li><a href="ajax/content2.html">Tab 2</a></li>
                                <li><a href="ajax/content4-broken.php">Tab 4 (broken)</a></li> -->
                            </ul>
                        </div>
    </body>
</html>
