
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>PagTesouro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="jquery-3.5.1.min.js"></script>
  <script src="jquery.mask.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script> 
  <script>
   function ValidaForm()
   {
    if (!(document.forms["PagTesouro"]["codigoServico"].value >= 0 && document.forms["PagTesouro"]["codigoServico"].value <= 99999))
    {
     alert("Código do Serviço válido somente entre 0 e 99999");
     return false;
    }
    if (!(document.forms["PagTesouro"]["referencia"].value >= 0 && document.forms["PagTesouro"]["referencia"].value <= 99999999999999999999))
    {
     alert("Número de Referência válido somente entre 0 e 99999999999999999999");
     return false;
    }
   }
   function FocoInicial()
   {
    document.getElementById("codigoServico").focus();
   }
   $(document).ready(function()
   {
    var cpfcnpjfunc=function (val)
    {
     if (val.length<15)
     {
      return '9999000.000.000-00';
     }
     else
     {
      return '00.000.000/0000-00';
     }
    },
    cpfcnpjopt=
    {
     onKeyPress: function(val, e, field, options)
     {
      field.mask(cpfcnpjfunc.apply({}, arguments), options);
      $("#jf").val(field.cleanVal());
     },
     reverse: true
    };
    $('input[name="cnpjCpf"]').val($("#jf").val());
    $('.cpfcnpj').mask(cpfcnpjfunc, cpfcnpjopt);
    $('.cpfcnpj').mask(cpfcnpjfunc, cpfcnpjopt);
    $('.competencia').mask('00/0000');
    $('.dinheiro').mask('000.000.000.000.000,00',{reverse: true});
   });
  </script>
  <style>
   .piscando{
    animation:blink 1.2s infinite;
    }
   @keyframes blink{
    0%{     color: #FF0000;    }
    49%{    color: #FF0000; }
    60%{    color: transparent; }
    99%{    color:transparent;  }
    100%{   color: #FF0000;    }
	}
	
	
	<!-- teste 
	.fab{
  position: fixed;
  bottom:10px;
  right:10px;
}

fab button{
  cursor: pointer;
  width: 48px;
  height: 48px;
  border-radius: 30px;
  background-color: #cb60b3;
  border: none;
  box-shadow: 0 1px 5px rgba(0,0,0,.4);
  font-size: 24px;
  color: white;
    
  -webkit-transition: .2s ease-out;
  -moz-transition: .2s ease-out;
  transition: .2s ease-out;
}

.fab button:focus{
  outline: none;
}

.fab button.main{
  position: absolute;
  width: 60px;
  height: 60px;
  border-radius: 30px;
  background-color: #5b19b7;
  right: 0;
  bottom: 0;
  z-index: 20;
}

.fab button.main:before{
  content: '⏚';
}
   itor-->
  </style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Generator" content="Drupal 7 (http://drupal.org)" />
<link rel="canonical" href="/papem/node/38" />
<link rel="shortlink" href="/papem/node/38" />
<link rel="shortcut icon" href="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/favicon.ico" type="image/vnd.microsoft.icon" />
  <title>Portal de Consignações | PAPEM</title>
  <style>
@import url("https://www.marinha.mil.br/papem/modules/system/system.base.css?qw56a0");
</style>
<style>
@import url("https://www.marinha.mil.br/papem/sites/all/modules/comment/comment.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/modules/field/theme/field.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/all/modules/node/node.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/all/modules/search/search.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/all/modules/views/css/views.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/all/modules/ckeditor/css/ckeditor.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/all/modules/media/modules/media_wysiwyg/css/media_wysiwyg.base.css?qw56a0");
</style>
<style>
@import url("https://www.marinha.mil.br/papem/sites/all/modules/ctools/css/ctools.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/css/color_scheme/white/styles.css?qw56a0");
</style>
<style>
@import url("https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/css/style.css?qw56a0");
</style>
<style>
@import url("https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/css_injector/css_injector_1.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/css_injector/css_injector_2.css?qw56a0");
@import url("https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/css_injector/css_injector_3.css?qw56a0");
</style>
  <!-- HTML5 element support for IE6-8 -->
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script>window.jQuery || document.write("<script src='/papem/sites/all/modules/jquery_update/replace/jquery/1.9/jquery.js'>\x3C/script>")</script>
<script src="https://www.marinha.mil.br/papem/misc/jquery-extend-3.4.0.js?v=1.9.1"></script>
<script src="https://www.marinha.mil.br/papem/misc/jquery-html-prefilter-3.5.0-backport.js?v=1.9.1"></script>
<script src="https://www.marinha.mil.br/papem/misc/jquery.once.js?v=1.2"></script>
<script src="https://www.marinha.mil.br/papem/misc/drupal.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/languages/pt-br_A963MDGIEwz9I1f2rwbYq2R79hYr-hZ1pyU0m9-pjTc.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/modules/matomo/matomo.js?qw56a0"></script>
<script>var _paq = _paq || [];(function(){var u=(("https:" == document.location.protocol) ? "https://analytics.mar.mil.br/" : "http://analytics.mar.mil.br/");_paq.push(["setSiteId", "36"]);_paq.push(["setTrackerUrl", u+"piwik.php"]);_paq.push(["setDoNotTrack", 1]);_paq.push(["trackPageView"]);_paq.push(["setIgnoreClasses", ["no-tracking","colorbox"]]);_paq.push(["enableLinkTracking"]);var d=document,g=d.createElement("script"),s=d.getElementsByTagName("script")[0];g.type="text/javascript";g.defer=true;g.async=true;g.src=u+"piwik.js";s.parentNode.insertBefore(g,s);})();</script>
<script src="https://www.marinha.mil.br/papem/sites/all/modules/piwik/piwik.js?qw56a0"></script>
<script>var _paq = _paq || [];(function(){var u=(("https:" == document.location.protocol) ? "https://analytics.mar.mil.br/" : "http://analytics.mar.mil.br/");_paq.push(["setSiteId", "36"]);_paq.push(["setTrackerUrl", u+"piwik.php"]);_paq.push(["setDoNotTrack", 1]);_paq.push(["trackPageView"]);_paq.push(["setIgnoreClasses", ["no-tracking","colorbox"]]);_paq.push(["enableLinkTracking"]);var d=document,g=d.createElement("script"),s=d.getElementsByTagName("script")[0];g.type="text/javascript";g.defer=true;g.async=true;g.src=u+"piwik.js";s.parentNode.insertBefore(g,s);})();</script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/js/highcontrast.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/js/mobile.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/affix.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/alert.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/button.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/carousel.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/collapse.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/dropdown.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/modal.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/tooltip.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/popover.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/scrollspy.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/tab.js?qw56a0"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/contrib/govbr_theme/bootstrap/js/transition.js?qw56a0"></script>
<script>jQuery.extend(Drupal.settings, {"basePath":"\/papem\/","pathPrefix":"","ajaxPageState":{"theme":"govbr_theme","theme_token":"RGVZgSsMLcgxlQO7ZhiWrfnZYJWAuDqGQpG3n6UyTrs","js":{"\/\/barra.brasil.gov.br\/barra.js":1,"sites\/all\/themes\/contrib\/bootstrap\/js\/bootstrap.js":1,"\/\/code.jquery.com\/jquery-1.9.1.js":1,"0":1,"misc\/jquery-extend-3.4.0.js":1,"misc\/jquery-html-prefilter-3.5.0-backport.js":1,"misc\/jquery.once.js":1,"misc\/drupal.js":1,"public:\/\/languages\/pt-br_A963MDGIEwz9I1f2rwbYq2R79hYr-hZ1pyU0m9-pjTc.js":1,"sites\/all\/modules\/matomo\/matomo.js":1,"1":1,"sites\/all\/modules\/piwik\/piwik.js":1,"2":1,"sites\/all\/themes\/contrib\/govbr_theme\/js\/highcontrast.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/js\/mobile.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/affix.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/alert.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/button.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/carousel.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/collapse.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/dropdown.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/modal.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/tooltip.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/popover.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/scrollspy.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/tab.js":1,"sites\/all\/themes\/contrib\/govbr_theme\/bootstrap\/js\/transition.js":1},"css":{"modules\/system\/system.base.css":1,"sites\/all\/modules\/comment\/comment.css":1,"modules\/field\/theme\/field.css":1,"sites\/all\/modules\/node\/node.css":1,"sites\/all\/modules\/search\/search.css":1,"sites\/all\/modules\/views\/css\/views.css":1,"sites\/all\/modules\/ckeditor\/css\/ckeditor.css":1,"sites\/all\/modules\/media\/modules\/media_wysiwyg\/css\/media_wysiwyg.base.css":1,"sites\/all\/modules\/ctools\/css\/ctools.css":1,"sites\/all\/themes\/contrib\/govbr_theme\/css\/color_scheme\/white\/styles.css":1,"sites\/all\/themes\/contrib\/govbr_theme\/css\/style.css":1,"public:\/\/css_injector\/css_injector_1.css":1,"public:\/\/css_injector\/css_injector_2.css":1,"public:\/\/css_injector\/css_injector_3.css":1}},"matomo":{"trackMailto":1},"piwik":{"trackMailto":1},"urlIsAjaxTrusted":{"\/papem\/search\/node":true},"bootstrap":{"anchorsFix":"0","anchorsSmoothScrolling":"0","formHasError":1,"popoverEnabled":1,"popoverOptions":{"animation":1,"html":0,"placement":"right","selector":"","trigger":"click","triggerAutoclose":1,"title":"","content":"","delay":0,"container":"body"},"tooltipEnabled":1,"tooltipOptions":{"animation":1,"html":0,"placement":"auto left","selector":"","trigger":"hover focus","delay":0,"container":"body"}}});</script>

 </head>
 <link rel="stylesheet" href="w3.css">
 <body onload="FocoInicial()">
  <table style="text-align: left; width: 50%; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
   <tbody>
     <center><div class="all">
        <div class="row">
            <div class="center-align col s12">
                <!--<p>
                  <img src="índice.png"><h5>Pagamento de GRU por meio do PagTesouro</h3><h7>(Homologação)</h3><br>
                                                          
                </p>
				<p><img src="IMG.jpg" width="1920" height="200" usemap="#Map" border="0" />-->
				<!-- Barra Brasil -->
  <div id="barra-brasil" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;" class="no-contrast">
    <ul id="menu-barra-temp" style="list-style:none;">
      <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a></li>
      <li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
    </ul>
  </div>

    <header id="header" role="header">
  <div class="container">
    <div class="header-top">
      <div class="row">
        <div class="col-sm-7">
          <ul class="shortcuts"><li id="link-content"><a href="/papem/node/38#main-content" accesskey="1" class="active" style="color:white">Ir para o conteúdo <span style="color:white">1</span></a></li>
<li id="link-menu"><a href="/papem/node/38#sidebar-first-menu" accesskey="2" class="active" style="color:white">Ir para o menu <span style="color:white">2</span></a></li>
<li id="link-search"><a href="/papem/node/38#edit-keys" accesskey="3" class="active" style="color:white">Ir para a pesquisa <span style="color:white">3</span></a></li>
<li id="link-footer"><a href="/papem/node/38#footer" accesskey="4" class="active" style="color:white">Ir para o rodapé <span style="color:white">4</span></a></li>
</ul>        </div>
        <div class="col-sm-5">
          <ul class="accessibility">
                        <li>
              <a href="/papem/node/49" style="color:white">Acessibilidade</a>            </li>
                        <li>
              <a href="javascript:void(0)" id="contrast" style="color:white">ALTO CONTRASTE</a>
            </li>
                        <li>
              <a href="/papem/sitemap" style="color:white">Mapa do Site</a>            </li>
                      </ul>
        </div>
      </div>
    </div>
    <div class="header-main">
      <div class="row">
        <div class="col-sm-8 info">
          <div class="site-info">
                          <a class="logo navbar-btn pull-left" href="http://localhost/pagina/papemgru/papem.php" title="Início">
                <img src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/coroa_2_linhas%5B1%5D.png" alt="Início" />
              </a>
                        <a href="/papem/">
              <span class="designation" style="color:white">Organização Militar</span>
              <h1 class="site-name" style="color:white">PAGADORIA DE PESSOAL DA MARINHA</h1>
              <span class="subordination" style="color:white">Marinha do Brasil</span>
            </a>
          </div>
        </div>
        <div class="col-sm-4">
                    <div class="search">
            <form class="search-form clearfix" action="/papem/search/node" method="post" id="search-form" accept-charset="UTF-8"><div><div class="form-wrapper form-group" id="edit-basic"><div class="input-group"><input placeholder="Buscar" class="form-control form-text" type="text" id="edit-keys" name="keys" value="" size="40" maxlength="255" /><span class="input-group-btn"><button type="submit" class="btn btn-default"><span class="icon glyphicon glyphicon-search" aria-hidden="true"></span>
</button></span></div><button class="element-invisible btn btn-primary form-submit" type="submit" id="edit-submit" name="op" value="Buscar">Buscar</button>
</div><input type="hidden" name="form_build_id" value="form-AGi3UIvQS8r_mNAIJKd8O0HIlIy3IGfefi9zlmsyCmQ" />
<input type="hidden" name="form_id" value="search_form" />
</div></form>          </div>
          
                  </div>
      </div>
    </div>
  </div>
</header>
<div id="navbar">
  <div class="container">
    <!-- .btn-navbar is used as the toggle for collapsed navbar content -->

          <div>
        <nav role="navigation">
          <ul class="menu secondary"><li class="first leaf"><a href="/papem/node/7" title="">Contato</a></li>
<li class="leaf"><a href="/papem/node/8" title="">Links Úteis</a></li>
<li class="leaf"><a href="http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2011/lei/l12527.htm" title="">Lei de Acesso às Informações</a></li>
<li class="last leaf"><a href="http://transparencia.gov.br/" title="">Portal da Transparência</a></li>
</ul>        </nav>
      </div>
      </div>
</div>

<div id="main" class="main-container container">

  <header role="banner" id="page-header">
          </header> <!-- /#page-header -->
            </div>
        </div></center>
		<input class="form-control" placeholder="NOME" name="nome" type="text" class="competencia" id="testebuscar">
		<a href="#"><div id="resultado2"></div></a>
	
      <form method="post" action="pagtesouroControl.php" name="PagTesouro" onsubmit="return ValidaForm()">
       <br>
	   <div class="container">
	   <div class="row">
	   <div class="col">
	 <select class="form-control" id="exampleFormControlSelect1" name="naturezaD">
	  <option checked>Qual a natureza da devolução?</option>
      <option onclick="pagamentoPessoal()" value="Ppessoal">Pagamento de pessoal</option><!-- codigo recolhimento: 68801-0 -->
      <!--<option onclick="mostrarMuniciamento()" value="Municiamento">Municiamento</option> codigo recolhimento: 68888-6 -->
      <option onclick="mostrarSisres()" value="Sisres">SISRES</option><!-- codigo recolhimento: 68801-0 -->
    </select>
	    <br>
		<!-- PAGAMENTO PESSOAL -->
		<select class="form-control" id="pp" style="display:none" name="situacaoFuncional">
	  <option checked>Qual a situação funcional?</option>
      <option onclick="mostrarServidorCivil()" value="servidorCivil">Servidor Civil</option>
      <option onclick="mostrarVeteranoAnistiado()" value="veterano">Veterano/Anistiado</option>
      <option onclick="mostrarPensionista()" value="pensionista">Pensionista</option>
	  <option onclick="mostrarMilitarAtiva()" value="militarAtivo">Militar(Ativo)</option>
    </select>
	
	<!-- MUNICIAMENTO -->
	<label style="display:none" id="municiamento1"></label>
	<!--<input class="form-control" placeholder="OM recolhedora" name="municiamentoOmRecolhedora" type="text" class="competencia" style="display:none" id="municiamento2">
	<div id="resultado"></div>-->
	<?php include "lista_om_recolhedoras.php"; ?>
	<label style="display:none" id="municiamento3"></label>
	<input class="form-control" placeholder="CNPJ da OM recolhedora" name="cnpjOmRecolhedora" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="municiamento4">
	<label style="display:none" id="municiamento5"></label>
	<input class="form-control" placeholder="Valor recolhido" name="valorOmRecolhedora" type="text" style="display:none; margin-top: 2%;" id="municiamento6">	
	
	<!-- SISRES -->
		<select class="form-control" id="ativarsisres" name="tipoSisres" style="display:none">
	  <option checked>Qual a situação funcional?</option>
      <option onclick="mostrarSisresSC()" value="SisresSC">Servidor Civil</option>
      <option onclick="mostrarSisresOutros()" value="SisresOutros">Militar/Pensionista/Veterano/Anistiado</option>
    </select>
    <input class="form-control" placeholder="NOME" name="sisresSC_nome" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresSC1">	
	<input class="form-control" placeholder="CPF" name="sisresSC_cpf" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresSC2">
	<input class="form-control" placeholder="Matricula SIAPE" name="sisresSC_MatSIAPE" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresSC3">
	<!--<form>
	<INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op1" id="tributavel1" style="display:none" onclick="mostrarValorTributavel()"> <label id="tributavel2" style="display:none; margin-right:10%;">Tributavel</label>
    <INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op2" id="ntribuavel1" style="display:none" onclick="mostrarValorNaoTributavel()"> <label id="ntributavel2" style="display:none">Nao tributavel</label>
    </form>-->
	<!--<input class="form-control" placeholder="OC" name="sisresOutros_OC" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresOutros1">-->
    <input class="form-control" placeholder="NOME" name="sisresOutros_nome" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresOutros1">	
	<input class="form-control" placeholder="CPF" name="sisresOutros_cpf" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresOutros2">
	<input class="form-control" placeholder="NIP" name="sisresOutros_Nip" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresOutros3">
	<!--<input class="form-control" placeholder="OC" name="sisresOutros_OC" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresOutros4">
	<div id="resultado2"></div>-->
	
	<!--<input class="form-control" placeholder="Valor a ser recolhido" name="sisresOutros_Valor" type="text" style="display:none; margin-top: 2%;" id="sisresOutros3" onKeyUp="mascaraMoeda(this, event)">
    input class="form-control" placeholder="OM recolhedora" name="sisresOutros_Recolhedora" type="text" style="display:none; margin-top: 2%;" id="sisresOutros6"-->		
	<br>
	<table style="text-align: left; width: 80%; margin-left:10%;" border="0" cellpadding="2" cellspacing="2">
    <tbody>
	<!-- Teste 
	<div class="fab">
	<button class="main">
	</button>
	<ul>
	</ul>
	</div>
	Itor -->
	<!-- Linha um -->
	<td style="vertical-align: middle; width: 50%; text-align: center;">			
           <label style="display:none" id="servidor1" ></label>
		   <label style="display:none" id="VeteranoAnistiado1" ></label>
		   <label style="display:none" id="pensionista1" ></label>
		   <label style="display:none" id="ativa1" ></label>
           <br>
           <input class="form-control" placeholder="Nome Completo" type="text" class="competencia" style="display:none" id="servidor2" name="servidorNome">
           <input class="form-control" placeholder="Nome Completo" name="veteranoNome" type="text" class="competencia" style="display:none" id="VeteranoAnistiado2">
		   <input class="form-control" placeholder="Nome Completo" name="pensionistaNome" type="text" class="competencia" style="display:none" id="pensionista2">
		   <input class="form-control" placeholder="Nome Completo" name="ativaNome" type="text" class="competencia" style="display:none" id="ativa2">
		   <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="servidor3" >Mês do recebimento indevido</label>
		   <label style="display:none" id="VeteranoAnistiado3" >Mês do recebimento indevido</label>
		   <label style="display:none" id="pensionista3" >Mês do recebimento indevido</label>
		   <label style="display:none" id="ativa3" >Mês do recebimento indevido</label>
           <br>
           <input class="form-control" placeholder="Vencimento" type="date" style="display:none" id="servidor4" name="servidorMesrecebimentoIndevido">
           <input class="form-control" placeholder="Vencimento" name="veteranoVencimento" type="date" style="display:none" id="VeteranoAnistiado4">
		   <input class="form-control" placeholder="Vencimento" name="pensionistaVencimento" type="date" style="display:none" id="pensionista4">
		   <input class="form-control" placeholder="Vencimento" name="ativaVencimento" type="date" style="display:none" id="ativa4">
           
		   <br>
          </td>
		  </tr>
		  <!-- Linha dois -->
	<tr>	  
	<td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="servidor5" ></label>
		   <label style="display:none" id="VeteranoAnistiado5" ></label>
		   <label style="display:none" id="pensionista5" ></label>
		   <label style="display:none" id="ativa5" ></label>
           <br>
		   
           
           <input class="form-control" placeholder="CPF" name="veteranoCpfCnpj" type="text" class="competencia" style="display:none" id="VeteranoAnistiado6" class="cpfcnpj">
		   <input class="form-control" placeholder="CPF" name="pensionistaCpfCnpj" type="text" class="competencia" style="display:none" id="pensionista6" class="cpfcnpj">
		   <input class="form-control" placeholder="CPF" name="ativaCpfCnpj" type="text" class="competencia" style="display:none" id="ativa6" class="cpfcnpj">
		   <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">  
           
		   <label style="display:none" id="VeteranoAnistiado6" >NIP</label>
		   <label style="display:none" id="pensionista6" >NIP</label>
		   <label style="display:none" id="ativa6" >NIP</label>
		   <br>
		   
           <input class="form-control" placeholder="Digite seu NIP" name="veteranoNip" type="text" class="competencia" style="display:none" id="VeteranoAnistiado7">
		   <input class="form-control" placeholder="Digite seu NIP" name="pensionistaNip" type="text" class="competencia" style="display:none" id="pensionista7">
		   <input class="form-control" placeholder="Digite seu NIP" name="ativaNip" type="text" class="competencia" style="display:none" id="ativa7">  
          </td>
		  <center>
		  <INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op1" id="tributavel1" style="display:none" onclick="mostrarValorTributavel()"> <label id="tributavel2" style="display:none; margin-right:10%;">Tributavel</label>
          <INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op2" id="ntribuavel1" style="display:none" onclick="mostrarValorNaoTributavel()"> <label id="ntributavel2" style="display:none">Nao tributavel</label>
          <br>
		  <input class="form-control" placeholder="Valor Bruto a ser recolhido" type="text" style="display:none; width:950;" id="servidorBruto" name="valorBruto" onKeyUp="mascaraMoeda(this, event)">
		  <input class="form-control" placeholder="Valor Liquido a ser recolhido" type="text" style="display:none; width:950; margin-top:10px;" id="servidorLiquido" name="valorLiquido" onKeyUp="mascaraMoeda(this, event)">
	      </center>
		   <br>
		  
			          
		   
		  <!-- Linha tres -->
	<tr>	  
	<td style="vertical-align: middle; width: 50%; text-align: center;">
		   <label style="display:none" id="VeteranoAnistiado9" ></label>
		   <label style="display:none" id="pensionista9" ></label>
		   <label style="display:none" id="ativa9" ></label>
           <br>
          </td>
          <center><?php 
		  include "parcela_devolvida_veteranos.php";
		  include "parcela_devolvida_pensionista.php";
		  include "parcela_devolvida_ativa.php";
		  ?></center>
		 
		   <!--<label style="display:none" id="VeteranoAnistiado11" >Parcela devolvidas</label>
		   <label style="display:none" id="pensionista11" >Parcela devolvidas</label>
		   <label style="display:none" id="ativa11" >Parcela devolvidas</label>
		   <br>
           <input class="form-control" placeholder="Valor" name="veteranoParcelasDevolvidas" type="text" class="competencia" style="display:none" id="VeteranoAnistiado12">
		   <input class="form-control" placeholder="Valor" name="pensionistaParcelasDevolvidas" type="text" class="competencia" style="display:none" id="pensionista12">
		   <input class="form-control" placeholder="Valor" name="ativaParcelasDevolvidas" type="text" class="competencia" style="display:none" id="ativa12">-->
		   <br>
        </tr>
		<center><?php include "parcela_devolvida.php"; ?>
		<?php include "lista_sisresOutros_OC.php"; ?>
		<?php include "sisresOutros_Recolhedora.php"; ?>
        <input class="form-control" placeholder="CPF" type="text" class="competencia" style="display:none; width:950px; margin-top:10px;" id="servidor6" class="cpfcnpj" name="servidorcpfcnpj">		
		</center>
	</tbody>
	</table>
	<img src="bp.png" style="height:30%; position: fixed; z-index:2; margin-top:-320px; margin-left: 10px;"></img>
	<center><textarea id="storyPP" name="story" placeholder="Motivo do recolhimento" rows="5" cols="33" style="display:none; margin-top:10px;"></textarea></center>
     <center><textarea id="story" name="story" placeholder="Motivo do recolhimento" rows="5" cols="33" style="display:none; margin-top:-2%;"></textarea></center>
	 <center><button type="submit" class="btn btn-success">confirmar</button></center>
	</form>
	
		
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <!-- abaixo está o JavaScript responsavel por ocultar e fazer os campos aparecerem na pagina --> 
<script>
//Formatação Moeda
String.prototype.reverse = function(){
  return this.split('').reverse().join(''); 
};

function mascaraMoeda(campo,evento){
  var tecla = (!evento) ? window.event.keyCode : evento.which;
  var valor  =  campo.value.replace(/[^\d]+/gi,'').reverse();
  var resultado  = "";
  var mascara = "##.###.###,##".reverse();
  for (var x=0, y=0; x<mascara.length && y<valor.length;) {
    if (mascara.charAt(x) != '#') {
      resultado += mascara.charAt(x);
      x++;
    } else {
      resultado += valor.charAt(y);
      y++;
      x++;
    }
  }
  campo.value = resultado.reverse();
}

//abaixo leva as opções do campo do pagamento pessoal
function pagamentoPessoal(){
var pp = document.querySelector('#pp');
pp.style.display = "inline";
//apagar as opções do sisres da tela
var sisres = document.querySelector('#ativarsisres');
sisres.style.display = "none";
var story = document.querySelector("#story");
story.style.display = "none";
var parcela = document.querySelector("#parceladevol");//parcelas
parcela.style.display = "none";
var Oc = document.querySelector("#Oc");
Oc.style.display = "none";
var OmRecolhedora = document.querySelector("#OmRecolhedora");
OmRecolhedora.style.display = "none";
var tributavel1 = document.querySelector("#tributavel1");
tributavel1.style.display = "none";
var tributavel2 = document.querySelector("#tributavel2");
tributavel2.style.display = "none";
var ntributavel1 = document.querySelector("#ntribuavel1");
ntributavel1.style.display = "none";
var ntributavel2 = document.querySelector("#ntributavel2");
ntributavel2.style.display = "none";



var x=0;
var x2=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var dados = "#sisresSC" + y;
   var dados2 = "#sisresOutros" + y2;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   campos.style.display = "none";
   campos2.style.display = "none";
   x++;
   x2++;
   }  

var x3=0;
var x4=0;
for (var i = 0; i < 20; i++) {
   var y3 = x3+1;
   var y4 = x4+1;
   var dados3 = "#sisresOutros" + y3;
   var dados4 = "#sisresSC" + y4;
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
   
   campos3.style.display = "none";
   campos4.style.display = "none";
   
   x3++;
   x4++;
   }
 
   
}

//abaixo leva as opções do campo do Sisres
function mostrarSisres(){
var sisres = document.querySelector('#ativarsisres');
sisres.style.display = "inline";

//apagar da tela pagamento pessoal
var pp = document.querySelector('#pp');
pp.style.display = "none";

var x=0;
var x2=0;
var x3=0;
var x4=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var y4 = x4+1;
   var dados = "#servidor" + y;
   var dados2 = "#VeteranoAnistiado" +y;
   var dados3 = "#pensionista" +y;
   var dados4 = "#ativa" +y;
   
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
   
   campos.style.display = "none";
   campos2.style.display = "none";
   campos3.style.display = "none";
   campos4.style.display = "none";
   
   var veteranoAnistiado10 = document.querySelector('#VeteranoAnistiado10');
   var pensionista10 = document.querySelector('#pensionista10');
   var ativa10 = document.querySelector('#ativa10');
   
   veteranoAnistiado10.style.display = "none";
   pensionista10.style.display = "none";
   ativa10.style.display = "none";
   
   var veteranoAnistiado7 = document.querySelector('#VeteranoAnistiado7');
   var pensionista7 = document.querySelector('#pensionista7');
   var ativa7 = document.querySelector('#ativa7');
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   var bruto = document.querySelector("#servidorBruto");
   var liquido = document.querySelector("#servidorLiquido");
   
   veteranoAnistiado7.style.display = "none";
   pensionista7.style.display = "none";
   ativa7.style.display = "none";
   story.style.display = "none";
   parcela.style.display = "none";
   Oc.style.display = "none";
   OmRecolhedora.style.display = "none";
   tributavel1.style.display = "none";
   tributavel2.style.display = "none";
   ntribuavel1.style.display = "none";
   ntribuavel2.style.display = "none";
   bruto.style.display = "none";
   liquido.style.display = "none";
   
   x++;
   
   }
}

function sisres(){
var sisres = document.querySelector('#sisres');
sisres.style.display = "inline"; 
}
function mostrarReferencia(){
var referencia = document.querySelector('#referencia');
referencia.style.display = "inline"; 
}

//mostra todos os campos de servior civil, porem de uma forma mais basica, afim de esclarecimento 
function mostrarServidorCivil(){
var x=0;
var x2=0;
var x3=0;
var x4=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var y4 = x4+1;
   var dados = "#servidor" + y;
   
   var campos = document.querySelector(dados);
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var parcela = document. querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   
   campos.style.display = "inline";
   story.style.display = "inline";
   parcela.style.display= "inline";
   parcela.style.display = "inline";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   
   x++;
   
   }    
}

//mostra todos os campos dos Veterano/Anistiado, igual ao acima, porem de uma forma compactada (usando uma estrutura de repetição
function mostrarVeteranoAnistiado(){
var x=0;
var x2=0;
var x3=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var dados = "#VeteranoAnistiado" + y;
   var dados2 = "#pensionista" + y2;
   var dados3 = "#ativa" + y3;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   
   campos.style.display = "inline";
   campos2.style.display = "none";
   campos3.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "inline";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   x++;
   x2++;
   x3++;
   }    
}

