<?php
/*
 * @Description: Armazena um HiperLink, um endereco da web cadastrado por um usuario.
 * @Autor: Paulo Ricardo
 * @Data de criacao: 13/11/2012
 */

class HiperLink {
	private $idHiperLink               = null;
	private $url                       = "";
	private $totalAcessos              = 0;
        
        public function __construct() {
        }

        public function getIdHiperLink(){
		return $this->idHiperLink;
	}

	public function setIdHiperLink($idHiperLink){
                
		$this->idHiperLink = $idHiperLink;
	}

	public function getUrl(){
		return $this->url;
	}

	public function setUrl($url){
		$this->url = $url;
	}


	public function getTotalAcessos(){
		return $this->totalAcessos;
	}

	public function setTotalAcessos($totalAcessos){
		$this->totalAcessos = $totalAcessos;
	}

}

?>
