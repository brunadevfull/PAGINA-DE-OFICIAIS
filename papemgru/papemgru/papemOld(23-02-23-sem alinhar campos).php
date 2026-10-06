  <head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>PagTesouro</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="jquery-3.5.1.min.js"></script>
  <script src="jquery.mask.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script> 

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
  <script>
  //limpar cache
if (!location.hash) {
location.hash = "#reloading";
location.reload(true);
} else {
location.hash = "#reloaded";
}
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
	
	#mp:hover{
		color:#FDEE2F !important; 
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
 <!--script lang="javascript">
    setTimeout(function(){
      window.location.href = "https://10.1.129.16/papemgru";
	},60000);
  </script-->
</body>
  <table style="text-align: left; width: 50%; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
   <tbody>
     <div class="all">
        <div class="row">
            <div class="center-align col s12">
                <!--<p>
                  <img src="índice.png"><h5>Pagamento de GRU por meio do PagTesouro</h3><h7>(Homologação)</h3><br>
                                                          
                </p>
				<p><img src="IMG.jpg" width="1920" height="200" usemap="#Map" border="0" />-->
				<!-- Barra Brasil -->
  <!--div id="barra-brasil" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;" class="no-contrast">
    <ul id="menu-barra-temp" style="list-style:none;">
      <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a></li>
      <li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
    </ul>
  </div-->

    <header id="header" role="header">
  <div class="container">
    <div class="header-top">
      <div class="row">
        <!--div class="col-sm-7">
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
        </div-->
      </div>
    </div>
    <div class="header-main">
      <div class="row">
        <div class="col-sm-8 info">
          <div class="site-info">
                          <a class="logo navbar-btn pull-left" href="https://hmg-pagtesouro.papem.mb/papemgru/" title="Início">
                <img src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/%5Bremoval.ai%5D_tmp-624dedf80cb61%281%29%282%29_0.png" alt="Início" />
              </a>
                        <a href="https://hmg-pagtesouro.papem.mb/papemgru/">
              <span class="designation" style="color:white">Marinha do Brasil</span>
              <h1 class="site-name" style="color:white; font-family: 'Open Sans'; margin: 0 0 5px 0;">Pagadoria de Pessoal da Marinha</h1>
              <span class="subordination" style="color:white; margin: 107px; margin-top: -1%;">"Ordem, Prontidão e Regularidade"</span>
            </a>
          </div>
        </div>
        <div class="col-sm-4">
                    <div class="search">
            <!--form class="search-form clearfix" action="/papem/search/node" method="post" id="search-form" accept-charset="UTF-8"><div><div class="form-wrapper form-group" id="edit-basic"><div class="input-group"><input placeholder="Buscar" class="form-control form-text" type="text" id="edit-keys" name="keys" value="" size="40" maxlength="255" /><span class="input-group-btn"><button type="submit" class="btn btn-default"><span class="icon glyphicon glyphicon-search" aria-hidden="true"></span>
</button></span></div><button class="element-invisible btn btn-primary form-submit" type="submit" id="edit-submit" name="op" value="Buscar">Buscar</button>
</div><input type="hidden" name="form_build_id" value="form-AGi3UIvQS8r_mNAIJKd8O0HIlIy3IGfefi9zlmsyCmQ" />
<input type="hidden" name="form_id" value="search_form" />
</div></form-->          </div>
          
                  </div>
      </div>
    </div>
  </div>
</header>
<div id="navbar" style="background:#0e4097;">
  <div class="container">
    <!-- .btn-navbar is used as the toggle for collapsed navbar content -->

          <div>
        <nav role="navigation">
          <ul class="menu secondary"><li class="first leaf"><a href="manualPagTesouro.pdf" download="manualPagTesouro.pdf" title="" style="color:white; font-size:15px;" id="mp"><b>Manual PagTesouro</b> <i class="fa fa-book" id="mp" style="font-size:18px;color:#FDEE2F"></i></a></li>
<!--li class="leaf"><a href="http://www.planalto.gov.br/ccivil_03/_ato2011-2014/2011/lei/l12527.htm" title="" style="color:white;">Lei de Acesso às Informações</a></li>
<li class="last leaf"><a href="http://transparencia.gov.br/" title="" style="color:white;">Portal da Transparência</a></li-->
</ul>        </nav>
      </div>
      </div>
</div>

