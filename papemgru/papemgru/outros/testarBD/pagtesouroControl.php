<?php
//preparando variaveis
include "PagTesouroClasse.php"; 
$control = $_POST['naturezaD'];

if($control == "Ppessoal"){
$sf = $_POST['situacaoFuncional'];	
switch($sf){
	case $sf == "servidorCivil":
	echo $S_nome = $_POST["servidorNome"];
	echo $S_vencimento = $_POST["servidorMesrecebimentoIndevido"];
	echo $S_cpfcnpj = $_POST["servidorcpfcnpj"];
	echo $S_valor = $_POST["valorBruto"];
	
	
	$servidorC = new PagTesouro;
	$servidorC->servidorCivil($S_nome, $S_vencimento, $S_cpfcnpj, $S_valor);	
	break;
	
	case $sf == "veterano":
	$V_nome = $_POST["veteranoNome"];
	$V_vencimento = $_POST["veteranoVencimento"];
	$V_cpfcnpj = $_POST["veteranoCpfCnpj"];
	$V_Nip = $_POST["veteranoNip"];
	$V_ValorRecolhido = $_POST["valorBruto"];
	$V_ParcelaDevolvidas = $_POST["veteranoParcelasDevolvidas"];
	
	// 01-> ano atual / 02-> ano anterior
	
	
	$servidorV = new PagTesouro;
	$servidorV->veteranoAnistiado($V_nome, $V_vencimento, $V_cpfcnpj, $V_Nip, $V_ValorRecolhido, $V_ParcelaDevolvidas);
	break;
	
	case $sf == "pensionista":
	$P_nome = $_POST["pensionistaNome"];
	$P_vencimento = $_POST["pensionistaVencimento"];
	$P_cpfcnpj = $_POST["pensionistaCpfCnpj"];
	$P_Nip = $_POST["pensionistaNip"];
	$P_ValorRecolhido = $_POST["valorBruto"];
	$P_ParcelaDevolvidas = $_POST["pensionistaParcelasDevolvidas"];
	
	// 01-> ano atual / 02-> ano anterior / 03->
	$pieces = explode("-",$P_vencimento);
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
	}
	
	$servidorP = new PagTesouro;
	$servidorP->pensionista($P_nome, $P_vencimento, $P_cpfcnpj, $P_Nip, $P_ValorRecolhido, $P_ParcelaDevolvidas, $controlador_de_pagamento);
	break;
	
	case $sf == "militarAtivo":
	$A_nome = $_POST["ativaNome"];
	$A_vencimento = $_POST["ativaVencimento"];
	$A_cpfcnpj = $_POST["ativaCpfCnpj"];
	$A_Nip = $_POST["ativaNip"];
	$A_ValorRecolhido = $_POST["valorBruto"];
	$A_ParcelaDevolvidas = $_POST["ativaParcelasDevolvidas"];
	
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
	}
	
	$servidorA = new PagTesouro;
	$servidorA->militarAtivo($A_nome, $A_vencimento, $A_cpfcnpj, $A_Nip, $A_ValorRecolhido, $A_ParcelaDevolvidas, $controlador_de_pagamento);
	break;
}	
}else if($control == "Municiamento"){
	
	$OmRecolhedora = $_POST['municiamentoOmRecolhedora'];
	$CnpjOm = $_POST['cnpjOmRecolhedora'];
	$Valor = $_POST['valorOmRecolhedora'];
	
	$municiamentoA = new PagTesouro;
	$municiamentoA->municiamentoOM($OmRecolhedora, $CnpjOm, $Valor);
	
}else if($control == "Sisres") {
	$sisres = $_POST['tipoSisres'];	
    switch($sisres){
		
		case $sisres == "SisresSC";
		$sisresSC_nome = $_POST["sisresSC_nome"];
		$sisresSC_cpf = $_POST["sisresSC_cpf"];
		$sisresSC_MatSiape = $_POST["sisresSC_MatSIAPE"];
		$sisresSC_Valor = $_POST["valorBruto"];
		
		$sisresA = new PagTesouro;
		$sisresA->sisresSC($sisresSC_nome, $sisresSC_cpf, $sisresSC_MatSiape, $sisresSC_Valor);
		
		break;
		
		case $sisres == "SisresOutros";
		
		echo $sisresOutros_nome = $_POST["sisresOutros_nome"];
		echo $sisresOutros_cpf = $_POST["sisresOutros_cpf"];
		echo $sisresOutros_nip = $_POST["sisresOutros_Nip"];
		echo $sisresOutros_oc = $_POST["sisresOutros_OC"];
		echo $sisresOutros_Valor = $_POST["valorBruto"];
		echo $sisresOutros_OmRecolhedora = $_POST["sisresOutros_Recolhedora"];
		
		$sisresB = new PagTesouro;
		$sisresB->sisresOutros($sisresOutros_nome, $sisresOutros_cpf, $sisresOutros_nip, $sisresOutros_oc, $sisresOutros_Valor, $sisresOutros_OmRecolhedora);
		
		break;
	}
}
?>