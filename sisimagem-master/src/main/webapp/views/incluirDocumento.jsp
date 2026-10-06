<jsp:useBean id="documento" class="bean.Documento" scope="session" />
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>

<%@page import="controller.Chooser"%>
<%@page import="controller.Utilitaria"%>
<%@page import="bean.Registro"%>
<%@page import="bean.TipoRegistro"%>
<%@page import="bean.Usuario"%>

<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<%@page import="java.io.File"%>
<%@page import="java.io.IOException"%>

<!--<html xmlns="http://www.w3.org/1999/xhtml"> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<title>SisImagem - Incluir Documento</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="../js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.3.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
<!-- <script type="text/javascript" src="../js/validar.js" ></script>-->
<script type="text/javascript" src="../js/validacao.js" ></script>
<script type="text/javascript" src="../js/tablecloth.js"></script>

<script language="JavaScript">
function addAnexo() {
    var c = document.createElement("option");
    c.text = document.incluirDocumento.nomeAnexo.value;
	document.incluirDocumento.anexo.add(c,document.incluirDocumento.anexo.options.length);
	
	var x = document.incluirDocumento.getElementById("anexo");
    var txt = "";
    var i;
    for (i = 0; i < x.length; i++) {
        txt = txt + x.options[i].text + "<br>";
    }
    document.incluirDocumento.anexoAtributo.innerHTML = txt;
}
function validaPasta(){
	
	if(	document.getElementById("recordType").value==="4" || document.getElementById("recordType").value==="12"){
		if((document.getElementById("recordType").value==="4")) {
			document.getElementById("classificacao").value ="PAPEM - PAPEM40 - PAPEM41";
		 	document.getElementById("pasta").value ="D11/1";
		 
		}else if((document.getElementById("recordType").value==="12")){
			document.getElementById("classificacao").value ="PAPEM - PAPEM40 - PAPEM42";
		 	document.getElementById("pasta").value ="";	 
		}
		document.incluirDocumento.action="../controller/controlador?cmd=buscaProximoRecordId";
		document.incluirDocumento.submit();
	}else{
		 document.incluirDocumento.action="../views/pesquisar.jsp";
		 document.incluirDocumento.submit();	
	
	}
	
}
function validacamposPesquisar()
{
	if(document.incluirDocumento.dataDocumento.value        === ""){
		alert("Campo 'Data do documento' é obrigatório");
	}
	else if(document.incluirDocumento.dataInclusao.value               === ""){
		alert("Campo 'Data de inclusão' é obrigatório");
	}
	else if(document.incluirDocumento.pasta.value               === ""){
		alert("Campo 'Pasta' é obrigatório");
	}
	else if(document.incluirDocumento.nomeArquivoOriginal.value === ""){
		alert("Campo 'Documento' é obrigatório");
	}
	else if(document.incluirDocumento.recordNumber.value === ""){
		alert("Campo 'Processo' é obrigatório");
	}
	
	else if(document.incluirDocumento.beneficiario.value          === ""  &&
	   document.incluirDocumento.consignado.value            === ""  &&
	   document.incluirDocumento.dataProtocolo.value         === ""  &&
	   document.incluirDocumento.nipMatricula.value          === ""  &&
	   document.incluirDocumento.numeroDocumento.value       === ""  &&
	   document.incluirDocumento.origem.value                === ""  &&
	   document.incluirDocumento.protocolo.value             === ""  &&
	   document.incluirDocumento.tipoDocumento.value         === ""  &&  
	   document.incluirDocumento.cpf.value                   === ""  &&  
	   document.incluirDocumento.entidadeConsignataria.value === "" ){
	  alert("Preencha um dos campos opcionais");
	
    }else{
    document.incluirDocumento.action="../controller/controlador?cmd=incluirDocumento";
	document.incluirDocumento.submit();
	}
}	

function time(){
	today = new Date();
	h = today.getHours();
	m = today.getMinutes();
	s = today.getSeconds();

	d = today.getDate(); // busca o dia
	mes = today.getMonth();
	mes = mes + 1;
	y = today.getFullYear();

	if (d < 10){ d = "0" + d; }
	if (mes < 10){ mes = "0" + mes; }
	if (h < 10){ h = "0" + h; }
	if (m < 10){ m = "0" + m; } 	
	if (s < 10){ s = "0" + s; }

	diaMesAno = d + "/" + mes + "/" + y;
	horaMinutoSegundo = h + ":" + m + ":" + s;

	var dataInclusao = document.getElementById("dataInclusao");
	dataInclusao.value = diaMesAno + " " + horaMinutoSegundo;
	//dataInclusao.value = diaMesAno;

	var dataDocumento = document.getElementById("dataDocumento");
	dataDocumento.value = diaMesAno + " " + horaMinutoSegundo;		
	
	//setTimeout('time()',500);
}
</script>
</head>
<body onload="time()">
<%
    Object mensagem = request.getAttribute("mensagem");
    String valor;
    if (mensagem == null){
    	valor = "";
    }else{
    	valor = (String) mensagem;
    }
      // pegar o código do usuário do controle de acesso
     //session.setAttribute("usuario", "41");
