<?php
/**
 * Description of M_to_m_grupos_links:
 * Armazena o relacionamento entre um link e um grupo de um usuario.
 *
 * @author Paulo Ricardo
 * Criada em 05/05/2013
 * Lembrar: criar gets e setters para os campos de entidade.
 */
class M_to_m_grupos_links {
	private $idHiperLinkUsuario = null;
	private $idGrupoHiperLinksUsuario = null;
        
        public function __construct($idHiperLinkUsuario, $idGrupoHiperLinksUsuario ) {
            $this->setIdHiperLinkUsuario($idHiperLinkUsuario);
            $this->setIdGrupoHiperLinksUsuario($idGrupoHiperLinksUsuario);
        }

        public function getIdGrupoHiperLinksUsuario(){
		return $this->idGrupoHiperLinksUsuario;
	}

	public function setIdGrupoHiperLinksUsuario($idGrupoHiperLinksUsuario){
		$this->idGrupoHiperLinksUsuario = $idGrupoHiperLinksUsuario;
	}

	public function getIdHiperLinkUsuario(){
		return $this->idHiperLinkUsuario;
	}

	public function setIdHiperLinkUsuario($idHiperLinkUsuario){
		$this->idHiperLinkUsuario = $idHiperLinkUsuario;
	}
}
?>
