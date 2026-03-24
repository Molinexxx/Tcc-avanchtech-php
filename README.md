# CRUD basico de barbearia em PHP

Projeto simples e didatico feito em **PHP puro + SQLite** para servir como exemplo de CRUD.

## O que este projeto faz

- Cadastra agendamentos de clientes da barbearia.
- Lista todos os agendamentos em uma tabela.
- Edita um agendamento existente.
- Exclui um agendamento com confirmacao.
- Mostra um pequeno resumo com total de agendamentos e faturamento previsto.

## Estrutura

- `index.php`: concentra a interface, o processamento do formulario e a listagem.
- `src/bootstrap.php`: cria a conexao com o banco e inicializa a tabela automaticamente.
- `src/AppointmentRepository.php`: separa as operacoes do CRUD em metodos claros.
- `style.css`: deixa a interface mais organizada e agradavel.
- `database/barbearia.sqlite`: arquivo do banco SQLite, criado automaticamente ao executar.

## Como rodar

1. Tenha o PHP instalado na maquina.
2. Abra o terminal na pasta do projeto.
3. Rode:

```bash
php -S localhost:8000
```

4. Abra o navegador em `http://localhost:8000`.

## Observacoes

- O banco SQLite e criado sozinho na primeira execucao.
- Dois agendamentos de exemplo sao inseridos automaticamente para facilitar os testes.
- Se quiser trocar SQLite por MySQL depois, a parte mais facil de adaptar fica em `src/bootstrap.php`.
