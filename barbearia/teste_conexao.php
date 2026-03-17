<?php

require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        echo "Conexao OK";
    } else {
        echo "Falha na conexao";
    }
} catch (Throwable $e) {
    echo "Erro: " . $e->getMessage();
}
