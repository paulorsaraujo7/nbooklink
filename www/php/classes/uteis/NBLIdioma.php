<?php
/*
 * @Description: Responsável por controlar as funções de internacionalização.
 * @Autor: Paulo Ricardo
 * @Data: 17/11/2012
 * @Obs: A immplementacao dessa classe tem a melhoria em relacao a implementacao
 *       anterior pois a sessao eh carregada a medida em que vao sendo requisitados
 *       os textos ao contrario da implementacao antiga onde todo o BD de elementos
 *       iam pra sessao consumindo memoria RAM do servidor.
 *
 */
class NBLIdioma {
    
    /*
     * @Description: Recupera uma mensagem de interacao com usuario respeitando
     * o idioma selecionado e armazena a mensagem na sessao do usuario.
     * A vantagem eh a sessao eh preenchida a medida em que vao sendo feitas as requisiscoes de msgs
     * @Data de criacao: 22-04-2013 - segunda-feira
     * @in             : $elemento e um identificador unico do elemento no BD. Ex: tituloFormGrupoLink
     * @out            : retorna uma string que sera utilizada por quem chamou.
     * Obs: O funcionamento dessa funcao eh garantido pelo fato de quando o idioma eh modificado, as
     *      variaveis de sessao que armazenam dados sobre os elementos sao destruidos sendo somente 
     *      redefinidas as variaveis $_SESSION['_IDIOMA_']['idiomaSelecionado'] e 
     *                               $_SESSION['_IDIOMA_']['idiomaSelecionadoAnterior']
     */
    public static function getTextoPorIdElemento($idElemento)
    {
        //O TEXTO SOLICITADO NAO ESTA NA SESSAO
        if ( !isset($_SESSION['_IDIOMA_']["$idElemento"]['conteudo']) ) {
            $idiomaSelecionado = $_SESSION['_IDIOMA_']['idiomaSelecionado']; //QUA O IDIOMA SELECIONADO
            $conteudo          = "";
            $Elemento          = ElementoDAO::obterPorIdElemento($idElemento);//BUSCA MSG NO BD
            
            if ($idiomaSelecionado == "PT_BR") $conteudo = $Elemento->getConteudoPT_BR ();
            if ($idiomaSelecionado == "EN_US") $conteudo = $Elemento->getConteudoEN_US ();
            $_SESSION['_IDIOMA_']["$idElemento"]['conteudo'] = $conteudo;
        }
        return utf8_encode($_SESSION['_IDIOMA_']["$idElemento"]['conteudo']);
    }

    
    /*
     * @Description: Atende a uma requisicao para mudanca de idioma. Se o selecionado for diferente do que ja
     * estava, entao destroe a sessao que sera populada aos poucos pela funcao getTextoPorIdElemento
     * @Data de criacao: 22-04-2013 - segunda-feira
     * @in: $novoIdioma: eh o novo idioma selecionado.
     */
    public static function mudarIdioma($novoIdioma)
    {
        if ( !isset($_SESSION['_IDIOMA_']) ) //QUANDO O IDIOMA N TA NEM DEFINIDO
            $_SESSION['_IDIOMA_']['idiomaSelecionado'] = $novoIdioma;
        
        if ($novoIdioma != $_SESSION['_IDIOMA_']['idiomaSelecionado']) {
            unset($_SESSION['_IDIOMA_']); //Isso Destroe tudo sobre o idioma
            $_SESSION['_IDIOMA_']['idiomaSelecionado'] = $novoIdioma; //Recria com o novo idioma
            //GRAVA UM COOKIE COM O IDIOMA SELECIONADO
            setcookie("i", $novoIdioma, time()+3600*24*14); //o cookie vale por 14 dias
        }
        header("Location:index.php"); /*REDIRECIONA PARA INDEX*/
    }
}
