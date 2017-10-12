<?php
/*
 * @Description: Armazena um HiperLink, um endereco da web cadastrado por um usuario.
 * @Autor: Paulo Ricardo
 * @Data de criacao: 13/11/2012
 */

class HiperLinksUsuario {
    private $idHiperLinkUsuario	  	 = NULL;
    private $ultimoAcesso                = NULL;
    private $dataCadastro                = NULL;
    private $contadorAcessos	  	 = 0;
    private $nivelCompartilhamento  	 = 0;	 
    private $nota                        = 0;
    private $nome                        = "";
    private $descricao                   = ""; 
    private $contadorImportacao          = 0;
    private $idHiperLink                 = NULL;  	 
    private $idUsuario                   = NULL;
    
    
    public function __construct() {
        $this->ultimoAcesso = new NBLDateTime(NULL);
        $this->dataCadastro = new NBLDateTime(NULL);
    }

    public function getIdHiperLinkUsuario(){
            return $this->idHiperLinkUsuario;
    }

    public function setIdHiperLinkUsuario($idHiperLinkUsuario){
            $this->idHiperLinkUsuario = $idHiperLinkUsuario;
    }


    public function getDataCadastro(){
            return $this->dataCadastro;
    }

    public function setDataCadastro(NBLDateTime $dataCadastro){
            $this->dataCadastro = $dataCadastro;
    }
    
    
    public function getUltimoAcesso(){
            return $this->ultimoAcesso;
    }

    public function setUltimoAcesso(NBLDateTime $ultimoAcesso){
            $this->ultimoAcesso = $ultimoAcesso;
    }
    
    public function getContadorAcessos(){
            return $this->contadorAcessos;
    }

    public function setContadorAcessos($contadorAcessos){
            $this->contadorAcessos = $contadorAcessos;
    }

    public function getNivelCompartilhamento(){
            return $this->nivelCompartilhamento;
    }

    public function setNivelCompartilhamento($nivelCompartilhamento){
            $this->nivelCompartilhamento = $nivelCompartilhamento;
    }

    public function getNota(){
            return $this->nota;
    }

    public function setNota($nota){
            $this->nota = $nota;
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

    public function getContadorImportacao(){
            return $this->contadorImportacao;
    }

    public function setContadorImportacao($contadorImportacao){
            $this->contadorImportacao = $contadorImportacao;
    }

    public function getIdHiperLink(){
            return $this->idHiperLink;
    }

    public function setIdHiperLink($idHiperLink){
            $this->idHiperLink = $idHiperLink;
    }

    public function getIdUsuario(){
            return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario){
            $this->idUsuario = $idUsuario;
    }
}
?>
