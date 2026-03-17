<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /barbearia/views/auth/login.php');
    exit;
}

function currentUserRole(): string
{
    return $_SESSION['role'] ?? '';
}

function requireRoles(array $roles): void
{
    if (!in_array(currentUserRole(), $roles, true)) {
        header('Location: /barbearia/dashboard.php?erro=acesso');
        exit;
    }
}

function requireAdmin(): void
{
    requireRoles(['admin']);
}
