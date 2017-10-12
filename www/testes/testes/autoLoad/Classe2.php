<?php

class Classe2 {

    private $pal;

    public function __construct() {
        $this->pal = "Oi";
    }

    public function getNum() {
        return $this->pal;
    }

    public function setNum(int $pal) {
        $this->pal = $pal;
    }

}

?>
