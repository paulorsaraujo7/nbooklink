<?php

/*
 * Criação em 23 de maio de 2012 às 05:41
 *
 */

/**
 * Implementa o padrão de projeto Singleton apenas para efeito de exemplo.
 *
 * @author Paulo Ricardo
 * 
 * 
 * Wikipedia:
 * "
 * Quando você necessita de somente uma instância da classe, por exemplo, 
 * a conexão com banco de dados, vamos supor que você terá que chamar diversas 
 * vezes a conexão com o banco de dados em um código na mesma execução, 
 * se você instanciar toda vez a classe de banco, haverá grande 
 * perda de desempenho, assim usando o padrão singleton, 
 * é garantida que nesta execução será instânciada a classe somente uma vez. 
 * Lembrando que este pattern é considerado por muitos desenvolvedores 
 * um anti-pattern, então, cuidado onde for utilizá-lo
 * 
 * DIAGRAMA UML NA PASTA
 * "
 */
class Singleton {

    private static $_instance;

    // Evita que a classe seja instanciada publicamente
    private function __construct() {
        
    }

    // Evita que a classe seja clonada
    private function __clone() {
        
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) { // Testa se há instância definifa na propriedade, caso sim, a classe não será instanciada novamente.
            self::$_instance = new self; // o new self cria uma instância da própria classe à própria classe.
        }
        return self::$_instance;
    }

}

?>