<div id="main" class="main-container container">

  <header role="banner" id="page-header">
          </header> <!-- /#page-header -->
            </div>
        </div>
		<?php
		if(isset($_GET['erro'])){
			echo "<div style='background-color:#FF0000; width:100%; height:25px; color:white; margin-top:-40px;'><center>O campo ".$_GET['erro']." não foi preenchido, tente novamente.</center></div>";
		}	
		?>
		
      <form method="post" action="pagtesouroControl.php" name="PagTesouro" onsubmit="return ValidaForm()">
       <br>
	   <div class="container">
	   <div class="row">
	   <div class="col">
	   <h1 style="color:#7F7F7F; font-family: 'Open Sans';">PagTesouro PAPEM</h1>
		<p style="color:red;"><strong style="color:red;">Obs:</strong> Habilite a opção de abertura de janelas, para a melhor utilização do sistema.</p>
	 <select class="form-control" id="exampleFormControlSelect1" name="naturezaD">
	  <option checked>Qual a natureza da devolução?</option>
      <option onclick="pagamentoPessoal()" value="Ppessoal" onclick="zerarRadio()">Pagamento de pessoal</option><!-- codigo recolhimento: 68801-0 -->
      <option onclick="mostrarRecAtivos()" value="recAtivos" onclick="zerarRadio()">Recuperação de Ativos (somente para o uso do SVPM)</option>
      <option onclick="mostrarSisres()" value="Sisres" onclick="zerarRadio()">SISRES</option><!-- codigo recolhimento: 68801-0 -->
    </select>
	    <br>
		<!-- PAGAMENTO PESSOAL -->
		<select class="form-control" id="pp" style="display:none" name="situacaoFuncional">
	  <option checked>Qual a situação funcional?</option>
      <option onclick="mostrarServidorCivil()" value="servidorCivil" onclick="zerarRadio()">Servidor Civil</option>
      <option onclick="mostrarVeteranoAnistiado()" value="veterano" onclick="zerarRadio()">Veterano/Anistiado</option>
      <option onclick="mostrarPensionista()" value="pensionista" onclick="zerarRadio()">Pensionista</option>
	  <option onclick="mostrarMilitarAtiva()" value="militarAtivo" onclick="zerarRadio()">Militar (Ativo)</option>
    </select>
	
	<!-- REC ATIVOS -->
	<input class="form-control" placeholder="Nome Completo" name="recAtivos_nome" type="text" class="competencia" style="display:none;" id="recAtivos1" maxlength="80">	
	<input class="form-control" placeholder="CPF" name="recAtivos_cpf" type="text" class="competencia" style="display:none; margin-top: 2%;" id="recAtivos2" onkeypress="return onlynumber();">
	<input class="form-control" placeholder="NIP" name="recAtivos_Nip" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="recAtivos3" onkeypress="return onlynumber();">
	<input class="form-control" placeholder="Valor do Exercício Atual" name="recAtivos_exAtual" type="text" style="display:none; margin-top: 2%;" id="recAtivos4" onKeyPress="return(moeda(this,'.',',',event))" maxlength="14">
	<input class="form-control" placeholder="Valor do Exercício Anterior" name="recAtivos_exAnterior" type="text" style="display:none; margin-top: 2%; margin-bottom:-150px;" id="recAtivos5" onKeyPress="return(moeda(this,'.',',',event))" maxlength="14">
	<!--input class="form-control" placeholder="Exercicio Anterior" type="number" style="display:none; margin-top: 2%; width:950;" id="servidorBruto" name="recAtivos_exAnterior" onKeyPress="return(moeda(this,'.',',',event))">-->
	
	<!-- SISRES -->
		<select class="form-control" id="ativarsisres" name="tipoSisres" style="display:none">
	  <option checked>Qual a situação funcional?</option>
      <option onclick="mostrarSisresSC()" value="SisresSC">Servidor Civil</option>
      <option onclick="mostrarSisresOutros()" value="SisresOutros">Militar/Pensionista/Veterano/Anistiado</option>
    </select>
    <input class="form-control" placeholder="Nome Completo" name="sisresSC_nome" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresSC1" maxlength="80">	
	<input class="form-control" placeholder="CPF" name="sisresSC_cpf" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresSC2" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
	<input class="form-control" placeholder="Matricula SIAPE" name="sisresSC_MatSIAPE" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresSC3" maxlength="7">
	<input class="form-control" placeholder="Valor a ser recolhido" name="sisresSC_valorRecolhido" type="text" class="competencia" style="display:none; margin-top: 2%; margin-bottom:-50%;" onKeyPress="return(moeda(this,'.',',',event))" id="sisresSC4">
	<!--<form>
	<INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op1" id="tributavel1" style="display:none" onclick="mostrarValorTributavel()"> <label id="tributavel2" style="display:none; margin-right:10%;">Tributavel</label>
    <INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op2" id="ntribuavel1" style="display:none" onclick="mostrarValorNaoTributavel()"> <label id="ntributavel2" style="display:none">Nao tributavel</label>
    </form>-->
	<!--<input class="form-control" placeholder="OC" name="sisresOutros_OC" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresOutros1">-->
    <input class="form-control" placeholder="Nome Completo" name="sisresOutros_nome" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresOutros1" maxlength="80">	
	<input class="form-control" placeholder="CPF" name="sisresOutros_cpf" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresOutros2" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
	<input class="form-control" placeholder="NIP" name="sisresOutros_Nip" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresOutros3" maxlength="9" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
	<input class="form-control" placeholder="Valor a ser recolhido" name="sisresOutros_valorRecolhido" type="text" class="competencia" style="display:none; margin-top: 2%; margin-bottom:-50%;" onKeyPress="return(moeda(this,'.',',',event))" id="sisresOutros4">
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
           <input class="form-control" placeholder="Nome Completo" type="text" class="competencia" style="display:none" id="servidor2" name="servidorNome" maxlength="80">
           <input class="form-control" placeholder="Nome Completo" name="veteranoNome" type="text" class="competencia" style="display:none" id="VeteranoAnistiado2" maxlength="80">
		   <input class="form-control" placeholder="Nome Completo" name="pensionistaNome" type="text" class="competencia" style="display:none" id="pensionista2" maxlength="80">
		   <input class="form-control" placeholder="Nome Completo" name="ativaNome" type="text" class="competencia" style="display:none" id="ativa2" maxlength="80">
		   <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="servidor3" ></label>
		   <label style="display:none" id="VeteranoAnistiado3" ></label>
		   <label style="display:none" id="pensionista3" ></label>
		   <label style="display:none" id="ativa3" ></label>
           <label style="display:none; margin:10px; margin-left:-550px;" id="sisresSC5" ></label>
		   <label style="display:none; margin:10px; margin-left:-550px;" id="sisresOutros5" ></label>
		   <br>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

 
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" type="text" style="display:none" id="servidor4" name="servidor4">
<script>

