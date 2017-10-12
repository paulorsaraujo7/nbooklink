<?php

/*
 * SE PROPOE A ARMAZENAR ELEMENTOS DE INTERNACIONALIZACAO DO SITIO
 * 
 * PRINCIPAIS PROPÓSITOS:
 * 1 - CODIGO TORNAR-SE ESCALAVEL
 * 2 - PADRAO LAZY LOAD
 * 3 - CORRIGIR AS INCONSISTENCIAS DA CLASSE ANTIGA 
 * 
 * IMPLEMENTA O PADRAO SINGLETON
 *

 * @author Paulo Ricardo
 * CRIADA EM: 18/07/2012
 * 
 */

class MIdioma {

    public static $ms1;
    public static $ms2;
    public static $ms3;
    public static $idioma;
    public static $idiomaAnterior;

    public static function carregaMensagens($idioma) { //SO CARREGA SE AINDA NÃO TIVER SIDO OU FOR DIFERENTE DO ANTERIOR.
        self::$idioma = $idioma; //ja deixo definido
        if (self::$idioma != self::$idiomaAnterior) {
            echo "passei pelo if principal";
            if (self::$idioma == 'pt_br') {
                echo 'passei pelo if do pt_br';
                self::$ms1 = "olá 1";
                self::$ms2 = "olá 2";
                self::$ms3 = "olá 3";
            } else {
                echo 'passei pelo if do en_us';
                self::$ms1 = "hello 1";
                self::$ms2 = "hello 2";
                self::$ms3 = "hello 3";
            }
            self::$idiomaAnterior = self::$idioma;
        }
    }

}

?>
