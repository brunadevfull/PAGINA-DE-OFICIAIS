<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>

<!--<html xmlns="http://www.w3.org/1999/xhtml"> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<title>SisImagem - Login</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }


function validacamposPesquisar(){
	if(document.login.usuario.value == ""){
		alert("Campo 'Usuário' é obrigatório");
	}else if(document.login.senha.value == ""){
		alert("Campo 'Senha' é obrigatório");
	}else if(document.login.grupo.value == ""){
		alert("Campo 'Grupo' é obrigatório");
	}
}
</script>
</head>
<body class="index">
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

<p class="titulo">Login</p>

<form id="login" name="login" method="post" action="./controller/controlador?cmd=login">
<center>

 
<div class="divisao incluirDocumentoRespPapem42" >
  <div class="conteudo contDocumentoLeft">
  
	
	<div class="divsLabel meio2 topo0">
		<label>Usuário <font color="red">*</font></label>&nbsp;
		<input type="text" size="40" name="usuario" id="usuario"/>
	</div>
	
	<div class="divsLabel esquerda topo40">
			<label>Senha <font color="red">*</font></label>&nbsp;
       		<input type="text" name="senha" size="79" />
	</div>
	<div class="divsLabel esquerda topo40">
			<label>Grupo <font color="red">*</font></label>&nbsp;
       		<input type="text" name="grupo" size="79" />
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