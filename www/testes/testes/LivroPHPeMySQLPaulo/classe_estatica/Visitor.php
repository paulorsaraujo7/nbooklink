<?php

session_start();

//CLASSE QUE TEM MEMBRO ESTÁTICO
class Visitor {

    private static $visitors = 0;

    function __construct() {
        self::$visitors++;
    }

    static function getVisitors() {
        return self::$visitors;
    }

}

/* Instância da classe */
$visits = new Visitor();
echo Visitor::getVisitors() . "<br>";

$visits2 = new Visitor();
echo Visitor::getVisitors() . "<br>";

$_SESSION['ce'] = $visits;
?>
