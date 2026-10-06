<?php
 //responsavel pela comunicação com o pagTesouro (feito atraves da aplicação da DGOM)
class PagTesouro{
	function servidorCivil($nome,$vencimento,$cpfcnpj,$valor, $valorB, $codRubrica, $nomeRubrica, $tipotributo, $nome_OM, $nome_OC, $motivostoryPP, $competenciaDate, $natureza_despesa, $servidor_MatSIAPE){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://desenvolvimento.dgom.mb:3000/handle';
 $codigoServico= 1541;
}
elseif ($ambiente=='P')
{
 $url = 'https://siplad2treina.dgom.mb:3000/handle';
 $codigoServico= 11860;
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}


$referencia=123;
$nomeContribuinte= $nome;
//$competencia=str_replace("-","",$_POST["vencimento"]); 
$competencia = $competenciaDate;
/*if ($_POST["vencimento"]=="")
{
 $vencimento=$dataVencimento;
}
else
{
 $vencimento=date("dmY",strtotime($_POST["vencimento"]));
 if (strtotime($_POST["vencimento"])<strtotime(date('Y-m-d')))
 {
  echo '<script type="text/javascript">alert("Data de vencimento menor que data atual.");</script>';
  echo '<script type="text/javascript">history.back();</script>';
  exit;
 }       
}*/

/*$vencimento= 30042022; // ajustando a data;
$dataano = date("Y");
$datames = date("m");
$datadia = 21; //date("d");
if($datames == "12"){
$dataano = $dataano + 1;	
$datafinal = $datadia."01".$dataano;	
}else{
//$dataVencimento = 30042022;
$datames = $datames + 1;
if($datames < 10){
$datafinal = $datadia."0".$datames.$dataano;	
}else{	
$datafinal = $datadia.$datames.$dataano;
}
}
$dataVencimento = $datafinal;
$vencimento = $datafinal;*/

$date = date_create();
date_add($date, date_interval_create_from_date_string('30 days'));
$Vencimento = date_format($date, 'Y-m-d');
$partesVencimento = explode("-",$Vencimento);
$dataVencimento=$partesVencimento[2].$partesVencimento[1].$partesVencimento[0];


if($tipotributo == 2){
$tipotributo=0;	
}else{
$tipotributo=1;	
}  
// 2 = Não tributavel | 1 = tributavel
$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
$valorB=str_replace(".","",$valorB);
$valorB=str_replace(",",".",$valorB);
//$valorDescontos=str_replace(".","",$_POST["valorDescontos"]);
//$valorDescontos=str_replace(",",".",$valorDescontos);
$valorDescontos = 0;
//$valorOutrasDeducoes=str_replace(".","",$_POST["valorOutrasDeducoes"]);
//$valorOutrasDeducoes=str_replace(",",".",$valorOutrasDeducoes);
$valorOutrasDeducoes = 0;
//$valorMulta=str_replace(".","",$_POST["valorMulta"]);
//$valorMulta=str_replace(",",".",$valorMulta);
$valorMulta = 0;
//$valorJuros=str_replace(".","",$_POST["valorJuros"]);
//$valorJuros=str_replace(",",".",$valorJuros);
$valorJuros = 0;
//$valorOutrosAcrescimos=str_replace(".","",$_POST["valorOutrosAcrescimos"]);
//$valorOutrosAcrescimos=str_replace(",",".",$valorOutrosAcrescimos);

$pieces_om_recolhedora = explode("-",$nome_OM);
$indicativoNaval = $pieces_om_recolhedora[2];
$nomeOmRecolhedora = $pieces_om_recolhedora[0]; 

$pieces_OC = explode("-",$nome_OC);
$indicativoNavalOC = $pieces_OC[2];
$nomeOmRecolhedoraOC = $pieces_OC[0]; 

$valorOutrosAcrescimos = 0;
$data = array(
  "cat" => "PAPEM",	
  "codigoServico" => $codigoServico,
  "vencimento" => $dataVencimento,
  "competencia" => $competencia,
  "nomeContribuinte" =>  $nomeContribuinte,
  "cnpjCpf" => $cnpjCpf,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "valorPrincipal" => $valorPrincipal,
  "nomeUG" => $nomeOmRecolhedora,
  "cod_om" => $indicativoNaval,
  "cat_servico" => "PAPEM",
  "codRubrica"=> $codRubrica,
  "nomeRubrica"=> $nomeRubrica,
  "motivo"=> $motivostoryPP,
  "tributavel"=> $tipotributo,
  "nomeOC"=> $nomeOmRecolhedoraOC, 
  "cod_oc"=> $indicativoNavalOC,
  "valorBrutoExercAnt"=> $valorB,
  "codSiapeNip"=> $servidor_MatSIAPE,
  "modoNavegacao" => "2",
  "tema" => "tema-light",
  "NatDev" => $natureza_despesa
  
);      
$data_string = json_encode($data);
$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt ($ch, CURLOPT_CAINFO, "MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if ($result === false)
{
 echo 'Erro: '.curl_error($ch);
}            
else
{
 $result=json_decode($result);
 if (is_array($result))
 {
  $i=1;
  while ($i<=count($result))
  {
   echo '<p style="text-align:center;">ERRO: '.$result[$i-1]->{'codigo'}."-".$result[$i-1]->{'descricao'}.'<br>';
   $i++;
  }
  echo '<a href="javascript:history.back()">Voltar</a><p>';
 }
 else
 {
  echo '<script type="text/javascript">window.open(\''.$result->{'proximaUrl'}.'\', \'_blank\');</script>';
  echo '<script type="text/javascript">history.back();</script>';
 }
}
		
	}
	
	
	function militarAtivo($nome, $vencimento, $cpfcnpj, $Nip, $ValorRecolhido, $ValorRecolhidoB, $ParcelaDevolvidas, $codRubrica, $nomeRubrica, $tipotributo, $nome_OM, $nome_OC, $motivostoryPP, $competenciaDate, $natureza_despesa){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://desenvolvimento.dgom.mb:3000/handle';
 $codigoServico= 1541;
}
elseif ($ambiente=='P')
{
 $url = 'http://10.9.17.22:3000/handle';
 $codigoServico= 11860;
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}

/*if($controlador_de_pagamento == 1){
$codigoServico= 1541;
}else{
$codigoServico= 1541;	
}*/
$referencia= 123;
$nomeContribuinte= $nome;
//$competencia=str_replace("/","",$_POST["competencia"]);
$competencia = $competenciaDate;
/*if ($_POST["vencimento"]=="")
{
 $vencimento=$dataVencimento;
}
else
{
 $vencimento=date("dmY",strtotime($_POST["vencimento"]));
 if (strtotime($_POST["vencimento"])<strtotime(date('Y-m-d')))
 {
  echo '<script type="text/javascript">alert("Data de vencimento menor que data atual.");</script>';
  echo '<script type="text/javascript">history.back();</script>';
  exit;
 }       
}


$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = intval("30".$datames.$dataano);*/
//$vencimento= 30042022; // ajustando a data;
/*$dataano = date("Y");
$datames = date("m");
$datadia = 21; //date("d");
if($datames == "12"){
$dataano = $dataano + 1;	
$datafinal = $datadia."01".$dataano;	
}else{
//$dataVencimento = 30042022;
$datames = $datames + 1;
if($datames < 10){
$datafinal = $datadia."0".$datames.$dataano;	
}else{	
$datafinal = $datadia.$datames.$dataano;
}
}
$dataVencimento = $datafinal;
$vencimento = $datafinal;

if($tipotributo == 2){
$tipotributo="Não";	
}else{
$tipotributo="Sim";	
}*/

$date = date_create();
date_add($date, date_interval_create_from_date_string('30 days'));
$Vencimento = date_format($date, 'Y-m-d');
$partesVencimento = explode("-",$Vencimento);
$dataVencimento=$partesVencimento[2].$partesVencimento[1].$partesVencimento[0];


if($tipotributo == 2){
$tipotributo=0;	
}else{
$tipotributo=1;	
}  
// 2 = Não tributavel | 1 = tributavel 
 
$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$ValorRecolhido);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
$ValorRecolhidoB=str_replace(".","",$ValorRecolhidoB);
$ValorRecolhidoB=str_replace(",",".",$ValorRecolhidoB);
//$valorDescontos=str_replace(".","",$_POST["valorDescontos"]);
//$valorDescontos=str_replace(",",".",$valorDescontos);
$valorDescontos = 0;
//$valorOutrasDeducoes=str_replace(".","",$_POST["valorOutrasDeducoes"]);
//$valorOutrasDeducoes=str_replace(",",".",$valorOutrasDeducoes);
$valorOutrasDeducoes = 0;
//$valorMulta=str_replace(".","",$_POST["valorMulta"]);
//$valorMulta=str_replace(",",".",$valorMulta);
$valorMulta = 0;
//$valorJuros=str_replace(".","",$_POST["valorJuros"]);
//$valorJuros=str_replace(",",".",$valorJuros);
$valorJuros= 0;
//$valorOutrosAcrescimos=str_replace(".","",$_POST["valorOutrosAcrescimos"]);
//$valorOutrosAcrescimos=str_replace(",",".",$valorOutrosAcrescimos);

