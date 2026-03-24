<?php

declare(strict_types=1);

final class AppointmentRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function all(): array
    {
        $statement = $this->pdo->query(
            'SELECT * FROM agendamentos ORDER BY datetime(data_horario) ASC, id DESC'
        );

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM agendamentos WHERE id = :id');
        $statement->execute(['id' => $id]);
        $appointment = $statement->fetch();

        return $appointment ?: null;
    }

    public function create(array $data): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO agendamentos (
                cliente, telefone, servico, barbeiro, data_horario, valor, status, observacoes
            ) VALUES (
                :cliente, :telefone, :servico, :barbeiro, :data_horario, :valor, :status, :observacoes
            )'
        );

        $statement->execute($data);
    }

    public function update(int $id, array $data): void
    {
        $data['id'] = $id;

        $statement = $this->pdo->prepare(
            'UPDATE agendamentos SET
                cliente = :cliente,
                telefone = :telefone,
                servico = :servico,
                barbeiro = :barbeiro,
                data_horario = :data_horario,
                valor = :valor,
                status = :status,
                observacoes = :observacoes
            WHERE id = :id'
        );

        $statement->execute($data);
    }

    public function delete(int $id): void
    {
        $statement = $this->pdo->prepare('DELETE FROM agendamentos WHERE id = :id');
        $statement->execute(['id' => $id]);
    }

    public function summary(): array
    {
        $statement = $this->pdo->query(
            'SELECT
                COUNT(*) AS total_agendamentos,
                COALESCE(SUM(valor), 0) AS faturamento_previsto,
                SUM(CASE WHEN status = "Confirmado" THEN 1 ELSE 0 END) AS confirmados,
                SUM(CASE WHEN status = "Concluido" THEN 1 ELSE 0 END) AS concluidos
            FROM agendamentos'
        );

        return $statement->fetch() ?: [];
    }
}
