<?php
//preparando variaveis
include "PagTesouroClasse.php";
if(!empty($_POST['naturezaD'])){
	 $control = $_POST['naturezaD'];
	 if($control == "recAtivos" ){
		$_POST['TipoTributo'] = 1;
		$_POST['ParcelasDevolvidas'] = "123-teste";
	 }
	}else{
		$mg="Os proximos campos ";
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=abaixo");	
	}
echo $motivostory = $_POST['story'];
echo $motivostoryPP = $_POST['storyPP'];

// $control == "Sisres" -> não tem tributavel no Sisres
if($control == "Ppessoal"){	
if(!empty($_POST['TipoTributo'])){
	 $tipotributo = $_POST['TipoTributo'];
	}else{
		$mg="Os proximos campos ";
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=abaixo");	
	} 
if($_POST['situacaoFuncional'] == "servidorCivil"){
	$codRubrica = $_POST['ParcelasDevolvidasCodR'];
	$nomeRubrica = $_POST['ParcelasDevolvidasNomeR'];
}else{	
$parcelaDevolvida = $_POST['ParcelasDevolvidas'];
$parcela = explode("-",$parcelaDevolvida);
echo $codRubrica = $parcela[0];
echo $nomeRubrica = $parcela[1];
}
}
echo $motivo = $_POST['story']."<br>";
echo $nome_OM = $_POST['sisresOutros_Recolhedora'];

//echo $_POST['sisresOutros_OC']."1<br>";OC
//echo $_POST['sisresOutros_Recolhedora']."3<br>";OM VeteranoAnistiado4


