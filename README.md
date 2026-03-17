# BarberPro SaaS em PHP

Sistema SaaS para barbearias desenvolvido em PHP com estrutura MVC simples, autenticacao por sessao e banco MySQL.

## Funcionalidades

- cadastro e login de barbearias
- dashboard com indicadores operacionais
- gestao de clientes
- gestao de servicos
- gestao de usuarios da equipe
- controle de permissoes por perfil
- cadastro, edicao e exclusao de agendamentos
- atualizacao de status dos atendimentos
- validacao de conflito de horarios
- agenda geral e minha agenda
- configuracao de horario de funcionamento
- cadastro de pausas e indisponibilidade de profissionais
- isolamento por `barbearia_id`

## Perfis de acesso

- `admin`: acesso total ao sistema
- `recepcao`: agenda, clientes, servicos e disponibilidade
- `barbeiro`: visualizacao de clientes e acesso apenas a propria agenda

## Tecnologias

- PHP
- MySQL
- PDO
- Bootstrap
- CSS customizado

## Estrutura do projeto

- `barbearia/config`: conexao com banco e autenticacao
- `barbearia/controllers`: regras de negocio e actions
- `barbearia/models`: acesso ao banco
- `barbearia/views`: interface do sistema
- `barbearia/css`: estilos
- `barbearia.sql`: estrutura completa do banco

## Como executar

1. Importe o arquivo `barbearia.sql` no MySQL.
2. Ajuste as credenciais em `barbearia/config/database.php`.
3. Rode o projeto em um ambiente local com PHP, como XAMPP ou Laragon.
4. Acesse `/barbearia/views/auth/login.php`.

## Recursos implementados

- multi-barbearia com separacao por empresa
- dashboard adaptado por perfil
- controle de equipe
- validacao de expediente e pausas na agenda
- restricao de acesso por tipo de usuario
- manutencao completa de agendamentos

## Proximos passos

- modulo financeiro
- comandas e pagamentos
- relatorios
- historico completo de clientes
- melhorias de seguranca e deploy
