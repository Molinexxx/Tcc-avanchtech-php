<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "barbearia";

$conn = mysqli_connect($host, $user, $password, $db);

if(!$conn){
    die("Erro na conexão com o banco");
}