$pieces_om_recolhedora = explode("-",$nome_OM);
$indicativoNaval = $pieces_om_recolhedora[0];
$nomeOmRecolhedora = $pieces_om_recolhedora[1];
$codOmRecolhedora = $pieces_om_recolhedora[2]; 


$pieces_OC = explode("-",$nome_OC);
$indicativoNavalOC = $pieces_OC[0];
$nomeOmRecolhedoraOC = $pieces_OC[1];
$codOmRecolhedoraOC = $pieces_OC[2]; 

$valorOutrosAcrescimos = 0;
$data = array(
  "cat" => "PAPEM",	
  "codigoServico" => $codigoServico,
  "vencimento" => "$dataVencimento",
  "competencia" => $competencia,
  "nomeContribuinte" =>  $nomeContribuinte,
  "cnpjCpf" => $cnpjCpf,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "valorPrincipal" => $valorPrincipal,
  "nomeUG" => $indicativoNaval,
  "cod_om" => $codOmRecolhedora,
  "cat_servico" => "PAPEM",
  "codRubrica"=> $codRubrica,
  "nomeRubrica"=> $nomeRubrica,
  "motivo"=> $motivostoryPP,
  "tributavel"=> $tipotributo,
  "nomeOC"=> $indicativoNavalOC,
  "cod_oc"=> $codOmRecolhedoraOC,
  "valorBrutoExercAnt"=> $ValorRecolhidoB,
  "codSiapeNip"=> $Nip,
  "modoNavegacao" => "2",
  "tema" => "tema-light",
  "NatDev" => $natureza_despesa
);   
$data_string = json_encode($data);
$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt ($ch, CURLOPT_CAINFO, "MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if ($result === false)
{
 echo 'Erro: '.curl_error($ch);
}            
else
{
 $result=json_decode($result);
 if (is_array($result))
 {
  $i=1;
  while ($i<=count($result))
  {
   echo '<p style="text-align:center;">ERRO: '.$result[$i-1]->{'codigo'}."-".$result[$i-1]->{'descricao'}.'<br>';
   $i++;
  }
  echo '<a href="javascript:history.back()">Voltar</a><p>';
 }
 else
 {
  echo '<script type="text/javascript">window.open(\''.$result->{'proximaUrl'}.'\', \'_blank\');</script>';
  echo '<script type="text/javascript">history.back();</script>';
 }
}
	
	}
	

