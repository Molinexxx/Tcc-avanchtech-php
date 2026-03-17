<?php

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarBarbeiros($barbeariaId)
    {
        $sql = "SELECT id, nome, email, role
                FROM usuarios
                WHERE barbearia_id = ?
                ORDER BY nome ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);

        return $stmt->fetchAll();
    }

    public function listarTodos($barbeariaId)
    {
        $sql = "SELECT id, nome, email, role, created_at
                FROM usuarios
                WHERE barbearia_id = ?
                ORDER BY nome ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);

        return $stmt->fetchAll();
    }

    public function buscarPorId($id, $barbeariaId)
    {
        $sql = "SELECT id, nome, email, role
                FROM usuarios
                WHERE id = ? AND barbearia_id = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id, $barbeariaId]);

        return $stmt->fetch();
    }

    public function cadastrar($barbeariaId, $nome, $email, $senha, $role)
    {
        $sql = "INSERT INTO usuarios (nome, email, senha, role, barbearia_id)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$nome, $email, $senha, $role, $barbeariaId]);
    }

    public function atualizar($id, $barbeariaId, $nome, $email, $role, $senha = null)
    {
        if ($senha !== null && $senha !== '') {
            $sql = "UPDATE usuarios
                    SET nome = ?, email = ?, role = ?, senha = ?
                    WHERE id = ? AND barbearia_id = ?";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([$nome, $email, $role, $senha, $id, $barbeariaId]);
        }

        $sql = "UPDATE usuarios
                SET nome = ?, email = ?, role = ?
                WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$nome, $email, $role, $id, $barbeariaId]);
    }

    public function excluir($id, $barbeariaId)
    {
        $sql = "DELETE FROM usuarios
                WHERE id = ? AND barbearia_id = ? AND role <> 'admin'";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id, $barbeariaId]);
    }

    public function emailExiste($email, $barbeariaId, $ignorarId = null): bool
    {
        $sql = "SELECT id
                FROM usuarios
                WHERE email = ? AND barbearia_id = ?";
        $params = [$email, $barbeariaId];

        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = $ignorarId;
        }

        $sql .= " LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetch();
    }

    public function countBarbeiros($barbeariaId): int
    {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$barbeariaId]);
        $result = $stmt->fetch();

        return (int) ($result['total'] ?? 0);
    }
}
