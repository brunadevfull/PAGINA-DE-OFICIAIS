<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>  
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%@page import="bean.Documento"%>
<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Exibi Documento</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="../js/tablecloth.js" ></script>
			
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

<p class="titulo">Resultados da Pesquisa</p>

<center>
 <form name="exibiDocumento" method="post" action="../controller/controlador?cmd=detalharDocumento">
	<table>
		<tr>
			<th>ID</th>
			<th>NIP/Matrícula</th>
			<th>Data do Documento</th>
			<th>Data de Inclusão</th>
			<th>Tipo</th>
		</tr>
	  <%
			List<Documento> listaGenero = (List<Documento>) request.getAttribute("listaDocumento");
			if(listaGenero != null && !listaGenero.isEmpty()){
				Iterator<Documento> it = listaGenero.iterator();
				while( it.hasNext() )
				{
				   Documento bean = it.next();
		%>
		<tr>
		<td><a href="../controller/controlador?cmd=detalharDocumento&idArquivo=<%= bean.getIdArquivo()%>">
           <%= bean.getIdArquivo() %></a></td>
		<td><a><%= bean.getNipMatricula() %></a></td>
		<td><a><%= bean.getDataDocumento() %></a></td>
		<td><a><%= bean.getDataInclusao() %></a></td>
		<td><a><%= bean.getPasta() %></a></td>
		</tr>
		<%
			}
			}else{
		%>
		<tr>
		<td colspan="5" align="center">
		<center><a>Não há documentos que atendam a estes critérios de pesquisa</a></center>
		</td>
		</tr>
		<%	}  %>
	</table>
</form>
<br>
<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
</center>
</body>
</html>