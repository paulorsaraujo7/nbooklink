<?php

class classeTeste {

    public $t;
    public static $s = 0;

    public function __construct() {
        $this->t = "oi";
    }

    public static function soma() {
        self::$s = self::$s + 1;
    }

}

?>
