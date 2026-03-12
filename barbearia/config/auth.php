<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /barbearia/views/auth/login.php');
    exit;
}