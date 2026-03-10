<?php
include 'conexao.php';

$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$servico = $_POST['servico'];

$sql = "INSERT INTO clientes (nome, telefone, servico)
VALUES ('$nome','$telefone','$servico')";

mysqli_query($conn, $sql);

header("Location: index.php");
?>