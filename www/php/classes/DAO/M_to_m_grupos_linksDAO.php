<?php
/**
 * @author Paulo Ricardo Santos e Araújo.
 * Criada em 05/05/2013.
 */
class M_to_m_grupos_linksDAO extends MainDAO {
    const objModel = "M_to_m_grupos_links";

    /*Obtem uma entidade pelo id do hiperlink de usuario
     * @IN: id da entidade;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorIdHiperLinkUsuario($idHiperLinkUsuario) {
        $id = (int) $idHiperLinkUsuario;
        $query = "SELECT * FROM m_to_m_grupos_links WHERE idHiperLinkUsuario = " . " '$idHiperLinkUsuario' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }
    
    /*Obtem uma entidade pelo id do hiperlink de usuario
     * @IN: id da entidade;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorIdGrupoHiperLinksUsuario($idGrupoHiperLinksUsuario) {
        $id = (int) $idGrupoHiperLinksUsuario;
        $query = "SELECT * FROM m_to_m_grupos_linksDAO WHERE idGrupoHiperLinksUsuario = " . " '$idGrupoHiperLinksUsuario' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }
    
    
    /*EXCLUI UM LINK DA TABELA MUITOS PARA MUITOS 
     * @IN: id do link a ser excluido;
     * @OUT: True se foi excluído ou false se houve erro
     * 
     * USADA POR (apenas para exemplo): HiperLinksUsuarioDAO::excluirUmaOcorrencia() 
     * para excluir de apenas um grupo um link que pode pertencer a varios
     */
    public static function excluiPorIdLinkEIdGrupo($idLink, $idGrupo) {
        $idLink = (int) $idLink;
        $query = "DELETE FROM m_to_m_grupos_links WHERE 
                    idHiperLinkUsuario = $idLink AND idGrupoHiperLinksUsuario = $idGrupo";
        return parent::query($query);
    }

    
    /*EXCLUI TODAS AS ENTRADAS DA TABELA QUE POSSUAM O ID DO LINK PASSADO.
     * @IN: id do link a ser excluido;
     * @OUT: True se foi excluído ou false se houve erro
     * 
     * USADA POR (apenas para exemplo): HiperLinkUsuarioController no metodo update.
     */
    public static function excluiPorIdLink($idLink) {
        $idLink = (int) $idLink;
        $query = "DELETE FROM m_to_m_grupos_links WHERE 
                    idHiperLinkUsuario = $idLink";
        return parent::query($query);
    }
    
    
    
        /*ESTE METODO N POSSUI ALTERAR POIS EH MAIS FACIL APAGAR AS ENTRADAS PELO ID DE UM LINK E INCLUI-LAS NOVAMENTE
         * A CHAVE PRIMARIA EH AUTO-INCREMETO
         */
        public static function persiste(M_to_m_grupos_links $M_to_m_grupos_links) {
            $idHiperLinkUsuario                = $M_to_m_grupos_links->getIdHiperLinkUsuario();
            $idGrupoHiperLinksUsuario          = $M_to_m_grupos_links->getIdGrupoHiperLinksUsuario();
            
            $query = "
                    INSERT INTO `nbooklin_nbl`.`m_to_m_grupos_links` 
                    (
                        `idHiperLinkUsuario`,
                        `idGrupoHiperLinksUsuario`
                     )
                     VALUES 
                     (
                        '$idHiperLinkUsuario',
                        '$idGrupoHiperLinksUsuario'
                     )
                ";
            
           
            if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
                return TRUE;
        }
}