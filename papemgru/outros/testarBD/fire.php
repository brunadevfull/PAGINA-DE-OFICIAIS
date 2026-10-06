outra forma de fazer

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="materialize/css/materialize.min.css"> 
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body>

        <div class="container">

            <h3>Consulta de Clientes</h3>

            <form action="fire.php" method="get">

                <input type="text" name="nome" placeholder="Digite para pesquisar" required>
                <input type="submit" value="Pesquisar" class="btn">

            </form>

            <?php
            //se existir $_GET["nome"]; (valor na url)

            if (isset($_GET["nome"])) {
                $nome = $_GET["nome"];
                //echo $nome;
                
                $con = new mysqli("localhost", "root", "root", "pagtesouro");
    
                $sql = "select DISTINCT MOM_NOME from om where MOM_NOME like '".$nome."%' order by MOM_NOME";

                $result = mysqli_query($con, $sql);
    
    if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_array($result)){
         echo $row['MOM_NOME']."<br>";
		}
    }else{
        echo "Nenhum funcionário encontrado!";
    }
    
    mysqli_close($con);
    
    }
?>

        </div>

        
        
    </body>
</html>