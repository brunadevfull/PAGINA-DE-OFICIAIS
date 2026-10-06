<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">-->
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>
<%@page import="bean.Usuario"%>
<%@page import="bean.Grupo"%>
<%@page import="model.DAOTrim"%>
<%@page import="controller.OperacaoException"%>
<%@page import="java.util.List"%>
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
   
   	<%
   	try{
		DAOTrim daoTrim = new DAOTrim();
		//daoTrim.obterConexao();
		daoTrim.obterConexaoDesenv();
		System.out.println("VAI LISTAR usuario POR ID");
		// coloca lista de usuario na session
		List<Usuario> listaUsuario = null;
		listaUsuario= daoTrim.listaUsuario();
		System.out.println(listaUsuario.size());
	
		session.setAttribute("listaUsuario", listaUsuario);
		System.out.println("CONSEGUIU LISTA usuario");
		// coloca lista de grupo na session
		List<Grupo> listaGrupo = null;
		listaGrupo= daoTrim.listaGrupo();
		System.out.println(listaGrupo.size());
	
		session.setAttribute("listaGrupo", listaGrupo);
		
		}
		catch(Exception e)
		{
			throw new OperacaoException(e.getMessage());
		}
		
		
	%>
    <div class="resultado">
			<iframe name="Resultado" class="Resultado" id="Resultado" src="cadastroUsuario.jsp" 
			frameborder="0" marginheight="0" marginwidth="0" scrolling="auto" allowtransparency="true"></iframe>
  	</div>
</body>
</html>