/*function EscreveData() {
 var mydate=new Date()
 var year=mydate.getYear()
 if (year < 1000)
 year+=1900
 var day=mydate.getDay()
 var month=mydate.getMonth()
 var daym=mydate.getDate()
 if (daym<10)
 daym="0"+daym
 var dayarray=new
 Array("Domingo","Segunda-feira","Ter&ccedil;a-feira","Quarta-feira","Quinta-feira","Sexta-feira","S&aacute;bado")
 var montharray=new
 Array("Janeiro","Fevereiro","Mar&ccedil;o","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro")
 document.write(""+dayarray[day]+", "+daym+" de "+montharray[month]+" de "+year+"</b></font></small>")
 }
 
function EscreveHora() {
 agora = new Date (); horas = agora.getHours ();
 minutos = agora.getMinutes ();
 if (horas < 10) horas = "0" + horas;
 if (minutos < 10) minutos = "0" + minutos;
 document.write(horas+":" +minutos+ "h");
 }*/
 
     function mueveReloj(){
        momentoActual = new Date()
        hora = momentoActual.getHours()
        minuto = momentoActual.getMinutes()
        segundo = momentoActual.getSeconds()
		day = momentoActual.getDate()
        dia = momentoActual.getDay()
        mes = momentoActual.getMonth()
		ano = momentoActual.getFullYear()
		str_mes = new String (mes)
			switch (mes) {
			case 0:
				mes = "Janeiro"
				break
			case 1:
				mes = "Fevereiro"
				break
			case 2:
				mes = "Mar&ccedil;o"
				break
			case 3:
				mes = "Abril"
				break
			case 4:
				mes = "Maio"
				break
			case 5:
				mes = "Junho"
				break
			case 6:
				mes = "Julho"
				break
			case 7:
				mes = "Agosto"
				break
			case 8:
				mes = "Setembro"
				break
			case 9:
				mes = "Outubro"
				break
			case 10:
				mes = "Novembro"
				break
			case 11:
				mes = "Dezembro"
				break
	
		}
		str_dia = new String (dia)
        if (str_dia.length == 1) 
            dia = "0" + dia
		
			switch (dia) {
			case "00":
				dia = "Domingo"
				break
			case "01":
				dia = "Segunda-Feira"
				break
			case "02":
				dia = "Ter&ccedil;a-Feira"
				break
			case "03":
				dia = "Quarta-Feira"
				break
			case "04":
				dia = "Quinta-Feira"
				break
			case "05":
				dia = "Sexta-Feira"
				break
			case "06":
				dia = "Sábado"
				break
		}	
        str_segundo = new String (segundo)
        if (str_segundo.length == 1) 
            segundo = "0" + segundo
            
        str_minuto = new String (minuto)
        if (str_minuto.length == 1) 
            minuto = "0" + minuto
    
        str_hora = new String (hora)
        if (str_hora.length == 1) 
            hora = "0" + hora
            
        horaImprimible = dia + " : " + day +" de " + mes + " de " + ano +"  "+ hora + ":" + minuto + ":" + segundo  
        
		document.getElementById("relogio").innerHTML = dia + " : " + day +" de " + mes + " de " + ano +"  "+ hora + ":" + minuto + ":" + segundo
        //document.form_reloj.reloj.value = horaImprimible
        
        setTimeout("mueveReloj()",1000)
    }