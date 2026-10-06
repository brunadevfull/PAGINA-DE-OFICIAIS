<?php
$ano = date("Y");
$dia = date ("d");
$mes = date ("m")+1;
if($mes < 10){
$datafinal = $dia."0".$mes.$ano;	
}else{	
$datafinal = $dia.$mes.$ano;
}
echo $datafinal;
?>