<?php
// $meta = get_meta_tags("http://www.google.com"); //Obter dados meta do site
// var_dump($meta);

/*Observar a RFC  da url
 * 
 * http://www.ietf.org/rfc/rfc1738.txt
 */


//$url = 'www.google.com.br/search?q=PROCURADORIA+DA+REP%C3%9ABLICA&ie=utf-8&oe=utf-8&aq=t&rls=org.mozilla:pt-BR:official&client=firefox-a'; //valida
//$url = 'ftp://foo:@host.com'; //valida
//$url = '\\ITANS\users\caico\PAULO'; //invalida

/*
 * ANALISA SE UMA URL EH VALIDA. 
 * SE N FOR, EH POSSIVEL ESTA FALTANDO O PROTOCOLO APENAS.
 * @in: url a ser analisada.
 * $out: retorna a url (modificada ou nao com o acrescimo do protocolo no comeco da string)
 * 
 * MELHORIA: SE NAO PASSAR O PROTOCOLO, POR TENTATIVA TENTA OS OUTROS (IMPLICA LENTIDAO 
 * PARA TESTAR SE OBTEM RESPOSTA DE ALGUM SERVIDOR E NAO EH 100 POR CENTO GARANTIDO POIS
 * O SERVIDOR CONSULTADO PODE ESTAR FORA DO, AR POR EXEMPLO.
 */
function validarURL ($url)
{
    is_string($url) ? $url : (string) $url; //SE NAO FOI PASSADO COMO STRING, TRANSFORMA EM STRING
    $url = trim($url); //RETIRA ESPACOS DO COMECO E FIM DA STRING
    //$url    - a url original
    //$url_1  - a url depois da primeira modificacao
    //$url_2  - a url depois da segunda modificacao
    
    //PRIMEIRA - TENTATIVA - ACRESCENTAR PROTOCOLO
    if(!filter_var($url, FILTER_VALIDATE_URL)) { //URL INVALIDA
        $url_1 = "http://" . $url;        //TENTA ACRESCENTAR INICIALMENTE O PROTOCOLO HTTP NO COMECO DA STRING.
        //TESTA NOVAMENTE
        if(!filter_var($url_1, FILTER_VALIDATE_URL)) { //MESMO ACRESCENTANDO O PROTOCOLO A URL EH INVALIDA
            //SEGUNDA TENTATIVA - PODE SER ERRO NO PROTOCOLO INFORMADO. SUBSTITUI PELO HTTP
            $pos = strpos($url, '//'); //POSICAO DO //
            $url_2 = 'http:' . substr($url, $pos); // SUBSTRING COMECA COM O // EX: //www.nbooklink.com
            if(!filter_var($url_2, FILTER_VALIDATE_URL)) {
                return FALSE;
            }
            else { //SEGUNDA TENTATIVA FUNCIONOU
                return $url_2;
            }
        }
        else { /*PRIMEIRA TENTATIVA FUNCIONOU*/
            return $url_1;
        }
    } //A URL ORIGINAL EH VALIDA
    else {
        return $url;
    }
}

$url = 'http://www.php-pt.com/index.php?option=com_content&task=view&id=49&Itemid=32'; //valida
var_dump(validarURL($url));


?>
