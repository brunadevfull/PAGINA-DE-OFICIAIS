<jsp:useBean id="usuarioDetalhe" class="bean.Usuario" scope="session" />
<jsp:setProperty name="usuario" property="*"/>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>
<%@page import="bean.Grupo"%>
<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<!--<html xmlns="http://www.w3.org/1999/xhtml"> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

<title>SisImagem - Cadastro de Usuário - Detalhe</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />

<script language="JavaScript" type="text/javascript">
function alterarUsuario(){
	if(document.detalharUsuario.senha.value != document.detalharUsuario.confsenha.value){
		alert("Campo 'Senha' e 'Confirmar Senha' estão divergentes");
	}else if (document.detalharUsuario.senha.value != '' && document.detalharUsuario.senha.value != 'marinha' && verificaForcaSenha() <5){
		alert("Senha fraca");
	}else if(document.detalharUsuario.grupo.value == ""){
		alert("Campo 'Grupo' é obrigatório");
	}else {
		document.detalharUsuario.action="../controller/controlador?cmd=alterarUsuario";
		document.detalharUsuario.submit();
	} 
}

function verificaForcaSenha(){
	senha = document.detalharUsuario.senha.value;
	forca = 0;
	if(senha.length>=7){
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
<p class="mensagem"><%=valor %></p>

<p class="titulo">Cadastro de Usuário - Detalhe</p>

<form id="detalharUsuario" name="detalharUsuario" method="post" >
<center>


<div class="divisao incluirDocumentoRespPapem42" >
  <div class="conteudo contDocumentoLeft">
  
	<div class="divsLabel esquerda topo0">
		<label id="lProcesso">Usuário</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="text" size="20" maxlength="20" name="recordNumber" id="recordNumber" readonly="readonly" value="<jsp:getProperty property="usuario" name="usuarioDetalhe"/>" />
	</div>
	<div class="divsLabel meio2 topo0">
			<label>Grupo <font color="red">*</font></label>&nbsp;
	       	<select name="grupo" id="grupo" >
				<option value=""></option>
				<% 
				List<Grupo> listaGrupo = (List<Grupo>) session.getAttribute("listaGrupo");
				if(listaGrupo != null && !listaGrupo.isEmpty()){
					Iterator<Grupo> itGrupo = listaGrupo.iterator();
					while(itGrupo.hasNext()){
						Grupo grupo = (Grupo) itGrupo.next();
				%>
				<option  
				<%if(grupo.getNome().equals(usuarioDetalhe.getGrupo())){ %>
				selected="selected"
				<%} %>
				value="<%=grupo.getNome() %>"><%=grupo.getNome() %></option>		
				<%
					}
				}
				%>
			
			</select>
	</div>
	<div class="divsLabel direita topo0">
		<label>Bloqueio</label>&nbsp;
		<input type="checkbox" name="bloqueio" id="bloqueio"  value="B"
		<% System.out.println("valor de bloqueio no detalhar usuario: "+usuarioDetalhe.getBloqueio()); 
		  if(usuarioDetalhe.getBloqueio().equals("B")){ %>
		 checked
		<%} %> />
	</div>
	<div class="divsLabel esquerda topo40">
		<label>Senha <font color="red">*</font></label>&nbsp;
		<input type="password" size="15" maxlength="15" name="senha" id="senha"/>
	</div>
	
	<div class="divsLabel meio2 topo40">
			<label>Confirmar Senha <font color="red">*</font></label>&nbsp;
       		<input type="password" name="confsenha" size="15" maxlength="15" /> 
	</div>
	
	
  </div>
		
	<div class="divisao botoesIncluirDocumentoRespostaPapem42">
		<input type="button" value="Alterar" class="button gray" onclick="alterarUsuario()"/>
		<input type="reset" value="Limpar" id="btnLimpar" class="button gray" />
		<input type="button" value="Voltar" class="button gray" onclick="javascript:history.back(-1)"/>
	</div>	
	<div class="divisao botoesIncluirDocumentoRespostaPapem42"><br/>
	<p align="justify" style="text-indent:10px;">
Senha deve ter no mínimo 7 e no máximo 15 caracteres e deve ser composta obrigatoriamente de uma letra maiúscula, uma letra minúscula, um caracter especial (!@#$%&*) e um número.
</p>
	<p align="justify" style="text-indent:10px;">
		<b>ATENÇÃO:</b>
   		A senha é pessoal e intransferível, portanto, não podendo ser compartilhada com terceiros, no intuito de evitar possíveis problemas administrativos e/ou judiciais, por se tratar de documentos sensíveis e de informação pessoal (Lei nº 12.527/2011 e Decreto nº 7.724/2012).
	</p>
	</div>			
</div> 
</center>
</form>
</body>
</html>