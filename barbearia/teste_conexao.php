<?php
require_once __DIR__ . '/config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();

    if ($conn) {
        echo "Conexão OK";
    } else {
        echo "Falha na conexão";
    }
} catch (Throwable $e) {
    echo "Erro: " . $e->getMessage();
}