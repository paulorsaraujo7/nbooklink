<?php
require_once 'idioma.php'; //PRIMEIRO QUE TUDO CARREGA O IDIOMA DO SITIO
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Página de teste</title>
    </head>
    <body>
        <a href="idioma.php?i=EN_US"> <?php echo $_SESSION['_IDIOMA_']['altLinkIdiomaInglesUS']['conteudo'] ?>        </a>
        <a href="idioma.php?i=PT_BR"> <?php echo $_SESSION['_IDIOMA_']['altLinkIdiomaPortuguesBrasil']['conteudo'] ?> </a>

        <?php
        ?>
    </body>
</html>
