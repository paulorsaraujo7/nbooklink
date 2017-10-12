<?php

class estatica {

    public static $s = 0;

    public static function soma() {
        self::$s++;
        return self::$s;
    }

}

?>
