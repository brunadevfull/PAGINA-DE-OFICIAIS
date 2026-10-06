<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@page import="bean.Usuario"%>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SisImagem</title>
    <link href="../css/estilo.css" rel="stylesheet" type="text/css" />
    <link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />
    
<script language="JavaScript">    
    function chamaScanear(){
    	document.scanearDocumento.submit();	
	}
    function chamaSair(){
    	document.encerraSessao.submit();	
	}

    function chamaCadastroUsuario(){
    	document.cadastroUsuario.submit();	
	}
</script>
</head>

<body>

 <img class="top-logo" src="../images/SISIMAGEM.png" width="200"/>
 <img class="top-icon" src="../images/icon.png" width="50" />

 <a href="content.jsp" target="Principal">
 	<img class="bottom-button" src="../images/principal.fw.png" onmousedown="this.src='../images/principalHov.fw.png'" onmouseup="this.src='../images/principal.fw.png'"  name="Inicio" width="150px" />
 </a>
  <a href="pesquisar.jsp" target="Principal">
 	<img class="bottom-button-2" src="../images/pesquisar.fw.png" onmousedown="this.src='../images/pesquisarHov.fw.png'" onmouseup="this.src='../images/pesquisar.fw.png'"  name="Inicio" width="150px"/>
 </a>
 <% if ( ((String)session.getAttribute("grupo")).equals("ADM") ){
 %>
 <a href="cadastro.jsp" target="Principal">
 	<img class="bottom-button-3" src="../images/cadUsuario.fw.png" onmousedown="this.src='../images/cadUsuarioHov.fw.png'" onmouseup="this.src='../images/cadUsuario.fw.png'"  name="Inicio" width="150px"/>
 </a>

 <%} else { %>
  
 <a href="alterarSenha.jsp" target="_top">
 	<img class="bottom-button-3" src="../images/altSenha.fw.png" onmousedown="this.src='../images/altSenhaHov.fw.png'" onmouseup="this.src='../images/altSenha.fw.png'"  name="Inicio" width="150px"/>
 </a>

 <%} %>
 <% 
   
 	if ( ((String)session.getAttribute("grupo")).equals("ADM") ||
		 ((String)session.getAttribute("grupo")).equals("PAPEM40") ){ 
 		 %> 
 <a href="incluir.jsp" target="Principal">
 	<img class="bottom-button-4" src="../images/incluirDoc.fw.png" onmousedown="this.src='../images/incluirDocHov.fw.png'" onmouseup="this.src='../images/incluirDoc.fw.png'"  name="Inicio" width="150px"/>
 </a>
 <!--
 <a  href="scanearDoc.jsp" target="Principal">
 	<img class="bottom-button-4" src="../images/scanearDoc.fw.png" onmousedown="this.src='../images/scanearDocHov.fw.png'" onmouseup="this.src='../images/scanearDoc.fw.png'"  name="Inicio" width="150px"/>
 </a>
 -->
<form name="scanearDocumento" method="post" action="../controller/scanearDocumento">
	<img  class="bottom-button-5" style="cursor:pointer;" src="../images/scanearDoc.fw.png" onclick="chamaScanear();" width="150px" />
</form>
<% } %> 
  
<form name="encerraSessao" method="post" action="../controller/controlador?cmd=encerraSessao">
<img  class="bottom-button-6" style="cursor:pointer;" src="../images/sair.fw.png" onmousedown="this.src='../images/sairHov.fw.png'" onmouseup="this.src='../images/sair.fw.png'" onclick="chamaSair();" width="75px" />
</form>
</body>
</html>