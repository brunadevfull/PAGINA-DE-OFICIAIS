<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%@page import="bean.Documento"%>
<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>SisImagem - Pesquisar</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="../js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.3.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
<script type="text/javascript" src="../js/validacao.js" ></script>
<script type="text/javascript" src="../js/tablecloth.js"></script>

<script language="JavaScript">
function validaCamposPesquisar(){
	    
		document.pesquisarDocumento.submit();
}
</script>		
</head>
<body>
<%
    Object mensagem = request.getAttribute("mensagem");
    String valor;
    if (mensagem == null){
    	valor = "";
    }else{
    	valor = (String) mensagem;
    }
%>
<p id="mensagem"><%=valor %></p>
<p class="titulo">Pesquisar Por</p>

<form name="pesquisarDocumento" method="post" action="../controller/controlador?cmd=pesquisarDocumento">
<center>
 <div class="divisao pesquisarDocumento">

		<div class="conteudo contDocumentoLeft">
		
		<div class="divsLabel esquerda topo0">
			<label>NIP/Matr&iacute;cula</label> &nbsp;
			<input type="text" size="13" name="NIP/Matrícula" maxlength="9" />
		</div>
		
		<div class="divsLabel topo0 meioCPF">
		<label>CPF</label> &nbsp;
		<input type="text" size="25" name="CPF" id="CPF" maxlength="11"/>	
		</div>
		
		<div class="divsLabel meioPasta topo0">
		<label>Processo</label> &nbsp;
		<input type="text" size="21" name="rrecordid" />	
		</div>
		
		<div class="divsLabel esquerda topo40">
		<label>Consignado</label> &nbsp;
		<input type="text" size="95" name="Consignado" />
		</div>
		
		<div class="divsLabel esquerda topo80">
		<label>Benefici&aacute;rio</label> &nbsp;
		<input type="text" size="95" name="Beneficiário" />
		</div>
		
		<div class="divsLabel esquerda topo120">
		<label>Título do Documento </label> &nbsp;
		<input type="text" size="87" name="rtitle" />
		</div>
		
		<div class="divsLabel esquerda topo160">
		<label>Tipo de Documento</label> &nbsp;
    	<input type="text" size="31" name="Tipo de Documento" />
    	</div>
		
		<div class="divsLabel meio topo160">
    	<label>N&uacute;mero do Documento</label> &nbsp;
    	<input type="text" size="25" name="Número do Documento" />
		</div>
		
		<div class="divsLabel esquerda topo200">
    	<label>Protocolo</label> &nbsp;
    	<input type="text" size="40" name="Protocolo" />
		</div>
		
		<div class="divsLabel meio topo200">
		<label>Data de Protocolo</label> &nbsp;
    	<input type="text" size="30" name="Data de Protocolo" />
    	</div>
    		
    	<div class="divsLabel esquerda topo240">
		<label>Data de Cria&ccedil;&atilde;o (Legado)</label> &nbsp;
    	<input type="text" size="25" name="Data de Criação (Legado)" />
		</div>
		

		<div class="divsLabel meio topo240" >
		<label>Data de Protocolo (Legado)</label> &nbsp;
    	<input type="text" size="21" name="Data de Protocolo (Legado)" /> 
		</div>	
    		
		<div class="divsLabel esquerda topo280">
		<label>Número do Registro</label> &nbsp;
		<input type="text" size="30" name="rrecordid" />
		</div>
		
		<div class="divsLabel meio topo280">
		<label>Origem</label> &nbsp;
    	<input type="text" size="40" name="Origem" />
    	</div>
    	
		<div class="divsLabel esquerda topo320">
		<label>Entidade Consignat&aacute;ria</label> &nbsp;
    	<input type="text" size="27" name="Entidade Consignatária" />
    	</div>
    	
		<div class="divsLabel meio topo320">
		<label>Observa&ccedil;&otilde;es</label>&nbsp;
    	<input name="OBSERVAÇÕES" size="35"/>
    	</div>

    	<div class="divsLabel esquerda topo360">
			<label>Responsável</label>&nbsp;
			<input type="text" size="37" name="rloc.lcname"  />
		</div>
    	   	
    	<div class="divsLabel meio topo360">
    	<label>Of&iacute;cio Judicial Anexo</label>&nbsp;
		<select name="Ofício Judicial Anexo">
			<option value="">Não</option>
	  		<option value="SIM">Sim</option>
		</select>
		</div>
    	
    	<div class="divsLabel direita topo360">
		<label>Urgente</label>&nbsp;
		<input type="checkbox" name="Urgente" id="Urgente"  value="1"/>
		</div>

    	<!--
    	<div class="divsLabel topo320 meioPesquisa">
    	<label>Urgente</label>&nbsp;
    	<input type="text" size="39" name="Urgente" />
    	</div>-->
	</div>

	<div class="divisao botoesPesquisar"><!--
	<input type="submit" value="Pesquisarrrrrrr" id="btnPesquisarrrr" class="button gray" />
	   	--><a class="button gray" onclick="validaCamposPesquisar()" >
		<img src="../images/lupa.png" width="8"/> Pesquisar</a>
		<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
	</div>
</div>
</center>
</form>
</body>
</html>