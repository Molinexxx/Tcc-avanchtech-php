<?php

session_start();

require_once("../../config/database.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if(password_verify($senha,$usuario['senha'])){

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['barbearia_id'] = $usuario['barbearia_id'];

        header("Location: ../../views/client/dashboard.php");
        exit;

    }
}

header("Location: ../../views/auth/login.php?erro=1");
exit;