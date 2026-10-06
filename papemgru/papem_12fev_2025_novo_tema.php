<!DOCTYPE html>
<html lang="pt-br" dir="ltr">
  <head>
<!-- codigo Correa  -->
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
  <!--script>
  //limpar cache
if (!location.hash) {
location.hash = "#reloading";
location.reload(true);
} else {
location.hash = "#reloaded";
}
</script-->
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
<!-- codigo Correa -->


    <meta charset="utf-8" />
<meta name="Generator" content="Drupal 10 (https://www.drupal.org)" />
<meta name="MobileOptimized" content="width" />
<meta name="HandheldFriendly" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<style>div#sliding-popup, div#sliding-popup .eu-cookie-withdraw-banner, .eu-cookie-withdraw-tab {background: #0779bf} div#sliding-popup.eu-cookie-withdraw-wrapper { background: transparent; } #sliding-popup h1, #sliding-popup h2, #sliding-popup h3, #sliding-popup p, #sliding-popup label, #sliding-popup div, .eu-cookie-compliance-more-button, .eu-cookie-compliance-secondary-button, .eu-cookie-withdraw-tab { color: #ffffff;} .eu-cookie-withdraw-tab { border-color: #ffffff;}</style>
<link rel="icon" href="https://www.marinha.mil.br/papem/sites/all/themes/govbr/favicon.ico" type="image/vnd.microsoft.icon" />
<script>window.a2a_config=window.a2a_config||{};a2a_config.callbacks=[];a2a_config.overlays=[];a2a_config.templates={};</script>

    <title>Pagtesouro | Pagadoria de Pessoal da Marinha</title>
    <link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/ajax-progress.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/align.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/autocomplete-loading.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/fieldgroup.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/container-inline.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/clearfix.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/details.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/hidden.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/item-list.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/js.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/nowrap.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/position-container.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/progress.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/reset-appearance.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/resize.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/sticky-header.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/system-status-counter.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/system-status-report-counters.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/system-status-report-general-info.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/tabledrag.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/tablesort.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/system/components/tree-child.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/core/themes/stable9/css/views/views.module.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/all/modules/eu_cookie_compliance/css/eu_cookie_compliance.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/all/modules/addtoany/css/addtoany.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/all/themes/govbr/assets/css/bootstrap.min.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/all/themes/govbr/assets/css/style.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/all/themes/govbr/assets/fonts/rawline.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://use.fontawesome.com/releases/v6.4.2/css/all.css" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/css_editor/govbr.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/asset_injector/css/bloco_acesso_rapido-038079db4f40f7f6d9d3d5ade759b1d6.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/asset_injector/css/espaco_do_conteudo_para_o_rodape-c45cda4b48fd6430c7d9dcd2cae8c220.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/asset_injector/css/espaco_do_menu_para_o_conteudo-44380b69018c6cd5386469ffcb905adb.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/asset_injector/css/espaco_entre_nome_e_slogan-e57e1602a7171cd1df2edc647f247333.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/asset_injector/css/main-c11345962ea12a8cdf6b816883fc72ca.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/asset_injector/css/ocultar_barra_publicado_em_das_paginas-9a4f7e1ba3957b20593ac790fa52f885.css?srbqx3" />
<link rel="stylesheet" media="all" href="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/asset_injector/css/remover_titulos_das_paginas-da68f7d91568fafc7946b2cbd5520ea2.css?srbqx3" />

    <script src="https://use.fontawesome.com/releases/v6.4.2/js/all.js" defer crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/releases/v6.4.2/js/v4-shims.js" defer crossorigin="anonymous"></script>

  </head>
  <body>
        <a href="#main-content" class="visually-hidden focusable">
      Pular para o conteúdo principal
    </a>
    
      <div class="dialog-off-canvas-main-canvas" data-off-canvas-main-canvas>
    <section class="top-bar" id="topbar">
  <div class="container">
    <div class="row">
        <div class="align-items-center d-flex justify-content-between">
            <div class="esquerda">
                    <div id="block-govbr-logogovbr">
  
    
      
            <div>  <a href="https://www.gov.br/"><img loading="lazy" src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/styles/full_web/public/2024-02/govbr.png.webp?itok=POYRHZ8u" width="543" height="197" alt="" />

