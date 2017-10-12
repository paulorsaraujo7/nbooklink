<?php
/**
 * @author Paulo Ricardo Santos e Araújo.
 * Criada em 22-04-2013.
 */
class ElementoDAO extends MainDAO {
    const objModel = "Elemento";

    /*Obtem uma entidade pelo id
     * @IN: id da entidade;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorIdElemento($id) {
        $id = (string) $id;
        $query = "SELECT * FROM elementos WHERE idElemento = " . " '$id' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }
}