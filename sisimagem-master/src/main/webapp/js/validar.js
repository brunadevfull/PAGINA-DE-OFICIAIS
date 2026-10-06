/* SCRIPT DE VALIDA��O DE NIP */


function limpar(){
	document.forms["incluirDocumento"].reset();
}

function chamaScanear(){
    document.incluirDocumento.action="../controller/scanearArquivo.jsp";
    //  document.incluirDocumento.action="scanearArquivo.jsp";
	document.incluirDocumento.submit();	
}

function chamaLocalizar(){
	 document.incluirDocumento.action="localizarArquivo.jsp";
	document.incluirDocumento.submit();	
}

function chamaAbrir(){
	document.abrir.submit();
}

function limpar(div, subdivs){
	   alert( document.getElementById(div).nextSibling);

			for(div; div != null; div = div.nextSibling){
	       
			if(document.getElementById(div.id)){

				var objeto = document.getElementById(div.id);
				 alert(document.getElementById(div.id));
				if(objeto == "[object HTMLDivElement]"){
					if(subdivs){
						limpar(objeto.id, subdivs);
					}
				}

				if( (objeto.type == 'text')||
					(objeto.type == 'password')||
					(objeto.type == 'textarea')||
					(objeto.type == 'input')){

						objeto.value = '';

				} else if(objeto.type == 'select-one'){
					objeto.selectedIndex = -1;
				} else if(objeto.type == 'checkbox'){
					objeto.checked = false;
				}
			}
		}
			
}
















function validaNIP(txtnip){
	
var nip = txtnip;
var tamnip = nip.length; 

var vresto = 0;
var vsoma = 0;
var vdv = -1;
var resnip = false;

if (tamnip > "7") {

var n1 = nip.substring(0,1) * 8;
var n2 = nip.substring(1,2) * 7;
var n3 = nip.substring(2,3) * 6;
var n4 = nip.substring(3,4) * 5;
var n5 = nip.substring(4,5) * 4;
var n6 = nip.substring(5,6) * 3;
var n7 = nip.substring(6,7) * 2;
var n8 = nip.substring(7,8);

vsoma = n1+n2+n3+n4+n5+n6+n7;

vresto = vsoma % 11;

if (vresto == "1"){
	vdv = 0;
}else if (vresto == "0"){
	vdv = 1;
}else if (vresto > "1"){
	vdv = 11 - vresto;
}

if (n8 == vdv){
	resnip = true;
} else{ 
	resnip = false;
	alert("NIP inv�lido.");
	}
return resnip;
}
}
/* FIM - SCRIPT DE VALIDA��O DE NIP */


/* VALIDA��O DE DATA (MES-ANO) */
function validaData(campo){ 

	var data = campo;
	
	var tcampo = "  /    "; 
	
	var tamcampo = campo.length;
	
	//alert(tamcampo);
	//if (campo != ""){
	if ((campo != tcampo) && (campo !="")){
    hoje = new Date();  
    anoAtual = hoje.getFullYear();
         
    var mes = data.substring(0,2);
    var ano = data.substring(3,7);
    var anometade = data.substring(3,5);

    resultado = ((mes > 0) && (mes < 13)) && ((ano.length == 4) && (ano <= anoAtual) && (anometade <= 20));
      
     if (!resultado){
    	var data = campo;
    	alert("Data inv�lida.");
        return false;
        } else if (tamcampo < "7"){
         alert("Preencha o campo corretamente.");
         return false;
        } else if ((ano < "1994") && (ano != "    ")){
        alert("N�o existem dados anteriores a 1994.");
        return false;
        }else {
        alert("Preencha o campo corretamente.");	
        return false;	
        }
 	return true;
     }
}
/* FIM - VALIDA��O DE DATA (MES-ANO) */

/* VALIDA��O DE DATA (ANO) */
function validaPeriodo(campo){

	var periodo = campo;
	
	if (campo != ""){  
    hoje = new Date();  
    anoAtual = hoje.getFullYear();
    
    var ano = periodo.substring(0,4);
    var anometade = periodo.substring(0,2);
  
    resultado = (ano <= anoAtual) && (anometade <= 20);

    if (!resultado){
    	alert("Per�odo inv�lido.");
        return false;
        } else if (campo < "1994"){
        alert("N�o existem dados anteriores a 1994.");
        return false;
        } 
     
 	return true;
     }
}
/* FIM - VALIDA��O DE DATA (ANO) */