function IsPopupBlocker() {
        var oWin = window.open("popup.htm","go","width=1,height=1,top=900,left=900");
        if (oWin==null || typeof(oWin)=="undefined") {
                return true;
        }
        oWin.close();
    }
    if (IsPopupBlocker()) {
        alert("O Bloqueador de abertura de janelas está ativado, para que o site funcione corretamente, desabilite essa opção no navegador. ");
    }

$("#servidor4").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="VeteranoAnistiado4" type="text" style="display:none" id="VeteranoAnistiado4">
<script>
$("#VeteranoAnistiado4").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="pensionista4" type="text" style="display:none" id="pensionista4">
<script>
$("#pensionista4").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="ativa4" type="text" style="display:none" id="ativa4">
<script>
$("#ativa4").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>

<!--input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresSC6" type="text" style="display:none; width:950px; margin: -8px 77px 20px -0px;" id="sisresSC6"-->

<script>
$("#sisresSC6").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>
<!--input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresOutros5" type="text" style="display:none; width:950px; margin: -8px 77px 20px -0px;" id="sisresOutros6"-->
<script>
$("#sisresOutros6").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>
           
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
		   
           
           <input class="form-control" placeholder="CPF" name="veteranoCpfCnpj" type="text" class="competencia" style="display:none" id="VeteranoAnistiado6" class="cpfcnpj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="11">
		   <input class="form-control" placeholder="CPF" name="pensionistaCpfCnpj" type="text" class="competencia" style="display:none" id="pensionista6" class="cpfcnpj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="11">
		   <input class="form-control" placeholder="CPF" name="ativaCpfCnpj" type="text" class="competencia" style="display:none" id="ativa6" class="cpfcnpj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="11">
		   <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">  
		  
           
		   <label style="display:none" id="VeteranoAnistiado6" >NIP</label>
		   <label style="display:none" id="pensionista6" >NIP</label>
		   <label style="display:none" id="ativa6">NIP</label>
		   <br>
		   
           <input class="form-control" placeholder="NIP" name="veteranoNip" type="text" class="competencia" style="display:none" id="VeteranoAnistiado7" maxlength="9" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
		   <input class="form-control" placeholder="NIP" name="pensionistaNip" type="text" class="competencia" style="display:none" id="pensionista7" maxlength="9" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
		   <input class="form-control" placeholder="NIP" name="ativaNip" type="text" class="competencia" style="display:none" id="ativa7" maxlength="9" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">  
          </td>
		  <center>
		  <INPUT TYPE="RADIO" NAME="TipoTributo" VALUE="1" id="tributavel1" style="display:none; margin-top:1.5%;" onclick="mostrarValorTributavel()"> <label id="tributavel2" style="display:none; margin-right:10%;">Tributável</label>
          <INPUT TYPE="RADIO" NAME="TipoTributo" VALUE="2" id="ntribuavel1" style="display:none;" onclick="mostrarValorNaoTributavel()" checked> <label id="ntributavel2" style="display:none">Não tributável</label>
          <br>
		  <input class="form-control" placeholder="Valor Bruto a ser recolhido" type="text" style="display:none; width:950; margin: 10px -25px 0px 0px;" id="servidorBruto" name="valorBruto" onKeyPress="return(moeda(this,'.',',',event))" maxlength="14">
		  <input class="form-control" placeholder="Valor Líquido a ser recolhido" type="text" style="display:none; width:950; margin: 10px -25px 0px 0px;" id="servidorLiquido" name="valorLiquido" onKeyPress="return(moeda(this,'.',',',event))" maxlength="14">
	      </center>
		   <br>
		  
			          
		   
		  <!-- Linha tres -->
	<tr>	  
	<td style="vertical-align: middle; width: 50%; text-align: center;">
		   <label style="display:none" id="VeteranoAnistiado9" ></label>
		   <label style="display:none" id="pensionista9" ></label>
		   <label style="display:none" id="ativa9" ></label>
          </td>
          <center><?php
		  include "parcela_devolvida_SC.php";
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
		   
		</tr>
		<center><?php include "parcela_devolvida.php"; ?>
		<br><?php include "lista_sisresOutros_OC.php"; ?>
		<?php include "lista_sisresOutros_UPAG.php"; ?>
		<?php include "sisresOutros_Recolhedora.php"; ?>
		<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresOutros5" type="text" style="display:none; width:950px; margin-top: 2%; margin: 10px 0px 0px 25px;" id="sisresOutros6">
        <input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresSC6" type="text" style="display:none; width:950px; margin-top: 2%; margin: 10px 0px 0px 25px;" id="sisresSC6">
		<input class="form-control" placeholder="CPF" type="text" class="competencia" style="display:none; width:950px; margin-top:10px; margin-left:25px;" id="servidor6" class="cpfcnpj" name="servidorcpfcnpj" onkeypress="return onlynumber();" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="11">		
		</center>
	</tbody>
	</table>
	<!--img src="bp.png" style="position: relative; height:25; z-index:2; margin-top:-405px; margin-left: 10px;"></img-->
	<center><textarea id="storyPP" name="storyPP" placeholder="Motivo do recolhimento" rows="5" cols="33" style="display:none; margin-top:10px; padding: 8px 12px;" maxlength="200"></textarea></center>
     <center><textarea id="story" name="story" placeholder="Motivo do recolhimento" rows="5" cols="33" style="display:none; margin-top:-2%; padding: 8px 12px;" maxlength="200"></textarea></center>
	 <center><button type="submit" class="btn btn-success" style="margin-top:10px;">confirmar</button></center>
	</form>
	
		
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
 <!-- abaixo está o JavaScript responsavel por ocultar e fazer os campos aparecerem na pagina --> 
