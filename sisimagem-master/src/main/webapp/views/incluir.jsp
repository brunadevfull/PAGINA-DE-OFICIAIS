<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">-->
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>
<%@page import="bean.Documento"%>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SisImagem - Incluir</title>
    <link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
</head>
<body>
    <!-- 
    <div class="criteria">
    	<iframe name="Criteria" class="Criteria" id="Criteria" src="criteria.jsp" frameborder="0" marginheight="0" marginwidth="0" allowtransparency="true" onload='javascript:resizeIframe(this);' scrolling="no">  </iframe>
    </div>
    -->
   	<%
		session.removeAttribute("documento");
	    Documento documento = new Documento();
	    session.setAttribute("documento",documento);
	%>
    <div class="resultado">
			<iframe name="Resultado" class="Resultado" id="Resultado" src="incluirDocumento.jsp" 
			frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" allowtransparency="true"></iframe>
  	</div>
</body>
</html>