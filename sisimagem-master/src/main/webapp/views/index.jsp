<HTML>
<HEAD>
<TITLE>SisImagem</TITLE>
    <link href="../css/estilo.css" rel="stylesheet" type="text/css" />
    <script language="javascript" type="text/javascript">
  function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
  }
</script>
</HEAD>

<body class="index">
    <div class="topo">
		<iframe name="Topo" class="Topo" src="../views/topo.jsp" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" allowtransparency="true"/></iframe>
    </div>
    <div class="content">
		<iframe name="Principal" class="Principal" id="Principal" src="../views/content.jsp" frameborder="0" marginheight="0" marginwidth="0" allowtransparency="true" onload='javascript:resizeIframe(this);' >  </iframe>
    </div>
</body>
</html>