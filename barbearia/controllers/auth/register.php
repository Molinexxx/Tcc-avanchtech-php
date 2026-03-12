<?php

require_once("../../config/database.php");

$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = $_POST['senha'];
$confirmarSenha = $_POST['confirmar_senha'];

if ($senha !== $confirmarSenha) {
    header("Location: ../../views/auth/register.php?erro=senhas");
    exit;
}

$sql = "SELECT id FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: ../../views/auth/register.php?erro=email");
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $email, $senhaHash);
$stmt->execute();

header("Location: ../../views/auth/login.php?sucesso=1");
exit;