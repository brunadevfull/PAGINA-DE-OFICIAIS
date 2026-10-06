<?php
 //responsavel pela comunicação com o pagTesouro
class PagTesouro{
	function servidorCivil($nome,$vencimento,$cpfcnpj,$valor, $controlador_de_pagamento){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}

if($controlador_de_pagamento == 1){
$codigoServico= 1541;
}else{
$codigoServico= 1541;	
}
$referencia=123;
$nomeContribuinte= $nome;
//$competencia=str_replace("-","",$_POST["vencimento"]); 
$competencia ="022022";
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

$vencimento= 31032022; // ajustando a data;
$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = 31032022;

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
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

/*Na te 
*/

$valorOutrosAcrescimos = 0;
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => 1541,
  "competencia" => $competencia,
  "vencimento" => $dataVencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
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
	
	function veteranoAnistiado($nome, $vencimento, $cpfcnpj, $Nip, $ValorRecolhido, $ParcelaDevolvidas, $controlador_de_pagamento){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}
if($controlador_de_pagamento == 1){
$codigoServico= 1541;
}else{
$codigoServico= 1541;	
}
$referencia=$_POST["referencia"];
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
$vencimento= 31032022; // ajustando a data;
$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = 31032022;

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$ValorRecolhido);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
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
$valorOutrosAcrescimos = 0;
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => $referencia,
  "competencia" => $competencia,
  "vencimento" => $dataVencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
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
	
	function pensionista($nome, $vencimento, $cpfcnpj, $Nip, $ValorRecolhido, $ParcelaDevolvidas, $controlador_de_pagamento){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}
if($controlador_de_pagamento == 1){
$codigoServico= 1541;
}else{
$codigoServico= 1541;	
}	
$referencia=$_POST["referencia"];
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
$vencimento= 31032022; // ajustando a data;
$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = 31032022;

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$ValorRecolhido);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
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
$valorOutrosAcrescimos = 0;
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => $referencia,
  "competencia" => $competencia,
  "vencimento" => $dataVencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
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
	
	function militarAtivo($nome, $vencimento, $cpfcnpj, $Nip, $ValorRecolhido, $ParcelaDevolvidas, $controlador_de_pagamento){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}
if($controlador_de_pagamento == 1){
$codigoServico= 1541;
}else{
$codigoServico= 1541;	
}
$referencia= 123;
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
$vencimento= 31032022; // ajustando a data;
$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = 31032022;

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$ValorRecolhido);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
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
$valorOutrosAcrescimos = 0;
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => $referencia,
  "competencia" => $competencia,
  "vencimento" => $dataVencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
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
	
	function sisresSC($nome,$cpfcnpj, $matsiape, $valor){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}
$codigoServico= 1541;
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
$vencimento= 31032022; // ajustando a data;
$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = 31032022;

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
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
$valorOutrosAcrescimos = 0;
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => $referencia,
  "competencia" => $competencia,
  "vencimento" => $dataVencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
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

function sisresOutros($nome,$cpfcnpj, $nip, $oc, $valor, $OmRecolhedora){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}
$codigoServico= 1541;
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
$vencimento= 31032022; // ajustando a data;
$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = 31032022;

$cnpjCpf=str_replace("/","",$cpfcnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
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
$valorOutrosAcrescimos = 0;
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => $referencia,
  "competencia" => $competencia,
  "vencimento" => $dataVencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
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

function municiamentoOM($nome,$cnpj, $valor){
		$chave="eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiI3NzMyMDAifQ.hCTTOPrhcuSEc9wtzzzy4WLm9CCo4ZqSYgeulNKNqkcuKgN2es3EuA8mnKY6ybHhKsNwOC35HNM_L8-ayEE8Jz25NUjrlyzHUHzGcdgVX9P2vA4WUt4hqGj0KF0TLfK4yJnqoqef7PEeo1zQp5hGveVo5xYjj-jCI5tSZTYhDeK0ccepgPNhVQ5PuFIhT7ViPj8MUKe0qMBc-djIvGr1r3DGk5nBjAMatk00vXVfiJPTgJquhXoTTRQfYRvZd44o8lFYlnkSWO3KhF7sQSAG5sTnF9TBsWi9czwzwr2dYCwEJ8600eLeMDDlaYhajl8DHRoIaAnvxt32fIe5Wwd_Cw";
$ambiente="H";

if ($ambiente=='H')
{
 $url = 'https://valpagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
elseif ($ambiente=='P')
{
 $url = 'https://pagtesouro.tesouro.gov.br/api/gru/solicitacao-pagamento';
}
else
{
 echo '<p style="text-align:center;">Erro da variável ambiente, valores válidos são H ou P</p>';
 exit;
}
$codigoServico= 1861;
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
$vencimento= 31032022; // ajustando a data;
$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = 31032022;

$cnpjCpf=str_replace("/","",$cnpj);
$cnpjCpf=str_replace(".","",$cnpjCpf);
$cnpjCpf=str_replace("-","",$cnpjCpf);
$valorPrincipal=str_replace(".","",$valor);
$valorPrincipal=str_replace(",",".",$valorPrincipal);
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
$valorOutrosAcrescimos = 0;
$data = array(
  "codigoServico" => $codigoServico,
  "referencia" => $referencia,
  "competencia" => $competencia,
  "vencimento" => $dataVencimento,
  "cnpjCpf" => $cnpjCpf,
  "nomeContribuinte" =>  $nomeContribuinte,
  "valorPrincipal" => $valorPrincipal,
  "valorDescontos" => $valorDescontos,
  "valorOutrasDeducoes" => $valorOutrasDeducoes,
  "valorMulta" => $valorMulta,
  "valorJuros" => $valorJuros,
  "valorOutrosAcrescimos" => $valorOutrosAcrescimos,
  "modoNavegacao" => "2"
);
$data_string = json_encode($data);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer '.$chave));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);
if ($result === false)
{
 echo '<p style="text-align:center;">Erro ao acessar o site do PagTesouro</p>';
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

	
	
}
?>