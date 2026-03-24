<?php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $databaseDirectory = __DIR__ . '/../database';
    $databaseFile = $databaseDirectory . '/barbearia.sqlite';

    if (!is_dir($databaseDirectory)) {
        mkdir($databaseDirectory, 0777, true);
    }

    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    initializeDatabase($pdo);

    return $pdo;
}

function initializeDatabase(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS agendamentos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            cliente TEXT NOT NULL,
            telefone TEXT NOT NULL,
            servico TEXT NOT NULL,
            barbeiro TEXT NOT NULL,
            data_horario TEXT NOT NULL,
            valor REAL NOT NULL,
            status TEXT NOT NULL DEFAULT "Agendado",
            observacoes TEXT DEFAULT "",
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )'
    );

    $total = (int) $pdo->query('SELECT COUNT(*) FROM agendamentos')->fetchColumn();

    if ($total > 0) {
        return;
    }

    $seed = $pdo->prepare(
        'INSERT INTO agendamentos (
            cliente, telefone, servico, barbeiro, data_horario, valor, status, observacoes
        ) VALUES (
            :cliente, :telefone, :servico, :barbeiro, :data_horario, :valor, :status, :observacoes
        )'
    );

    $initialAppointments = [
        [
            'cliente' => 'Joao Silva',
            'telefone' => '(11) 99999-1111',
            'servico' => 'Corte social',
            'barbeiro' => 'Carlos',
            'data_horario' => date('Y-m-d H:i', strtotime('+1 day 09:00')),
            'valor' => 35.00,
            'status' => 'Agendado',
            'observacoes' => 'Cliente prefere acabamento na navalha.',
        ],
        [
            'cliente' => 'Pedro Santos',
            'telefone' => '(11) 98888-2222',
            'servico' => 'Barba completa',
            'barbeiro' => 'Marcos',
            'data_horario' => date('Y-m-d H:i', strtotime('+1 day 11:00')),
            'valor' => 30.00,
            'status' => 'Confirmado',
            'observacoes' => 'Usar toalha quente.',
        ],
    ];

    foreach ($initialAppointments as $appointment) {
        $seed->execute($appointment);
    }
}

function redirect(string $location): void
{
    header('Location: ' . $location);
    exit;
}

function flash(string $message): void
{
    $_SESSION['flash'] = $message;
}

function getFlash(): ?string
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $message = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $message;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