</a>
</div>
      
  </div>

            </div>
            <div id="menu-top-container">
                    <nav role="navigation" aria-labelledby="block-govbr-topmenu-menu" id="block-govbr-topmenu">
            
  <h2 class="visually-hidden" id="block-govbr-topmenu-menu">Top Menu</h2>
  

        
              <ul class="menu-list">
              <li>
                          <a href="http://www.acessoainformacao.gov.br/" class="nav-link">Acesso à Informação</a>
              </li>
          <li>
                          <a href="https://www.gov.br/pt-br/participacao-social/" class="nav-link">Participe</a>
              </li>
          <li>
                          <a href="http://www.planalto.gov.br/legislacao" class="nav-link">Legislação</a>
              </li>
          <li>
                          <a href="https://www.gov.br/pt-br/orgaos-do-governo" class="nav-link">Órgãos do Governo</a>
              </li>
        </ul>
   

  </nav>


            </div>
            
            <span class="menu-top justify-content-center align-items-center rounded-circle oculto-no-desktop"
                id="menu-top">
                <i class="fas fa-chevron-down arrow"></i>
            </span>
        </div>
    </div>
</div></section>
<header>
  <div class="container header-mobile oculto-no-desktop">
  <div class="top">
    <div class="logo">
      <a href="https://www.marinha.mil.br/papem/"><img src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/logoPAPEM.png" alt="Pagadoria de Pessoal da Marinha" class="logo-top"></a>
    </div>
    <span class="header-menu-top" id="header-menu-top">
      <i class="fa-solid fa-ellipsis-vertical"></i>
    </span>
    <div class="mapa">
      <a href="/mapa-do-site" class="siteMap">Mapa do Site</a>
      <a href="#altocontraste">
        <i class="fas fa-adjust"></i>
      </a>
    </div>
    <a id="acessibilidade">
      <i class="fa-solid fa-universal-access"></i>
    </a>
  </div>

  <div class="header-mobile-menu oculto-no-mobile" id="menu-mobile-esquerda">
        <nav role="navigation" aria-labelledby="block-govbr-headerleft-menu" id="block-govbr-headerleft">
            
  <h2 class="visually-hidden" id="block-govbr-headerleft-menu">Header left</h2>
  

        
              <ul class="menu-list">
              <li>
                          <a href="http://www.defesa.gov.br/" class="nav-link">Ministério da Defesa</a>
              </li>
          <li>
                          <a href="http://www.eb.mil.br/" class="nav-link">Exército</a>
              </li>
          <li>
                          <a href="http://www.fab.mil.br/index.php" class="nav-link">Força Aérea</a>
              </li>
          <li>
                          <a href="https://drupal-homolog.marinha.mil.br/papem/node/218" class="nav-link">Ouvidoria</a>
              </li>
          <li>
                          <a href="https://marinha.mil.br/area-de-imprensa" class="nav-link">Área de Imprensa</a>
              </li>
        </ul>
   

  </nav>


  </div>
  <div class="header-mobile-acessibilidade oculto-no-mobile" id="menu-mobile-acessibilidade">
        <nav role="navigation" aria-labelledby="block-govbr-headerright-menu" id="block-govbr-headerright">
            
  <h2 class="visually-hidden" id="block-govbr-headerright-menu">Header Right</h2>
  

        
              <ul class="menu-list">
              <li>
                          <a href="https://www.marinha.mil.br/papem/pt-br/node/49" class="nav-link" data-drupal-link-system-path="node/49">Acessibilidade</a>
              </li>
          <li>
                          <a href="https://www.marinha.mil.br/papem/pt-br/sitemap" class="nav-link" data-drupal-link-system-path="node/50">Mapa do Site</a>
              </li>
          <li>
                          <a href="#altocontraste" class="nav-link">Alto Contraste</a>
              </li>
        </ul>
   

  </nav>


  </div>
</div>
<div class="container oculto-no-mobile">
  <div class="row header-cima d-flex justify-content-between py-3">
    <div class="esquerda">
          <nav role="navigation" aria-labelledby="block-govbr-headerleft-menu" id="block-govbr-headerleft">
            
  <h2 class="visually-hidden" id="block-govbr-headerleft-menu">Header left</h2>
  

        
              <ul class="menu-list">
              <li>
                          <a href="http://www.defesa.gov.br/" class="nav-link">Ministério da Defesa</a>
              </li>
          <li>
                          <a href="http://www.eb.mil.br/" class="nav-link">Exército</a>
              </li>
          <li>
                          <a href="http://www.fab.mil.br/index.php" class="nav-link">Força Aérea</a>
              </li>
          <li>
                          <a href="https://drupal-homolog.marinha.mil.br/papem/node/218" class="nav-link">Ouvidoria</a>
              </li>
          <li>
                          <a href="https://marinha.mil.br/area-de-imprensa" class="nav-link">Área de Imprensa</a>
              </li>
        </ul>
   

  </nav>


    </div>
    <div class="direita">
          <nav role="navigation" aria-labelledby="block-govbr-headerright-menu" id="block-govbr-headerright">
            
  <h2 class="visually-hidden" id="block-govbr-headerright-menu">Header Right</h2>
  

        
              <ul class="menu-list">
              <li>
                          <a href="https://www.marinha.mil.br/papem/pt-br/node/49" class="nav-link" data-drupal-link-system-path="node/49">Acessibilidade</a>
              </li>
          <li>
                          <a href="https://www.marinha.mil.br/papem/pt-br/sitemap" class="nav-link" data-drupal-link-system-path="node/50">Mapa do Site</a>
              </li>
          <li>
                          <a href="#altocontraste" class="nav-link">Alto Contraste</a>
              </li>
        </ul>
   

  </nav>


    </div>
  </div>