%>
<p class="mensagem"><%=valor %></p>

<p class="titulo">Incluir Documento</p>


<form id="incluirDocumento" name="incluirDocumento" method="post" enctype="multipart/form-data" action="../controller/controlador?cmd=incluirDocumento">
<center>
<div class="divisao incluirDocumento" >

<div class="conteudo contDocumentoLeft">
		
		<div class="divsLabel esquerda topo0">
	
	<label>Tipo Registro</label>&nbsp;
		<%
		
		Registro registro = new Registro();
		
		Iterator it = registro.lTipoRegistro.iterator();
		TipoRegistro tipoRegistro;
		
		%>
		<% System.out.println(documento.getRecordType()); %>
		<select name="recordType" id="recordType" onchange="validaPasta()" >
			<option value=""></option>
		<% 
		while(it.hasNext()){
		tipoRegistro = (TipoRegistro) it.next();
		%>
		<option value="<%=tipoRegistro.getId() %>" 
		<% if(documento.getRecordType()!= null && documento.getRecordType().equals(tipoRegistro.getId().toString())){%>
		selected
		<%} %>
		><%=tipoRegistro.getNome() %></option>		
		<%
		}
		%>
		
		</select>
		</div>
	 <div id="camposInclusao" 
	 <%if(documento.getRecordType()!= null&&( documento.getRecordType().equals("4") || documento.getRecordType().equals("12") || documento.getRecordType().equals("5")) ){%>
		style="visibility:visible"
		<%}else{ %>	
		style="visibility:hidden"
		<%} %>>	 
	  <div class="divsLabel meioClass topo0">
		<label>Classificação</label>&nbsp;		
			<input type="text" size="35" name="classificacao" id="classificacao" readonly="readonly" value="<jsp:getProperty property="classificacao" name="documento"/>" />
	  </div>
	    <div class="divsLabel esquerda topo40">
		<label id="lPasta">Pasta </label>&nbsp;
		<input type="text" size="21" name="pasta" id="pasta" readonly="readonly" value="<jsp:getProperty property="pasta" name="documento"/>" />
		</div>
		<div class="divsLabel meio topo40">
		<label id="lProcesso">Processo<font color="red">*</font></label>&nbsp;
		<input type="text" size="21" name="recordNumber" id="recordNumber" value="<jsp:getProperty property="recordNumber" name="documento"/>" />
		</div>
		
		
		<div class="divsLabel esquerda topo80">
		 	<label>Data do Documento <font color="red">*</font></label> &nbsp;
			<input type="text" size="29" name="dataDocumento" id="dataDocumento" 
			value="<jsp:getProperty property="dataDocumento" name="documento"/>"/>
		</div>
  
		<div class="divsLabel meio topo80">
			<label>Data de Inclusão <font color="red">*</font></label> &nbsp;
			<input type="text" size="29" name="dataInclusao" id="dataInclusao" readonly="readonly"
			value="<jsp:getProperty property="dataInclusao" name="documento"/>"/> 
		</div>
		
		<div class="divsLabel esquerdaNipMat topo120">
		<label>NIP/Matrícula </label> &nbsp;
		<input id="nipMatricula" size="13" type="text" name="nipMatricula" maxlength="9" minlength="8" value="<jsp:getProperty property="nipMatricula" name="documento"/>"/>
		</div>
				
		<div class="divsLabel meioCPF topo120">
		<label>CPF</label> &nbsp;
		<input type="text" size="25" name="cpf" id="cpf" value="<jsp:getProperty property="cpf" name="documento"/>"/>
		</div>
								 
		<div class="divsLabel esquerda topo160">
		<label>Consignado</label> &nbsp;
		<input type="text" size="94" name="consignado" value="<jsp:getProperty property="consignado" name="documento"/>"/>
		</div>
		
		<div class="divsLabel esquerda topo200">
		<label>Beneficiário</label> &nbsp;
		<input type="text" size="94" name="beneficiario" value="<jsp:getProperty property="beneficiario" name="documento"/>"/>
		</div>
	 
		<div class="divsLabel esquerda topo240">
		<label>Título do Documento</label> &nbsp;
		<input type="text" size="86" name="title" id="title"/>
		</div>		
		
		<div class="divsLabel esquerda topo280">
		<label>Tipo de Documento</label> &nbsp;
		<input type="text" size="31" name="tipoDocumento" value="<jsp:getProperty property="tipoDocumento" name="documento"/>"/>
		</div>
		
		<div class="divsLabel meio topo280">
		<label>Número do Documento</label> &nbsp;
		<input type="text" size="24" name="numeroDocumento" value="<jsp:getProperty property="numeroDocumento" name="documento"/>"/>
		</div>
		
		<div class="divsLabel esquerda topo320">
		<label>Protocolo</label> &nbsp;
		<input type="text" size="40" name="protocolo" value="<jsp:getProperty property="protocolo" name="documento"/>"/>
		</div>
		
		<div class="divsLabel meio topo320">
		<label>Data de Protocolo</label> &nbsp;
		<input type="text" size="29" id="dataProtocolo" name="dataProtocolo" value="<jsp:getProperty property="dataProtocolo" name="documento"/>"/>
		</div>

		<div class="divsLabel esquerda topo360">
		<label>Número do Registro</label> &nbsp;
		<input type="text" size="30" id="recordNumber" name="recordNumber" />
		</div>
		
		<div class="divsLabel meio topo360">
		<label>Origem</label> &nbsp;
		<input type="text" size="39" name="origem" value="<jsp:getProperty property="origem" name="documento"/>"/>
		</div>
		
		<div class="divsLabel esquerda topo400">
		<label>Entidade Consignatária</label> &nbsp;
		<input type="text" size="27" name="entidadeConsignataria" value="<jsp:getProperty property="entidadeConsignataria" name="documento"/>"/>
		</div>
		
		<div class="divsLabel meio topo400">
		<label>Observações</label> &nbsp;
		<input type="text" size="33" name="observacoes" value="<jsp:getProperty property="observacoes" name="documento"/>"/>
		</div>
			 
		<div class="divsLabel esquerda topo440">
		<label>Responsável</label> &nbsp;
		<input type="text" size="37" readonly ="readonly" name="responsavel" value="<%=((Usuario)session.getAttribute("usuario")).getUsuario()%>"/>
		</div>
		
		<div class="divsLabel meio topo440">
		<label>Ofício Judicial Anexo</label>&nbsp;
		<select name="oficioJudicialAnexo">
	  		<option value="">Não</option>
	  		<option value="SIM">Sim</option>
		</select>
		</div>
		 
		<div class="divsLabel direita topo440">
		<label>Urgente</label>&nbsp;
		<input type="checkbox" name="urgente" id="urgente" />
		</div>

		<div class="divsLabel esquerda topo480">
		<label>Documento <font color="red">*</font></label> &nbsp;&nbsp;
        <input type="file" name="nomeArquivoOriginal" size="77" />
		</div>
		