function sisresOutros($nome,$cpfcnpj, $nip, $nome_OC, $valor, $valorB, $nome_OM, $motivostory, $natureza_despesa){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://desenvolvimento.dgom.mb:3000/handle';
 $codigoServico= 1541;
}
elseif ($ambiente=='P')
{
 $url = 'http://10.9.17.22:3000/handle';
 $codigoServico= 11860;
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}

$referencia=123;
$nomeContribuinte= $nome;
//$competencia=str_replace("/","",$_POST["competencia"]);
$competencia = "032022";
/*if ($_POST["vencimento"]=="")
{
 $vencimento=$dataVencimento;
}
else
{
 $vencimento=date("dmY",strtotime($_POST["vencimento"]));
 if (strtotime($_POST["vencimento"])<strtotime(date('Y-m-d')))
 {
  echo '<script type="text/javascript">alert("Data de vencimento menor que data atual.");</script>';
  echo '<script type="text/javascript">history.back();</script>';
  exit;
 }       
}

$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = intval("30".$datames.$dataano);*/
//$vencimento= 30042022; // ajustando a data;
/*$dataano = date("Y");
$datames = date("m");
$datadia = 21; //date("d");
if($datames == "12"){
$dataano = $dataano + 1;	
$datafinal = $datadia."01".$dataano;	
}else{
//$dataVencimento = 30042022;
$datames = $datames + 1;
if($datames < 10){
$datafinal = $datadia."0".$datames.$dataano;	
}else{	
$datafinal = $datadia.$datames.$dataano;
}
}
$dataVencimento = $datafinal;
$vencimento = $datafinal;*/