</div>
<div id="menuTotalFixo">
  <div class="container">
    <div class="row header-baixo justify-content-between align-items-center">
      <div class="col-lg-8 col-md-6 header d-flex align-items-center py-2">
        <div class="menu-primary" id="menu-primary" style="display: block;">
          <i class="fa-solid fa-bars"></i>
        </div>
        <div id="closeMenu" style="display: none;">
          <i class="fas fa-times"></i>
        </div>
        <a href="https://www.marinha.mil.br/papem/" class="d-flex align-items-center">
          <div class="logo oculto-no-mobile">
            <img src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/logoPAPEM.png" alt="Pagadoria de Pessoal da Marinha">
          </div>
          <div class="cabecalho">
            <h1>Pagadoria de Pessoal da Marinha</h1>
            <p class="oculto-no-mobile">&quot;ORDEM, PRONTIDÃO E REGULARIDADE&quot;</p>
          </div>
        </a>
      </div>
      
    </div>
  </div>

  <div class="menu-primario-ativo pb-4" style="display: none;">
    <div class="barra-superior"></div>
    <div class="menu-content">
      <div class="container">
        <div class="row">
          <div class="col-12">
                <nav role="navigation" aria-labelledby="block-govbr-menuprimario-menu" id="block-govbr-menuprimario">
            
  <h2 class="visually-hidden" id="block-govbr-menuprimario-menu">Menu Primário</h2>
  

        
              <ul class="menu-list">
              <li>
                          <a href="manualPagTesouro.pdf" download="manualPagTesouro.pdf" class="nav-link">Manual PagTesouro</a>
              </li>
              </ul>
   

  </nav>


          </div>
        </div>
        <div class="row pt-5 menu-footer">
          <img src="/sites/all/themes/govbr/assets/img/logo govbr.svg" alt="">
        </div>
      </div>
    </div>
  </div>
</div>
    
       

    
</form>


          </div>
      </div>
    </div>
  </div>


</header>

  

   
<main class="pb-5 pt-3">
  <div class="container d-flex flex-column">
    <div id="block-govbr-page-title">
  
    
      
  <h1></h1>


  </div>



    <div data-drupal-messages-fallback class="hidden"></div><div id="block-govbr-testando">
  
      <h2>PagTesouro PAPEM</h2>
    
      <br>
 
<p style="color:red;"><strong style="color:red;">Obs:</strong> Habilite a opção de abertura de janelas, para a melhor utilização do sistema.</p>
   <select class="form-control" id="exampleFormControlSelect1" name="naturezaD">
    <option checked>Qual a natureza da devolução?</option>
      <option onclick="pagamentoPessoal()" value="Ppessoal" onclick="zerarRadio()">Pagamento de pessoal</option><!-- codigo recolhimento: 68801-0 -->
      <option onclick="mostrarRecAtivos()" value="recAtivos" onclick="zerarRadio()">Recuperação de Ativos (somente para o uso do SVPM)</option>
      <option onclick="mostrarSisres()" value="Sisres" onclick="zerarRadio()">SISRES</option><!-- codigo recolhimento: 68801-0 -->
    </select>
      <br>
    <!-- PAGAMENTO PESSOAL -->
    <select class="form-control" id="pp" style="display:none;" name="situacaoFuncional">
    <option checked>Qual a situação funcional?</option>
      <option onclick="mostrarServidorCivil()" value="servidorCivil" onclick="zerarRadio()">Servidor Civil</option>
      <option onclick="mostrarMilitarAtiva()" value="militarAtivo" onclick="zerarRadio()">Militar/Pensionista/Veterano/Anistiado/Ex-Militar</option>
    </select>

    <select class="form-control" id="ativa9" style="display:none; margin-top:15px;" name="naoutilizado">
    <option checked>Qual a situação funcional?</option>
      <option>Militar Ativo</option>