<script>
//Formatação Moeda
/*String.prototype.reverse = function(){
  return this.split('').reverse().join(''); 
};

function mascaraMoeda(campo,evento){
  const onlyDigits = event.target.value
    .split("")
    .filter(s => /\d/.test(s))
    .join("")
    .padStart(3, "0")
  const digitsFloat = onlyDigits.slice(0, -2) + "." + onlyDigits.slice(-2)
  event.target.value = maskCurrency(digitsFloat)
}

function maskCurrency(valor, locale = 'pt-BR', currency = 'BRL') {
  return new Intl.NumberFormat(locale, {
    currency
  }).format(valor)
}*/
  
function moeda(a, e, r, t) {
    let n = ""
      , h = j = 0
      , u = tamanho2 = 0
      , l = ajd2 = ""
      , o = window.Event ? t.which : t.keyCode;
    if (13 == o || 8 == o)
        return !0;
    if (n = String.fromCharCode(o),
    -1 == "0123456789".indexOf(n))
        return !1;
    for (u = a.value.length,
    h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
        ;
    for (l = ""; h < u; h++)
        -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
    if (l += n,
    0 == (u = l.length) && (a.value = ""),
    1 == u && (a.value = "0" + r + "0" + l),
    2 == u && (a.value = "0" + r + l),
    u > 2) {
        for (ajd2 = "",
        j = 0,
        h = u - 3; h >= 0; h--)
            3 == j && (ajd2 += e,
            j = 0),
            ajd2 += l.charAt(h),
            j++;
        for (a.value = "",
        tamanho2 = ajd2.length,
        h = tamanho2 - 1; h >= 0; h--)
            a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
    }
    return !1
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
var UPAG = document.querySelector("#UPAG");
UPAG.style.display = "none";
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
var x3=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var dados = "#sisresSC" + y;
   var dados2 = "#sisresOutros" + y2;
   var dados3 = "#recAtivos" + y3;
   var rec = "#recAtivos4";
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var recAtivos = document.querySelector(rec);
   var bruto = document.querySelector("#servidorBruto");
   campos.style.display = "none";
   campos2.style.display = "none";
   campos3.style.display = "none";
   recAtivos.style.display = "none";
   bruto.style.display = "none";
   x++;
   x2++;
   x3++;
   }  

var x4=0;
var x5=0;
for (var i = 0; i < 20; i++) {
   var y4 = x4+1;
   var y5 = x5+1;
   var dados4 = "#sisresOutros" + y4;
   var dados5 = "#sisresSC" + y5;
   var campos4 = document.querySelector(dados4);
   var campos5 = document.querySelector(dados5);
   
   campos4.style.display = "none";
   campos5.style.display = "none";
   
   x4++;
   x5++;
   }
 
   
}

