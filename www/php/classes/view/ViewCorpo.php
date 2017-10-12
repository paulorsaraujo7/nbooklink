<?php

/**
 * @Description: Responsável por desenhar o conteúdo do corpo da página.
 * @author: Paulo Ricardo
 * @Data de Criação: 08/11/2012
 */
class ViewCorpo extends ViewBase {
    public function display() {
        if (!parent::isLogado()){
        ?>
        <?php    
        }
        //USUÁRIO LOGADO E AUTENTICADO - redireciona para pagina de logado
        else {
            
        }
    }
}
?>
