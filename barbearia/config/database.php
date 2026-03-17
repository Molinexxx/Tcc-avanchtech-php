<?php

class Database
{
    private string $host = "localhost";
    private string $dbName = "barbearia";
    private string $username = "root";
    private string $password = "";

    public function getConnection(): ?PDO
    {
        $conn = null;

        try {
            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            echo "Erro de conexao: " . $e->getMessage();
        }

        return $conn;
    }
}
