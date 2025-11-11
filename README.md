# FinControl – Sistema de Controle Financeiro (PHP + MySQL)

Aplicação web para gerenciar usuários, receitas, despesas, ativos (registro manual de operações) e metas financeiras.

## 🧩 Estrutura do Projeto
```
fin-control/
├── config/
│   └── config.php              # Configurações (DB e BASE_URL)
├── database/
│   └── instalacao_completa.sql # Script SQL único (cria todas as tabelas + seeds)
├── public/
│   ├── index.php               # Roteador/ponto de entrada
│   ├── (API removida)          # Sem endpoints externos neste estágio
│   ├── css/style.css           # Estilos
│   └── js/app.js               # Scripts (gráficos e UI)
├── src/
│   ├── core/                   # Infra (PDO, serviços)
│   ├── controllers/            # Regras de negócio
│   ├── models/                 # Acesso a dados
│   └── views/                  # Páginas (dashboard, lançamentos, metas...)
└── README.md
```

## 🚀 Como Rodar (Windows + XAMPP)
1) Instale e inicie o XAMPP (Apache + MySQL).

2) Copie a pasta do projeto para:
```
C:\xampp\htdocs\fin-control
```

3) Crie o banco executando o SQL:
- Abra http://localhost/phpmyadmin
- Clique em SQL e cole todo o conteúdo de `database/instalacao_completa.sql`
- Execute. Deve aparecer o banco `fin_control` com as tabelas: users, categories, transactions, goals, password_resets.

4) Configure o arquivo `config/config.php` (caso necessário):
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');            // XAMPP geralmente é vazio
define('DB_NAME', 'fin_control');
define('BASE_URL', 'http://localhost/fin-control');
```

5) Acesse no navegador:
```
http://localhost/fin-control
```
Crie sua conta e faça login.

## 🧠 Funcionalidades Principais
- Autenticação: cadastro, login e recuperação de senha.
- Lançamentos: receitas, despesas e ativos (registro manual de ticker, quantidade e valor total).
- Metas: criação e acompanhamento de progresso.
- Dashboard: gráficos e resumos financeiros.

## 🛠 Requisitos
- PHP 7.4+ (XAMPP recomendado)
- MySQL 5.7+ ou 8+
- Navegador moderno (Chrome/Edge/Firefox)

## 🐞 Dicas Rápidas (Troubleshooting)
- “Table doesn’t exist”: rode `database/instalacao_completa.sql` no phpMyAdmin.
- Erro de conexão: confira `config/config.php` (usuário/senha/DB). No XAMPP o usuário padrão é `root` e senha vazia.
- Página em branco: ative erros em `config/config.php` com `ini_set('display_errors', 1); error_reporting(E_ALL);` e olhe `C:\xampp\apache\logs\error.log`.
- CSS/JS não carrega: force reload (Ctrl+F5) e verifique caminhos em `public/`.

## 📚 Licença
Projeto acadêmico — uso educacional.

---
Feito para ser simples de rodar e avaliar. API de ações removida nesta versão para foco na base financeira.
