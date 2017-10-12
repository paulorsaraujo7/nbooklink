<?php
/**
 * @Description: Classe que encapsula a visualização do topo.
 * @author Paulo Ricardo
 * @Data de criação: 08/11/2012
 * 
 * 
 * HISTORICO DE ATUALIZACOES:
 *  04/02/13 - Unificando o codigo para exibir o menu de navegacao
 * 
 * 
 */
class ViewTopo extends ViewBase {
    
    public function display()
    {
        //Perceber que a divTudo eh aberta aqui pois toda pg tera um topo, ela sera fechada no rodape pois toda pagina tera um rodape
          ?>
            <div id="divTopo">
                <a href="index.php"><img id="imgLogoMarca" src="http://www.nbooklink.com/imagens/imgLogoMarca.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleImgLogoMarca'); ?> " alt="NBookLink" ></a>
                <?php $sourceSlogan = $_SESSION['_IDIOMA_']['idiomaSelecionado']; ?>
                <img style="position: absolute; left: 250px; top:45px;" src="http://www.nbooklink.com/imagens/slogan<?php echo $sourceSlogan ?>.png"          
                     width="188" height="54" alt="<?php echo NBLIdioma::getTextoPorIdElemento('sloganNBL');?>"/>

          <?php      
            //O USUARIO NAO ESTA LOGADO OU NAO ESTA AUTENTICADO
           if (!parent::isLogado()) {
           ?>
                
                
            <div id="divFormLogin">
                       <form id="formLogin" action="recebeFormLogin.php" method="post" autocomplete="off">
                                    <label id="labelFormLoginEmail" for="inputFormLoginEmail" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormLoginEmail'); ?>"  > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormLoginEmail'); ?> </label>
                                    <input id="inputFormLoginEmail" name="email" type="text" maxlength="100" tabindex="0" >

                                    <label id="labelFormLoginSenha" for="inputFormLoginSenha" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelFormLoginSenha'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelFormLoginSenha'); ?> </label>
                                    <input id="inputFormLoginSenha" name="senha" type="password" maxlength="45">

                                    <input id="checkManterConectado" name="manterConectado" class="checkbox" type="checkbox" >
                                    <label id="labelCheckMaterConectado" for="checkManterConectado" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLabelCheckManterConectado'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('labelCheckManterConectado'); ?></label>

                                    <a id="linkRecuperarSenha" href="#" onclick="javascritp:alert('In a bit time / Em breve')" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLinkRecuperarSenha'); ?>" > <?php echo NBLIdioma::getTextoPorIdElemento('linkRecuperarSenha'); ?> </a>

                                    <input id="inputSubmitFormLogin" class="clicavel" type="submit" value="<?php echo NBLIdioma::getTextoPorIdElemento('inputSubmitFormLogin'); ?> ">
                      </form>
             </div>

             <?php//A div de bandeiras somente aparece no topo quando o usuario nao esta logado ?>
                <span id="spanBandeiras">
                     <a id="linkIdiomaPortuguesBrasil" href="index.php?i=PT_BR">
                         <img src="imagens/bandeiraBrasil.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLinkIdiomaPortuguesBrasil'); ?>" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altLinkIdiomaPortuguesBrasil'); ?> " >
                     </a>
                     <a id="linkIdiomaInglesEUA" href="index.php?i=EN_US">
                         <img src="imagens/bandeiraEUA.png" title="<?php echo NBLIdioma::getTextoPorIdElemento('titleLinkIdiomaInglesEUA'); ?>" alt="<?php echo NBLIdioma::getTextoPorIdElemento('altLinkIdiomaInglesUS'); ?>" >
                     </a>
             </span>
        <?php    
        }
        else { //USUARIO LOGADO E AUTENTICADO
             $Usuario = unserialize($_SESSION['Usuario']);
        ?>

                <div id="informacoesUsuarioTopoLogado">
                    <?php echo NBLIdioma::getTextoPorIdElemento('labelSaudacao') . ", " . $Usuario->getNome();  ?><br>
                    <?php echo NBLIdioma::getTextoPorIdElemento('labelUltimoAcesso') . ": " . $Usuario->getSessao()->getUltimoLogin()->toStringExibir();  ?><br>
                    <?php echo NBLIdioma::getTextoPorIdElemento('labelNumeroDeLogins') . ": " . $Usuario->getNumeroDeLogins();  ?><br>
                    <a href="logout.php" > <?php echo NBLIdioma::getTextoPorIdElemento('linkSair'); ?> </a>
                </div>
                
        <?php   
        }
        
        //A proxima div que fecha eh da div topo
        ?>
            </div>
        <?php
    }
}
?>
