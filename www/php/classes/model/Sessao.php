<?php

/*
 * 1 - ARMAZENA A ENTIDADE SESSAO que esta no BD na tabela 'sessoes'
 */

/**
 * Esta classe modela o objeto Sessao que armazenara a sessao de um usuario.
 * Ver documentacao do caso de uso.
 *
 * @author Paulo Ricardo
 * Criada em 04/08/2012
 *  
 */
class Sessao {

    //PERSISTEM
    private $PHPSESSID                  = null;  //ID DA SESSAO PHP
    private $idUsuario                  = null;  //ID DO USUARIO DONO DA SESSAO
    private $ultimoIPUtilizado          = null;  //ULTIMO IP UTLIZADO ANTES DO LOGOUT
    private $ultimoIdiomaSelecionado    = null;  //ULTIMO IDIOMA SELECIONADO ANTES DE FAZER O LOGOUT
    private $hashUltimaSessao = "";           //ARMAZENA UM HASH QUE IDENTIFICA A ULTIMA SESSAO DO USUÁRIO
    private $expiraEm;
    private $ultimoLogin;
    private $ultimoLogout;
    
    public function __construct() {
        $this->expiraEm       = new NBLDateTime(NULL);
        $this->ultimoLogin    = new NBLDateTime(NULL);
        $this->ultimoLogout   = new NBLDateTime(NULL);
    }

    public function getPHPSESSID() {
        return $this->PHPSESSID;
    }

    public function setPHPSESSID($PHPSESSID) {
        $this->PHPSESSID = $PHPSESSID;
    }

    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function getExpiraEm() {
        return $this->expiraEm;
    }

    public function setExpiraEm(NBLDateTime $expiraEm) {
        $this->expiraEm = $expiraEm;
    }

    public function getUltimoLogin() {
        return $this->ultimoLogin;
    }

    public function setUltimoLogin(NBLDateTime $ultimoLogin) {
        $this->ultimoLogin = $ultimoLogin;
    }

    public function getUltimoLogout() {
        return $this->ultimoLogout;
    }

    public function setUltimoLogout(NBLDateTime $ultimoLogout) {
        $this->ultimoLogout = $ultimoLogout;
    }

    public function getUltimoIPUtilizado() {
        return $this->ultimoIPUtilizado;
    }

    public function setUltimoIPUtilizado($ultimoIPUtilizado) {
        $this->ultimoIPUtilizado = $ultimoIPUtilizado;
    }

    public function getUltimoIdiomaSelecionado() {
        return $this->ultimoIdiomaSelecionado;
    }

    public function setUltimoIdiomaSelecionado($ultimoIdiomaSelecionado) {
        $this->ultimoIdiomaSelecionado = $ultimoIdiomaSelecionado;
    }

    
    public function getHashUltimaSessao() {
        return $this->hashUltimaSessao;
    }

    public function setHashUltimaSessao($hashUltimaSessao) {
        $this->hashUltimaSessao = $hashUltimaSessao;
    }
}
?>
