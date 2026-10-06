<%@ page language="java" contentType="text/html; charset=ISO-8859-1"  pageEncoding="ISO-8859-1"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<jsp:useBean id="documento" class="bean.Documento" scope="session" />
<%@page import="bean.Anexo"%>
<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<%@page import="java.io.File"%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Detalhar Documento</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />

<script>
  function incluirDocumentoPapem42(){
	  document.detalhaDocumento.action="./controlador?cmd=incluirDocumentoPapem42";
	  document.detalhaDocumento.submit();
  }	  
  function incluirDocumentoRespostaPapem42(){
	  document.detalhaDocumento.action="../views/incluirDocumentoRespostaPapem42.jsp";
	  document.detalhaDocumento.submit();
  }	 
  function incluirAnexoPapem41(){
	  document.detalhaDocumento.action="../views/incluirAnexoPapem41.jsp";
	  document.detalhaDocumento.submit();
  }	 

</script>
</head>
<body>
<center>

<p class="titulo">Detalhamento</p>

<form name="detalhaDocumento" method="post">
   <% 
   ServletContext context = getServletContext();
   String caminho = context.getInitParameter("path");
   String arquivo = caminho +  documento.getNomeArquivoCompleto().replace("+",File.separator);
   %>
 <input type="hidden" name="nomeDocumento" id="nomeDocumento" value="<%= arquivo %>"  /> 
  <a onclick='chamaAbrirDoc();'><img src='../images/documento.png' width='27px' /></a>
	<table class="tabelaDetalhe">
		<tr>
		<%System.out.println("antes do campo tipo"); %>
		<!-- mudança aqui -->
			<th>Tipo</th>
    		<td align="left"> <%= documento.getPasta()  %></td>
			<th>Processo/Pasta</th>
    		<td align="left"> <%= documento.getRecordNumberPai()  %></td>
			
		</tr> 
	    <tr>
			<th>Número Registro</th>
    		<td align="left"> <%= documento.getRecordNumber()  %></td>
			
			<th>Responsável</th>
    		<td align="left"><%= documento.getResponsavel() %></td>
		</tr> 
		<tr>
			<th>Data Inclusão</th>
    		<td align="left"> <%= documento.getDataInclusao()  %></td>
			
			<th>Data Documento</th>
    		<td align="left"><%= documento.getDataDocumento() %></td>
		</tr>
		<!-- mudança aqui -->
		<%System.out.println("antes do campo matricula"); %>
		<tr>
			<th>NIP/Matr&iacute;cula</th>
    		<td align="left">
    		<u><b><a onclick="chamaAbrirDoc();">
    			<%= documento.getNipMatricula() %></a></b></u></td>
    		
    		<th>CPF</th>
    		<td align="left"><%= documento.getCpf() %></td>	
		</tr>
		<tr>
			<th>Consignado</th>
    		<td align="left"> <%= documento.getConsignado()  %></td>
			
			<th>Benefici&aacute;rio</th>
    		<td align="left"><%= documento.getBeneficiario() %></td>
		</tr>
		<tr>
			<th>Data de Cria&ccedil;&atilde;o (Legado)</th>
    		<td align="left"><%= documento.getDataCriacaoLegado()%></td>

    		<th>Data de Protocolo</th>
    		<td align="left"><%= documento.getDataProtocolo()%></td>
		</tr>
		<tr>
			<th>Entidade Consignat&aacute;ria</th>
    		<td align="left"><%= documento.getEntidadeConsignataria()%></td>
			<th>Data de Protocolo (Legado)</th>
    		<td align="left"><%= documento.getDataProtocoloLegado()%></td>
		</tr>
		<tr>
			<th>N&uacute;mero do Documento</th>
    		<td align="left"><%= documento.getNumeroDocumento()%></td>
    		
    		<th>Protocolo</th>
    		<td align="left"><%= documento.getProtocolo()%></td>
		</tr>
		<tr>
			<th>Origem</th>
    		<td align="left"><%= documento.getOrigem()%></td>
			<th>Of&iacute;cio Judicial Anexo</th>
			<td align="left"><%= documento.getOficioJudicialAnexo()%></td>
		</tr>
		<%System.out.println("antes do campo tipo de documento"); %>
		<tr>
			<th>Tipo de Documento</th>
    		<td align="left"><%= documento.getTipoDocumento()%></td>
    		<th>Urgente</th>
    		<td align="left"><%= documento.getUrgente()%></td>
		</tr>

		<tr>
		<th>Observa&ccedil;&otilde;es</th>
    	<td align="left" colspan="3"><%= documento.getObservacoes()%></td>
		</tr>
</table>
</form>
<%System.out.println("antes da lista anexo"); %>
<%
List<Anexo> listaGenero = documento.getListaAnexo();
if(!listaGenero.isEmpty()){
%>
<form name='detalhaDocumentoAnexo' method='post'>	
<input type="hidden" name="nomeDocumento" id="nomeDocumento" value="" />
<p class='titulo'>Anexos</p>	
<table><tr>
		<th> </th>
		<th>Record Number</th>
		<th>Título</th>
		<th>Data do Documento</th>
		<th>Data de Inclusão</th>
		</tr>
<%
Iterator<Anexo> it = listaGenero.iterator();
int i =0;
while( it.hasNext() ){
	i++;
Anexo bean = it.next();
		
String arquivoAnexo = caminho + bean.getNomeArquivoCompleto().replace("+",File.separator);						

%> 

<tr>
<td style='text-align:center;'>
<input type="hidden" name="nomeDocumento<%=i %>" id="nomeDocumento<%=i %>" value="<%= arquivoAnexo %>">  
<a  onclick='chamaAbrirDocAnexo(<%=i %>);'><img src="../images/documento.png" width="27px" /></a>
</td>
<td><%= bean.getRecordNumber() %></td>
<td><%= bean.getTitle() %></td>
<td><%= bean.getDataDocumento() %></td>
<td><%= bean.getDataInclusao()%></td>
<% }} %>
</tr></table></form><br>
<div class="divisao botoesDetalhar">
<% if (! ((String)session.getAttribute("grupo")).equals("SASM") ){ %>
<%
System.out.println(documento.getPasta());
if(documento.getPasta().equals("Documentos PAPEM41")){%>
	<input type="submit" value="Incluir Anexo PAPEM41" class="button gray" onclick="incluirAnexoPapem41()"/>
<% }else if(documento.getPasta().equals("Processos PAPEM42")){%>
	<input type="submit" value="Incluir Documento de Resposta PAPEM-42" class="button gray" onclick="incluirDocumentoRespostaPapem42()"/>
	<input type="submit" value="Incluir Documento PAPEM42" class="button gray" onclick="incluirDocumentoPapem42()"/>
<%} }%>
<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
</div>

</center>
<script language="JavaScript">
function chamaAbrirDoc(){
    document.detalhaDocumento.action="../controller/AbrirDoc";
	document.detalhaDocumento.submit();	
}

function chamaAbrirDocAnexo(i){
	valorAnexo= document.getElementById("nomeDocumento"+i).value;
	document.detalhaDocumentoAnexo.nomeDocumento.value = valorAnexo;
	document.detalhaDocumentoAnexo.action="../controller/AbrirDoc";
	document.detalhaDocumentoAnexo.submit();
}
</script>
</body>
</html>