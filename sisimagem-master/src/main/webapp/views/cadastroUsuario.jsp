<%@ page language="java" contentType="text/html; charset=ISO-8859-1" pageEncoding="ISO-8859-1"%>  
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%@page import="bean.Usuario"%>
<%@page import="bean.Grupo"%>
<%@page import="java.util.List"%>
<%@page import="java.util.Iterator"%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Exibi Documento</title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/tablecloth.css" rel="stylesheet" type="text/css" media="screen" />


<script >
	
	function incluirUsuario(){
		if(document.cadastroUsuario.rlcname.value == ""){
			alert("Campo 'Usuário' é obrigatório");
		}else if(document.cadastroUsuario.rlcname.length < 5){
			alert("Campo 'Usuário' é obrigatório");
		}else if(document.cadastroUsuario.senha.value == ""){
			alert("Campo 'Senha' é obrigatório");
		} else if(document.cadastroUsuario.confSenha.value == ""){
			alert("Campo 'Confirmar Senha' é obrigatório");
		} else if(document.cadastroUsuario.senha.value != document.cadastroUsuario.confSenha.value){
			alert("Campo 'Senha' e 'Confirmar Senha' estão divergentes");
		} else if (document.cadastroUsuario.senha.value != 'marinha' && verificaForcaSenha() <5){
			alert("Senha fraca");
		} else if(document.cadastroUsuario.rjgname.value == ""){
			alert("Campo 'Grupo' é obrigatório");
		}  else {
			document.cadastroUsuario.action="../controller/controlador?cmd=incluirUsuario";
			document.cadastroUsuario.submit();
		} 
	}
  function pesquisarUsuario(){
	 document.cadastroUsuario.action="../controller/controlador?cmd=pesquisarUsuario";
	 document.cadastroUsuario.submit();
	
  }
	function verificaForcaSenha(){
		senha = document.cadastroUsuario.senha.value;
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
<p id="mensagem"><%=valor %></p>

<p class="titulo">Cadastro de Usuário</p>

<center>
 <form name="cadastroUsuario" method="post">
 	
 	 <div class="divisao incluirDocumento" style="border:none;" >
     <p id="mensagem"><%=valor %></p>
	 <div class="conteudo contDocumentoLeft">
  
	
	<div class="divsLabel esquerda topo0">
		<label>Usuário <font color="red">*</font></label>&nbsp;
		<input type="text" size="40" maxlength="20" name="rlcname" id="lcname"/>
	</div>
	<div class="divsLabel meio topo0">
			<label>Grupo <font color="red">*</font></label>&nbsp;
		<select name="rjgname" id="rjgname" >
			<option value=""></option>
		<% 
		List<Grupo> listaGrupo = (List<Grupo>) session.getAttribute("listaGrupo");
		if(listaGrupo != null && !listaGrupo.isEmpty()){
			Iterator<Grupo> itGrupo = listaGrupo.iterator();
			while(itGrupo.hasNext()){
				Grupo grupo = (Grupo) itGrupo.next();
		%>
		<option value="<%=grupo.getNome()%>"><%=grupo.getNome()%></option>		
		<%
			}
		}
		%>
		
		</select>
	</div>
	<div class="divsLabel esquerda topo40">
			<label>Senha <font color="red">*</font></label>&nbsp;
       		<input type="password" name="senha" size="15" maxlength="15" />
	</div>
	<div class="divsLabel meio topo40">
			<label>Confirmar Senha <font color="red">*</font></label>&nbsp;
       		<input type="password" name="confSenha" size="15" maxlength="15" />
	</div>
	<div style="position:relative; top:80px;">
		<input type="button" value="Incluir Usuário" id="btnIncluir" class="button gray" onclick="incluirUsuario()"/>
		<input type="submit" value="Pesquisar Usuário" id="btnIncluir" class="button gray" onclick="pesquisarUsuario()"/>
		<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
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
	<div class="divsLabel esquerda topo260">
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
	 </div>
	 </div>

</form>
<br>
</center>
</body>
</html>