<!-- <img src="../images/buscarDoc.fw.png" onclick="chamaAbrir();" width="150px" />-->
		<!--<input type="text" readonly="true" name="nomeArquivoOriginal" 
		value="<jsp:getProperty property="nomeArquivoOriginal" name="documento"/>"/>
		<img src="../images/scanearDoc.fw.png" onclick="chamaScanear();" width="150px" style="padding-bottom:10px;" />   
        <tr>
		<td>Anexo(s):</td>
		<td><select name="anexo" size="4" ></select></td>
		<td><input type="text" name="nomeAnexo" value="<%//=request.getAttribute("arquivo") %>"/>
		<input type="text" name="anexoAtributo" />
		 <img src="../images/scanearAnexo.fw.png" onclick="addAnexo();" />
		<img src="../images/SCANEAR.jpg" onclick="excluirAnexo();" />
		</td>
	</tr>
-->
</div>
</div>
<div class="divisao botoesIncluir">
	<input type="submit" value="Incluir" id="btnIncluir" class="button gray" />
	<input type="reset" value="Limpar" id="btnLimpar" class="button gray" />
	<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
</div>
</div>		

</center>
</form>

<script>
/* DIV CAMPOS DE BUSCA */
			$(document).ready(function(){
			 $("#palco > div").hide();

						      //$("#sel-sexo").change(function(){
						      //      $("#palco > div").hide();
						      //      $( '#'+$( this ).val() ).show('fast');
						      //});
				 document.getElementById("PAPEM41").style.display="block";

			//	 $("input[name='tipo']").click(function(){
				 $("#classificacao").change(function(){
				 		 $("#palco > div").hide();
						 $( '#'+$( this ).val() ).show('fast');
				});
			});
/*  FIM */
</script>	
</body>
</html>