/*
	FontSize for jQuery (version 1.0)
	Copyright (c) 2009 Ramon Victor
	http://www.ramonvictor.com/plugin-font-size-jquery
	
	Licensed under the MIT license:
		http://www.opensource.org/licenses/mit-license.php

	Any and all use of this script must be accompanied by this copyright/license notice in its present form.
*/

(function($){
  $.fn.fontSize = function(options) {
	  var defaults = {
			alvo: 'body',
			tipoPaiLink: 'p',
			setCookie: false,
			variacoes: 7
		};
	var d = $.extend(defaults, options);
	
  	return this.each(function() {
		//Acrescentando os links para aumentar e diminuir tamanho da fonte	
		pailink = d.tipoPaiLink;
		if(pailink == 'ul'){
		   $(this).html('<ul><li><a href="javascript:void(0);" class="menos">A-</a></li><li><a href="javascript:void(0);" class="mais">A+</a></li></ul>');			
		} else {
	       $(this).html('<a href="javascript:void(0);" class="menos">A-</a> <a href="javascript:void(0);" class="mais">A+</a>');
		}

		alvo = d.alvo;
		cook = d.setCookie;
		nvariacoes = d.variacoes;
		
		//Verificando número de variações
		if(nvariacoes % 2 == 0){
			nvariacoes +=1;
			padrao = parseInt(nvariacoes/2 + 1)
		} else {
			padrao = parseInt(nvariacoes/2 + 1);
		}
		
		//Verificando se há cookie
		if($.cookie("fontSize") != null){
			$(alvo).addClass($.cookie("fontSize"));
		} else {
		   $(alvo).addClass("tam"+padrao);				
		}
     	
		//Recuperando o número da classe atual
		$.natual = function() {
			atual = $(alvo).attr("class");		
			t = atual.indexOf("tam");
			num = atual.substring((t+3),(t+4));
			return parseInt(num);
		}
		
		//Gravando valor da classe no cookie
		$.verifyCookie = function(nclass) {
			if(cook) {
				$.cookie('fontSize', nclass.toString());
			}
		}

			
		//Diminuindo número da classe até chegar a "1"
		$('.menos').click(function () {
		    n = $.natual();												
			if(n>1){						
	    	    nAtual = "tam" + n;
		    	n -= 1;			
				nc = "tam" + n;
    			$(alvo).removeAttr("class");
				$(alvo).addClass(atual.replace(nAtual, nc));
				return $.verifyCookie(nc);				
			}
		});
		
		//Aumentando o n�mero da classe até chegar ao número total de variações
		$('.mais').click(function () { 
   		    n = $.natual();												
			if(n < nvariacoes){						
	    	    nAtual = "tam" + n;
		    	n += 1;	
				nc = "tam" + n;		
    			$(alvo).removeAttr("class");
				$(alvo).addClass(atual.replace(nAtual, nc));
				return $.verifyCookie(nc);				
			}
		});	
		
		
    });
  };
})(jQuery);