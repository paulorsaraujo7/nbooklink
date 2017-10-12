<?php
require_once 'principais.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        
        <link rel="shortcut icon" href="favicon.gif">
        <link href="css/contactReceived.css" rel="stylesheet" type="text/css"/>
        <link href="css/privacyPolice.css" rel="stylesheet" type="text/css"/>
        
        <?php
            $viewTopo = new ViewTopo();
            $viewRodape = new ViewRodape();
            echo ViewBase::retornaArquivosDeLigacoes();
        ?>
        <title><?php echo NBLIdioma::getTextoPorIdElemento('titleIndex'); ?></title>
    </head>
    <body>
        <div id="divTudo">
        <?php
             $viewTopo->display();
         ?>
        <div id="divConteudoUnico" class="divComBorda">
            <img id="imgPrivacyPolice" src="imagens/imgCadeado.png">
            <div id="divTextoPrivacyPolice"> 
                <pre>


<?php if( $_SESSION['_IDIOMA_']['idiomaSelecionado'] == "PT_BR" ) { ?>
Política de Privacidade do NBookLink:

O NBookLink foi criado para dar a você uma melhor experiência de usabilidade na Internet.

Você cria sua conta e passa a cadastrar seus sites favoritos. Isso poupa seu tempo nos próximos acessos. 

Algumas informações básicas precisam ser fornecidas para iniciar o uso, como um nome para tratamento e email.

O NBookLink tem alguns princípios que norteiam a relação com os nossos usuários:

<span class="letraEmDestaque">1 - A privacidade é direito fundamental de cada indivíduo.</span>  

<span class="letraEmDestaque">2 - Você é o único dono da informação criada por você mesmo.</span> 
Sendo assim, você tem a liberdade de excluir por completo sua conta e TODOS os dados que você armazenou 
no momento em que você desejar.

Ao marcar a opção 'Mantenha-me conectado', um número que o identifique será armazenado em seu navegador. 
Dessa forma, em seu próximo acesso ao NBoolLink não será necessário informar o seu email e senha de acesso.

É certo que o NBookLink, assim como qualquer sítio de internet gratuito, precisa se manter e obter algum 
lucro com base em propagandas. 
Inicialmente você não verá propagandas, porém, em um futuro próximo, elas serão exibidas sem prejudicar o seu espaço.

O NBookLink perguntará a você informações que nos ajude a saber qual tipo de novidades você gostaria de ver. 

Qualquer dúvida, crítica ou sugestões serão muito bem-vindas e podem ser enviadas pelo <a href="contact.php">formulário de contato</a>.

Abraço da nossa equipe.
<?php } else { ?>
Privacy Policy NBookLink:

The NBookLink was created to give you a better usability experience on the Internet.

You create your account and shall register their favorite sites. This saves your time.

Some basic information must be provided to start using as a name for processing and email.

The NBookLink has some principles that guide the relationship with our users:

<span class="letraEmDestaque">1 - Privacy is a fundamental right of every individual.</span>

<span class="letraEmDestaque">2 - You are the sole owner of the information that you created yourself.</span>
So you have the freedom to completely exclude your account and ALL data you have stored
when you wish.

By clicking 'Keep me logged in', a number that identifies you will be stored on your browser.
Thus, the next time you access NBoolLink it will not be necessary enter your email and password again.

Admittedly, the NBookLink as well as free internet anywhere, need to maintain and get some
profit based on advertisements.
Initially you will not see advertisements, but in the near future, they will be displayed without harming your space.

The NBookLink ask you for information that will help us know what kind of news you would like to see.

Any questions, critique or suggestions are very welcome and can be sent through the <a href="contact.php">contact form</a>.

An embrace of our team.
<?php } ?>
                </pre>
            </div>
            <a id="linkIrParaHomePagePrivacyPolice" href="index.php"><?php echo NBLIdioma::getTextoPorIdElemento('linkIrParaHomePage'); ?></a>  

        </div>
       <?php    
            $viewRodape->display();
        ?>
        </div>
    </body>
</html>
