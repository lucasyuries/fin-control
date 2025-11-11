# 💰 FinControl — Sistema de Controle Financeiro (PHP + MySQL)

Aplicação web simples para gerenciar receitas, despesas e investimentos. Inclui cadastro de usuários, lançamentos, metas e um recurso de ações com comparação entre preço médio e preço atual.

## 🧩 Estrutura do Projeto
```
fin-control/
├── config/
│   └── config.php              # Configurações (DB e BASE_URL)
├── database/
│   └── instalacao_completa.sql # Script SQL único (cria todas as tabelas + seeds)
├── public/
│   ├── index.php               # Roteador/ponto de entrada
│   ├── api/stocks.php          # API de ações (busca, cotação e portfólio)
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
- Execute. Deve aparecer o banco `fin_control` com as tabelas: users, categories, transactions, stock_prices, goals, password_resets.

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
- Lançamentos: receitas, despesas e ativos (ações via ticker + quantidade).
- Metas: criação e acompanhamento de progresso.
- Ações: busca por ticker, cotação (com cache em `stock_prices`) e comparação com preço médio do usuário.
- Dashboard: gráficos e resumos financeiros.

## 🔗 APIs Internas
- GET `public/api/stocks.php?action=search&q=PETR` — busca ações por termo.
- GET `public/api/stocks.php?action=quote&ticker=PETR4` — retorna cotação atual (usa cache e pode consultar API externa).
- GET `public/api/stocks.php?action=portfolio` — resumo do portfólio do usuário logado (média vs preço atual).

Observação: os endpoints exigem sessão ativa (login).

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
Feito para ser simples de rodar e avaliar.
