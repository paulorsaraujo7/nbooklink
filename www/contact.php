<?php 
require_once 'principais.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
        <link rel="shortcut icon" href="favicon.gif">
        
        
        <link  href="css/contact.css" rel="stylesheet" type="text/css"/>
        <title><?php echo NBLIdioma::getTextoPorIdElemento('tituloPaginaEntrarEmContato'); ?></title>
        <?php
            $c = new ViewContact(); 
            echo ViewBase::retornaArquivosDeLigacoes();
           
        ?>
        <script type="text/javascript" src="js/contact.js"></script>

        <script type="text/javascript">
            
        $(document).ready(function () {
                
                
                // PRIMEIRO ELEMENTO A RECEBER O FOCO E O CAMPO EMAIL DO LOGIN
                <?php 
                    /*VERIFICAR SE ESTA LOGADO
                      SE LOGADO: FOCO NA MSG
                      SE NAO LOGADO: FOCO NO NOME
                     */
                     if ( $c->isLogado() ) { //USUARIO LOGADO, O SCRIPT JS MUDA
                         
                ?>
                        $('#textAreaFormContatoMensagem').focus();
                <?php         
                     }
                     else {
                ?>
                        $('#inputFormContatoNome').focus();
                <?php         
                     }
                ?>
            
        </script>

        
        
    </head>
    <body>
        <div id="divTudo">
        <img id="imgGifAjax" src="imagens/imgGifAjax.png" alt="imgGifAjax"/>
        <?php
            $viewTopo = new ViewTopo();
            $viewTopo->display();
            
            
            //SE O USUARIO ESTIVER LOGADO ENTAO PREENCHE O NOME E O EMAIL DELE
            $nome = "";
            $email = "";
            $logado = FALSE;
            if ( isset($_SESSION['Usuario']) )
            {
                $Usuario = unserialize ($_SESSION['Usuario']);
                $nome = $Usuario->getNome();
                $email = $Usuario->getEmail();
                $logado = TRUE; //APENAS PARA COLOCAR OS CAMPOS DE NOME E EMAIL NO HTML COMO READONLY
            }
            $voltar = "index.php"; //O BOTAO VOLTAR REDIRECIONA EM PRINCIPIO PARA HOME    
            
            
            
            /*VERIFICA SE VEM ERRO DO CONTROLADOR
             * O LABEL RESPOSTA DEVE FICAR VERMELHO.
             */
            //EXIBE CODIGOS DE ERRO VINDO PARA A HOME
            if ( isset($_GET["erro"]) ) //SE FOR RETORNADO ERRO PARA HOME ENTAO EXIBA
                echo NBLIdioma::getTextoPorIdElemento($_GET['erro']);
            
            
        ?>


            <div id="divFormContato" class="divFormulario divComBorda">
                <label id="labelTituloFormContato" class="labelTituloForm" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelTituloFormEntrarEmContato'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelTituloFormEntrarEmContato'); ?> </label>
                
                <img id="imgEntrarEmContato" src="imagens/imgEntrarEmContato.png" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altImgEntrarEmContato'); ?>" >
                    <!--AS RESPOSTAS PRINCIPAIS VEM PARA ESSA DIV. -->

                <div id="divRespostaFormContato">
                    
<!--                O HTML ABAIXO SOMENTE SERÁ ENVIADO EM CASO DE ERRO
                    <img   id="imgDivRespostaFormContato" style="" src="imagens/respostaOK.png"></img>
                    <label id="labelDivRespostaFormContato"></label>-->
                </div>

                    <form name="formContato" method="POST" action="recebeFormContato.php">
                    <label id="labelFormContatoNome" class="labelEntradaForm"  for="inputFormContatoNome" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormEntrarEmContatoNome'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormEntrarEmContatoNome'); ?> </label>
                    <input id="inputFormContatoNome" name="nome" type="text" tabindex="0" value="<?php echo $nome ?>" <?php if ($logado) echo "readonly" ?>    >
                    <label id="labelRespostaFormContatoNome"></label>
                    
                    <label id="labelFormContatoEmail" class="labelEntradaForm"  for="inputFormContatoEmail" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormEntrarEmContatoEmail'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormEntrarEmContatoEmail'); ?> </label>
                    <input id="inputFormContatoEmail" name="email" type="text" tabindex="0" value="<?php echo $email ?>"  <?php if ($logado) echo "readonly" ?>   >
                    <label id="labelRespostaFormContatoEmail"></label>
                    
                    <span id="grupoRadiosFormContato">
                        <label class="labelEntradaForm" style="position: relative; top: 20px; float: left; clear: both;">
                        <input type="radio" name="tipoMensagem" value="s"  checked="checked" style="border-style: none; position: relative; top: 2px;"  />
                        <?php echo NBLIdioma::getTextoPorIdElemento('labelFormEntrarEmContatoSugestao'); ?>
                    </label>
                     
                    <label class="labelEntradaForm" style="position: relative; top: 20px; float: left; clear: both; ">
                        <input type="radio" name="tipoMensagem" value="e" style="border-style: none; position: relative; top: 2px;" />
                        <?php echo NBLIdioma::getTextoPorIdElemento('labelFormEntrarEmContatoInformarErro'); ?>
                    </label>
                    
                    <label class="labelEntradaForm" style="position: relative; top: 20px; float: left; clear: both;">
                        <input type="radio" name="tipoMensagem" value="c" style="border-style: none; position: relative; top: 2px;" />
                        <?php echo NBLIdioma::getTextoPorIdElemento('labelFormEntrarEmContatoCritica'); ?>
                    </label>
                    
                    <label class="labelEntradaForm" style="position: relative; top: 20px; float: left; clear: both;">
                        <input type="radio" name="tipoMensagem" value="o" style="border-style: none; position: relative; top: 2px;"  />
                        <?php echo NBLIdioma::getTextoPorIdElemento('labelFormEntrarEmContatoOutrosComentarios'); ?>
                    </label>
                    
                    </span>

                    
                    <label id="labelFormContatoMensagem" class="labelEntradaForm"  for="textAreaFormContatoMensagem" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormEntrarEmContatoMensagem'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormEntrarEmContatoMensagem'); ?> </label>
                    <textarea id="textAreaFormContatoMensagem" name="mensagem" type="" tabindex="0" ></textarea>
                    <label id="labelRespostaFormContatoMensagem"></label>
                    
                    
                    <!--Se o usuario estiver logado nao precisa da imagem de seguranca-->
                   <label id="labelFormContatoImgCAPTCHA" class="labelEntradaForm"  for="inputFormContatoImgCAPTCHA" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormCadUsuarioImgCAPTCHA'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormCadUsuarioImgCAPTCHA'); ?> </label>
                   <input id="inputFormContatoImgCAPTCHA" name="ImgCAPTCHA" type="text" tabindex="0">
                   <label id="labelRespostaFormContatoImgCAPTCHA"></label>
                   <img id="imgCAPTCHA" src="imagens/imgCAPTCHA.php"</img>
                   <a   id="imgRecarregaCAPTCHA" href="">
                        <img src="imagens/imgRecarregaCAPTCHA.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgRecarregaCAPTCHA'); ?>" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altImgRecarregaCAPTCHA'); ?>" >
                   </a>
                   <!--Esse bloco ficara invisivel se o usuario estiver logado-->
                   

                   
                   <input type="button" class="clicavel" value="<?php echo NBLIdioma::getTextoPorIdElemento('valueInputVoltar'); ?>" id="inputButtonFormContactVoltar" onclick="document.location='<?php echo $voltar ?>'">
                   <input id="inputSubmitFormContato" type="submit" class="clicavel" value="<?php echo NBLIdioma::getTextoPorIdElemento('valueInputSubmitFormEntrarEmContato'); ?> ">

                </form>
            </div>
            <div id="divExplicacaoFormEntraEmContato" class="divComBorda" >
                <img id="imgExplicacao" src="imagens/imgExplicacao.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgExplicacao');?>" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altImgExplicacao');?>" >
                <div id="divTextoExplicacaoEntrarEmContato"> 
                    <?php 
                        $array  = explode (".",NBLIdioma::getTextoPorIdElemento('msgExplicacaoFormEntrarEmContato')) ; //DIVIDE A STRING EM UM ARRAY SEPARANDO PELO PONTO
                        $string = implode(".</br>", $array); 
                        echo $string;
                    ?>
                </div>
            </div>
            
            <?php
            
            $viewRodape = new ViewRodape();
            $viewRodape->display();
        ?>
        </div>
    </body>
</html>
