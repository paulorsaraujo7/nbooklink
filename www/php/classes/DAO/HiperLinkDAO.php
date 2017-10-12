<?php
/**
 * @author Paulo Ricardo Santos e Araújo.
 * Criada em 18/04/2013.
 */
class HiperLinkDAO extends MainDAO {
    const objModel = "HiperLink";

    /*Obtem uma entidade pelo id
     * @IN: id da entidade;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorId($id) {
        $id = (int) $id;
        $query = "SELECT * FROM hiperlinks WHERE idHiperLink = " . " '$id' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }

    /*Obtem uma entidade pelo url
     * @IN: url do link;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorURL($URL) {
        $query = "SELECT * FROM hiperlinks WHERE url = " . " '$URL' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }
    
    
    /*
     * Verifica se existe registro na tabela de HiperLinks para uma dada url;
     * @url : a url a ser pesquisada.
     * @out : o id do hiperlink se existe e FALSE, caso nao exista.
     * 
     */
    public static function obterIdPorUrl($URL)
    {
        $query = "SELECT idHiperLink FROM hiperlinks WHERE url = " . " '$URL' ";
        $temp  = MainDAO::query($query);   //RETORNA UM RESOURSE MYSQL MESMO QUE N EXISTA;
        $temp  = mysql_fetch_array($temp); //SE N EXISTE, RETORNA FALSE, SE SIM, RETORNA O ARRAY QUE EH TRUE
        if ( $temp ) { //RETORNOU UM ID NO ARRAY
            $temp  = (int) $temp['idHiperLink'];
            return $temp;
        }
        else {
            return FALSE;
        }
    }



    public static function persiste(HiperLink $HiperLink) {
            
            $idHiperLink                = $HiperLink->getIdHiperLink();
            $totalAcessos               = $HiperLink->getTotalAcessos();
            $url                        = $HiperLink->getUrl();
            
            $query = "";
            if ($idHiperLink == 0 || $idHiperLink == NULL) { //NAO EXISTE USUARIO E É UMA INSERÇÃO.
                $query = "

                    INSERT INTO `nbooklin_nbl`.`hiperlinks` 
                    (
                        `idHiperLink`,
                        `totalAcessos`,
                        `url`
                     )
                     VALUES 
                     (
                        '$idHiperLink',
                        '$totalAcessos',
                        '$url'
                     )
                ";
            } else { //EXISTE ENTRADA NO BD E ATUALIZA (OS CAMPOS NULOS DEVEM IR SEM ASPAS
                $query = "

                        UPDATE `nbooklin_nbl`.`hiperlinks` SET 
                        idHiperLink               = '$idHiperLink',
                        totalAcessos              = '$totalAcessos',
                        url                       = '$url'
                        WHERE idHiperLink  = '$idHiperLink'
                ";
            }
            if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
                return TRUE;
        }
}