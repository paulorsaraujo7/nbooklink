<?php

/*
 * @Description: Armazena um HiperLink, um endereco da web cadastrado por um usuario.
 * @Autor: Paulo Ricardo
 * @Data de criacao: 13/11/2012
 */
class Elemento {
    private $idElemento	  	 = NULL;
    private $conteudoPT_BR       = "";
    private $conteudoEN_US	 = "";
    private $ordemCriacao 	 = 0;	 

    public function getIdElemento(){
            return $this->idElemento;
    }

    public function setIdElemento($idElemento){
            $this->idElemento = $idElemento;
    }

    public function getConteudoPT_BR(){
            return $this->conteudoPT_BR;
    }

    public function setConteudoPT_BR($conteudoPT_BR){
            $this->conteudoPT_BR = $conteudoPT_BR;
    }

    public function getConteudoEN_US(){
            return $this->conteudoEN_US;
    }

    public function setConteudoEN_US($conteudoEN_US){
            $this->conteudoEN_US = $conteudoEN_US;
    }

    public function getOrdemCriacao(){
            return $this->ordemCriacao();
    }

    public function setOrdemCriacao($ordemCriacao){
            $this->ordemCriacao = $ordemCriacao;
    }

}

?>
