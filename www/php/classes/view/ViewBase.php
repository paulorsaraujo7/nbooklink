<?php
/*@Description:     Interface a ser assinada por todos da visualização.
 *@Data de Criação: 08/11/2012  
 *@Autor:           Paulo Ricardo
 */
interface IViewBase {
    /*@Description: Verifica se o usuário esta logado ou não.
     *@Out: Booleano indicando se está logado ou não. 
     */
    public function isLogado(); 
    
    /*
     * @Description: Função para impressão do código da visualização.
     */
    public function display();
    
    public static function retornaArquivosDeLigacoes();
            

}

abstract class ViewBase implements IViewBase
{
    /*@Description: Verifica se o usário está logado e a sessão é válida.
     *@Out:         Booleano indicado 
     * 
     */
    public function isLogado()
    {
         if ( isset($_SESSION['_SESSAO_']['autenticado']) && $_SESSION['_SESSAO_']['autenticado'] == TRUE ) {
             return TRUE;
         }
         else {
             return FALSE;
         }
    }
    
    /*Retorna os arquivos de ligacoes utilizados em todas as paginas*/
    public static function retornaArquivosDeLigacoes()
    {
        return <<<EOF
        <link   href="http://www.nbooklink.com/css/principal.css"      rel="stylesheet" type="text/css"/>
        <link   href="http://www.nbooklink.com/css/topo.css"           rel="stylesheet" type="text/css"/>
        <link   href="http://www.nbooklink.com/css/ajax.css"           rel="stylesheet" type="text/css"/>

        <link rel="stylesheet" href="http://www.nbooklink.com/js/jquery-ui-1.10.1.custom/css/nbooklink/jquery-ui-1.10.1.custom.css" />

        <script src="http://www.nbooklink.com/js/jquery-ui-1.10.1.custom/js/jquery-1.9.1.js"></script>
        <script src="http://www.nbooklink.com/js/jquery-ui-1.10.1.custom/js/jquery-ui-1.10.1.custom.min.js"></script>
EOF;
    }
    
    public static function retornaGifAjax()
    {
        $msgAjaxCarregando = NBLIdioma::getTextoPorIdElemento('msgAjaxCarregando');
        return " '<img src=\"imagens/imgGifAjax.png\" class=\"icon\" /> <span class=\"destaque\">$msgAjaxCarregando...</span>' ";
    }




}