<?php
class Uteis {
    
    
    /*ESCAPA UMA STRING - SIMILAR */
    public static function escapeString($str)
    {
            $search=array("\\","\0","\n","\r","\x1a","'",'"');
            $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
            return str_replace($search,$replace,$str);
    }    
    
    
    /*
     * ANALISA SE UMA URL EH VALIDA. 
     * SE N FOR, TENTA CORRIGIR POSSIVEIS PROBLEMAS INFORMADOS ABAIXO.
     * 1 - TENTATIVA: FALTA O PROTOCOLO E TENTA ACRESCENTAR O PROTOCOLO PADRAO
     * 2 - TENTATIVA: ERRO NO PADRAO DE PROT. E ACRESCENTA O HTTP://
     * 
     * @in: url a ser analisada.
     * $out: retorna a url modificada OU FALSE, CASO N CONSIGA CORRIGIR O ERRO
     */
    public static function validarURL ($url)
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
}
?>
