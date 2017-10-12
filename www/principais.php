<?php
/*CLASSE QUE CARREGA AS CLASSES AUTOMATICAMENTE
 * USA O PADRAO SINGLETON.
 */
class ClassAutoloader {
        private static $instance;
        
        private function __clone(){}

        private function __construct() {

            //ini_set("session.use_cookies", 1);       // SESSOES PASSADAS POR COOKIES
            //ini_set("session.use_only_cookies", 1);  // SESSOES PASSADAS POR COOKIES
            //ini_set("session.cookie_lifetime", 0);   // COOKIE ATE O FECHAR BROWSER
            
            define('CAMINHO_BASE', (string) (__DIR__ . '/'));                  //DIRETÓRIO DE ONDE ESTARÁ O ARQUIVO QUE CONTEM ESTE CÓDIGO
            
            // Set include path
            $path  = (string)  get_include_path();
            $path .= (string) (PATH_SEPARATOR . CAMINHO_BASE . 'php/classes/DAO/');
            $path .= (string) (PATH_SEPARATOR . CAMINHO_BASE . 'php/classes/HTML/');
            $path .= (string) (PATH_SEPARATOR . CAMINHO_BASE . 'php/classes/control/');
            $path .= (string) (PATH_SEPARATOR . CAMINHO_BASE . 'php/classes/model/');
            $path .= (string) (PATH_SEPARATOR . CAMINHO_BASE . 'php/classes/uteis/');
            $path .= (string) (PATH_SEPARATOR . CAMINHO_BASE . 'php/classes/view/');
            // $path .= (string) (PATH_SEPARATOR . 'additional/path/');         //PARA ADIÇÕES POSTERIORES
            set_include_path($path);
        }
         
        private static function loader($className) {
           $className = (string) str_replace('/', DIRECTORY_SEPARATOR, $className); //POR QUE?
           include_once($className . '.php');        
        }
        
        /*SOMENTE REGISTRA UMA VEZ E CRIA APENAS UMA INSTANCIA DA CLASSE*/
        public static function registraClasses(){
            if (!isset(self::$instance)) { //SE AINDA NAO TIVER SIDO CRIADA
                $c = __CLASS__;
                self::$instance = new $c;
                spl_autoload_register(array(self::$instance, 'loader'));
            }
        }
    }
    ClassAutoloader::registraClasses();
    session_set_cookie_params(0); /*A SESSAO VALE ATÉ FECHAR O BROWSER*/
    session_start();
