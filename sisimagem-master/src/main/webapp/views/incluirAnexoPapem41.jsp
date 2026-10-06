<jsp:useBean id="documento" class="bean.Documento" scope="session" />
<jsp:setProperty name="documento" property="*"/>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--html xmlns="http://www.w3.org/1999/xhtml" xmlns:h="http://java.sun.com/jsf/html"-->

    
<html>   
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title> SisImagem - Incluir Anexo PAPEM-41 </title>
<link href="../css/estilo.css" rel="stylesheet" type="text/css" media="screen" />
<link href="../css/button.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="../js/jquery-1.4.2.js"></script>
<script type="text/javascript" src="../js/jquery.maskedinput-1.3.js"></script>
<script type="text/javascript" src="../js/jquery.validate.js"></script>
<script type="text/javascript" src="../js/validacao.js" ></script>
<script type="text/javascript" src="../js/tablecloth.js"></script>

<script language="JavaScript">
function validacamposPesquisar(){
	if(document.incluirAnexoPapem41.nomeArquivoOriginal.value == ""){
		alert("Campo 'Anexo' é obrigatório");
	}
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
<p class="titulo">Incluir Anexo Papem41</p>

<center>
<form id="incluirAnexoPapem41" name="incluirAnexoPapem41" method="post" enctype="multipart/form-data" action="../controller/controlador?cmd=incluirAnexoPapem41">
<div class="divisao incluirAnexoPapem41" >
	<div class="conteudo contDocumentoLeft">
		<div class="divsLabel esquerda topo0">
			<label>Anexar ao Documento <font color="red">*</font></label> &nbsp;&nbsp;
       		<input type="file" name="nomeArquivoOriginal" size="67" />
		</div>
	</div>

	<div class="divisao botoesIncluirPapem41">
		<input type="submit" value="Incluir" id="btnIncluir" class="button gray" />
		<input type="reset" value="Limpar" id="btnLimpar" class="button gray" />
		<a class="button gray" onclick="javascript:history.back(-1)">Voltar</a>
	</div>
</div> 
</form>
</center>
</body>
</html>