$date = date_create();
date_add($date, date_interval_create_from_date_string('30 days'));
$Vencimento = date_format($date, 'Y-m-d');
$partesVencimento = explode("-",$Vencimento);
$dataVencimento=$partesVencimento[2].$partesVencimento[1].$partesVencimento[0];


if($tipotributo == 2){
$tipotributo=0;	
}else{
$tipotributo=1;	
}  
// 2 = Não tributavel | 1 = tributavel 

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
$valorB=str_replace(".","",$$valorB);
$valorB=str_replace(",",".",$valorB);
//$valorDescontos=str_replace(".","",$_POST["valorDescontos"]);
//$valorDescontos=str_replace(",",".",$valorDescontos);
$valorDescontos = 0;
//$valorOutrasDeducoes=str_replace(".","",$_POST["valorOutrasDeducoes"]);
//$valorOutrasDeducoes=str_replace(",",".",$valorOutrasDeducoes);
$valorOutrasDeducoes = 0;
//$valorMulta=str_replace(".","",$_POST["valorMulta"]);
//$valorMulta=str_replace(",",".",$valorMulta);
$valorMulta = 0;
//$valorJuros=str_replace(".","",$_POST["valorJuros"]);
//$valorJuros=str_replace(",",".",$valorJuros);
$valorJuros = 0;
//$valorOutrosAcrescimos=str_replace(".","",$_POST["valorOutrosAcrescimos"]);
//$valorOutrosAcrescimos=str_replace(",",".",$valorOutrosAcrescimos);

$pieces_om_recolhedora = explode("-",$nome_OM);
$indicativoNaval = $pieces_om_recolhedora[0];
$codOmRecolhedora = $pieces_om_recolhedora[2]; 

$pieces_OC = explode("-",$nome_OC);
$indicativoNavalOC = $pieces_OC[0];
$codOmRecolhedoraOC = $pieces_OC[2]; 