<option>Ex-Militar</option>

      <option>Pensionista</option>
      <option>Veterano</option>
      <option>Anistiado</option>
    </select>
  
  <!-- REC ATIVOS -->
  <input class="form-control" placeholder="Nome Completo" name="recAtivos_nome" type="text" class="competencia" style="display:none;" id="recAtivos1" maxlength="80" minlength="3"> 
  <input class="form-control" placeholder="CPF" name="recAtivos_cpf" type="text" class="competencia" style="display:none; margin-top: 2%;" id="recAtivos2" onkeypress="return onlynumber();">
  <input class="form-control" placeholder="NIP" name="recAtivos_Nip" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="recAtivos3" onkeypress="return onlynumber();">
  <input class="form-control" placeholder="Valor do Exercício Atual" name="recAtivos_exAtual" type="text" style="display:none; margin-top: 2%;" id="recAtivos4" onKeyPress="return(moeda(this,'.',',',event))" maxlength="14">
  <input class="form-control" placeholder="Valor do Exercício Anterior" name="recAtivos_exAnterior" type="text" style="display:none; margin-top: 2%; margin-bottom:-150px;" id="recAtivos5" onKeyPress="return(moeda(this,'.',',',event))" maxlength="14">
  <!--input class="form-control" placeholder="Exercicio Anterior" type="number" style="display:none; margin-top: 2%; width:950;" id="servidorBruto" name="recAtivos_exAnterior" onKeyPress="return(moeda(this,'.',',',event))">-->
  
  <!-- SISRES -->
    <select class="form-control" id="ativarsisres" name="tipoSisres" style="display:none">
    <option checked>Qual a situação funcional?</option>
      <option onclick="mostrarSisresSC()" value="SisresSC">Servidor Civil</option>
      <option onclick="mostrarSisresOutros()" value="SisresOutros">Militar/Pensionista/Veterano/Anistiado/Ex-Militar</option>
    </select>
    <input class="form-control" placeholder="Nome Completo" name="sisresSC_nome" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresSC1" maxlength="80" minlength="3"> 
  <input class="form-control" placeholder="CPF" name="sisresSC_cpf" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresSC2" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
  <input class="form-control" placeholder="Matricula SIAPE" name="sisresSC_MatSIAPE" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresSC3" maxlength="7">
  <input class="form-control" placeholder="Valor a ser recolhido" name="sisresSC_valorRecolhido" type="text" class="competencia" style="display:none; margin-top: 2%; margin-bottom:-50%;" onKeyPress="return(moeda(this,'.',',',event))" id="sisresSC4">
  <!--<form>
  <INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op1" id="tributavel1" style="display:none" onclick="mostrarValorTributavel()"> <label id="tributavel2" style="display:none; margin-right:10%;">Tributavel</label>
    <INPUT TYPE="RADIO" NAME="OPCAO" VALUE="op2" id="ntribuavel1" style="display:none" onclick="mostrarValorNaoTributavel()"> <label id="ntributavel2" style="display:none">Nao tributavel</label>
    </form>-->
  <!--<input class="form-control" placeholder="OC" name="sisresOutros_OC" type="text" class="cpfcnpj" style="display:none; margin-top: 2%;" id="sisresOutros1">-->
    <input class="form-control" placeholder="Nome Completo" name="sisresOutros_nome" type="text" class="competencia" style="display:none; margin-top: 2%;" id="sisresOutros1" maxlength="80" minlength="3"> 
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
  <td style="vertical-align: middle; width: 100%; text-align: center;">     
           <label style="display:none" id="servidor1" ></label>
           <label style="display:none" id="ativa1" ></label>
           <br>
           <input class="form-control" placeholder="Nome Completo" type="text" class="competencia" style="display:none; width: 950px; height: 38px; margin-left: 25px; padding: 8px 12px;" id="servidor2" name="servidorNome" maxlength="80" minlength="3">
           <input class="form-control" placeholder="Nome Completo" name="ativaNome" type="text" class="competencia" style="display:none; width: 950px; height: 38px; margin-left: 25px; padding: 8px 12px;" id="ativa2" maxlength="80" minlength="3">
       <br>
          
           <label style="display:none" id="servidor3" ></label>
       <label style="display:none" id="ativa3" ></label>
           <label style="display:none; margin:10px; margin-left:-550px;" id="sisresSC5" ></label>
       <label style="display:none; margin:10px; margin-left:-550px;" id="sisresOutros5" ></label>
       <br>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>

 
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" type="text" style="display:none; width: 950px; height: 38px; margin-left: 25px; padding: 8px 12px;" id="servidor4" name="servidor4">





            
</div>



