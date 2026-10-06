<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>

<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<title>SisImagem - Login</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />


    <script language="javascript" type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }


function validacamposPesquisar(){
	if (verificaForcaSenha() <5){
		alert("Senha fraca");
	}
	if(document.login.usuario.value == ""){
		alert("Campo 'Usuário' é obrigatório");
	}else if(document.login.senha.value == ""){
		alert("Campo 'Senha' é obrigatório");
	}
}

function verificaForcaSenha(){
	senha = document.login.senha.value;
	forca = 0;
	if(senha.length>7){
		forca += 1;
	}
	if(senha.match(/[a-z]+/)){
		forca += 1;
	}
	if(senha.match(/[A-Z]+/)){
		forca += 1;
	}
	if(senha.match(/[0-9]+/)){
		forca += 1;
	}
	if(senha.match(/\W+/)){
		forca += 1;
	}
	return forca;
	
}



</script>
</head>
<body class="index">
 <img class="top-logo" src="../images/SISIMAGEM.png" width="220"/>
 <img class="top-icon" src="../images/icon.png" width="60" />


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


<p class="titulo topo440">Login</p>

<form id="login" name="login" method="post" action="../controller/controlador?cmd=login">
<center>

 
<div class="divisao menuLogin topo80" >

  <div class="conteudo contDocumentoLeft">
  
	
	<div class="divsLabel esquerda topo0" style="padding-left:50px;">
		<label>Usuário <font color="red">*</font></label>&nbsp;
		<input type="text" size="30" name="usuario" id="usuario"/>
	</div>
	
	<div class="divsLabel esquerda topo40"  style="padding-left:50px;">
			<label>Senha <font color="red">*</font></label>&nbsp;&nbsp;&nbsp;
       		<input type="password" name="senha" size="30" />
	</div>
		<div class="divsLabel esquerda topo80"  style="padding-left:50px;">
			 <p class="mensagem"><%=valor %></p>
	    </div>	
  </div>
		
	<div class="divisao botoesLogin">
		<input type="submit" value="Login" id="btnIncluir" class="button gray"/>
		<input type="reset" value="Limpar" id="btnLimpar" class="button gray" />
		<input class = "button gray" onclick="javascript:history.back(-1)" value="Voltar" type="button"/>
	</div>		
</div> 
</center>
</form>
</body>
</html>