$valorOutrosAcrescimos = 0;
$data = array(
  "cat" => "PAPEM",	
  "codigoServico" => $codigoServico,
  "vencimento" => $dataVencimento,
  "competencia" => $competencia,
  "nomeContribuinte" =>  $nomeContribuinte,
  "cnpjCpf" => $cnpjCpf,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "valorPrincipal" => $valorPrincipal,
  "nomeUG" => $indicativoNaval,
  "cod_om" => $codOmRecolhedora,
  "cat_servico" => "PAPEM",
  "codRubrica"=> "n/a",
  "nomeRubrica"=> "n/a",
  "motivo"=> $motivostory,
  "tributavel"=> $tipotributo,
  "nomeOC"=> $indicativoNavalOC,
  "cod_oc"=> $codOmRecolhedoraOC,
  "valorBrutoExercAnt"=> $valorB,
  "codSiapeNip"=> $nip,
  "modoNavegacao" => "2",
  "tema" => "tema-light",
  "NatDev" => $natureza_despesa
);    
$data_string = json_encode($data);
$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt ($ch, CURLOPT_CAINFO, "MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if ($result === false)
{
 echo 'Erro: '.curl_error($ch);
}            
else
{
 $result=json_decode($result);
 if (is_array($result))
 {
  $i=1;
  while ($i<=count($result))
  {
   echo '<p style="text-align:center;">ERRO: '.$result[$i-1]->{'codigo'}."-".$result[$i-1]->{'descricao'}.'<br>';
   $i++;
  }
  echo '<a href="javascript:history.back()">Voltar</a><p>';
 }
 else
 {
  echo '<script type="text/javascript">window.open(\''.$result->{'proximaUrl'}.'\', \'_blank\');</script>';
  echo '<script type="text/javascript">history.back();</script>';
  }
 }
}

function sisresSC($nome,$cpfcnpj, $nip, $valor, $valorB,$nome_OC, $nome_OM, $motivostory, $natureza_despesa){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://desenvolvimento.dgom.mb:3000/handle';
 $codigoServico= 1541;
}
elseif ($ambiente=='P')
{
 $url = 'http://10.9.17.22:3000/handle';
 $codigoServico= 11860;
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}

$referencia=123;
$nomeContribuinte= $nome;
//$competencia=str_replace("/","",$_POST["competencia"]);
$competencia = "032022";
/*if ($_POST["vencimento"]=="")
{
 $vencimento=$dataVencimento;
}
else
{
 $vencimento=date("dmY",strtotime($_POST["vencimento"]));
 if (strtotime($_POST["vencimento"])<strtotime(date('Y-m-d')))
 {
  echo '<script type="text/javascript">alert("Data de vencimento menor que data atual.");</script>';
  echo '<script type="text/javascript">history.back();</script>';
  exit;
 }       
}

$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = intval("30".$datames.$dataano);*/
//$vencimento= 30042022; // ajustando a data;
/*$dataano = date("Y");
$datames = date("m");
$datadia = 21; //date("d");
if($datames == "12"){
$dataano = $dataano + 1;	
$datafinal = $datadia."01".$dataano;	
}else{
//$dataVencimento = 30042022;
$datames = $datames + 1;
if($datames < 10){
$datafinal = $datadia."0".$datames.$dataano;	
}else{	
$datafinal = $datadia.$datames.$dataano;
}
}
$dataVencimento = $datafinal;
$vencimento = $datafinal;*/

$date = date_create();
date_add($date, date_interval_create_from_date_string('30 days'));
$Vencimento = date_format($date, 'Y-m-d');
$partesVencimento = explode("-",$Vencimento);
$dataVencimento=$partesVencimento[2].$partesVencimento[1].$partesVencimento[0];


if($tipotributo == 2){
$tipotributo=0;	
}else{
$tipotributo=1;	
}  
// 2 = Não tributavel | 1 = tributavel 

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
$valorB=str_replace(".","",$$valorB);
$valorB=str_replace(",",".",$valorB);
//$valorDescontos=str_replace(".","",$_POST["valorDescontos"]);
//$valorDescontos=str_replace(",",".",$valorDescontos);
$valorDescontos = 0;
//$valorOutrasDeducoes=str_replace(".","",$_POST["valorOutrasDeducoes"]);
//$valorOutrasDeducoes=str_replace(",",".",$valorOutrasDeducoes);
$valorOutrasDeducoes = 0;
//$valorMulta=str_replace(".","",$_POST["valorMulta"]);
//$valorMulta=str_replace(",",".",$valorMulta);
$valorMulta = 0;
//$valorJuros=str_replace(".","",$_POST["valorJuros"]);
//$valorJuros=str_replace(",",".",$valorJuros);
$valorJuros = 0;
//$valorOutrosAcrescimos=str_replace(".","",$_POST["valorOutrosAcrescimos"]);
//$valorOutrosAcrescimos=str_replace(",",".",$valorOutrosAcrescimos);

