<?php
/*
 * 1 - CLASSE DE ACESSO AO BD PARA A CLASSE DE ENTIDADE USUARIO
 * 2 - HERDA DA CLASSE BD.
 * 
 * DEVE PROVER AS SEGUINTES FUNCIONALIDADES:
 * 1 - CRUD
 * 2 - PESQUISAS POR DIVERSOS CAMPOS QUE RETORNAM OBJETOS.
 * 
 *  
 * 
 */

/**
 * Description of UsuarioDAO
 *
 * @author Paulo Ricardo Santos e Araújo.
 * Criada em 17/07/2012.
 * 
 * Lembrar: criar gets e setters para os campos de entidade.
 */
class UsuarioDAO extends MainDAO {
    
    const objModel = "Usuario";


    /*
     * RETORNA UM OBJETO DO TIPO SESSAO OU NULL SE NAO EXISTIR O OBJETO
     * @in:  resourse do MySQL
     * @out: SESSAO POPULADA
     */
    public function teste()
    {
        return self::retornaObjeto($this->obterObjetoPorCampo("idUsuario", 148));
    }

    
    /*Obtem um Usuario pelo ID
     * @IN: id do usuario;
     * @OUT: Objeto populado ou NULL
     */
    public static function obterPorId($idUsuario) {
        $idUsuario = (int) $idUsuario;
        $query = "SELECT * FROM usuarios WHERE idUsuario = " . " '$idUsuario' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }

    /*Exclui um Usuario pelo ID
     * @IN: id do usuario;
     * @OUT: True se foi excluído ou false se houve erro
     */
    public static function excluiPorId($idUsuario) {
        $idUsuario = (int) $idUsuario;
        $query = "DELETE FROM usuarios WHERE idUsuario = " . " '$idUsuario' ";
        return parent::query($query);
    }
    
    
    /*
     * RETORNA UM OBJETO DO TIPO USUÁRIO CASO EXISTA NO BD
     * @in:  endereço de email para ser verificado se existe um usuário no BD 
     * @out: USUARIO POPULADO ou NULL caso não exista;
     */
    public static function obterPorEmail($email) {
        //EMAIL VAZIO
        if (trim($email) == "")
            throw new Exception("erroEmailVazio");

        //EMAIL INVALIDO
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("erroEmailInvalido");

        $email = Uteis::escapeString($email);
        $query = "SELECT * FROM usuarios WHERE email = " . " '$email' ";
        return parent::retornaObjetoPorQuery($query, self::objModel);
    }

    public static function existePorEmail($email) {
        //EMAIL VAZIO
        if (trim($email) == "")
            throw new Exception("erroEmailVazio");


        //EMAIL INVALIDO
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            throw new Exception("erroEmailInvalido");

        $email = Uteis::escapeString($email);
        $query = "SELECT idUsuario FROM usuarios WHERE email = " . " '$email' ";
        

        $result = parent::query($query);
        //COMO O TIPO É UM SELECT OU RETORNA UM RESOURCE OU O ERRO É GERADO NA CLASSE MAE
        /*
         * TRATAR COMO RESOURSE VÁLIDO E SE NÃO VOLTAR LINHA VAZIA, ENTÃO POPULAR O OBJETO.
         */
        //O mysql_numrows é válido apenas para o comando SELECT ou SHOW    
        if (mysql_num_rows($result) == 0 ) //NÃO FOI ENCONTRADO USUÁRIO COM O EMAIL PASSADO
            return FALSE;
        else
            return TRUE;
    }

    
    
        public static function persiste(Usuario $Usuario) {
        /*
         * LEMBRAR O SEGUINTE:
         * 1 - NA CRIACAO DO OBJETO TODOS OS CAMPOS SÃƒO NULL POR PADRÃƒO, ENTÃƒO O QUE NAO FOR DEFINIDO ESTA COMO NULL
         * 2 - CAMPOS COM VALOR NULL DEVEM ESTAR ENTRE ASPAS SIMPLES
         * 3 - OS VALORES PADRAO SAO SEMPRE DEFINIDOS NO CODIGO CLIENTE - OS CAMPOS NULL DEVEM ESTAR ENTRE ASPAS SIMPLES.
         */

        $idUsuario          = $Usuario->getIdUsuario();
        $email              = Uteis::escapeString($Usuario->getEmail());
        $nome               = Uteis::escapeString($Usuario->getNome());
        $login              = Uteis::escapeString($Usuario->getLogin());
        $senha              = $Usuario->getSenha(); //A SENHA JA FOI CRIPTOGRAFADA NO METODO QUE PREENCHE A SENHA (POIS O CODIGO PODE PRECISAR DA SENHA MD5 ANTES DE PERSISTIR)
        
        $dataCadastro       = $Usuario->getDataCadastro()->toStringGravar();
        //$dataCadastro       = ( $Usuario->getDataCadastro()  != NULL ) ? "' {$Usuario->getDataCadastro()->toStringGravar()}  '" : "NULL";  
        
        
        $temFoto            = $Usuario->getTemFoto();
        $totalVisitas       = $Usuario->getTotalVisitas();
        $mensagemInicial    = $Usuario->getMensagemInicial();
        $genero             = $Usuario->getGenero();
        $anoNascimento      = $Usuario->getAnoNascimento();
        $mesNascimento      = $Usuario->getMesNascimento();
        $diaNascimento      = $Usuario->getDiaNascimento();
        $numeroDeLogins     = $Usuario->getNumeroDeLogins();
        
        //SE OS CAMPOS FOREM NULL, ENTAO DEVEM FICAR SEM ASPAS NO MOMENTO DA UTILIZACAO
        
            
        /* CODIGO ANTIGO. A DATA AINDA NÃO ERA DO TIPO NBLDateTime
         * $dataCadastro = ($dataCadastro != NULL) ? "'$dataCadastro'" : "NULL";
         */

        $query = "";
        if ($idUsuario == 0 || $idUsuario == NULL) { //NAO EXISTE USUARIO E É UMA INSERÇÃO.
            $query = "
            
                INSERT INTO `nbooklin_nbl`.`usuarios` 
                (
                    `idUsuario`, 
                    `email`, 
                    `nome`, 
                    `login`, 
                    `senha`, 
                    `dataCadastro`, 
                    `temFoto`,
                    `totalVisitas`,
                    `mensagemInicial`,
                    `genero`,
                    `anoNascimento`,
                    `mesNascimento`,
                    `diaNascimento`,
                    `numeroDeLogins`
                 )
                 VALUES 
                 (
                    '$idUsuario', 
                    '$email', 
                    '$nome', 
                    '$login', 
                    '$senha', 
                    $dataCadastro, 
                    '$temFoto', 
                    '$totalVisitas', 
                    '$mensagemInicial', 
                    '$genero', 
                    '$anoNascimento', 
                    '$mesNascimento', 
                    '$diaNascimento', 
                    '$numeroDeLogins'
                 )
            ";
        } else { //EXISTE ENTRADA NO BD E ATUALIZA (OS CAMPOS NULOS DEVEM IR SEM ASPAS
            $query = "
            
                    UPDATE `nbooklin_nbl`.`usuarios` SET 
                        idUsuario        = '$idUsuario', 
                        email            = '$email',
                        nome             = '$nome',
                        login            = '$login',
                        senha            = '$senha',
                        dataCadastro     = $dataCadastro,
                        temFoto          = '$temFoto',
                        totalVisitas     = '$totalVisitas',
                        mensagemInicial  = '$mensagemInicial',
                        genero           = '$genero',
                        anoNascimento    = '$anoNascimento',
                        mesNascimento    = '$mesNascimento',
                        diaNascimento    = '$diaNascimento',
                        numeroDeLogins   = '$numeroDeLogins'
                    WHERE idUsuario = '$idUsuario'
            ";
        }
        if (MainDAO::query($query)) //TRUE SE SUCESSO NA EXECUCAO - NUNCA VAI RETORNAR FALSE POIS GERARÃ� UM ERRO NA CLASSE MÃƒE
            return TRUE;
    }
}