<?php

class Database
{
    private $host = "localhost";
    private $db_name = "barbearia";
    private $username = "root";
    private $password = "";

    public function getConnection()
    {
        $conn = null;

        try {
            $conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }

        return $conn;
    }
}