<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Principal</title>
<link href="css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
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
<!--
<form name="encerraSessao" method="post" action="../controller/controlador?cmd=encerraSessao">
   <img  class="bottom-button-4" src="../images/scanearDoc.fw.png" onclick="submit();" width="150px" />
</form>
-->
</body>
</html>