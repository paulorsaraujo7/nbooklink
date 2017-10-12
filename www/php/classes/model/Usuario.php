<?php
/*
 * 1 - ARMAZENA A ENTIDADE USUARIO
 */

/**
 * Description of usuario
 *
 * @author Paulo Ricardo
 * Criada em 16/07/2012
 * 
 * 
 * Lembrar: criar gets e setters para os campos de entidade.
 */
class Usuario {

    //CAMPOS DE CLASSE
    private $idUsuario;
    private $email;
    private $nome;
    private $login;
    private $senha;
    private $dataCadastro;
    private $temFoto; //ARMAZENA O CAMINHO PRINCIPAL DA FOTO DO PERFIL
    private $totalVisitas; //ARMAZENA O TOTAL DE VISISTAS NO PERFIL
    private $mensagemInicial;
    private $genero; //MASCULINO OU FEMININO;
    private $anoNascimento;
    private $mesNascimento;
    private $diaNascimento;
    private $numeroDeLogins; // CONTADOR DO NÚMERO DE LOGINS FEITO PELO USUÁRIO.

    private $Sessao; //A SESSAO QUE ELE POSSA TER ARMAZENADA NO BD
    private $hashIdUsuario;  // ARMAZENA O HASH DO ID DO USUARIO.

    //CRIA UM USUÃ�RIO COM CAMPOS TODOS NULL

    public function __construct() {
        $this->idUsuario = 0;
        $this->email = "";
        $this->nome = "";
        $this->login = "";
        $this->senha = "";
        $this->dataCadastro = new NBLDateTime(NULL);
        $this->temFoto = "";
        $this->totalVisitas = 0;
        $this->mensagemInicial = "";
        $this->genero = "";
        $this->anoNascimento = 0;
        $this->mesNascimento = 0;
        $this->diaNascimento = 0;
        $this->Sessao = NULL;
        $this->numeroDeLogins = 0;
        
    }
    
    



    /*
     * Transforma em string o objeto.
     * @OUT: Exemplo: "idUsuario = 37, email = paulorsaraujo7@yahoo.com.br"
     */

    public function __toString() {
        $toString = "idUsuario = " . (string) $this->idUsuario . ", ";
        $toString .= "nome = " . (string) $this->nome . ", ";
        $toString .= "email = " . (string) $this->email;
        return $toString;
    }

    //GETS
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function getTemFoto() {
        return $this->temFoto;
    }

    public function getTotalVisitas() {
        return $this->totalVisitas;
    }

    public function getMensagemInicial() {
        return $this->mensagemInicial;
    }

    public function getGenero() {
        return $this->genero;
    }

    public function getAnoNascimento() {
        return $this->anoNascimento;
    }

    public function getMesNascimento() {
        return $this->mesNascimento;
    }

    public function getDiaNascimento() {
        return $this->diaNascimento;
    }

    public function getSessao() {
        return $this->Sessao;
    }

    public function getNumeroDeLogins() {
        return $this->numeroDeLogins;
    }

    //SETRS
    public function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function setDataCadastro(NBLDateTime $dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }

    public function setTemFoto($temFoto) {
        $this->temFoto = $temFoto;
    }

    public function setTotalVisitas($totalVisitas) {
        $this->totalVisitas = $totalVisitas;
    }

    public function setMensagemInicial($mensagemInicial) {
        $this->mensagemInicial = $mensagemInicial;
    }

    public function setGenero($genero) {
        $this->genero = $genero;
    }

    public function setAnoNascimento($anoNascimento) {
        $this->anoNascimento = $anoNascimento;
    }

    public function setMesNascimento($mesNascimento) {
        $this->mesNascimento = $mesNascimento;
    }

    public function setDiaNascimento($diaNascimento) {
        $this->diaNascimento = $diaNascimento;
    }

    //OU PASSA NULL OU UM OBJETO
    public function setSessao(Sessao $Sessao = null) {
        $this->Sessao = $Sessao;
    }

    public function setNumeroDeLogins($numeroDeLogins) {
        $this->numeroDeLogins = $numeroDeLogins;
    }

    
    
}

?>
