<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>  
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%@page import="bean.Usuario"%>
<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Exibi Documento</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />
			
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

<p class="titulo">Cadastro de Usuário</p>

<center>
 <form name="cadastroUsuario" method="post" action="../controller/controlador?cmd=incluirUsuario">
	 <div class="conteudo contDocumentoLeft">
  
	
	<div class="divsLabel esquerda topo0">
		<label>Usuário <font color="red">*</font></label>&nbsp;
		<input type="text" size="40" name="usuario" id="usuario"/>
	</div>
	
	<div class="divsLabel esquerda topo40">
			<label>Senha <font color="red">*</font></label>&nbsp;
       		<input type="text" name="senha" size="79" />
	</div>
	<div class="divsLabel esquerda topo80">
			<label>Confirmar Senha <font color="red">*</font></label>&nbsp;
       		<input type="text" name="confSenha" size="79" />
	</div>
	<div class="divsLabel esquerda topo120">
			<label>Grupo <font color="red">*</font></label>&nbsp;
       		<input type="text" name="grupo" size="79" />
	</div>
 
	
	<table>
		<tr>
			<th>Usuário</th>
			<th>Grupo</th>
			<th>Alterar</th>
			<th>Excluir</th>
			
		</tr>
	  <%
			List<Usuario> listaGenero = (List<Usuario>) session.getAttribute("listaUsuario");
	        
			if(listaGenero != null && !listaGenero.isEmpty()){
				Iterator<Usuario> it = listaGenero.iterator();
				while( it.hasNext() )
				{
				   Usuario bean = it.next();
		%>
		<tr>
		<td><a><%= bean.getUsuario() %></a></td>
		<td><a><%= bean.getGrupo() %></a></td>
		<td><a href="../controller/controlador?cmd=detalharUsuario&idUsuario=<%= listaGenero.indexOf(bean)%>">Alterar</a></td>
		<td><a href="../controller/controlador?cmd=excluirUsuario&idUsuario=<%= bean.getId()%>">Deletar</a></td>
		</tr>
		<%
			}
			}else{
		%>
		<tr>
		<td colspan="5" align="center">
		<center><a>Não há usuários</a></center>
		</td>
		</tr>
		<%	}  %>
	</table>
	
	 </div>
	<input type="submit" value="Incluir Usuário" id="btnIncluir" class="button gray" onclick="incluirUsuario()"/>

</form>
<br>
<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
</center>
</body>
</html>