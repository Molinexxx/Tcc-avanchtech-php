# CRUD básico de barbearia em PHP

Projeto simples e didático feito em **PHP puro + SQLite** para servir como exemplo de CRUD.

## O que este projeto faz

- Cadastra agendamentos de clientes da barbearia.
- Lista todos os agendamentos em uma tabela.
- Edita um agendamento existente.
- Exclui um agendamento com confirmação.
- Mostra o total de agendamentos e um indicador chamado **Faturamento previsto**.

O indicador soma o campo `valor` de todos os agendamentos cadastrados, incluindo os cancelados. A soma usa o valor registrado nos agendamentos e não verifica se houve pagamento.

## Estrutura

- `index.php`: concentra a interface, o processamento do formulário e a listagem.
- `src/bootstrap.php`: cria a conexão com o banco e inicializa a tabela automaticamente.
- `src/AppointmentRepository.php`: separa as operações do CRUD em métodos claros.
- `style.css`: deixa a interface mais organizada e agradável.
- `database/barbearia.sqlite`: arquivo do banco SQLite, criado automaticamente ao executar.

## Como rodar

1. Use PHP 8.0 ou superior com a extensão `pdo_sqlite` habilitada e permissão de escrita na pasta do projeto. O repositório de agendamentos usa promoção de propriedade no construtor, disponível desde o PHP 8.0.
2. Abra o terminal na pasta do projeto.
3. Rode:

```bash
php -S localhost:8000
```

4. Abra [http://localhost:8000](http://localhost:8000/).

## Observações

- O banco SQLite é criado automaticamente na primeira execução.
- A aplicação insere dois agendamentos de exemplo sempre que inicializa com a tabela vazia.
- [apresentacao.html](apresentacao.html) reúne os slides do projeto.
