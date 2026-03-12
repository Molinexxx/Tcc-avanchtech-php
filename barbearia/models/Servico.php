<?php
require_once __DIR__ . '/../config/database.php';

class Servico {

    private $conn;

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar(){
        $sql = "SELECT * FROM servicos";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}