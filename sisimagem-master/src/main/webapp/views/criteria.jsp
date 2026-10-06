<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SisImagem- Pesquisar</title>
    <link href="../css/estilo.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
</head>
<body>
<form action="regdoc.jsp">
    <div class="Table" style="width:100%;">
        <div class="Row">
            <div class="Cell" style=" padding-left:50px;">
                <div class="image-upload">
                	<!-- Buscar Imagem-->
                    
                        <img src="images/upload.png" style="vertical-align:middle; cursor:pointer; " width="50" title="Scanear Imagem"/>
                        Scanear Documento
                    
                    <!-- <label for="file-input">
                        <img src="../images/upload.png" style="vertical-align:middle; cursor:pointer; " width="50" title="Scanear Imagem"/>
                        Scanear Documento
                    </label>-->
                    <input id="file-input" type="file"/>
                    <!-- Buscar Imagem-->
                    <label for="file-input" style="padding-left:50px;">
                        <img src="images/folder.png" style="vertical-align:middle; cursor:pointer; " title="Buscar Imagem"/>
                        Buscar Documento
                    </label>
                    <input id="file-input" type="file"/>
                    <!-- Catalogar-->
                    <label for="file-submit" style="padding-left:50px;">
                        <img src="images/arrow.png" style="vertical-align:middle; cursor:pointer; " title="Catalogar no SisImagem"/>
                        Catalogar no SisImagem
                    </label>
                
                    <input id="file-submit" type="submit" formtarget="Resultado"/>
                </div>    
            </div>
		</div>
    </div>
</form>

</body>
</html>