</div>
  </main>



  </div>


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
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="ativa4" type="text" style="display:none; width: 950px; height: 38px; margin-left: 25px; padding: 8px 12px;" id="ativa4">
<script>
$("#ativa4").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>

<!--input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresSC6" type="text" style="display:none; width:950px; margin: -8px 77px 20px -0px;" id="sisresSC6"-->
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresSC6" type="text" style="display:none; width: 100%;
      max-width: 960px;
      margin: 5% auto;
      margin-top: -25px;
      padding: 20px;
      box-sizing: border-box;" id="sisresSC6">
<script>
$("#sisresSC6").datepicker( {
format: "mm-yyyy",
viewMode: "months", 
minViewMode: "months"
});
</script>
<!--input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresOutros5" type="text" style="display:none; width:950px; margin: -8px 77px 20px -0px;" id="sisresOutros6"-->
<input class="form-control" placeholder="Processo de pagamento do recebimento indevido" name="sisresOutros5" type="text" style="display:none; width: 100%;
      max-width: 960px;
      margin: 5% auto;
      margin-top: -25px;
      padding: 20px;
      box-sizing: border-box;" id="sisresOutros6">
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
       <label style="display:none" id="ativa5" ></label>
           <br>
       
           
           <input class="form-control" placeholder="CPF" name="ativaCpfCnpj" type="text" class="competencia" style="display:none; width: 950px; height: 38px; margin-left: 25px; padding: 8px 12px;" id="ativa6" class="cpfcnpj" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="11">
       <br>
            
      
           
       <label style="display:none" id="ativa7">NIP</label>
       <br>
       
           <input class="form-control" placeholder="NIP" name="ativaNip" type="text" class="competencia" style="display:none; width: 950px; height: 38px; margin-left: 25px; padding: 8px 12px;" id="ativa8" maxlength="9" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">  
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
       <label style="display:none" id="ativa9" ></label>
          </td>
          <center><?php
      include "parcela_devolvida_SC.php";
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

        
    <input class="form-control" placeholder="CPF" type="text" class="competencia" style="display:none; width:950px; margin-top:10px; margin-left:25px;" id="servidor6" class="cpfcnpj" name="servidorcpfcnpj" onkeypress="return onlynumber();" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" maxlength="11">
        <input class="form-control" placeholder="Matricula SIAPE" name="servidor_MatSIAPE" type="text" style="display:none; width: 950px; height: 38px; margin-left: 25px; margin-top:15px; padding: 8px 12px;" id="servidor8" maxlength="7">   
    </center>
  </tbody>
  </table>
  <!--img src="bp.png" style="position: relative; height:25; z-index:2; margin-top:-405px; margin-left: 10px;"></img-->
  <center><textarea id="storyPP" name="storyPP" placeholder="Motivo do recolhimento" rows="5" cols="33" style="display:none; margin-top:10px; padding: 8px 12px;" maxlength="200"></textarea></center>
     <center><textarea id="story" name="story" placeholder="Motivo do recolhimento" rows="5" cols="33" style="display:none; margin-top:-5%; padding: 8px 12px;" maxlength="200"></textarea></center>
   <center><button type="submit" id="btnConfirmar" class="btn btn-success" style="margin-top:15px; margin-bottom:15px; display:none;">confirmar</button></center>
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
var btnConfirmar = document.querySelector("#btnConfirmar");
story.style.display = "none";
btnConfirmar.style.display = "none";
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
var btnConfirmar = document.querySelector("#btnConfirmar");
btnConfirmar.style.display = "none";
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
var erro1 = document.querySelector("#recAtivos2");
erro1.style.display = "none";
var erro2 = document.querySelector("#recAtivos3");
erro2.style.display = "none";
var erro3 = document.querySelector("#recAtivos5");
erro3.style.display = "none";
var erro4 = document.querySelector("#ativa2");
erro4.style.display = "none";
var erro5 = document.querySelector("#ativa3");
erro5.style.display = "none";
var erro6 = document.querySelector("#ativa4");
erro6.style.display = "none";
var erro7 = document.querySelector("#ativa5");
erro7.style.display = "none";
var erro8 = document.querySelector("#ativa6");
erro8.style.display = "none";
var erro9 = document.querySelector("#ativa7");
erro9.style.display = "none";
var erro10 = document.querySelector("#ativa8");
erro10.style.display = "none";
var erro11 = document.querySelector("#storyPP");
erro11.style.display = "none";

