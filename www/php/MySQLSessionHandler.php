<?php

/*
 * Arquivo que conterá os manipuladores da sessão;
 * As sessões serão armazenadas no BD em vez de cookies;
 */

/*
 * Abre a conexão com o servidor e seleciona uma sessão em particular
 */

function mysql_session_open($session_path, $session_name) {
    mysql_pconnect("localhost", "root", "") or
            die("Não pode conectar ao MySQL, detalhe técnico:" . mysql_error());
    mysql_select_db("nbooklin_nbl");
}

// fim de mysql_session_open


/*
 * Fecha a sessão, embora não faça nada deve ser definida
 */

function mysql_session_close() {
    return 1;
}

//fim de mysql_session_close


/*
 * Lê os dados da sessão a partir do bd e verifica se a sessão ainda não expirou
 */

function mysql_session_select($SID) {
    $query = "SELECT * FROM sessoes WHERE SID = '$SID' AND
                expiraEm > " . time();

    $result = mysql_query($query);

    //Existe a sessão que não esteja expirada?
    if (mysql_num_rows($result)) { //Existe uma sessão não expirada
        $row = mysql_fetch_assoc($result);
        $valor = $row['valor'];
        return $valor;
    } else { //Não existe a sessão
        return "";
    }
}

//mysql_session_select


/*
 * Esta função escreve os dados da sessão no bd.
 * Se o SID já existir, os dados são atualizados.
 */

function mysql_session_write($SID, $valor) {


// Recupera o tempo de vida máximo da sessão
    /*
     * $lifetime = get_cfg_var("session.gc_maxlifetime"); 
     * Em princípio vou dá um valor definido aqui mesmo
     */



    // Configura a data de expiração da sessão. Soma o tempo atual mais o 
    //tempo definido na variável que determina o tempo máximo de vida
    $expiraEm = time() + 3600;

    //Insere os dados da sessão no bd
    $query = "INSERT INTO sessoes VALUES ('$SID','$expiraEm','$valor') ";

    $result = mysql_query($query);

    /*
     * Se a consulta falhar (violação de chave primária), a sessão já existe
     * e atualiza a sessão.
     */

    if (!$result) {
        $query = "UPDATE sessoes SET
            expiraEm = '$expiraEm', valor = '$valor' WHERE  SID = '$SID' AND expiraEm >" . time();
    }
    echo $query;
    $result = mysql_query($query);
}

//fim de mysql_session_write


/*
 * Apaga todas as informações de sessão do SID (somente uma linha)
 */

function mysql_session_destroy($SID) {
    //Apaga todas informações de sessão de um SID particular
    $query = "DELETE FROM sessoes WHERE SID = '$SID' ";
    $result = mysql_query($query);
}

//fim de mysql_session_destroy


/*
 * Apaga todas as sessões que já expiraram
 */

function mysql_session_garbage_collect($lifetime) {
    //Apaga todas as sessões acima do prazo
    $query = "DELETE FROM sessoes WHERE expiraEm < " . time() - $lifetime;
    $result = mysql_query($query);
    $result = mysql_affected_rows($result);
    return $result;
}

// fim de mysql_session_garbage_collect


/*
 * Definindo as sessões acima como os gerenciadores de sessão
 */
session_set_save_handler("mysql_session_open", "mysql_session_close", "mysql_session_select", "mysql_session_write", "mysql_session_destroy", "mysql_session_garbage_collect");
?>
