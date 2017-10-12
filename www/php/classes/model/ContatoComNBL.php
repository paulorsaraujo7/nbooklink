<?php
/*
 * @Description: Armazena as mensagens enviadas por internautas e usuários
 * @Autor: Paulo Ricardo
 * @Data de criacao: 25/12/2012
 */


class ContatoComNBL {
    
	private $idContatoComNBL = null;
	private $idUsuario       = null;
	private $tipoMensagem    = null;
	private $mensagem        = null;
	private $dataEnvio       = null;
	private $idioma          = null;
	private $status          = 'N';
	private $dataResposta    = null;
	private $resposta        = null;
        private $nomeRemetente   = null;
        private $emailRemetente  = null;

        
        public function __construct() {
            $this->dataEnvio       = new NBLDateTime(NULL);
            $this->dataResposta    = new NBLDateTime(NULL);
        }
       

        public function getIdContatoComNBL(){
		return $this->idContatoComNBL;
	}

	public function setIdContatoComNBL($idContatoComNBL){
		$this->idContatoComNBL = $idContatoComNBL;
	}

	public function getIdUsuario(){
		return $this->idUsuario;
	}

	public function setIdUsuario($idUsuario){
		$this->idUsuario = (int)$idUsuario;
	}

	public function getTipoMensagem(){
		return $this->tipoMensagem;
	}

	public function setTipoMensagem($tipoMensagem){
		$this->tipoMensagem = $tipoMensagem;
	}

	public function getMensagem(){
		return $this->mensagem;
	}

	public function setMensagem($mensagem){
		$this->mensagem = $mensagem;
	}

	public function getDataEnvio(){
		return $this->dataEnvio;
	}

	public function setDataEnvio(NBLDateTime $dataEnvio){
		$this->dataEnvio = $dataEnvio;
	}

	public function getIdioma(){
		return $this->idioma;
	}

	public function setIdioma($idioma){
		$this->idioma = $idioma;
	}

	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

	public function getDataResposta(){
		return $this->dataResposta;
	}

	public function setDataResposta(NBLDateTime $dataResposta){
		$this->dataResposta = $dataResposta;
	}

	public function getResposta(){
		return $this->resposta;
	}

	public function setResposta($reposta){
		$this->resposta = $reposta;
	}

	public function getNomeRemetente(){
		return $this->nomeRemetente;
	}

	public function setNomeRemetente($nomeRemetente){
		$this->nomeRemetente = $nomeRemetente;
	}
        
	public function getEmailRemetente(){
		return $this->emailRemetente;
	}

	public function setEmailRemetente($emailRemetente){
		$this->emailRemetente = $emailRemetente;
	}

        
        
        
        
}

?>
