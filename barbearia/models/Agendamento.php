<?php

require_once __DIR__ . '/../config/database.php';

class Agendamento
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function countHoje($barbeariaId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM agendamentos
                WHERE barbearia_id = :barbearia_id
                  AND DATE(data_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    }

    public function countHojePorUsuario($barbeariaId, $usuarioId)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM agendamentos
                WHERE barbearia_id = :barbearia_id
                  AND usuario_id = :usuario_id
                  AND DATE(data_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    }

    public function countPorStatusHoje($barbeariaId, $status)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM agendamentos
                WHERE barbearia_id = :barbearia_id
                  AND status = :status
                  AND DATE(data_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    }

    public function countPorStatusHojePorUsuario($barbeariaId, $usuarioId, $status)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM agendamentos
                WHERE barbearia_id = :barbearia_id
                  AND usuario_id = :usuario_id
                  AND status = :status
                  AND DATE(data_hora) = CURDATE()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    }

    public function listarProximos($barbeariaId)
    {
        $sql = "SELECT
                    a.id,
                    a.data_hora,
                    a.status,
                    c.nome AS cliente,
                    c.telefone,
                    s.nome AS servico,
                    s.preco,
                    u.nome AS barbeiro
                FROM agendamentos a
                INNER JOIN clientes c ON a.cliente_id = c.id
                INNER JOIN servicos s ON a.servico_id = s.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.barbearia_id = :barbearia_id
                  AND a.data_hora >= NOW()
                ORDER BY a.data_hora ASC
                LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarProximosPorUsuario($barbeariaId, $usuarioId)
    {
        $sql = "SELECT
                    a.id,
                    a.data_hora,
                    a.status,
                    c.nome AS cliente,
                    c.telefone,
                    s.nome AS servico,
                    s.preco,
                    u.nome AS barbeiro
                FROM agendamentos a
                INNER JOIN clientes c ON a.cliente_id = c.id
                INNER JOIN servicos s ON a.servico_id = s.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.barbearia_id = :barbearia_id
                  AND a.usuario_id = :usuario_id
                  AND a.data_hora >= NOW()
                ORDER BY a.data_hora ASC
                LIMIT 10";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarTodos($barbeariaId)
    {
        $sql = "SELECT
                    a.id,
                    a.cliente_id,
                    a.servico_id,
                    a.usuario_id,
                    a.data_hora,
                    a.status,
                    a.observacoes,
                    c.nome AS cliente,
                    s.nome AS servico,
                    u.nome AS barbeiro
                FROM agendamentos a
                INNER JOIN clientes c ON a.cliente_id = c.id
                INNER JOIN servicos s ON a.servico_id = s.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.barbearia_id = :barbearia_id
                ORDER BY a.data_hora DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarPorUsuario($barbeariaId, $usuarioId)
    {
        $sql = "SELECT
                    a.id,
                    a.cliente_id,
                    a.servico_id,
                    a.usuario_id,
                    a.data_hora,
                    a.status,
                    a.observacoes,
                    c.nome AS cliente,
                    s.nome AS servico,
                    u.nome AS barbeiro
                FROM agendamentos a
                INNER JOIN clientes c ON a.cliente_id = c.id
                INNER JOIN servicos s ON a.servico_id = s.id
                INNER JOIN usuarios u ON a.usuario_id = u.id
                WHERE a.barbearia_id = :barbearia_id
                  AND a.usuario_id = :usuario_id
                ORDER BY a.data_hora DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function buscarPorId($id, $barbeariaId)
    {
        $sql = "SELECT *
                FROM agendamentos
                WHERE id = ? AND barbearia_id = ?
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id, $barbeariaId]);

        return $stmt->fetch();
    }

    public function cadastrar($barbeariaId, $clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes)
    {
        $sql = "INSERT INTO agendamentos (
                    barbearia_id,
                    cliente_id,
                    servico_id,
                    usuario_id,
                    data_hora,
                    status,
                    observacoes
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$barbeariaId, $clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes]);
    }

    public function existeConflito($barbeariaId, $usuarioId, $dataHora, $duracaoMin, $ignorarId = null): bool
    {
        $sql = "SELECT a.id
                FROM agendamentos a
                INNER JOIN servicos s ON s.id = a.servico_id
                WHERE a.barbearia_id = :barbearia_id
                  AND a.usuario_id = :usuario_id
                  AND a.status <> 'cancelado'
                  AND a.data_hora < :novo_fim
                  AND DATE_ADD(a.data_hora, INTERVAL s.duracao_min MINUTE) > :novo_inicio";

        if ($ignorarId !== null) {
            $sql .= " AND a.id <> :ignorar_id";
        }

        $sql .= " LIMIT 1";

        $novoFim = date('Y-m-d H:i:s', strtotime($dataHora . " +{$duracaoMin} minutes"));
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':barbearia_id', $barbeariaId, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(':novo_inicio', $dataHora, PDO::PARAM_STR);
        $stmt->bindParam(':novo_fim', $novoFim, PDO::PARAM_STR);
        if ($ignorarId !== null) {
            $stmt->bindParam(':ignorar_id', $ignorarId, PDO::PARAM_INT);
        }
        $stmt->execute();

        return (bool) $stmt->fetch();
    }

    public function atualizar($id, $barbeariaId, $clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes)
    {
        $sql = "UPDATE agendamentos
                SET cliente_id = ?, servico_id = ?, usuario_id = ?, data_hora = ?, status = ?, observacoes = ?
                WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$clienteId, $servicoId, $usuarioId, $dataHora, $status, $observacoes, $id, $barbeariaId]);
    }

    public function excluir($id, $barbeariaId)
    {
        $sql = "DELETE FROM agendamentos WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id, $barbeariaId]);
    }

    public function atualizarStatus($id, $barbeariaId, $status)
    {
        $sql = "UPDATE agendamentos
                SET status = ?
                WHERE id = ? AND barbearia_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$status, $id, $barbeariaId]);
    }

    public function atualizarStatusPorUsuario($id, $barbeariaId, $usuarioId, $status)
    {
        $sql = "UPDATE agendamentos
                SET status = ?
                WHERE id = ? AND barbearia_id = ? AND usuario_id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$status, $id, $barbeariaId, $usuarioId]);
    }
}
