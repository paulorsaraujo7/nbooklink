<?php

class Classe1 {

    private $num;

    public function __construct() {
        $this->num = 1;
    }

    public function getNum() {
        return $this->num;
    }

    public function setNum(int $num) {
        $this->num = $num;
    }

}

?>
