<?php
class NBLDateTime {
    private $dateTime;
    

    //NO MOMENTO DA CRIACAO DEFINE O TIMEZONE DO OBJETO COM BASE NO IDIOMA SELECIONADO.
    public function __construct($stringData = NULL){
        $timezone = "";
        
        /*AGORA SELECIONAR O TIMEZONE DA NOVA DATA (ISSO VIRA DA CLASSE QUE DEFINE O IDIOMA)*/
        if ( isset($_SESSION["_IDIOMA_"]["idiomaSelecionado"]) ){
            if ( $_SESSION["_IDIOMA_"]["idiomaSelecionado"] == "PT_BR" ){
                $timezone = "America/Sao_Paulo";
                
            }
            elseif ( $_SESSION["_IDIOMA_"]["idiomaSelecionado"] == "EN_US" ){
                $timezone = "America/Chicago";
            }
            date_default_timezone_set($timezone); /*DEFINE O TIME ZONE PADRAO - MESMO DEFININDO NA CRIACAO DO OBJETO ABAIXO, AINDA ASSIM EH PRECISO.*/
        }
        else //Defini com base na URL do site ou outra política.
        {
            
        }
        /*FIM - AGORA SELECIONAR O TIMEZONE DA NOVA DATA (ISSO VIRA DA CLASSE QUE DEFINE O IDIOMA)*/
        
        /*SE FOI PASSADA UMA STRING DE DATA, ENTAO CRIA UMA DATA COM BASE NA STRING PASSADA - A STRING DO BD, POR EXEMPLO*/
            if ( $stringData != NULL ) /*SOMENTE CONSTROE UM OBJETO DO TIPO DATA SE EXISTIR - EVITA QUE SE RECUPERAR UMA DATA NULL DO BD CRIE UMA NOVA */
                $this->dateTime = new DateTime($stringData, new DateTimeZone($timezone));
            else {
                $this->dateTime = NULL;
            }

        
         /*SE FOI PASSADA UMA STRING DE DATA, ENTAO CRIA UMA DATA COM BASE NA STRING PASSADA - A STRING DO BD, POR EXEMPLO*/
//            if ( $stringData == 'now' ) /*CONSTROE UM OBJETO DATETIME COM A DATA ATUAL*/
//            {    
//                $this->dateTime = new DateTime('Y-m-d H:i:s', new DateTimeZone($timezone));
//            }
//            else {
//                if ( $stringData == NULL ) /*DEFINE A DATA COMO NULL*/
//                {
//                    $this->dateTime = NULL;
//                }
//                else { /*DEFINE A DATA COMO SENDO A PASSADA*/
//                    $this->dateTime = new DateTime($stringData, new DateTimeZone($timezone));
//                }
//            }
            return $this;
        /*FIM - SE FOI PASSADA UMA STRING DE DATA, ENTAO CRIA UMA DATA COM BASE NA STRING PASSADA - A STRING DO BD, POR EXEMPLO*/
    }
    
    
    /*Vai exibir a data com base no timeZone selecionado*/
    public function toStringExibir(){
        if ( !is_object($this->dateTime) ) /*SE NAO FOI SETADA UMA DATA RETORNA A STRING VAZIA NA EXIBICAO*/
            return "";
        
        if ( $this->dateTime->getTimezone()->getName() == "America/Sao_Paulo"  ){
            return $this->dateTime->format("d/m/Y H:i");
        }elseif ($this->dateTime->getTimezone()->getName() == "America/Chicago"  ){
            return $this->dateTime->format("m-d-Y H:i");
        }
    }
    
    /*Utilizada para inserir as datas na string SQL de gravação*/
    public function toStringGravar(){
        if ( $this->dateTime != NULL )
        {
            $temp = $this->dateTime->format("Y-m-d H:i:s");
            return "'$temp'";
        }
        else 
        {
            return "NULL";
        }
    }
    /*FIM - Utilizada para inserir as datas na string SQL de gravação*/
}