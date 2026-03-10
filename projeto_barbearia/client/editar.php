<?php
include 'conexao.php';

$id = $_GET['id'];

$sql = "SELECT * FROM clientes WHERE id=$id";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);
?>

<form action="atualizar.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

Nome:
<input type="text" name="nome" value="<?php echo $row['nome']; ?>"><br><br>

Telefone:
<input type="text" name="telefone" value="<?php echo $row['telefone']; ?>"><br><br>

Serviço:
<input type="text" name="servico" value="<?php echo $row['servico']; ?>"><br><br>

<button type="submit">Atualizar</button>

</form>