$pieces_om_recolhedora = explode("-",$nome_OM);
$indicativoNaval = $pieces_om_recolhedora[0];
$codOmRecolhedora = $pieces_om_recolhedora[2]; 

$pieces_OC = explode("-",$nome_OC);
$indicativoNavalOC = $pieces_OC[0];
$codOmRecolhedoraOC = $pieces_OC[2]; 

$valorOutrosAcrescimos = 0;
$data = array(
  "cat" => "PAPEM",	
  "codigoServico" => $codigoServico,
  "vencimento" => $dataVencimento,
  "competencia" => $competencia,
  "nomeContribuinte" =>  $nomeContribuinte,
  "cnpjCpf" => $cnpjCpf,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "valorPrincipal" => $valorPrincipal,
  "nomeUG" => $indicativoNaval,
  "cod_om" => $codOmRecolhedora,
  "cat_servico" => "PAPEM",
  "codRubrica"=> "n/a",
  "nomeRubrica"=> "n/a",
  "motivo"=> $motivostory,
  "tributavel"=> $tipotributo,
  "nomeOC"=> $indicativoNavalOC,
  "cod_oc"=> $codOmRecolhedoraOC,
  "valorBrutoExercAnt"=> $valorB,
  "codSiapeNip"=> $nip,
  "modoNavegacao" => "2",
  "tema" => "tema-light",
  "NatDev" => $natureza_despesa
);    
$data_string = json_encode($data);
$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt ($ch, CURLOPT_CAINFO, "MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if ($result === false)
{
 echo 'Erro: '.curl_error($ch);
}            
else
{
 $result=json_decode($result);
 if (is_array($result))
 {
  $i=1;
  while ($i<=count($result))
  {
   echo '<p style="text-align:center;">ERRO: '.$result[$i-1]->{'codigo'}."-".$result[$i-1]->{'descricao'}.'<br>';
   $i++;
  }
  echo '<a href="javascript:history.back()">Voltar</a><p>';
 }
 else
 {
  echo '<script type="text/javascript">window.open(\''.$result->{'proximaUrl'}.'\', \'_blank\');</script>';
  echo '<script type="text/javascript">history.back();</script>';
  }
 }
}

function recAtivosB($nome_recAtivos, $cpf_recAtivos, $nip_recAtivos,$exAnterior,$exAtual,$valor_recAtivos, $motivostory, $natureza_despesa){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://desenvolvimento.dgom.mb:3000/handle';
 $codigoServico= 1541;
}
elseif ($ambiente=='P')
{
 $url = 'http://10.9.17.22:3000/handle';
 $codigoServico= 11860;
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}

$referencia=123;
$nomeContribuinte= $nome_recAtivos;
//$competencia=str_replace("/","",$_POST["competencia"]);
$competencia = "032022";
/*if ($_POST["vencimento"]=="")
{
 $vencimento=$dataVencimento;
}
else
{
 $vencimento=date("dmY",strtotime($_POST["vencimento"]));
 if (strtotime($_POST["vencimento"])<strtotime(date('Y-m-d')))
 {
  echo '<script type="text/javascript">alert("Data de vencimento menor que data atual.");</script>';
  echo '<script type="text/javascript">history.back();</script>';
  exit;
 }       
}

$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = intval("30".$datames.$dataano);*/
//$vencimento= 30042022; // ajustando a data;
/*$dataano = date("Y");
$datames = date("m");
$datadia = 21; //date("d");
if($datames == "12"){
$dataano = $dataano + 1;	
$datafinal = $datadia."01".$dataano;	
}else{
//$dataVencimento = 30042022;
$datames = $datames + 1;
if($datames < 10){
$datafinal = $datadia."0".$datames.$dataano;	
}else{	
$datafinal = $datadia.$datames.$dataano;
}
}
$dataVencimento = $datafinal;
$vencimento = $datafinal;*/

