/* MASCARAS */
			//$.noConflict( )
			jQuery(function($){
			   //$.mask.addPlaceholder("~","[+-]");
			   $("#cpf").mask("999.999.999-99",{placeholder:" "});
			   $("#dataDocumento").mask("49/19/2039" + " " + "69:59:59",{placeholder:" "});
			   $("#dataInclusao").mask("49/19/2039" + " " + "69:59:59",{placeholder:" "});
			   //$("#dataInclusao").mask("49/19/2039",{placeholder:" "});
			   $("#dataProtocolo").mask("49/19/2039",{placeholder:" "});
			  // $("#data_periodo").mask("2039",{placeholder:" "});
			});
/* FIM MASCARAS */		

/*  VALIDAÇÃO FORMULÁRIO  - incluirDocumento */			
$(document).ready( function() {
		$("#incluirDocumento").validate({
		// Define as regras
		 rules:{
		  dataDocumento:{
			required: true
		  },
		  dataInclusao:{
			required: true
		  },
		  nipMatricula:{
			digits : true,
			maxlength: 9,
			minlength: 8
		  },
		  nomeArquivoOriginal:{
			required: true
		  },
		  recordNumber:{
				required: true
	      }
		 },
		// Define as mensagens de erro para cada regra  
		 messages:{
		   dataDocumento:{
		   required: "Digite a data do documento"
		   },
		   dataInclusao:{
				required: "Digite a data de inclusão"
			},
			nomeArquivoOriginal:{
				required: "Selecione um Documento"
			},
			recordNumber:{
				required: "Digite um número de Processo"
			}
		 }
		});
});
/* FIM VALIDAÇÃO FORMULARIO - incluirDocumento */			

$(document).ready( function() {
	$("#incluirDocumentoRespostaPapem42").validate({
	// Define as regras
	 rules:{
		titulo:{
			required: true
	  },
	  nomeArquivoOriginal:{
		  required: true
	  }
	 },
	// Define as mensagens de erro para cada regra
	 messages:{
	   titulo:{
		 	required: "Digite o Título do documento"
	   },
	   nomeArquivoOriginal:{
			required: "Selecione um Documento"
	   }
	 }
	});
});

/*  VALIDAÇÃO FORMULÁRIO  */			
$(document).ready( function() {
		$("#incluirAnexoPapem41").validate({
		// Define as regras
		 rules:{
		  nomeArquivoOriginal:{
			required: true
		  }
		 },
		// Define as mensagens de erro para cada regra  
		 messages:{
		   nomeArquivoOriginal:{
				required: "Selecione um Documento"
			}
		 }
		});
});
/* FIM VALIDAÇÃO FORMULARIO */		


/*
$(document).ready(function(){

	$("#btnPesquisarrrr").click(function(e){

		// bloqueando envio do form
		e.preventDefault();
			
		var erros = 0;
			
		// verifica se ha campos vazios
		$("#pesquisarDocumento input").each(function(){
				
			// conta erros
			$(this).val() == "" ? erros++ : "";
				
		});
			
		// verifica se ha erros
		if(erros > 0 ){
					 
			alert("Existe(em) campo(os) vazio(os) neste fomulário");
					
	    }else{
			//return true;	
			$("#pesquisarDocumento").submit()
		}		
				
		});

	});
*/