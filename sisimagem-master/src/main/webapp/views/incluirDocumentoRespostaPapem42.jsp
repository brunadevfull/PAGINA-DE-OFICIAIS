<jsp:useBean id="documento" class="bean.Documento" scope="session" />
<jsp:setProperty name="documento" property="*"/>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>

<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<title>SisImagem - Incluir Documento de Resposta PAPEM-42</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="../js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.3.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
<!-- <script type="text/javascript" src="../js/validar.js" ></script>-->
<script type="text/javascript" src="../js/validacao.js" ></script>
<script type="text/javascript" src="../js/tablecloth.js"></script>

<script language="JavaScript">
function validacamposPesquisar(){
	if(document.incluirDocumentoRespostaPapem42.nomeArquivoOriginal.value == ""){
		alert("Campo 'Documento' é obrigatório");
	}else if(document.incluirDocumentoRespostaPapem42.titulo.value == ""){
		alert("Campo 'Título' é obrigatório");
	}
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
      // pegar o código do usuário do controle de acesso
     //session.setAttribute("usuario", "41");
%>
<p class="mensagem"><%=valor %></p>

<p class="titulo">Incluir Documento Resposta Papem 42</p>

<form id="incluirDocumentoRespostaPapem42" name="incluirDocumentoRespostaPapem42" method="post" enctype="multipart/form-data" action="../controller/controlador?cmd=incluirDocumentoRespostaPapem42">
<center>


<div class="divisao incluirDocumentoRespPapem42" >
  <div class="conteudo contDocumentoLeft">
  
	<div class="divsLabel esquerda topo0">
		<label id="lProcesso">Processo</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" size="37" name="recordNumber" id="recordNumber" readonly="readonly" value="<jsp:getProperty property="recordNumber" name="documento"/>" />
	</div>
	
	<div class="divsLabel meio2 topo0">
		<label>Título <font color="red">*</font></label>&nbsp;
		<input type="text" size="40" name="titulo" id="titulo"/>
	</div>
	
	<div class="divsLabel esquerda topo40">
			<label>Documento <font color="red">*</font></label>&nbsp;
       		<input type="file" name="nomeArquivoOriginal" size="79" />
	</div>
  </div>
		
	<div class="divisao botoesIncluirDocumentoRespostaPapem42">
		<input type="submit" value="Incluir" id="btnIncluir" class="button gray" />
		<input type="reset" value="Limpar" id="btnLimpar" class="button gray" />
		<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
	</div>		
</div> 
</center>
</form>
</body>
</html>