$date = date_create();
date_add($date, date_interval_create_from_date_string('30 days'));
$Vencimento = date_format($date, 'Y-m-d');
$partesVencimento = explode("-",$Vencimento);
$dataVencimento=$partesVencimento[2].$partesVencimento[1].$partesVencimento[0];


if($tipotributo == 2){
$tipotributo=0;	
}else{
$tipotributo=1;	
}  
// 2 = Não tributavel | 1 = tributavel 

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
$valorB=str_replace(".","",$$valorB);
$valorB=str_replace(",",".",$valorB);
//$valorDescontos=str_replace(".","",$_POST["valorDescontos"]);
//$valorDescontos=str_replace(",",".",$valorDescontos);
$valorDescontos = 0;
//$valorOutrasDeducoes=str_replace(".","",$_POST["valorOutrasDeducoes"]);
//$valorOutrasDeducoes=str_replace(",",".",$valorOutrasDeducoes);
$valorOutrasDeducoes = 0;
//$valorMulta=str_replace(".","",$_POST["valorMulta"]);
//$valorMulta=str_replace(",",".",$valorMulta);
$valorMulta = 0;
//$valorJuros=str_replace(".","",$_POST["valorJuros"]);
//$valorJuros=str_replace(",",".",$valorJuros);
$valorJuros = 0;
//$valorOutrosAcrescimos=str_replace(".","",$_POST["valorOutrosAcrescimos"]);
//$valorOutrosAcrescimos=str_replace(",",".",$valorOutrosAcrescimos);

$pieces_om_recolhedora = explode("-",$nome_OM);
$indicativoNaval = $pieces_om_recolhedora[0];
$nomeOmRecolhedora = $pieces_om_recolhedora[1]; 

$pieces_OC = explode("-",$nome_OC);
$indicativoNavalOC = $pieces_OC[0];
$nomeOmRecolhedoraOC = $pieces_OC[1];

$indicativoNaval = "SVCVPM"; 

$valorOutrosAcrescimos = 0;
$data = array(
  "cat" => "PAPEM",	
  "codigoServico" => $codigoServico,
  "vencimento" => $dataVencimento,
  "competencia" => $competencia,
  "nomeContribuinte" =>  $nomeContribuinte,
  "cnpjCpf" => $cpf_recAtivos,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "valorPrincipal" => $valor_recAtivos,
  "nomeUG" => $indicativoNaval,
  "cat_servico" => "PAPEM",
  "codRubrica"=> "n/a",
  "nomeRubrica"=> "n/a",
  "motivo"=> $motivostory,
  "tributavel"=> $tipotributo,
  "nomeOC"=> $indicativoNaval,
  "valorBrutoExercAnt"=> $exAnterior,
  "codSiapeNip"=> $nip_recAtivos,
  "modoNavegacao" => "2",
  "tema" => "tema-light",
  "NatDev" => $natureza_despesa,
  "valorExercAtual" => $exAtual
  
);    
$data_string = json_encode($data);
$ch = curl_init($url);
//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt ($ch, CURLOPT_CAINFO, "MarinhadoBrasilAutoridadeCertificadoradaRECIM-chain.pem");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

if ($result === false)
{
 echo 'Erro: '.curl_error($ch);
}            
else
{
 $result=json_decode($result);
 if (is_array($result))
 {
  $i=1;
  while ($i<=count($result))
  {
   echo '<p style="text-align:center;">ERRO: '.$result[$i-1]->{'codigo'}."-".$result[$i-1]->{'descricao'}.'<br>';
   $i++;
  }
  echo '<a href="javascript:history.back()">Voltar</a><p>';
 }
 else
 {
  echo '<script type="text/javascript">window.open(\''.$result->{'proximaUrl'}.'\', \'_blank\');</script>';
  echo '<script type="text/javascript">history.back();</script>';
  }
 }
}

function validarCPF($cpf) {
 
         
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return "false";
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return "false";
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return "false";
        }
    }
    return "true";

}

	
	
}
?>