//abaixo leva as opções do campo do Sisres
function mostrarSisres(){
var sisres = document.querySelector('#ativarsisres');
sisres.style.display = "inline";

//apagar da tela pagamento pessoal
var pp = document.querySelector('#pp');
pp.style.display = "none";

var story = document.querySelector("#story");
story.style.display = "none";
var parcela = document.querySelector("#parceladevol");//parcelas
parcela.style.display = "none";
var Oc = document.querySelector("#Oc");
Oc.style.display = "none";
var UPAG = document.querySelector("#UPAG");
UPAG.style.display = "none";
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
var x3=0;
var x4=0;
var x5=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var y4 = x4+1;
   var y5 = x5+1;
   var dados = "#servidor" + y;
   var dados2 = "#VeteranoAnistiado" +y;
   var dados3 = "#pensionista" +y;
   var dados4 = "#ativa" +y;
   var dados5 = "#recAtivos" + y5;
   var rec = "#recAtivos4";
   
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
   var campos5 = document.querySelector(dados5);
   var recAtivos = document.querySelector(rec);
   var bruto = document.querySelector("#servidorBruto");
   
   campos.style.display = "none";
   campos2.style.display = "none";
   campos3.style.display = "none";
   campos4.style.display = "none";
   campos5.style.display = "none";
   recAtivos.style.display = "none";
   bruto.style.display = "none";
   x5++;
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
   var dados2 = "#VeteranoAnistiado" + y2;
   var dados3 = "#pensionista" + y3;
   var dados4 = "#ativa" + y4;   
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var parcela = document. querySelector("#parceladevol");
   var Oc = document.querySelector("#UPAG");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   
   campos.style.display = "inline";
   campos2.style.display = "none";
   campos3.style.display = "none";
   campos4.style.display = "none";
   story.style.display = "inline";
   parcela.style.display= "none";
   parcela.style.display = "none";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   
   var oc = document.querySelector('#oc');
   oc.style.display = "none";
   
   var parceladevol = document.querySelector('#parceladevol');
   parceladevol.style.display = "none";
   
   var VeteranoAnistiado2 = document.querySelector('#VeteranoAnistiado2');
   var VeteranoAnistiado3 = document.querySelector('#VeteranoAnistiado3');
   var VeteranoAnistiado4 = document.querySelector('#VeteranoAnistiado4');
   var VeteranoAnistiado5 = document.querySelector('#VeteranoAnistiado5');
   var VeteranoAnistiado6 = document.querySelector('#VeteranoAnistiado6');
   var VeteranoAnistiado7 = document.querySelector('#VeteranoAnistiado7');
   
   
   VeteranoAnistiado2.style.display = "none";
   VeteranoAnistiado3.style.display = "none";
   VeteranoAnistiado4.style.display = "none";
   VeteranoAnistiado5.style.display = "none";
   VeteranoAnistiado6.style.display = "none";
   VeteranoAnistiado7.style.display = "none";
   
   var pensionista2 = document.querySelector('#pensionista2');
   var pensionista3 = document.querySelector('#pensionista3');
   var pensionista4 = document.querySelector('#pensionista4');
   var pensionista5 = document.querySelector('#pensionista5');
   var pensionista6 = document.querySelector('#pensionista6');
   var pensionista7 = document.querySelector('#pensionista7');
   
   
   pensionista2.style.display = "none";
   pensionista3.style.display = "none";
   pensionista4.style.display = "none";
   pensionista5.style.display = "none";
   pensionista6.style.display = "none";
   pensionista7.style.display = "none";
   
   var ativa2 = document.querySelector('#ativa2');
   var ativa3 = document.querySelector('#ativa3');
   var ativa4 = document.querySelector('#ativa4');
   var ativa5 = document.querySelector('#ativa5');
   var ativa6 = document.querySelector('#ativa6');
   var ativa7 = document.querySelector('#ativa7');
   
   
   ativa2.style.display = "none";
   ativa3.style.display = "none";
   ativa4.style.display = "none";
   ativa5.style.display = "none";
   ativa6.style.display = "none";
   ativa7.style.display = "none";
   
   
   const radioBtn = document.querySelector("#tributavel1")
   radioBtn.checked = false
   
   const nradioBtn = document.querySelector("#ntribuavel1")
   nradioBtn.checked = false
   
   x++;
   
   }    
}