/* FUN��O PERMITE APENAS NUMEROS */
function isNumberKey(evt){
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
return true;
}
/* FIM - FUN��O PERMITE APENAS NUMEROS */




/* SCRIPT DE VALIDA��O CARACTERESPECIAL (TXT_VINCULO) MUDAR!!!!!!!!!!!!*/

function caracterEspecial(frm,txtcampo){
//var campo = frm.txtcampo.value;
var campo = txtcampo.value;
var msg = "";
if ( campo.search( /\s/g ) != -1 ){
msg+= "N�o � permitido espa�os em branco\n";
campo = campo.replace( /\s/g , "" );
}

if ( campo.search( /[^a-z0-9]/i ) != -1 ){
msg += "N�o � permitido caracteres especiais";
campo = campo.replace( /[^a-z0-9]/gi , "" );
}

//var txtcamponovo = txtcampo;

if (msg) {
//alert(msg);
frm.txtVinculo.value = campo;
return false;
}

return true;
}

/* FIM - SCRIPT DE VALIDA��O CARACTERESPECIAL (TXT_VINCULO)*/



//SCRIPT DE VERIFI��O DE DATAS fichaFinanceiraWebView.php
//function verificaDatas(nnip, dtInicial, dtFinal, vinc, tip){
function verificaDatas(){
    
	var nip = document.getElementById('txtMatriculaFinanceira').value;
	var dtini = document.getElementById('txtPeriodoInicial').value;
	var dtfim = document.getElementById('txtPeriodoFinal').value;
	var periodo = document.getElementById('txtPeriodo').value;
	var vinculo = document.getElementById('txtVinculo').value;
	var tipo = document.getElementById('tipo').value;
	
	var tamnip = nip.length;
	
	alert(nip);
	alert(dtini);
	alert(dtfim);
	alert(periodo);
	alert(vinculo);
	alert(tipo);
	
	/*
	var nip = nnip;   
    var dtini = dtInicial; 
    var dtfim = dtFinal;
    var vinculo = vinc;
    var tipo = tip;
    var tamnip = nnip.length;
    */
	
    
	
	/*
	
	//var tamnip = document.getElementById('txtMatriculaFinanceira').value.length;
    var tamdtini = dtini.length;
    var tamdtfim = dtfim.length;        
    var mesini = dtini.substring(0,2);
    var anoini = dtini.substring(3,7);
    var mesfim = dtfim.substring(0,2);
    var anofim = dtfim.substring(3,7);
    //var resultnip = resnip;
    
    //alert(resultnip);
    
    alert(tipo);
   
    if (vinculo == ""){
    	//alert('Preencha corretamente o formul�rio.');
    	vinculo = " " ;
    }

 
    if ((nip == '') || (dtini == '') || (dtfim == '')){ 
        alert('Preencha corretamente o formul�rio.');
        form1.txtMatriculaFinanceira.focus();
   		return false;
    }
   
    
    */
    
    
    
    
    
    
/*  
	var horario = new Date()
    var hora = horario.getHours()
    var minuto = horario.getMinutes()
    var segundo = horario.getSeconds()
*/

	
	
	
	
	/*
	
	var data = new Date();
	//var dia = data.getDate();
	var arraymes = data.getMonth();
	var ano = data.getFullYear();

	mesatual = new Array(12);
	mesatual[0] = "01";
	mesatual[1] = "02";
	mesatual[2] = "03";
	mesatual[3] = "04";
	mesatual[4] = "05";
	mesatual[5] = "06";
	mesatual[6] = "07";
	mesatual[7] = "08";
	mesatual[8] = "09";
	mesatual[9] = "10";
	mesatual[10] = "11";
	mesatual[11] = "12";

	//alert (mesatual[arraymes]);
	//alert (ano);
	//alert (mesfim);

    if ((nip == '') || (dtini == '') || (dtfim == '')){ 
        alert('Preencha corretamente o formul�rio.');
        form1.txtMatriculaFinanceira.focus();
   		return false;
    }	
    if (tamnip < "8"){  	  	
   		alert('Nip incompleto.');
        form1.txtMatriculaFinanceira.focus();
   		return false;
   	}

    if (tamdtini < "7"){  	  	
   		alert('Per�odo Inicial Inv�lido');
        form1.txtPeriodoInicial.focus();
   		return false;
   	}

    if (mesini > 12 || mesini < 1){
		//document.write(mes);		
   	    alert("M�s inv�lido.");  
   	    form1.txtPeriodoInicial.focus();
   	    document.all.form1.txtPeriodoInicial.select();   	    
   	    return false;
  	}

	if (anoini < 1994){  
   	    alert("N�o existem dados anteriores a 1994.");
     	//alert(anoini);
   	    form1.txtPeriodoInicial.focus();
   	    document.all.form1.txtPeriodoInicial.select();   	      
   	    return false;
  	}

	if (anoini > ano){  
   	    alert("Ano n�o pode ser maior que ano atual.");
     	//alert(anoini);
   	    form1.txtPeriodoInicial.focus();
   	    document.all.form1.txtPeriodoInicial.select();   	      
   	    return false;
  	}

	if ((mesini > mesatual[arraymes]) && (anoini == ano)){  
   	    alert("Per�do Inicial n�o pode ser maior que Per�odo Atual.");
     	//alert(anoini);
   	    form1.txtPeriodoInicial.focus();
   	    document.all.form1.txtPeriodoInicial.select();   	      
   	    return false;
  	}

*/

/* inicio javascrpit final*/  	

	
	
	
	
	
	/*
	
	
	
	
	if (tamdtfim < "7"){  	  	
   		alert('Periodo Final Inv�lido.');
        form1.txtPeriodoFinal.focus();
   		return false;
   	}

	if (mesfim > 12 || mesfim < 1){
		//document.write(mes);		
   	    alert("M�s inv�lido.");  
   	    form1.txtPeriodoFinal.focus();
   	    document.all.form1.txtPeriodoFinal.select();    	    
   	    return false;
  	}

	if (anofim < 1994){  
   	    alert("N�o existem dados anteriores a 1994.");  
   	    form1.txtPeriodoFinal.focus();
   	    document.all.form1.txtPeriodoFinal.select();     	    
   	    return false;
  	} 

	if (anofim > ano){  
   	    alert("Ano n�o pode ser maior que ano atual.");  
   	    form1.txtPeriodoFinal.focus();
   	    document.all.form1.txtPeriodoFinal.select();     	    
   	    return false;
  	} 

	if ((mesfim > mesatual[arraymes]) && (anofim == ano)){  
   	    alert("Per�do Final n�o pode ser maior que Per�odo Atual.");
     	//alert(anoini);
   	    form1.txtPeriodoFinal.focus();
   	    document.all.form1.txtPeriodoFinal.select();   	      
   	    return false;
  	}



*/


/* 
  datFim = new Date(dtfim.substring(6,10),
            dtfim.substring(3,5),
            dtfim.substring(0,2));
   datFim.setMonth(datFim.getMonth() - 1); 
*/
  
	
	
	/*
	
    datInicio = new Date(dtini.substring(3,7),
            			 dtini.substring(0,2));
    datInicio.setMonth(datInicio.getMonth() - 1);

	datFim = new Date(dtfim.substring(3,7),
       				  dtfim.substring(0,2));
	datFim.setMonth(datFim.getMonth() - 1);
    
    if(datInicio <= datFim){
 //       alert('Cadastro Completo!');
        return true;
    } else {
        alert('ATEN��O: Per�odo Inicial deve ser menor que Per�odo Final');
        document.all.form1.txtPeriodoFinal.focus();
        document.all.form1.txtPeriodoFinal.select();
        return false;
    }   
}
//FIM SCRIPT DE VERIFI��O DE DATAS INDEX.PHP

*/}


