<?php
/**
 * @Description: Classe que encapsula a visualização do rodape.
 * @author Paulo Ricardo
 * @Data de criação: 08/11/2012
 */
class ViewRodape extends ViewBase {
    
    public function display()
    { //Perceber que a ultima div fechada eh da divTudo que foi aberta na ViewTopo
    ?>
            <div id="clear" style="clear: both; min-height: 25px; ">
                
            </div>
            <div id="divRodape">
                NBookLink® - <?php echo date("Y") ?>
            </div>
    <?php        
    }
}
?>