if($control == "Ppessoal"){
$sf = $_POST['situacaoFuncional'];	
switch($sf){
	case $sf == "servidorCivil":
	if(!empty($_POST["servidorNome"])){
	 $S_nome = $_POST["servidorNome"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Nome");	
	}
	
	if(!empty($_POST["servidor4"])){
	 echo $S_vencimento = $_POST["servidor4"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Mes");	
	}
	
	if(!empty($_POST["servidorcpfcnpj"])){
	 echo $S_cpfcnpj = $_POST["servidorcpfcnpj"];
	}else{
	 $S_cpfcnpj = 1;	
	}
	
	if(!empty($nome_OM)){
	 echo $nome_OM = $nome_OM;
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=OM");	
	}
	$nome_OC = $_POST['sisresOutros_UPAG'];
	if(!empty($nome_OC)){
	 echo $nome_OC = $nome_OC;
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=OC");	
	}

	$servidor_MatSIAPE = $_POST['servidor_MatSIAPE'];
	if(!empty($_POST['servidor_MatSIAPE'])){
	 $servidor_MatSIAPE = $_POST['servidor_MatSIAPE'];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=MatriculaSiape");	
	}
	 
	 
	 if($tipotributo == 2){
		 echo $S_valor = $_POST["valorBruto"];
		      $S_valorB = $_POST["valorBruto"];
	 }else{
		 echo $S_valor = $_POST["valorLiquido"];
		      $S_valorB = $_POST["valorBruto"];
			  
	 };
	 echo "esse é o tipo: ".$tipotributo;
	//echo $S_nome, $S_vencimento, $S_cpfcnpj, $S_valor, $codRubrica, $nomeRubrica, $tipotributo, $motivo;
	$DataCompetencia = explode("-",$_POST['servidor4']);
	$competenciaDate =$DataCompetencia[0].$DataCompetencia[1];
	$natureza_despesa = "Pagamento de Pessoal";
	$servidorC = new PagTesouro;
	if($S_cpfcnpj == 1){
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");	
	}else{
		$validador = new PagTesouro;
        echo $validador->validarCPF($_POST["servidorcpfcnpj"]);
		$validarCPF = $validador;
	if($validador->validarCPF($_POST["servidorcpfcnpj"]) == "true"){		
//tratamento feito, só falta mexer o nome das variaveis referente a rubrica
$parcelaRubrica = explode("-",$codRubrica);
echo $codRubrica = $parcelaRubrica[0];
echo $nomeRubrica = $parcelaRubrica[1];
$validador = new PagTesouro;
echo $validador->validarCPF($S_cpfcnpj);		
$servidorC->servidorCivil($S_nome, $S_vencimento, $S_cpfcnpj, $S_valor, $S_valorB, $codRubrica, $nomeRubrica, $tipotributo, $nome_OM, $nome_OC, $motivostoryPP, $competenciaDate, $natureza_despesa, $servidor_MatSIAPE);	
	}else{
		header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");
	}}
	break;
	
	case $sf == "militarAtivo":
	if(!empty($_POST["ativaNome"])){
	 $A_nome = $_POST["ativaNome"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Nome");	
	}
	if(!empty($_POST["ativa4"])){
	 $A_vencimento = $_POST["ativa4"];
	}else{
		//header("location:https://10.1.129.16/papemgru/papem.php?erro=Mes");	
	}
	
	if(!empty($_POST["ativaCpfCnpj"])){
	 $A_cpfcnpj = $_POST["ativaCpfCnpj"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");	
	}

    if(!empty($_POST["ativaNip"])){
	 $A_Nip = $_POST["ativaNip"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Nip");	
	}

    if(!empty($_POST["ParcelasDevolvidas"])){
	 $A_ParcelaDevolvidas = $_POST["ParcelasDevolvidas"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Parcela");	
	}
	
	if(!empty($nome_OM)){
	 echo $nome_OM = $nome_OM;
	}else{
	 header("location:https://10.1.129.16/papem.php?erro=OM");	
	}
	$nome_OC = $_POST['sisresOutros_OC'];
	if(!empty($nome_OC)){
	 echo $nome_OC = $nome_OC;
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=OC");	
	} 
	 	
	
	if($tipotributo == 2){
		 echo $A_ValorRecolhido = $_POST["valorBruto"];
		      $A_ValorRecolhidoB = $_POST["valorBruto"];
	 }else{
		 echo $A_ValorRecolhido = $_POST["valorLiquido"];
		      $A_ValorRecolhidoB = $_POST["valorBruto"];
	 };
	
	// 01-> ano atual / 02-> ano anterior
	$pieces = explode("-",$A_vencimento);
	if($pieces[0] == date("Y")){
		if($pieces[2] > (date("d")) and $pieces[1]== date("m")){
			$controlador_de_pagamento = "não existe divida futura";
		}else if($pieces[1] > 0 and $pieces[1] < (date("m")+1)){
			$controlador_de_pagamento = 1;
		}else{
			$controlador_de_pagamento = "não existe divida futura";
		}		
	}else if($pieces[1] == 12){
		$controlador_de_pagamento = 1;		
	}else{
		$controlador_de_pagamento = 2;
	
	$DataCompetencia = explode("-",$_POST['ativa4']);
    $competenciaDate =$DataCompetencia[0].$DataCompetencia[1];
	$natureza_despesa = "Pagamento de Pessoal";
	$servidorA = new PagTesouro;
	$validador = new PagTesouro;
	if( $validador->validarCPF($_POST["ativaCpfCnpj"])=="true"){
	 if(!empty($A_Nip)){
		echo "<br>confirmação 01:".$A_nome;
		echo "<br>2:".$A_vencimento;
		echo "<br>3:".$A_cpfcnpj;
		echo "<br>4:".$A_Nip;
		echo "<br>5:".$A_ValorRecolhido;
		echo "<br>6:".$A_ValorRecolhidoB;
		echo "<br>7:".$A_ParcelaDevolvidas;
		echo "<br>8:".$codRubrica;
		echo "<br>9:".$nomeRubrica;
		echo "<br>10:".$tipotributo;
		echo "<br>11:".$nome_OM;
		echo "<br>12:".$nome_OC;
		echo "<br>13:".$motivostoryPP;
		echo "<br>14:".$competenciaDate;
		echo "<br>15:".$natureza_despesa;
	 $servidorA->militarAtivo($A_nome, $A_vencimento, $A_cpfcnpj, $A_Nip, $A_ValorRecolhido, $A_ValorRecolhidoB, $A_ParcelaDevolvidas, $codRubrica, $nomeRubrica, $tipotributo, $nome_OM, $nome_OC, $motivostoryPP, $competenciaDate, $natureza_despesa);
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Nip");	
	}
	}else{
		header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");	
	}
	
	break;
}	
}}
if($control == "recAtivos"){
	
	$nome_recAtivos = $_POST['recAtivos_nome'];
	$cpf_recAtivos = $_POST['recAtivos_cpf'];
	$nip_recAtivos = $_POST['recAtivos_Nip'];
	$recAtivos_exAtual = floatval($_POST['recAtivos_exAtual']); 
	$valor_w = explode(",",$_POST['recAtivos_exAtual']);
	echo $valor_w[0];
	echo $valor_w[1];
	$e = array($valor_w[0],$valor_w[1]);
	$g = implode(".",$e);
	echo $g;
	$valor_p = explode(",",$_POST['recAtivos_exAnterior']);
	$a = array($valor_p[0],$valor_p[1]);
	$b = implode(".",$a);
	$b;
	$valor_recAtivos = floatval($g) + floatval($b);
	echo $natureza_despesa = "Recuperação de Ativos";
	
	echo $nome_recAtivos;
	echo $cpf_recAtivos."<br>";
	echo $valor_recAtivos;
    echo $motivostory."final";
	$exAnterior = $b;
	$exAtual= $g;
	$recAtivosA = new PagTesouro;
	echo $nip_recAtivos;
	$validador = new PagTesouro;
	if(!empty($cpf_recAtivos) and $validador->validarCPF($_POST['recAtivos_cpf'])=="true"){
	 $recAtivosA->recAtivosB($nome_recAtivos, $cpf_recAtivos, $nip_recAtivos, $exAnterior, $exAtual, $valor_recAtivos, $motivostory, $natureza_despesa);
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=CPF");	
	}
	//$recAtivosA->recAtivosB($nome_recAtivos, $cpf_recAtivos, $nip_recAtivos, $exAnterior, $exAtual, $valor_recAtivos, $motivostory, $natureza_despesa);
	
}else if($control == "Sisres") {
	$sisres = $_POST['tipoSisres'];	
    switch($sisres){
		
		case $sisres == "SisresSC";
	if(!empty($_POST["sisresSC_nome"])){
	 $sisresSC_nome = $_POST["sisresSC_nome"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Nome");	
	}
	
	if(!empty($_POST["sisresSC_cpf"])){
	 $sisresSC_cpf = $_POST["sisresSC_cpf"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");	
	}
	
	if(!empty($_POST["sisresSC_MatSIAPE"])){
	 $sisresSC_MatSiape = $_POST["sisresSC_MatSIAPE"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Matricula");	
	}
		
		
		
		//configurar o valor recolhido + remover tributavel e o nao tributavel + remover rubrica 
		/*if($tipotributo == 2){
		 echo $sisresSC_Valor = $_POST["valorBruto"];
		      $sisresSC_ValorB = $_POST["valorBruto"];
	 }else{
		 echo $sisresSC_Valor = $_POST["valorLiquido"];
		      $sisresSC_ValorB = $_POST["valorBruto"];
	 }; Valor Liquido e Bruto, não seria usado nesse fluxo, por isso nasceu o valor recolhido */
		$nome_OC = $_POST['sisresOutros_UPAG'];
		$sisresSC_Valor = $_POST["sisresSC_valorRecolhido"];
		$sisresSC_ValorB =0;
		$natureza_despesa = "SISRES";
		$sisresA = new PagTesouro;
		echo "<br>1".$sisresSC_nome;
		echo "<br>2".$sisresSC_cpf;
		echo "<br>3".$sisresSC_MatSiape;
		echo "<br>4".$sisresSC_Valor;
		echo "<br>5".$sisresSC_ValorB;
		echo "<br>6".$nome_OC;
		echo "<br>7".$nome_OM;
		echo "<br>8".$motivostory;
		echo "<br>9".$natureza_despesa;
		$validador = new PagTesouro;
	if(!empty($_POST["sisresSC_cpf"])and $validador->validarCPF($_POST["sisresSC_cpf"])=="true"){
	 $sisresA->sisresSC($sisresSC_nome, $sisresSC_cpf, $sisresSC_MatSiape, $sisresSC_Valor, $sisresSC_ValorB, $nome_OC, $nome_OM , $motivostory, $natureza_despesa);
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");	
	}
		//$sisresA->sisresSC($sisresSC_nome, $sisresSC_cpf, $sisresSC_MatSiape, $sisresSC_Valor, $sisresSC_ValorB, $nome_OC, $nome_OM , $motivostory, $natureza_despesa);
		
		break;
		
		case $sisres == "SisresOutros";
	if(!empty($_POST["sisresOutros_nome"])){
	 $sisresOutros_nome = $_POST["sisresOutros_nome"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Nome");	
	}
	
	if(!empty($_POST["sisresOutros_cpf"])){
	 $sisresOutros_cpf = $_POST["sisresOutros_cpf"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");	
	}

    if(!empty($_POST["sisresOutros_Nip"])){
	 $sisresOutros_nip = $_POST["sisresOutros_Nip"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Nip");	
	}

    if(!empty($_POST["sisresOutros_OC"])){
	 $sisresOutros_oc = $_POST["sisresOutros_OC"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=OC");	
	}
	
	if(!empty($_POST["sisresOutros_Recolhedora"])){
	 $sisresOutros_OmRecolhedora = $_POST["sisresOutros_Recolhedora"];
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=OM");	
	}	
		
		/*if($tipotributo == 2){
		 echo $sisresOutros_Valor = $_POST["valorBruto"];
		      $sisresOutros_ValorB = $_POST["valorBruto"];
	 }else{
		 echo $sisresOutros_Valor = $_POST["valorLiquido"];
		      $sisresOutros_ValorB = $_POST["valorBruto"];
	 };*/
	    $sisresOutros_oc = $_POST['sisresOutros_OC'];
	    $sisresOutros_Valor = $_POST["sisresOutros_valorRecolhido"];
		$sisresOutros_ValorB =0;
		$natureza_despesa = "SISRES";		
		$sisresB = new PagTesouro;
		$validador = new PagTesouro;
	if(!empty($_POST["sisresOutros_cpf"])and $validador->validarCPF($_POST["sisresOutros_cpf"])=="true"){
	 $sisresB->sisresOutros($sisresOutros_nome, $sisresOutros_cpf, $sisresOutros_nip, $sisresOutros_oc, $sisresOutros_Valor, $sisresOutros_ValorB, $sisresOutros_OmRecolhedora, $motivostory, $natureza_despesa);
	}else{
	 header("location:https://10.1.129.16/papemgru/papem.php?erro=Cpf");	
	}
		
		//$sisresB->sisresOutros($sisresOutros_nome, $sisresOutros_cpf, $sisresOutros_nip, $sisresOutros_oc, $sisresOutros_Valor, $sisresOutros_ValorB, $sisresOutros_OmRecolhedora, $motivostory, $natureza_despesa);
		
		break;
	}
}

?>