var x=0;
var x2=0;
var x3=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var y3 = x3+1;
   var dados = "#servidor" + y;
   var dados2 = "#ativa" +y2;
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
   x5++;
   var ativa10 = document.querySelector('#ativa10');
   ativa10.style.display = "none";
   
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
   x2++;
   x3++;   
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
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var dados = "#servidor" + y; 
   var dados2 = "#ativa" + y2;   
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var parcela = document. querySelector("#parceladevol");
   var Oc = document.querySelector("#UPAG");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   var btnconfirmar = document.querySelector("#btnConfirmar");
   
   campos.style.display = "inline";
   campos2.style.display = "none";
   story.style.display = "inline";
   parcela.style.display= "none";
   parcela.style.display = "none";
   Oc.style.display = "inline";
   OmRecolhedora.style.display = "inline";
   tributavel1.style.display = "inline";
   tributavel2.style.display = "inline";
   ntribuavel1.style.display = "inline";
   ntribuavel2.style.display = "inline";
   btnConfirmar.style.display = "inline";
   
   var oc = document.querySelector('#oc');
   oc.style.display = "none";
   
   var parceladevol = document.querySelector('#parceladevol');
   parceladevol.style.display = "none";
   
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
   x2++;
   }    
}

//mostra todos os campos dos Veterano/Anistiado, igual ao acima, porem de uma forma compactada (usando uma estrutura de repetição
/*function mostrarVeteranoAnistiado(){
      
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
   var servidor8 = document.querySelector('#servidor8');
   var pensionista7 = document.querySelector('#pensionista7');
   var ativa7 = document.querySelector('#ativa7');
   
   servidor7.style.display = "none";
   servidor8.style.display = "none";
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
   var servidor8 = document.querySelector('#servidor8');
   
   
   servidor2.style.display = "none";
   servidor4.style.display = "none";
   servidor6.style.display = "none";
   servidor8.style.display = "none";
   
   const radioBtn = document.querySelector("#tributavel1")
   radioBtn.checked = false
   
   const nradioBtn = document.querySelector("#ntribuavel1")
   nradioBtn.checked = false
   
   x++;
   x2++;
   x3++;
   }   
}*/