//mostra todos os campos dos Veterano/Anistiado, igual ao acima, porem de uma forma compactada (usando uma estrutura de repetição
function mostrarVeteranoAnistiado(){
      
var x=0;
var x2=0;
var x3=0;
var x4=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var y4 = x4+1;
   var dados = "#VeteranoAnistiado" + y;
   var dados2 = "#pensionista" + y2;
   var dados3 = "#ativa" + y3;
   var dados4 = "#servidor" + y4;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
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
   campos4.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "inline";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   
   var pensionista10 = document.querySelector('#pensionista10');
   var ativa10 = document.querySelector('#ativa10');
   var UpagSC = document.querySelector('#UPAG');
   
   pensionista10.style.display = "none";
   ativa10.style.display = "none";
   UpagSC.style.display = "none";
   
  
   var servidor7 = document.querySelector('#servidor7');
   var pensionista7 = document.querySelector('#pensionista7');
   var ativa7 = document.querySelector('#ativa7');
   
   servidor7.style.display = "none";
   pensionista7.style.display = "none";
   ativa7.style.display = "none";
   
   var servidor2 = document.querySelector('#servidor2');
   var servidor4 = document.querySelector('#servidor4');
   var servidor3 = document.querySelector('#servidor3');
   var servidor6 = document.querySelector('#servidor6');
   
   
   servidor2.style.display = "none";
   servidor3.style.display = "none";
   servidor4.style.display = "none";
   servidor6.style.display = "none";
   
   const radioBtn = document.querySelector("#tributavel1")
   radioBtn.checked = false
   
   const nradioBtn = document.querySelector("#ntribuavel1")
   nradioBtn.checked = false
   
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
   var dados4 = "#servidor" + y3;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
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
   
   campos4.style.display = "none";
   
   var veteranoAnistiado10 = document.querySelector('#VeteranoAnistiado10');
   var ativa10 = document.querySelector('#ativa10');
   
   veteranoAnistiado10.style.display = "none";
   ativa10.style.display = "none";
   
   var veteranoAnistiado7 = document.querySelector('#VeteranoAnistiado7');
   var ativa7 = document.querySelector('#ativa7');
   var UpagSC = document.querySelector('#UPAG');
 
   
   veteranoAnistiado7.style.display = "none";
   ativa7.style.display = "none";
   UpagSC.style.display = "none";
   
   var servidor2 = document.querySelector('#servidor2');
   var servidor4 = document.querySelector('#servidor4');
   var servidor6 = document.querySelector('#servidor6');
   
   
   servidor2.style.display = "none";
   servidor4.style.display = "none";
   servidor6.style.display = "none";
   
   const radioBtn = document.querySelector("#tributavel1")
   radioBtn.checked = false
   
   const nradioBtn = document.querySelector("#ntribuavel1")
   nradioBtn.checked = false
   
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
   var dados4 = "#servidor" + y3;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
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
   
   campos4.style.display = "none";
   
   var veteranoAnistiado10 = document.querySelector('#VeteranoAnistiado10');
   var pensionista10 = document.querySelector('#pensionista10');
   var UpagSC = document.querySelector('#UPAG');
   
   veteranoAnistiado10.style.display = "none";
   pensionista10.style.display = "none";
   UpagSC.style.display = "none";
   
   var veteranoAnistiado7 = document.querySelector('#VeteranoAnistiado7');
   var pensionista7 = document.querySelector('#pensionista7');
   
   
   veteranoAnistiado7.style.display = "none";
   pensionista7.style.display = "none";
   
   var servidor2 = document.querySelector('#servidor2');
   var servidor4 = document.querySelector('#servidor4');
   var servidor6 = document.querySelector('#servidor6');
   
   
   servidor2.style.display = "none";
   servidor4.style.display = "none";
   servidor6.style.display = "none";
   
   
   const radioBtn = document.querySelector("#tributavel1")
   radioBtn.checked = false
   
   const nradioBtn = document.querySelector("#ntribuavel1")
   nradioBtn.checked = false
   
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
   var UPAG = document.querySelector("#UPAG");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   campos.style.display = "inline";
   campos2.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "none";
   UPAG.style.display = "inline";
   Oc.style.display = "none";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "none";
   tributavel2.style.display = "none";
   ntribuavel1.style.display = "none";
   ntribuavel2.style.display = "none";
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
   var UPAG = document.querySelector("#UPAG");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   campos.style.display = "inline";
   campos2.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "none";
   Oc.style.display = "inline";
   UPAG.style.display = "none";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "none";
   tributavel2.style.display = "none";
   ntribuavel1.style.display = "none";
   ntribuavel2.style.display = "none";
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

function mostrarRecAtivos(){
var x=0;
var x2=0;
var x3=0;
var x4=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var y4 = x4+1;
   var dados = "#recAtivos" + y;
   var dados2 = "#ativa" + y2;
   var dados3 = "#VeteranoAnistiado" + y3;
   var dados4 = "#pensionista" + y4;
   var dados5 = "#sisresOutros" + y;
   var dados6 = "#sisresSC" + y2;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
   var campos5 = document.querySelector(dados5);
   var campos6 = document.querySelector(dados6);
   var story = document.querySelector("#story");
   var bruto = document.querySelector("#servidorBruto");
   campos.style.display = "inline";
   story.style.display = "inline";
   bruto.style.display = "inline";
   
   campos2.style.display = "none";
   campos3.style.display = "none";
   campos4.style.display = "none";
   campos5.style.display = "none";
   campos6.style.display = "none";
   
   var ativarsisres = document.querySelector('#ativarsisres');
   ativarsisres.style.display = "none";
   
   var sisresSC6 = document.querySelector('#sisresSC6');
   sisresSC6.style.display = "none";
   
   var sisresOutros6 = document.querySelector('#sisresOutros6');
   sisresOutros6.style.display = "none";
   
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
   liquido.style.display = "none";
   bruto.style.display = "none";
   
   var oc = document.querySelector('#oc');
   oc.style.display = "none";
   
   var pp = document.querySelector('#pp');
   pp.style.display = "none";
   
   var parceladevol = document.querySelector('#parceladevol');
   parceladevol.style.display = "none";
   
   var VeteranoAnistiado2 = document.querySelector('#VeteranoAnistiado2');
   var VeteranoAnistiado3 = document.querySelector('#VeteranoAnistiado3');
   var VeteranoAnistiado4 = document.querySelector('#VeteranoAnistiado4');
   var VeteranoAnistiado5 = document.querySelector('#VeteranoAnistiado5');
   var VeteranoAnistiado6 = document.querySelector('#VeteranoAnistiado6');
   var VeteranoAnistiado7 = document.querySelector('#VeteranoAnistiado7');
   
   
   VeteranoAnistiado2.style.display = "none";
   VeteranoAnistiado3.style.display = "none";
   VeteranoAnistiado4.style.display = "none";
   VeteranoAnistiado5.style.display = "none";
   VeteranoAnistiado6.style.display = "none";
   VeteranoAnistiado7.style.display = "none";
   
   var pensionista2 = document.querySelector('#pensionista2');
   var pensionista3 = document.querySelector('#pensionista3');
   var pensionista4 = document.querySelector('#pensionista4');
   var pensionista5 = document.querySelector('#pensionista5');
   var pensionista6 = document.querySelector('#pensionista6');
   var pensionista7 = document.querySelector('#pensionista7');
   
   
   pensionista2.style.display = "none";
   pensionista3.style.display = "none";
   pensionista4.style.display = "none";
   pensionista5.style.display = "none";
   pensionista6.style.display = "none";
   pensionista7.style.display = "none";
   
   var ativa2 = document.querySelector('#ativa2');
   var ativa3 = document.querySelector('#ativa3');
   var ativa4 = document.querySelector('#ativa4');
   var ativa5 = document.querySelector('#ativa5');
   var ativa6 = document.querySelector('#ativa6');
   var ativa7 = document.querySelector('#ativa7');
   
   
   ativa2.style.display = "none";
   ativa3.style.display = "none";
   ativa4.style.display = "none";
   ativa5.style.display = "none";
   ativa6.style.display = "none";
   ativa7.style.display = "none";
   
   var UpagSC = document.querySelector('#UPAG');
   UpagSC.style.display = "none";
   
  
   var servidor7 = document.querySelector('#servidor7');
   servidor7.style.display = "none";
   var servidor2 = document.querySelector('#servidor2');
   var servidor4 = document.querySelector('#servidor4');
   var servidor3 = document.querySelector('#servidor3');
   var servidor6 = document.querySelector('#servidor6');
   
   
   servidor2.style.display = "none";
   servidor3.style.display = "none";
   servidor4.style.display = "none";
   servidor6.style.display = "none";
   
   x++;
   x2++;
   x3++;
   x4++;

   }    
}

