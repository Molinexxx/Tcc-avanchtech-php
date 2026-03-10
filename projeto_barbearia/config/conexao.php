<?php

$host = "localhost";
$user = "root";
$pass = "";
$banco = "barbearia";

$conn = mysqli_connect($host, $user, $pass, $banco);

if (!$conn) {
    die("Erro na conexão");
}

?>