//responsavel por mostrar todos os campos dos pensionistas
function mostrarPensionista(){
var x=0;
var x2=0;
var x3=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var dados = "#pensionista" + y;
   var dados2 = "#VeteranoAnistiado" + y2;
   var dados3 = "#ativa" + y3;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   campos.style.display = "inline";
   campos2.style.display = "none";
   campos3.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "inline";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   x++;
   x2++;
   x3++;
   }   
}

//responsavel por gerar os campos dos militares da ativa
function mostrarMilitarAtiva(){
var x=0;
var x2=0;
var x3=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var dados = "#ativa" + y;
   var dados2 = "#VeteranoAnistiado" + y2;
   var dados3 = "#pensionista" + y3;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   campos.style.display = "inline";
   campos2.style.display = "none";
   campos3.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "inline";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   x++;
   x2++;
   x3++;
   }    
}

//responsavel por mostrar os campos municiamento
function mostrarMuniciamento(){
var x=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var dados = "#municiamento" + y;
   var campos = document.querySelector(dados);
   campos.style.display = "inline";
   x++
   }    
}


function mostrarSisresSC(){
var x=0;
var x2=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var dados = "#sisresSC" + y;
   var dados2 = "#sisresOutros" + y2;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var story = document.querySelector("#story");
   var parcela = document.querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   campos.style.display = "inline";
   campos2.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "inline";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   x++;
   x2++;
   }    
}

