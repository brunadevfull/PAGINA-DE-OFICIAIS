
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>PagTesouro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="jquery-3.5.1.min.js"></script>
  <script src="jquery.mask.min.js"></script>
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
          <ul class="shortcuts"><li id="link-content"><a href="/papem/node/38#main-content" accesskey="1" class="active">Ir para o conteúdo <span>1</span></a></li>
<li id="link-menu"><a href="/papem/node/38#sidebar-first-menu" accesskey="2" class="active">Ir para o menu <span>2</span></a></li>
<li id="link-search"><a href="/papem/node/38#edit-keys" accesskey="3" class="active">Ir para a pesquisa <span>3</span></a></li>
<li id="link-footer"><a href="/papem/node/38#footer" accesskey="4" class="active">Ir para o rodapé <span>4</span></a></li>
</ul>        </div>
        <div class="col-sm-5">
          <ul class="accessibility">
                        <li>
              <a href="/papem/node/49">Acessibilidade</a>            </li>
                        <li>
              <a href="javascript:void(0)" id="contrast">ALTO CONTRASTE</a>
            </li>
                        <li>
              <a href="/papem/sitemap">Mapa do Site</a>            </li>
                      </ul>
        </div>
      </div>
    </div>
    <div class="header-main">
      <div class="row">
        <div class="col-sm-8 info">
          <div class="site-info">
                          <a class="logo navbar-btn pull-left" href="/papem/" title="Início">
                <img src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/coroa_2_linhas%5B1%5D.png" alt="Início" />
              </a>
                        <a href="/papem/">
              <span class="designation">Organização Militar</span>
              <h1 class="site-name">PAGADORIA DE PESSOAL DA MARINHA</h1>
              <span class="subordination">Marinha do Brasil</span>
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
      <form method="post" action="pagtesouro.php" name="PagTesouro" onsubmit="return ValidaForm()">
       <br>
	   <div class="container">
	   <div class="row">
	   <div class="col">
	 <select class="form-control" id="exampleFormControlSelect1">
	  <option checked>Qual a natureza da devolução?</option>
      <option onclick="mostrarReferencia()">Pagamento de pessoal</option><!-- codigo recolhimento: 68801-0 -->
      <option>Municiamento</option><!-- codigo recolhimento: 68888-6 -->
      <option>SISRES</option><!-- codigo recolhimento: 68801-0 -->
    </select>
	    <br>
		<input class="form-control" name="referencia" placeholder="Número de Referência" min="0" type="number" style="display:none" id="referencia" onkeypress="mostrarCampos()">
           <br>
		   </div>
		</div>
        </div>
			<br>
		<div class="mb-5">	
       <table style="text-align: left; width: 80%; margin-left:10%;" border="0" cellpadding="2" cellspacing="2">
        <tbody>
         <tr>
          <!--<td style="vertical-align: middle; width: 50%; text-align: center;">
		 
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           
          </td>
         </tr>
         <tr>-->
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos20" >Competência</label>
           <br>
           <input class="form-control" placeholder="Competência" name="competencia" type="text" class="competencia" placeholder="MM/AAAA" pattern="(1[0-3]|0[1-9])\/([0-9]{4})" style="display:none" id="campos1">
           <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos2" >Vencimento</label>
           <br>
           <input class="form-control" placeholder="Vencimento" name="vencimento" type="date" style="display:none" id="campos3">
           <br>
          </td>
         </tr>
         <tr>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos4" >CNPJ/CPF do Contribuinte</label>
           <br>
           <input class="form-control" name="cnpjCpf" type="text" class="cpfcnpj" style="display:none" id="campos5">
           <div style="display: none;">
            <input name="jf" id="jf" type="text">
           </div>
           <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos6" >Nome do Contribuinte</label>
           <br>
           <input class="form-control" name="nomeContribuinte" type="text" maxlength="45" size="45" style="display:none" id="campos7" required>
           <br>
          </td>
         </tr>
         <tr>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos8">Valor Principal</label>
           <br>
           <input class="form-control" name="valorPrincipal" type="text" class="dinheiro" value="000" placeholder="0,00" style="display:none; text-align:right;" id="campos9" required>
           <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos10" >Valor de Descontos</label>
           <br>
           <input class="form-control" name="valorDescontos" type="text" class="dinheiro" value="000" placeholder="0,00" style="display:none; text-align:right;" id="campos11" required>
           <br>
          </td>
         </tr>
         <tr>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos12" >Valor de Outras Deduções</label>
           <br>
           <input class="form-control" name="valorOutrasDeducoes" type="text" class="dinheiro" value="000" placeholder="0,00" style="display:none; text-align:right;" id="campos13" required>
           <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos14" >Valor de Multa</label>
           <br>
           <input class="form-control" name="valorMulta" type="text" class="dinheiro" value="000" placeholder="0,00" style="display:none; text-align:right;" id="campos15" required>
           <br>
          </td>
         </tr>
         <tr>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos16" >Valor de Juros</label>
           <br>
           <input class="form-control" name="valorJuros" type="text" class="dinheiro" value="000" placeholder="0,00" style="display:none; text-align:right;" id="campos17" required>
           <br>
          </td>
          <td style="vertical-align: middle; width: 50%; text-align: center;">
           <label style="display:none" id="campos18" >Valor de Outros Acréscimos</label>
           <br>
           <input class="form-control" name="valorOutrosAcrescimos" type="text" class="dinheiro" value="000" placeholder="0,00" style="display:none; text-align:right;" id="campos19" required>
           <br>
          </td>
         </tr>
        </tbody>
       </table>
	   </div>
       <br>
       <table style="text-align: left; width: 100%; margin-left: auto; margin-right: auto;" border="0" cellpadding="2" cellspacing="2">
        <tbody>
         <tr>
          <td style="vertical-align: middle; text-align: center;">
           <button value="Enviar" name="enviar" class="w3-btn w3-padding w3-blue">Enviar</button>
           <br>
          </td>
		  <br><br>
		  </tr>
		  <tr>
			<td>
				<br><br>
				<div class="w3-container w3-blue">
					<a style="font-size:small;text-decoration: none;" href="#">- Código Fonte.</a>
				</div>
			</td>
		  </tr>
        </tbody>
       </table>
      </form>
     </td>
    </tr>
	</tbody>	
  </table> 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
function mostrarReferencia(){
var referencia = document.querySelector('#referencia');
referencia.style.display = "inline"; 
}

function mostrarCampos(){
var x=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var dados = "#campos" + y;
   var campos = document.querySelector(dados);
   campos.style.display = "inline";
   x++
   }    
}
</script>
 </body>
</html>
