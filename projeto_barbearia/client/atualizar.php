<?php
include 'conexao.php';

$id = $_POST['id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$servico = $_POST['servico'];

$sql = "UPDATE clientes 
SET nome='$nome', telefone='$telefone', servico='$servico'
WHERE id=$id";

mysqli_query($conn,$sql);

header("Location: index.php");

?>