function onlynumber(evt) {
   var theEvent = evt || window.event;
   var key = theEvent.keyCode || theEvent.which;
   key = String.fromCharCode( key );
   //var regex = /^[0-9.,]+$/;
   var regex = /^[0-9.]+$/;
   if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
   }
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
		url: "consultar.php",
		method: "POST",
		data:{nome:nome},
		success: function(data){
			$('#resultado2').html(data);
		}
	});	
}
$(document).ready(function(){
	buscarNome2();
	
	$('#sisresOutros4').keyup(function(){
		var nome = $(this).val();
		if (nome != ''){
			buscarNome2(nome);
		}else{
			buscarNome2();
			buscarN
		}
	});
});	;

//Evitando Sql Inject - impedindo que o usuario insira caractere especial
//Nome Completo
		document.getElementById("ativa2").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("servidor7").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("pensionista2").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("servidor2").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("VeteranoAnistiado2").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };

//CPF

		document.getElementById("VeteranoAnistiado6").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("pensionista6").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("ativa6").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("servidor6").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };

//NIP

	   document.getElementById("ativa7").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("pensionista7").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("VeteranoAnistiado7").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };

//MOTIVO

	   document.getElementById("storyPP").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };

       document.getElementById("story").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };

//SISRES 	

	   document.getElementById("sisresSC1").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };

	   document.getElementById("sisresSC2").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };

	   document.getElementById("sisresSC3").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("sisresOutros1").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("sisresOutros2").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       };
	   
	   document.getElementById("sisresOutros3").onkeypress = function(e) {
         var chr = String.fromCharCode(e.which);
         if ("1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM ".indexOf(chr) < 0)
           return false;
       }; 
</script>

 </body>
</html>

