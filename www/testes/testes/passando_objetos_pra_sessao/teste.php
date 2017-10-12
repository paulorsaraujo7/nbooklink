<?php

require_once ("classeTeste.php");
session_start();


$c = new classeTeste();
echo $c->t;
// $c::$soma = $c::$soma+1; // JÁ AQUI NÃO ACESSA PQ É UMA VARIAVEL DE CLASSE.
$_SESSION["teste"] = $c;











/* Teste com a funÃ§Ã£o MD5
  echo md5("paulo");
  echo "<br>";
  echo md5("lino");
 */

/* TESTES PARA FUNCIONALIDADE DE VÃ�RIOS IDIOMAS
  var_dump($_SERVER['REQUEST_URI']); //REQUEST_URI
  $query = 'select * from elementos';
  $sql = BD::query($query);
  while ( $row = mysql_fetch_array($sql) )
  {
  $idElemento = $row['idElemento'];
  $_SESSION['elementos'][$idElemento] = array ( 'conteudo' => $row['conteudoPT_BR'], 'local' => $row['localOndeAparece'] );
  }
  echo $_SESSION['elementos']['labelFormLoginSenha']['conteudo'];
  echo $_SESSION['elementos']['labelFormLoginEmail']['conteudo'];
  echo $_SESSION['elementos']['labelFormLoginEmail']['local'];
 */


/* Testes com arrays
  $resultado = carregaIdioma("pt_br");


  $a = array("k1" => "um", "k2" => "dois");
  var_dump($a);

  $b["c1"] = array("um" => array ("maisum" => "maisoutroum"));
  $b["c2"] = array("dois" => "maisdois");
  var_dump($b);

  $m['idiomas'] = $b;
  var_dump($m);

  echo $m['idiomas']['c1']['um']['maisum'];

  $query = carregaIdioma("pt_br");
  $mens = null;
  while ($row = mysql_fetch_array($query)) {
  $mens[ $row['idElemento']  ] = array( 'pt_br' => $row['pt_br'], 'ondeAparece' => $row['ondeAparece'] );

  }
  var_dump($mens);

  echo $mens['teste1']['pt_br'];

  echo "<br>";
  echo $mens['teste2']['pt_br'];


  carregaIdioma("portuguesBrasil");
  echo $_SESSION['idioma'];
  echo "<br>";
  var_dump($_SESSION['elementos']);
  echo $_SESSION['elementos']['labelEmailFormLogin']['texto'];

 */
?>
