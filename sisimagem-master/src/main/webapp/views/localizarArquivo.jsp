<jsp:useBean id="documento" class="bean.Documento" scope="session" />
<jsp:setProperty name="documento" property="*"/>

<!DOCTYPE html PUBLIC>
<%@page import="controller.Chooser"%>
<%@page import="java.io.File"%>
<%@page import="java.io.IOException"%>
<%@page import="java.util.Iterator"%>
<%@page import="java.util.Map"%>
<%@page import="java.util.Set"%>
<%@page import="bean.Documento"%>
<%@page import="controller.Utilitaria"%>
<%@page import="javax.swing.JFileChooser"%>
<%@page import="javax.swing.filechooser.FileNameExtensionFilter"%>

<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:h="http://java.sun.com/jsf/html">

<head>
</head>
<body>
<%
  			
	JFileChooser chooser = new JFileChooser();
		System.out.println("criou o chooser");
		FileNameExtensionFilter filter = new FileNameExtensionFilter(
				"Arquivos & Imagens", "jpg", "gif", "png", "tif", "jpeg", "pdf", 
			    "ass", "rtf", "mht", "xls", "log", "docx", "doc", "odt", "htm");
		
		chooser.setFileFilter(filter);
		chooser.setDialogTitle("Escolhendo o arquivo");
		int returnVal = chooser.showOpenDialog(null);
		System.out.println("open dialog");
		if (returnVal == JFileChooser.APPROVE_OPTION) {
			File file = new File(chooser.getSelectedFile().getAbsolutePath());
			System.out.println("Nome do arquivo escolhido:"+file);	
			
			documento.setNomeArquivoOriginal(file.getAbsolutePath());
		}
		
    response.sendRedirect( "incluirDocumento.jsp" );
%>  
</body>
</html>