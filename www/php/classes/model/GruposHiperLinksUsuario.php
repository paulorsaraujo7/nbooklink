<?php
class GruposHiperLinksUsuario {

    //CAMPOS DE CLASSE
    private $idGrupoHiperLinksUsuario = null;
    private $nome = null;
    private $descricao = null;
    private $idUsuario = null;
    
    

    //CRIA UM USUÃ�RIO COM CAMPOS TODOS NULL

    public function __construct() {

        $this->idGrupoHiperLinksUsuario = 0;
        $this->nome = "";
        $this->descricao = "";
        $this->idUsuario = "";
    }
    
    
	public function getIdGrupoHiperLinksUsuario(){
		return $this->idGrupoHiperLinksUsuario;
	}

	public function setIdGrupoHiperLinksUsuario($idGrupoHiperLinksUsuario){
		$this->idGrupoHiperLinksUsuario = $idGrupoHiperLinksUsuario;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getIdUsuario(){
		return $this->idUsuario;
	}

	public function setIdUsuario($idUsuario){
		$this->idUsuario = $idUsuario;
	}

}

?>
