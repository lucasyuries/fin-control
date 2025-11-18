# FinControl – Sistema de Controle Financeiro (PHP + MySQL)

Aplicação web para gerenciar usuários, receitas, despesas e metas financeiras.

## 🧩 Estrutura do Projeto
A arquitetura segue o padrão **MVC (Model-View-Controller)** para separação de responsabilidades.
fin-control/ ├── config/ │ └── config.php # Configurações (DB e BASE_URL) ├── database/ │ └── schema.sql # Script SQL final para criação do banco ├── public/ │ ├── index.php # Roteador/ponto de entrada │ ├── css/style.css # Estilos globais (CSS consolidado) │ └── js/app.js # Scripts (gráficos e UI, lógica JS consolidada) ├── src/ │ ├── core/ # Infra (Database, Session Manager) │ ├── controllers/ # Regras de negócio e lógica de requisição │ ├── models/ # Acesso a dados (Camada SQL) │ └── views/ # Páginas (HTML + PHP de apresentação) └── README.md


## 🚀 Como Rodar (Windows + XAMPP)
1) Instale e inicie o XAMPP (Apache + MySQL).

2) Copie a pasta do projeto para:
C:\xampp\htdocs\fin-control


3) Crie o banco executando o SQL **atualizado**:
- Abra [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
- Clique em SQL e cole todo o conteúdo do arquivo **`database/schema.sql`**
- Execute. Deve aparecer o banco `fin_control` com as tabelas: users, categories, transactions, goals, password_resets.

4) Configure o arquivo `config/config.php` (caso necessário):
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');            // XAMPP geralmente é vazio
define('DB_NAME', 'fin_control');
define('BASE_URL', 'http://localhost/fin-control');
Acesse no navegador:

http://localhost/fin-control
Crie sua conta e faça login.

🧠 Funcionalidades
Autenticação Segura: Cadastro, Login e Fluxo de Recuperação de Senha (token).

Lançamentos: Registro de Receitas e Despesas com Categorização.

Dashboard: Resumos financeiros, gráficos de evolução mensal e distribuição por categoria.

Metas: Criação e acompanhamento de objetivos financeiros.