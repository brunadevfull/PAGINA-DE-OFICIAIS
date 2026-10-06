//Função chama o arquivo XML
	
    function loadXMLDoc(filename)
    {
		if (window.XMLHttpRequest)
		  {
			xhttp=new XMLHttpRequest();
		  }
		else // PARA FUNCIONAR TAMBÉM NO IE 5 E IE 6
		  {
			xhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xhttp.open("POST",filename,false);
		xhttp.send();
		return xhttp.responseXML;
    }
	//Fim da Função
	
    $(document).ready(function(){
		changer();
    });
	
	//A função changer faz a mesma coisa que a função do document.ready. 
	//porém, ela é chamada toda a vez que a imagem do oficial é clicada, com o objetivo de atualizar a imagem.
	function changer(){
	   xmlDoc=loadXMLDoc("catalogo.xml");
		//Chama a função pra abrir o xml
			for (var i=0;i<=52;i++){  //52
				x=xmlDoc.getElementsByTagName("type")[i];
				y=x.childNodes[0];
				document.getElementById(String(i)).src= y.nodeValue;
			}
		/*O laço FOR serve para:
		1-Pegar os dados do campo TYPE no XML
		2-'Setar' o src da tag IMG do id correspondente. 
		!!!!ATENÇÃO!!!! A CONTAGEM DE IDS COMEÇA COM 0 E TERMINA NA (QUANTIDADE DE OFICIAIS)+1. Na inserção ou ao deletar um oficial, o valor i deverá ser atualizado. 
		
		 */		 
    }
	
	//A função chama o arquivo changedom.php para escrever no XML a situação do oficial "i",
	// jogada no header v1 que a página vai receber.
	function myAjax(i) {
      $.ajax({
           type: "GET",
           url: 'changedom.php',
		   data: 'v1='+ i,
		   success:function() {
		   //Quando a função termina, chama a função changer() para atualizar as imagens dinamicamente. 
		   changer();  	
           }
	
      });
 	}