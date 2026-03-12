<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {

    private $conn;

    public function __construct(){
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar($barbearia_id){
        $sql = "SELECT * FROM clientes WHERE barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbearia_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrar($nome, $telefone, $servico, $barbearia_id){
        $sql = "INSERT INTO clientes (nome, telefone, servico, barbearia_id)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nome, $telefone, $servico, $barbearia_id]);
    }

    public function excluir($id){
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function atualizar($id, $nome, $telefone, $servico){
        $sql = "UPDATE clientes
                SET nome = ?, telefone = ?, servico = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nome, $telefone, $servico, $id]);
    }
    public function buscarPorId($id){
    $sql = "SELECT * FROM clientes WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
}