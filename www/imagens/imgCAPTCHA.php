<?php
header("Content-type: image/png");
// Paulo Ricardo em 26 de junho de 2012.
session_start();
function randomkeys($length) {
    $pattern = "1234567890";
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $pattern{rand(0, 9)}; //captura um dos números
    }
    return $key;
}

$primeiro = randomkeys(1);
$segundo = randomkeys(1);
$terceiro = randomkeys(1);
$quarto = randomkeys(1);
$quinto = randomkeys(1);

$codigoRandomico = $primeiro . $segundo . $terceiro . $quarto . $quinto;


unset($_SESSION['ImgCAPTCHA']);
$_SESSION['ImgCAPTCHA'] = $codigoRandomico;
$im = imagecreatetruecolor(100, 40);
$text_color = imagecolorallocate($im, 0, 0, 0);
$branco = imagecolorallocate($im, 255, 255, 255);
$cor1 = imagecolorallocatealpha($im, 162, 162, 162, 70);
$cor2 = imagecolorallocatealpha($im, 128, 181, 0, 70);
$cor3 = imagecolorallocatealpha($im, 0, 162, 181, 70);
$cor4 = imagecolorallocatealpha($im, 181, 0, 141, 50);
$surpresa = imagecolorallocatealpha($im, rand(0, 255), rand(0, 255), rand(0, 255), rand(0, 255));

imagefill($im, 0, 0, $branco);

imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor1);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor2);
imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor3);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor4);

imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor1);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor2);
imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor3);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor4);


imagettftext($im, 20, rand(-5, 5), rand(20, 20), rand(30, 15), $cor4, '../fontes/arial.ttf', $codigoRandomico);

imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor3);
imageline($im, rand(0, 200), rand(0, 40), rand(0, 200), rand(0, 200), $cor4);
imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor1);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor2);
imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor3);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor4);
imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor1);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor2);
imageellipse($im, rand(10, 50), rand(10, 50), rand(20, 50), rand(10, 50), $cor3);
imageline($im, rand(0, 100), rand(0, 40), rand(0, 100), rand(0, 100), $cor4);

$rot = imagerotate($im, rand(-3, 3), $surpresa);
imagecopyresized($rot, $rot, 0, 0, 0, 0, 100, 40, imagesx($rot), imagesy($rot));
imagepng($im);
imagedestroy($im);