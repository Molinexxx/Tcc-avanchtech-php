<?php
include 'conexao.php';

$sql = "SELECT * FROM clientes";
$result = mysqli_query($conn, $sql);
?>

<h2>Clientes</h2>

<a href="cadastrar.php">Novo Cliente</a>

<table border="1">

<tr>
<th>Nome</th>
<th>Telefone</th>
<th>Serviço</th>
<th>Ações</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){
?>

<tr>
<td><?php echo $row['nome']; ?></td>
<td><?php echo $row['telefone']; ?></td>
<td><?php echo $row['servico']; ?></td>

<td>

<a href="editar.php?id=<?php echo $row['id']; ?>">Editar</a>

<a href="excluir.php?id=<?php echo $row['id']; ?>">Excluir</a>

</td>

</tr>

<?php
}
?>

</table>