<?php

class MIdioma {

    public $ms1;
    public $ms2;
    public $ms3;
    public $idioma;
    public $idiomaAnterior;

    public function carregaMensagens($idioma) { //SO CARREGA SE AINDA NÃO TIVER SIDO OU FOR DIFERENTE DO ANTERIOR.
        if ($this->$idioma == 'pt_br') {
            echo 'passei pelo if do pt_br';
            $this->$ms1 = "olá 1";
            $this->$ms2 = "olá 2";
            $this->$ms3 = "olá 3";
        } else {
            echo 'passei pelo if do en_us';
            $this->$ms1 = "hello 1";
            $this->$ms2 = "hello 2";
            $this->$ms3 = "hello 3";
        }
    }

}

?>
