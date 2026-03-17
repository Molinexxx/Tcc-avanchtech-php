<?php

require_once __DIR__ . '/../config/database.php';

class Cliente
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listar($barbeariaId)
    {
        $sql = "SELECT * FROM clientes WHERE barbearia_id = ? ORDER BY nome ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);

        return $stmt->fetchAll();
    }

    public function cadastrar($nome, $telefone, $servico, $barbeariaId)
    {
        $sql = "INSERT INTO clientes (nome, telefone, servico, barbearia_id)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$nome, $telefone, $servico, $barbeariaId]);
    }

    public function excluir($id, $barbeariaId)
    {
        $sql = "DELETE FROM clientes WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id, $barbeariaId]);
    }

    public function atualizar($id, $nome, $telefone, $servico, $barbeariaId)
    {
        $sql = "UPDATE clientes
                SET nome = ?, telefone = ?, servico = ?
                WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$nome, $telefone, $servico, $id, $barbeariaId]);
    }

    public function buscarPorId($id, $barbeariaId)
    {
        $sql = "SELECT * FROM clientes WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id, $barbeariaId]);

        return $stmt->fetch();
    }

    public function countPorBarbearia($barbeariaId): int
    {
        $sql = "SELECT COUNT(*) AS total FROM clientes WHERE barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);
        $result = $stmt->fetch();

        return (int) ($result['total'] ?? 0);
    }
}
