<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/src/bootstrap.php';
require __DIR__ . '/src/AppointmentRepository.php';

$repository = new AppointmentRepository(db());

$statusOptions = ['Agendado', 'Confirmado', 'Concluido', 'Cancelado'];
$serviceOptions = ['Corte social', 'Degrade', 'Barba completa', 'Corte + barba', 'Sobrancelha'];
$barberOptions = ['Carlos', 'Marcos', 'Rafael', 'Fernanda'];

$errors = [];
$editingAppointment = null;
$action = $_GET['action'] ?? 'list';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

if ($action === 'edit' && $id) {
    $editingAppointment = $repository->find($id);

    if (!$editingAppointment) {
        flash('Agendamento nao encontrado.');
        redirect('index.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['form_action'] ?? '';

    if ($postAction === 'delete') {
        $deleteId = (int) ($_POST['id'] ?? 0);

        if ($deleteId > 0) {
            $repository->delete($deleteId);
            flash('Agendamento removido com sucesso.');
        }

        redirect('index.php');
    }

    $formData = normalizeAppointmentData($_POST);
    $errors = validateAppointmentData($formData);

    if (empty($errors)) {
        if ($postAction === 'create') {
            $repository->create($formData);
            flash('Agendamento cadastrado com sucesso.');
            redirect('index.php');
        }

        if ($postAction === 'update') {
            $updateId = (int) ($_POST['id'] ?? 0);
            $repository->update($updateId, $formData);
            flash('Agendamento atualizado com sucesso.');
            redirect('index.php');
        }
    }

    $editingAppointment = $formData;
    if (($postAction === 'update') && isset($_POST['id'])) {
        $editingAppointment['id'] = (int) $_POST['id'];
        $action = 'edit';
    } else {
        $action = 'create';
    }
}

$appointments = $repository->all();
$summary = $repository->summary();
$flashMessage = getFlash();

function normalizeAppointmentData(array $input): array
{
    $rawDateTime = trim((string) ($input['data_horario'] ?? ''));

    return [
        'cliente' => trim((string) ($input['cliente'] ?? '')),
        'telefone' => trim((string) ($input['telefone'] ?? '')),
        'servico' => trim((string) ($input['servico'] ?? '')),
        'barbeiro' => trim((string) ($input['barbeiro'] ?? '')),
        // O input datetime-local envia "2026-03-23T09:00", mas o banco fica mais legivel com espaco.
        'data_horario' => str_replace('T', ' ', $rawDateTime),
        'valor' => (float) str_replace(',', '.', (string) ($input['valor'] ?? '0')),
        'status' => trim((string) ($input['status'] ?? 'Agendado')),
        'observacoes' => trim((string) ($input['observacoes'] ?? '')),
    ];
}

function validateAppointmentData(array $data): array
{
    $errors = [];

    if ($data['cliente'] === '') {
        $errors[] = 'Informe o nome do cliente.';
    }

    if ($data['telefone'] === '') {
        $errors[] = 'Informe um telefone de contato.';
    }

    if ($data['servico'] === '') {
        $errors[] = 'Selecione ou informe um servico.';
    }

    if ($data['barbeiro'] === '') {
        $errors[] = 'Informe o barbeiro responsavel.';
    }

    if ($data['data_horario'] === '') {
        $errors[] = 'Escolha data e horario.';
    }

    if ($data['valor'] <= 0) {
        $errors[] = 'O valor precisa ser maior que zero.';
    }

    return $errors;
}

function old(?array $editingAppointment = null, string $field = '', string $default = ''): string
{
    if ($editingAppointment && array_key_exists($field, $editingAppointment)) {
        return (string) $editingAppointment[$field];
    }

    return $default;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Barbearia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container">
        <section class="hero">
            <div>
                <p class="eyebrow">PHP + SQLite</p>
                <h1>CRUD basico de barbearia</h1>
                <p class="hero-text">
                    Exemplo simples e autoexplicativo para cadastrar, editar, listar e excluir agendamentos.
                    Tudo funciona em PHP puro com um banco SQLite criado automaticamente.
                </p>
            </div>
            <a class="primary-link" href="index.php?action=create">Novo agendamento</a>
        </section>

        <section class="summary-grid">
            <article class="summary-card">
                <span>Total de agendamentos</span>
                <strong><?= (int) ($summary['total_agendamentos'] ?? 0) ?></strong>
            </article>
            <article class="summary-card">
                <span>Faturamento previsto</span>
                <strong>R$ <?= number_format((float) ($summary['faturamento_previsto'] ?? 0), 2, ',', '.') ?></strong>
            </article>
            <article class="summary-card">
                <span>Confirmados</span>
                <strong><?= (int) ($summary['confirmados'] ?? 0) ?></strong>
            </article>
            <article class="summary-card">
                <span>Concluidos</span>
                <strong><?= (int) ($summary['concluidos'] ?? 0) ?></strong>
            </article>
        </section>

        <?php if ($flashMessage): ?>
            <div class="alert success"><?= e($flashMessage) ?></div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert error">
                <strong>Revise os campos abaixo:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <section class="content-grid">
            <article class="panel form-panel">
                <div class="panel-header">
                    <h2><?= $action === 'edit' ? 'Editar agendamento' : 'Cadastrar agendamento' ?></h2>
                    <p>Formulario com os campos principais de uma barbearia.</p>
                </div>

                <form method="post" class="appointment-form">
                    <input type="hidden" name="form_action" value="<?= $action === 'edit' ? 'update' : 'create' ?>">
                    <?php if ($action === 'edit' && isset($editingAppointment['id'])): ?>
                        <input type="hidden" name="id" value="<?= (int) $editingAppointment['id'] ?>">
                    <?php endif; ?>

                    <label>
                        Cliente
                        <input type="text" name="cliente" value="<?= e(old($editingAppointment, 'cliente')) ?>" placeholder="Ex.: Joao Silva" required>
                    </label>

                    <label>
                        Telefone
                        <input type="text" name="telefone" value="<?= e(old($editingAppointment, 'telefone')) ?>" placeholder="(11) 99999-9999" required>
                    </label>

                    <label>
                        Servico
                        <input list="servicos" name="servico" value="<?= e(old($editingAppointment, 'servico')) ?>" placeholder="Escolha ou digite um servico" required>
                        <datalist id="servicos">
                            <?php foreach ($serviceOptions as $service): ?>
                                <option value="<?= e($service) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </label>

                    <label>
                        Barbeiro
                        <input list="barbeiros" name="barbeiro" value="<?= e(old($editingAppointment, 'barbeiro')) ?>" placeholder="Quem vai atender?" required>
                        <datalist id="barbeiros">
                            <?php foreach ($barberOptions as $barber): ?>
                                <option value="<?= e($barber) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </label>

                    <label>
                        Data e horario
                        <input type="datetime-local" name="data_horario" value="<?= e(str_replace(' ', 'T', old($editingAppointment, 'data_horario'))) ?>" required>
                    </label>

                    <label>
                        Valor (R$)
                        <input type="number" step="0.01" min="1" name="valor" value="<?= e(old($editingAppointment, 'valor')) ?>" required>
                    </label>

                    <label>
                        Status
                        <select name="status" required>
                            <?php foreach ($statusOptions as $status): ?>
                                <option value="<?= e($status) ?>" <?= old($editingAppointment, 'status', 'Agendado') === $status ? 'selected' : '' ?>>
                                    <?= e($status) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <label>
                        Observacoes
                        <textarea name="observacoes" rows="4" placeholder="Observacoes internas sobre o cliente ou servico"><?= e(old($editingAppointment, 'observacoes')) ?></textarea>
                    </label>

                    <div class="form-actions">
                        <button type="submit" class="btn primary">
                            <?= $action === 'edit' ? 'Salvar alteracoes' : 'Cadastrar' ?>
                        </button>
                        <?php if ($action === 'edit'): ?>
                            <a class="btn secondary" href="index.php">Cancelar edicao</a>
                        <?php endif; ?>
                    </div>
                </form>
            </article>

            <article class="panel table-panel">
                <div class="panel-header">
                    <h2>Agendamentos cadastrados</h2>
                    <p>A tabela abaixo representa o "Read" do CRUD.</p>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Servico</th>
                                <th>Barbeiro</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Valor</th>
                                <th>Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$appointments): ?>
                                <tr>
                                    <td colspan="7" class="empty-state">Nenhum agendamento cadastrado ate o momento.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($appointment['cliente']) ?></strong>
                                        <small><?= e($appointment['telefone']) ?></small>
                                    </td>
                                    <td><?= e($appointment['servico']) ?></td>
                                    <td><?= e($appointment['barbeiro']) ?></td>
                                    <td><?= e(date('d/m/Y H:i', strtotime($appointment['data_horario']))) ?></td>
                                    <td>
                                        <span class="badge"><?= e($appointment['status']) ?></span>
                                    </td>
                                    <td>R$ <?= number_format((float) $appointment['valor'], 2, ',', '.') ?></td>
                                    <td class="actions">
                                        <a class="btn small secondary" href="index.php?action=edit&id=<?= (int) $appointment['id'] ?>">Editar</a>
                                        <form method="post" onsubmit="return confirm('Deseja realmente excluir este agendamento?');">
                                            <input type="hidden" name="form_action" value="delete">
                                            <input type="hidden" name="id" value="<?= (int) $appointment['id'] ?>">
                                            <button type="submit" class="btn small danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php if ($appointment['observacoes'] !== ''): ?>
                                    <tr class="notes-row">
                                        <td colspan="7"><strong>Observacoes:</strong> <?= e($appointment['observacoes']) ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>
        </section>
    </main>
</body>
</html>
