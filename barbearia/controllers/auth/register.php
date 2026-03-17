<?php

require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/auth/register.php');
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$nomeBarbearia = trim($_POST['nome_barbearia'] ?? '');
$senha = $_POST['senha'] ?? '';
$confirmarSenha = $_POST['confirmar_senha'] ?? '';

if ($nome === '' || $email === '' || $nomeBarbearia === '' || $senha === '' || $confirmarSenha === '') {
    header("Location: ../../views/auth/register.php?erro=campos");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../../views/auth/register.php?erro=email_invalido");
    exit;
}

if ($senha !== $confirmarSenha) {
    header("Location: ../../views/auth/register.php?erro=senhas");
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->fetch()) {
        header("Location: ../../views/auth/register.php?erro=email");
        exit;
    }

    $conn->beginTransaction();

    $stmt = $conn->prepare("INSERT INTO barbearias (nome, email) VALUES (:nome, :email)");
    $stmt->bindValue(':nome', $nomeBarbearia, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $barbeariaId = (int) $conn->lastInsertId();
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO usuarios (nome, email, senha, role, barbearia_id)
         VALUES (:nome, :email, :senha, :role, :barbearia_id)"
    );
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
    $stmt->bindValue(':role', 'admin', PDO::PARAM_STR);
    $stmt->bindValue(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $conn->prepare(
        "INSERT INTO servicos (barbearia_id, nome, preco, duracao_min)
         VALUES
         (:barbearia_id, 'Corte tradicional', 35.00, 40),
         (:barbearia_id, 'Barba', 25.00, 30),
         (:barbearia_id, 'Corte + barba', 55.00, 60)"
    );
    $stmt->bindValue(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
    $stmt->execute();

    $stmt = $conn->prepare(
        "INSERT INTO horarios_funcionamento (barbearia_id, dia_semana, aberto, hora_inicio, hora_fim)
         VALUES
         (:barbearia_id, 0, 0, NULL, NULL),
         (:barbearia_id, 1, 1, '09:00:00', '19:00:00'),
         (:barbearia_id, 2, 1, '09:00:00', '19:00:00'),
         (:barbearia_id, 3, 1, '09:00:00', '19:00:00'),
         (:barbearia_id, 4, 1, '09:00:00', '19:00:00'),
         (:barbearia_id, 5, 1, '09:00:00', '19:00:00'),
         (:barbearia_id, 6, 1, '09:00:00', '17:00:00')"
    );
    $stmt->bindValue(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
    $stmt->execute();

    $conn->commit();

    header("Location: ../../views/auth/login.php?sucesso=1");
    exit;
} catch (Throwable $e) {
    if (isset($conn) && $conn instanceof PDO && $conn->inTransaction()) {
        $conn->rollBack();
    }

    echo "Erro no cadastro: " . $e->getMessage();
    exit;
}
