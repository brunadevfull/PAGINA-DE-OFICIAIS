<?php
$host = 'localhost';
	$pass = 'root';
	$user = 'root';
	$bd = 'test';
	
	$con = new PDO("mysql: host=localhost; dbname=pagtesouro", "root", "root");
	
	if(isset($_POST["nome"])){
		$busca = $_POST["nome"];
		$query = "select DISTINCT CFIN_NOM_PARC from parcelar where CFIN_NOM_PARC like '".$busca."%' order by CFIN_NOM_PARC";
	}else{
		$query = "select * from parcelar where CFIN_NOM_PARC ='zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz' ";
	}

$statement = $con->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$rowCount = $statement->rowCount();

if($rowCount > 0){
		
	foreach($result as $row){
		echo $row['CFIN_NOM_PARC']."<br>";
    }
}
