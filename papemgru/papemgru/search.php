<html>
<!-- codigo responsavel por se conectar com o banco de dados, e executa o sql pra fazer a pesquisa
<option value="<?php echo $row['MOM_NOME']; ?>"><?php echo $row['MOM_NOME']; ?></option>
 -->
<?php
	
	$con = new PDO("mysql: host=localhost; dbname=pagtesouro", "root", "root");
	
	if(isset($_POST["nome"])){
		$busca = $_POST["nome"];
		$query = "select DISTINCT MOM_NOME from om where MOM_NOME like '".$busca."%' order by MOM_NOME";
	}else{
		$query = "select * from om where MOM_NOME ='zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz' ";
	}
	
	
$statement = $con->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$rowCount = $statement->rowCount();

if($rowCount > 0){
		
	foreach($result as $row){
		?><table><td><?php echo $row['MOM_NOME']; ?></td><br></table><?php
    }
}
?>
</html>