function mostrarSisresOutros(){
var x=0;
var x2=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var dados = "#sisresOutros" + y;
   var dados2 = "#sisresSC" + y2;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var story = document.querySelector("#story");
   var parcela = document.querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   campos.style.display = "inline";
   campos2.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "inline";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   x++;
   x2++;
   }    
}

function mostrarValorTributavel(){
var bruto = document.querySelector("#servidorBruto");
var liquido = document.querySelector("#servidorLiquido");

bruto.style.display = "inline";
liquido.style.display = "inline";
	
}

function mostrarValorNaoTributavel(){
var bruto = document.querySelector("#servidorBruto");
var liquido = document.querySelector("#servidorLiquido");

bruto.style.display = "inline";
liquido.style.display = "none";
	
}

//buscar nome trabalha com a função sql, pra ativar a pesquisa em tempo real
function buscarNome(nome){
	$.ajax({
		url: "search.php",
		method: "POST",
		data:{nome:nome},
		success: function(data){
			$('#resultado').html(data)               ;
		}
	});	
}
$(document).ready(function(){
	buscarNome();
	
	$('#municiamento2').keyup(function(){
		var nome = $(this).val();
		if (nome != ''){
			buscarNome(nome);
		}else{
			buscarNome();
		}
	});
});	;

$(document).ready(function(){
                
                $("#municiamento2").keyup(function(){
                   
                   var nome = $("#resultado").val();
                   
                   $.post("search.php",
                         {nome:valordigitado},
                         function(resposta){
                             $("#conteudo").html(resposta);
                         });
                   
                });
                
            });

function buscarNome2(nome){
	$.ajax({
		url: "buscar.php",
		method: "POST",
		data:{nome:nome},
		success: function(data){
			$('#resultado2').html(data);
		}
	});	
}
$(document).ready(function(){
	buscarNome2();
	
	$('#testebuscar').keyup(function(){
		var nome = $(this).val();
		if (nome != ''){
			buscarNome2(nome);
		}else{
			buscarNome2();
			buscarN
		}
	});
});	;
</script>
 </body>
</html>

