<html>
<!-- codigo pra se conectar ao banco de dados, e fazer as pesquisas sql (forma alternativa); mudar pra pdo focando em oracle-->
	<?php
    $valordigitado = $_POST["nome"];
    
    if($valordigitado != ""){
    
    $con = new mysqli("localhost", "root", "root", "pagtesouro");
    
    $sql = "select DISTINCT MOM_NOME from om where MOM_NOME like '".$valordigitado."%' order by MOM_NOME";

    $result = mysqli_query($con, $sql);
    
    if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
			$node=$row['MOM_NOME'];
         echo "<a onclick='passarval()' id='click' value='".$row['MOM_NOME']."'>".$row['MOM_NOME']."</a><br>";
		}
    }else{
        echo "Nenhum funcionário encontrado!";
    }
    
    mysqli_close($con);
    
    }
?>
<script>
function passarval(){
var pp = document.querySelector('#busca');
var pa = document.querySelector('#click').value;
pp.value = pa; 
}
</script>
</html>