//responsavel por gerar os campos dos militares da ativa
function mostrarMilitarAtiva(){
var x=0;
var x2=0;
for (var i = 0; i < 20; i++) {
   var y = x+1;
   var y2 = x2+1;
   var dados = "#ativa" + y;
   var dados2 = "#servidor" + y2;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var story = document.querySelector("#storyPP");
   var parcela = document.querySelector("#parceladevol");
   var Oc = document.querySelector("#Oc");
   var OmRecolhedora = document.querySelector("#OmRecolhedora");
   var tributavel1 = document.querySelector("#tributavel1");
   var tributavel2 = document.querySelector("#tributavel2");
   var ntribuavel1 = document.querySelector("#ntribuavel1");
   var ntribuavel2 = document.querySelector("#ntributavel2");
   var btnConfirmar = document.querySelector("#btnConfirmar");
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
   btnConfirmar.style.display = "inline";
   
   var UpagSC = document.querySelector('#UPAG');   
   UpagSC.style.display = "none";

   
   const radioBtn = document.querySelector("#tributavel1")
   radioBtn.checked = false
   
   const nradioBtn = document.querySelector("#ntribuavel1")
   nradioBtn.checked = false
   
   x++;
   x2++;
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
   var btnConfirmar = document.querySelector("#btnConfirmar");
   campos.style.display = "inline";
   campos2.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "none";
   UPAG.style.display = "inline";
   btnConfirmar.style.display = "inline";
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
   var btnConfirmar = document.querySelector("#btnConfirmar");
   campos.style.display = "inline";
   campos2.style.display = "none";
   story.style.display = "inline";
   parcela.style.display = "none";
   Oc.style.display = "inline";
   btnConfirmar.style.display = "inline";
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
   var dados3 = "#sisresOutros" + y3;
   var dados4 = "#sisresSC" + y4;
   var campos = document.querySelector(dados);
   var campos2 = document.querySelector(dados2);
   var campos3 = document.querySelector(dados3);
   var campos4 = document.querySelector(dados4);
   var story = document.querySelector("#story");
   var bruto = document.querySelector("#servidorBruto");
   var btnConfirmar = document.querySelector("#btnConfirmar");
   campos.style.display = "inline";
   story.style.display = "inline";
   bruto.style.display = "inline";
   btnConfirmar.style.display = "inline";
   
   campos2.style.display = "none";
   campos3.style.display = "none";
   campos4.style.display = "none";
   
   var ativarsisres = document.querySelector('#ativarsisres');
   ativarsisres.style.display = "none";
   
   var sisresSC6 = document.querySelector('#sisresSC6');
   sisresSC6.style.display = "none";
   
   var sisresOutros6 = document.querySelector('#sisresOutros6');
   sisresOutros6.style.display = "none";
   
   var ativa10 = document.querySelector('#ativa10');
   ativa10.style.display = "none";

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
   var servidor8 = document.querySelector('#servidor8');
   servidor8.style.display = "none";
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
}); ;

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
}); ;

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
     
     document.getElementById("servidor2").onkeypress = function(e) {
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

    
    <script type="application/json" data-drupal-selector="drupal-settings-json">{"path":{"baseUrl":"\/papem\/","scriptPath":null,"pathPrefix":"pt-br\/","currentPath":"node","currentPathIsAdmin":false,"isFront":true,"currentLanguage":"pt-br"},"pluralDelimiter":"\u0003","suppressDeprecationErrors":true,"eu_cookie_compliance":{"cookie_policy_version":"1.0.0","popup_enabled":true,"popup_agreed_enabled":false,"popup_hide_agreed":false,"popup_clicking_confirmation":false,"popup_scrolling_confirmation":false,"popup_html_info":"\u003Cdiv aria-labelledby=\u0022popup-text\u0022  class=\u0022eu-cookie-compliance-banner eu-cookie-compliance-banner-info eu-cookie-compliance-banner--opt-in\u0022\u003E\n  \u003Cdiv class=\u0022popup-content info eu-cookie-compliance-content\u0022\u003E\n        \u003Cdiv id=\u0022popup-text\u0022 class=\u0022eu-cookie-compliance-message\u0022 role=\u0022document\u0022\u003E\n      \u003Ch2\u003EUsamos cookies neste site para melhorar sua experi\u00eancia de usu\u00e1rio\u003C\/h2\u003E\n\u003Cp\u003EAo clicar no bot\u00e3o Aceitar, voc\u00ea concorda que o fa\u00e7amos.\u003C\/p\u003E\n\n              \u003Cbutton type=\u0022button\u0022 class=\u0022find-more-button eu-cookie-compliance-more-button\u0022\u003EMais informa\u00e7\u00f5es\u003C\/button\u003E\n          \u003C\/div\u003E\n\n    \n    \u003Cdiv id=\u0022popup-buttons\u0022 class=\u0022eu-cookie-compliance-buttons\u0022\u003E\n            \u003Cbutton type=\u0022button\u0022 class=\u0022agree-button eu-cookie-compliance-secondary-button button button--small\u0022\u003EAceitar\u003C\/button\u003E\n              \u003Cbutton type=\u0022button\u0022 class=\u0022decline-button eu-cookie-compliance-default-button button button--small button--primary\u0022\u003EN\u00e3o, obrigado\u003C\/button\u003E\n          \u003C\/div\u003E\n  \u003C\/div\u003E\n\u003C\/div\u003E","use_mobile_message":false,"mobile_popup_html_info":"\u003Cdiv aria-labelledby=\u0022popup-text\u0022  class=\u0022eu-cookie-compliance-banner eu-cookie-compliance-banner-info eu-cookie-compliance-banner--opt-in\u0022\u003E\n  \u003Cdiv class=\u0022popup-content info eu-cookie-compliance-content\u0022\u003E\n        \u003Cdiv id=\u0022popup-text\u0022 class=\u0022eu-cookie-compliance-message\u0022 role=\u0022document\u0022\u003E\n      \n              \u003Cbutton type=\u0022button\u0022 class=\u0022find-more-button eu-cookie-compliance-more-button\u0022\u003EMais informa\u00e7\u00f5es\u003C\/button\u003E\n          \u003C\/div\u003E\n\n    \n    \u003Cdiv id=\u0022popup-buttons\u0022 class=\u0022eu-cookie-compliance-buttons\u0022\u003E\n            \u003Cbutton type=\u0022button\u0022 class=\u0022agree-button eu-cookie-compliance-secondary-button button button--small\u0022\u003EAceitar\u003C\/button\u003E\n              \u003Cbutton type=\u0022button\u0022 class=\u0022decline-button eu-cookie-compliance-default-button button button--small button--primary\u0022\u003EN\u00e3o, obrigado\u003C\/button\u003E\n          \u003C\/div\u003E\n  \u003C\/div\u003E\n\u003C\/div\u003E","mobile_breakpoint":768,"popup_html_agreed":false,"popup_use_bare_css":false,"popup_height":"auto","popup_width":"100%","popup_delay":1000,"popup_link":"https:\/\/www.marinha.mil.br\/politica-de-privacidade","popup_link_new_window":true,"popup_position":false,"fixed_top_position":true,"popup_language":"pt-br","store_consent":true,"better_support_for_screen_readers":false,"cookie_name":"","reload_page":false,"domain":"","domain_all_sites":false,"popup_eu_only":false,"popup_eu_only_js":false,"cookie_lifetime":100,"cookie_session":0,"set_cookie_session_zero_on_disagree":0,"disagree_do_not_show_popup":false,"method":"opt_in","automatic_cookies_removal":true,"allowed_cookies":"","withdraw_markup":"\u003Cbutton type=\u0022button\u0022 class=\u0022eu-cookie-withdraw-tab\u0022\u003EConfigura\u00e7\u00f5es de privacidade\u003C\/button\u003E\n\u003Cdiv aria-labelledby=\u0022popup-text\u0022 class=\u0022eu-cookie-withdraw-banner\u0022\u003E\n  \u003Cdiv class=\u0022popup-content info eu-cookie-compliance-content\u0022\u003E\n    \u003Cdiv id=\u0022popup-text\u0022 class=\u0022eu-cookie-compliance-message\u0022 role=\u0022document\u0022\u003E\n      \u003Ch2\u003EUsamos cookies neste site para melhorar sua experi\u00eancia de usu\u00e1rio\u003C\/h2\u003E\n\u003Cp\u003EVoc\u00ea deu seu consentimento para definirmos cookies.\u003C\/p\u003E\n\n    \u003C\/div\u003E\n    \u003Cdiv id=\u0022popup-buttons\u0022 class=\u0022eu-cookie-compliance-buttons\u0022\u003E\n      \u003Cbutton type=\u0022button\u0022 class=\u0022eu-cookie-withdraw-button  button button--small button--primary\u0022\u003ERetirar consentimento\u003C\/button\u003E\n    \u003C\/div\u003E\n  \u003C\/div\u003E\n\u003C\/div\u003E","withdraw_enabled":true,"reload_options":0,"reload_routes_list":"","withdraw_button_on_info_popup":false,"cookie_categories":[],"cookie_categories_details":[],"enable_save_preferences_button":true,"cookie_value_disagreed":"0","cookie_value_agreed_show_thank_you":"1","cookie_value_agreed":"2","containing_element":"body","settings_tab_enabled":false,"olivero_primary_button_classes":" button button--small button--primary","olivero_secondary_button_classes":" button button--small","close_button_action":"close_banner","open_by_default":true,"modules_allow_popup":true,"hide_the_banner":false,"geoip_match":true},"user":{"uid":0,"permissionsHash":"a10a82d6d58b94ecaa8d251e761a2d8af6514d6fc9d0afa9e3665236bd169517"}}</script>
<script src="https://www.marinha.mil.br/papem/core/assets/vendor/jquery/jquery.min.js?v=3.7.0"></script>
<script src="https://www.marinha.mil.br/papem/core/assets/vendor/once/once.min.js?v=1.0.1"></script>
<script src="https://www.marinha.mil.br/papem/sites/www.marinha.mil.br.papem/files/languages/pt-br_sjbT8DUKvNzb8EDGJt3KWiDE0ZVjx7hQZ4y4EudGKOo.js?srbqx3"></script>
<script src="https://www.marinha.mil.br/papem/core/misc/drupalSettingsLoader.js?v=10.1.6"></script>
<script src="https://www.marinha.mil.br/papem/core/misc/drupal.js?v=10.1.6"></script>
<script src="https://www.marinha.mil.br/papem/core/misc/drupal.init.js?v=10.1.6"></script>
<script src="https://static.addtoany.com/menu/page.js" async></script>
<script src="https://www.marinha.mil.br/papem/sites/all/modules/addtoany/js/addtoany.js?v=10.1.6"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/govbr/assets/js/bootstrap.bundle.min.js?srbqx3"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/themes/govbr/assets/js/scripts.js?srbqx3"></script>
<script src="https://www.marinha.mil.br/papem/core/assets/vendor/js-cookie/js.cookie.min.js?v=3.0.5"></script>
<script src="https://www.marinha.mil.br/papem/sites/all/modules/eu_cookie_compliance/js/eu_cookie_compliance.min.js?v=10.1.6" defer></script>

  </body>
</html>