fv= new Array();
function validateForm(){
    
    for(j=0;j<fv.length;j++){
        if(!fv[j].dataCheck()){
            $(fv[j]['fieldid']).focus();
            $(fv[j]['fieldid']).value="";
            alert('Preencha corretamente o formul�rio.');
            return false;
        }
    }
    return true;
}
Validator = Class.create({

    initialize: function(fieldid, allowBlank, minValue, maxValue, mask, validate, equal, notequal) {
                    this.fieldid = fieldid;
                    this.allowBlank = allowBlank;
                    this.minValue   = minValue;
                    this.maxValue   = maxValue;
                    this.mask       = mask;
                    this.validate   = validate;
                    this.equal      = equal;
                    this.notequal   = notequal;
                    $(fieldid).observe('blur', this.dataCheck.bind(this)); // <== RIGHT
                    if(mask)$(fieldid).observe('keyup', this.dataMask.bind(this)); // <== RIGHT
                },
    dataMask: function(event){
                    var mask = this.mask;
                    var data = $(this.fieldid).getValue().replace(/\D/gi,"");
                    //total de caracteres 
                    var total = data.length;
                    
                    for(i=0;i<total;i++){
                        mask = mask.replace(/#/, data.charAt(i)); 
                    }
                    data = mask.substr(0,total+mask.replace(/\W/gi,"").length).replace(/(#)/gi, '').replace(/\W$/gi,'');
                    $(this.fieldid).value =data;
                },
    dataCheck: function(event) {
                    var value;
                    
                    value = $(this.fieldid).getValue();
                    if (value == '') {
                        if (!this.allowBlank) {
                            // ...report invalid blank...
                            $(this.fieldid).style.border='1px solid red';
                            return false;
                        }
                    } else {
                        value = value.length;
                        if (isNaN(value) || value < this.minValue || value > this.maxValue) {
                            // ...report bad value...
                            $(this.fieldid).style.border='1px solid red';
                            return false;
                        }
                        if(this.equal){
                            if($(this.fieldid).getValue() != $(this.equal).getValue()){
                                $(this.fieldid).style.border='1px solid red';
                                return false;
                            }
                        }
                        if(this.notequal){
                            if($(this.fieldid).getValue() == $(this.notequal).getValue()){
                                $(this.fieldid).style.border='1px solid red';
                                return false;
                            }
                        }
                        if(this.validate){
                            return eval('this.'+this.validate+'();');
                        }
                    }
                    $(this.fieldid).style.border='1px solid green';
                    return true;
                },
    validateData: function(){
        var exp = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}$/;
        
        if(!exp.test($(this.fieldid).getValue())){
           
            if($(this.fieldid).getValue().length>0){
                $(this.fieldid).style.border='1px solid red';
                return false;
            }
        }
        $(this.fieldid).style.border='1px solid green';
        return true;

    },
    validateEmail: function(){
        exp = /^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/;
        if(!exp.test($(this.fieldid).getValue())){
            $(this.fieldid).style.border='1px solid red';
            return false;
        }
        $(this.fieldid).style.border='1px solid green';
        return true;
    },
			
	validateCpf: function (){
    
        var cpf = $(this.fieldid).getValue();
        var firstChar = '/'+cpf.charAt(1)+'/gi';
        unique = cpf.replace(eval(firstChar), '');
        
        if(unique.length==3){
            $(this.fieldid).style.border='1px solid red';
            return false;
        }
        var exp = /\.|\-/g;
        cpf = cpf.toString().replace( exp, "" ); 
        var digitoDigitado = eval(cpf.charAt(9)+cpf.charAt(10));
				
		
		// Aqui come�a a checagem do CPF
		var POSICAO, I, SOMA, DV, DV_INFORMADO;
		var DIGITO = new Array(10);
		DV_INFORMADO = cpf.substr(9, 2); // Retira os dois �ltimos d�gitos do n�mero informado
		
		
		// Desemembra o n�mero do CPF na array DIGITO
		for (I=0; I<=8; I++) {
		  DIGITO[I] = cpf.substr( I, 1);
		}
		
		// Calcula o valor do 10� d�gito da verifica��o
		POSICAO = 10;
		SOMA = 0;
		   for (I=0; I<=8; I++) {
			  SOMA = SOMA + DIGITO[I] * POSICAO;
			  POSICAO = POSICAO - 1;
		   }
		DIGITO[9] = SOMA % 11;
		   if (DIGITO[9] < 2) {
				DIGITO[9] = 0;
		}
		   else{
			   DIGITO[9] = 11 - DIGITO[9];
		}
		
		// Calcula o valor do 11� d�gito da verifica��o
		POSICAO = 11;
		SOMA = 0;
		   for (I=0; I<=9; I++) {
			  SOMA = SOMA + DIGITO[I] * POSICAO;
			  POSICAO = POSICAO - 1;
		   }
		DIGITO[10] = SOMA % 11;
		   if (DIGITO[10] < 2) {
				DIGITO[10] = 0;
		   }
		   else {
				DIGITO[10] = 11 - DIGITO[10];
		   }
		
		// Verifica se os valores dos d�gitos verificadores conferem
		DV = DIGITO[9] * 10 + DIGITO[10];
		
		   if (DV != digitoDigitado) {
			  $(this.fieldid).style.border='1px solid red';
			//  alert('CPF inv�lido');
			  return false;
		   } 
	//	alert ("OK");
		$(this.fieldid).style.border='1px solid green';
		return true;
	}
});
