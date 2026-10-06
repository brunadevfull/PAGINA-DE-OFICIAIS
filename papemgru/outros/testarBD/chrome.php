<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        
        <link rel="stylesheet" href="materialize/css/materialize.min.css">                
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        
        <script>
            $(document).ready(function(){
                
                $("#busca").keyup(function(){
                   
                   var nome = $("#busca").val();
                   
                   $.post("buscar.php",
                         {nome:nome},
                         function(resposta){
                             $("#conteudo").html(resposta);
                         });
                   
                });
                
            });
       </script> 
    </head>
    <body>
        
        <div class="container">
            
            <h3>Consulta de Funcionários</h3>
            
            <form autocomplete="off">
                <input type="text" placeholder="Digite para pesquisar" id="busca" value="">
            </form>
            
            <div id="conteudo"></div>
			            
        </div>        
    </body>
</html>
