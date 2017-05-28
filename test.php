<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$l=0.1*3.1415926535898;
$n=5;
$c=340;
$freq = 1000;
$th = range(1,360);
//$x = array_fill(0, 360, 1);
//$r = array_fill(0, 360, 1);
//$sin_th = array_fill(0, 360, 1);
foreach ($th as $value) {

  $rad_th[] = ($value / 180)*3.1415926535898;

}
foreach ($rad_th as $value) {

  $sin_th[] = sin($value);

}
foreach ($sin_th as $value ) {

  $x[]=$l/$c*$freq*$value;

}
foreach ($x as $value ) {

  $r[]=sin($n*$value)/($n*$value);

}
// $fp = fopen('results.json', 'w');
// fwrite($fp, json_encode($r));
?>
