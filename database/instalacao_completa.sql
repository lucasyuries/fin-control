-- =============================================================
-- FIN-CONTROL • Instalação do Banco de Dados (MySQL 8+)
-- =============================================================
-- Instruções
-- 1) Execute este script apenas uma vez para instalar.
-- 2) Opcional: ajuste o nome do banco se preferir outro.
-- 3) Este script é idempotente (CREATE IF NOT EXISTS), não apaga dados.
-- 4) Ao final há alguns dados iniciais de ações.
-- =============================================================

-- CRIAR BANCO (ajuste o nome se quiser)
CREATE DATABASE IF NOT EXISTS `fin_control`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `fin_control`;

-- Para RESETAR (opcional):
-- DROP DATABASE IF EXISTS `fin_control`;
-- CREATE DATABASE `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE `fin_control`;

-- =======================
-- Tabela: users
-- =======================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tabela: categories (por usuário)
-- =======================
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `nome` VARCHAR(50) NOT NULL,
  `tipo` ENUM('receita','despesa') NOT NULL,
  `icone` VARCHAR(50) DEFAULT NULL,
  `cor` VARCHAR(20) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_categoria_usuario` (`usuario_id`),
  CONSTRAINT `fk_categoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tabela: stock_prices (cache de cotações)
-- =======================
CREATE TABLE IF NOT EXISTS `stock_prices` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticker` VARCHAR(20) NOT NULL,
  `nome` VARCHAR(100) NOT NULL,
  `preco_atual` DECIMAL(12,2) NOT NULL,
  `variacao_dia` DECIMAL(6,2) DEFAULT 0.00,
  `ultima_atualizacao` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mercado` VARCHAR(20) DEFAULT 'BOVESPA',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_ticker` (`ticker`),
  KEY `idx_ultima_atualizacao` (`ultima_atualizacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tabela: transactions (receitas, despesas e ativos)
-- Observação: manter ENUM('receita','despesa','ativo') para compatibilidade com o código
-- Quando for um ativo (compra/venda), utilize os campos ticker e quantidade.
-- =======================
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `categoria_id` INT UNSIGNED DEFAULT NULL,
  `tipo` ENUM('receita','despesa','ativo') NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `valor` DECIMAL(12,2) NOT NULL,
  `data` DATE NOT NULL,
  `ticker` VARCHAR(20) DEFAULT NULL COMMENT 'Código da ação (ex.: PETR4, VALE3)',
  `quantidade` DECIMAL(12,4) DEFAULT NULL COMMENT 'Quantidade de ações quando for ativo',
  `observacoes` TEXT,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_transacao_usuario` (`usuario_id`),
  KEY `fk_transacao_categoria` (`categoria_id`),
  KEY `idx_data` (`data`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_ticker` (`ticker`),
  CONSTRAINT `fk_transacao_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_transacao_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tabela: password_resets (recuperação de senha)
-- =======================
CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` DATETIME NOT NULL,
  `used` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Tabela: goals (metas financeiras)
-- =======================
CREATE TABLE IF NOT EXISTS `goals` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `tipo` ENUM('patrimonio','ativo','provento') NOT NULL DEFAULT 'patrimonio',
  `nome` VARCHAR(100) NOT NULL,
  `valor_objetivo` DECIMAL(12,2) NOT NULL,
  `aporte_mensal` DECIMAL(12,2) DEFAULT 0.00,
  `variacao_anual` DECIMAL(6,2) DEFAULT 0.00,
  `status` ENUM('em_andamento','concluida','cancelada') NOT NULL DEFAULT 'em_andamento',
  `data_inicio` DATE NOT NULL,
  `data_conclusao` DATE DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_meta_usuario` (`usuario_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_meta_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =======================
-- Seeds (cotações iniciais para autocomplete)
-- =======================
INSERT INTO `stock_prices` (`ticker`, `nome`, `preco_atual`, `mercado`)
VALUES
  ('PETR4', 'Petrobras PN', 38.50, 'BOVESPA'),
  ('VALE3', 'Vale ON', 65.20, 'BOVESPA'),
  ('ITUB4', 'Itaú Unibanco PN', 28.90, 'BOVESPA'),
  ('BBDC4', 'Bradesco PN', 14.75, 'BOVESPA'),
  ('ABEV3', 'Ambev ON', 12.35, 'BOVESPA'),
  ('B3SA3', 'B3 ON', 11.80, 'BOVESPA'),
  ('WEGE3', 'WEG ON', 42.15, 'BOVESPA'),
  ('RENT3', 'Localiza ON', 58.30, 'BOVESPA'),
  ('MGLU3', 'Magazine Luiza ON', 3.45, 'BOVESPA'),
  ('LREN3', 'Lojas Renner ON', 16.20, 'BOVESPA'),
  ('SUZB3', 'Suzano ON', 52.80, 'BOVESPA'),
  ('GGBR4', 'Gerdau PN', 23.40, 'BOVESPA'),
  ('EMBR3', 'Embraer ON', 28.65, 'BOVESPA'),
  ('BBAS3', 'Banco do Brasil ON', 26.50, 'BOVESPA'),
  ('RADL3', 'Raia Drogasil ON', 24.90, 'BOVESPA')
ON DUPLICATE KEY UPDATE
  `nome` = VALUES(`nome`),
  `preco_atual` = VALUES(`preco_atual`),
  `mercado` = VALUES(`mercado`),
  `ultima_atualizacao` = CURRENT_TIMESTAMP;

-- =======================
-- Verificação
-- =======================
SELECT 'Banco de dados instalado com sucesso!' AS Status;
SHOW TABLES;

-- Dica: Para calcular preço médio por ticker do usuário usando transactions (tipo='ativo')
-- você vai somar (valor total) e (quantidade) de compras/vendas e dividir quando apropriado.
-- Implementaremos isso na aplicação, não aqui no SQL.
-- =====================================-- =====================================-- =====================================-- =====================================-- =====================================

-- FINCONTROL - INSTALAÇÃO DO BANCO

-- =====================================-- FINCONTROL - INSTALAÇÃO DO BANCO DE DADOS



DROP DATABASE IF EXISTS fin_control;-- =====================================-- FINCONTROL - INSTALAÇÃO DO BANCO DE DADOS

CREATE DATABASE fin_control DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE fin_control;-- Execute este script APENAS UMA VEZ na instalação inicial



CREATE TABLE users (-- Se precisar resetar, use: DROP DATABASE fin_control; antes de executar-- =====================================-- FINCONTROL - DATABASE INSTALLATION-- FINCONTROL - INSTALAÇÃO DO BANCO DE DADOS

  id int(11) NOT NULL AUTO_INCREMENT,

  nome varchar(100) NOT NULL,

  email varchar(100) NOT NULL UNIQUE,

  senha varchar(255) NOT NULL,-- Criar banco de dados-- Execute este script APENAS UMA VEZ na instalação inicial

  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,CREATE DATABASE IF NOT EXISTS `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  PRIMARY KEY (id),

  KEY idx_email (email)USE `fin_control`;-- Se precisar resetar, use: DROP DATABASE fin_control; antes de executar-- =====================================-- =====================================

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



CREATE TABLE categories (

  id int(11) NOT NULL AUTO_INCREMENT,-- =====================================

  usuario_id int(11) NOT NULL,

  nome varchar(50) NOT NULL,-- ESTRUTURA DAS TABELAS

  tipo enum('receita','despesa') NOT NULL,

  icone varchar(50) DEFAULT NULL,-- =====================================-- Criar banco de dados-- Execute this script ONLY ONCE during initial installation-- Execute este script APENAS UMA VEZ na instalação inicial

  cor varchar(20) DEFAULT NULL,

  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (id),

  KEY fk_categoria_usuario (usuario_id),-- Tabela: users (Usuários do sistema)CREATE DATABASE IF NOT EXISTS `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  CONSTRAINT fk_categoria_usuario FOREIGN KEY (usuario_id) REFERENCES users (id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;CREATE TABLE IF NOT EXISTS `users` (



CREATE TABLE transactions (  `id` int(11) NOT NULL AUTO_INCREMENT,USE `fin_control`;-- To reset, use: DROP DATABASE fin_control; before executing-- Se precisar resetar, use: DROP DATABASE fin_control; antes de executar

  id int(11) NOT NULL AUTO_INCREMENT,

  usuario_id int(11) NOT NULL,  `nome` varchar(100) NOT NULL,

  categoria_id int(11) DEFAULT NULL,

  tipo enum('receita','despesa','ativo') NOT NULL,  `email` varchar(100) NOT NULL UNIQUE,

  descricao varchar(255) NOT NULL,

  valor decimal(10,2) NOT NULL,  `senha` varchar(255) NOT NULL,

  data date NOT NULL,

  ticker varchar(20) DEFAULT NULL,  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,-- =====================================

  quantidade decimal(10,4) DEFAULT NULL,

  observacoes text,  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  PRIMARY KEY (`id`),-- ESTRUTURA DAS TABELAS

  PRIMARY KEY (id),

  KEY fk_transacao_usuario (usuario_id),  KEY `idx_email` (`email`)

  KEY fk_transacao_categoria (categoria_id),

  KEY idx_data (data),) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;-- =====================================-- OPTION 1: CREATE DATABASE (first installation)-- OPÇÃO 1: CRIAR BANCO (primeira instalação)

  KEY idx_tipo (tipo),

  KEY idx_ticker (ticker),

  CONSTRAINT fk_transacao_categoria FOREIGN KEY (categoria_id) REFERENCES categories (id) ON DELETE SET NULL,

  CONSTRAINT fk_transacao_usuario FOREIGN KEY (usuario_id) REFERENCES users (id) ON DELETE CASCADE-- Tabela: categories (Categorias de receitas/despesas)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `categories` (

CREATE TABLE stock_prices (

  id int(11) NOT NULL AUTO_INCREMENT,  `id` int(11) NOT NULL AUTO_INCREMENT,-- Tabela: users (Usuários do sistema)CREATE DATABASE IF NOT EXISTS `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;CREATE DATABASE IF NOT EXISTS `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  ticker varchar(20) NOT NULL,

  nome varchar(100) NOT NULL,  `usuario_id` int(11) NOT NULL,

  preco_atual decimal(10,2) NOT NULL,

  variacao_dia decimal(5,2) DEFAULT 0.00,  `nome` varchar(50) NOT NULL,CREATE TABLE IF NOT EXISTS `users` (

  ultima_atualizacao timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  mercado varchar(20) DEFAULT 'BOVESPA',  `tipo` enum('receita','despesa') NOT NULL,

  PRIMARY KEY (id),

  UNIQUE KEY idx_ticker (ticker)  `icone` varchar(50) DEFAULT NULL,  `id` int(11) NOT NULL AUTO_INCREMENT,

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

  `cor` varchar(20) DEFAULT NULL,

CREATE TABLE password_resets (

  id int(11) NOT NULL AUTO_INCREMENT,  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `nome` varchar(100) NOT NULL,

  email varchar(100) NOT NULL,

  token varchar(255) NOT NULL,  PRIMARY KEY (`id`),

  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  expires_at datetime NOT NULL,  KEY `fk_categoria_usuario` (`usuario_id`),  `email` varchar(100) NOT NULL UNIQUE,-- OPTION 2: RESET DATABASE (uncomment the 2 lines below to start from scratch)-- OPÇÃO 2: RESETAR BANCO (descomente as 2 linhas abaixo se quiser começar do zero)

  used tinyint(1) DEFAULT 0,

  PRIMARY KEY (id),  CONSTRAINT `fk_categoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE

  KEY idx_email (email)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;  `senha` varchar(255) NOT NULL,



CREATE TABLE goals (

  id int(11) NOT NULL AUTO_INCREMENT,

  usuario_id int(11) NOT NULL,-- Tabela: transactions (Lançamentos: receitas, despesas e investimentos)  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,-- DROP DATABASE IF EXISTS `fin_control`;-- DROP DATABASE IF EXISTS `fin_control`;

  tipo enum('patrimonio','ativo','provento') NOT NULL DEFAULT 'patrimonio',

  nome varchar(100) NOT NULL,CREATE TABLE IF NOT EXISTS `transactions` (

  valor_objetivo decimal(10,2) NOT NULL,

  aporte_mensal decimal(10,2) DEFAULT 0.00,  `id` int(11) NOT NULL AUTO_INCREMENT,  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  variacao_anual decimal(5,2) DEFAULT 0.00,

  status enum('em_andamento','concluida','cancelada') NOT NULL DEFAULT 'em_andamento',  `usuario_id` int(11) NOT NULL,

  data_inicio date NOT NULL,

  data_conclusao date DEFAULT NULL,  `categoria_id` int(11) DEFAULT NULL,  PRIMARY KEY (`id`),-- CREATE DATABASE `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;-- CREATE DATABASE `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  `tipo` enum('receita','despesa','ativo') NOT NULL,

  PRIMARY KEY (id),

  KEY fk_meta_usuario (usuario_id),  `descricao` varchar(255) NOT NULL,  KEY `idx_email` (`email`)

  CONSTRAINT fk_meta_usuario FOREIGN KEY (usuario_id) REFERENCES users (id) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;  `valor` decimal(10,2) NOT NULL,



INSERT INTO stock_prices (ticker, nome, preco_atual, mercado) VALUES  `data` date NOT NULL,) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

('PETR4', 'Petrobras PN', 38.50, 'BOVESPA'),

('VALE3', 'Vale ON', 65.20, 'BOVESPA'),  `ticker` varchar(20) DEFAULT NULL COMMENT 'Código da ação (ex: PETR4, VALE3)',

('ITUB4', 'Itaú Unibanco PN', 28.90, 'BOVESPA'),

('BBDC4', 'Bradesco PN', 14.75, 'BOVESPA'),  `quantidade` decimal(10,4) DEFAULT NULL COMMENT 'Quantidade de ações',

('ABEV3', 'Ambev ON', 12.35, 'BOVESPA'),

('B3SA3', 'B3 ON', 11.80, 'BOVESPA'),  `observacoes` text,

('WEGE3', 'WEG ON', 42.15, 'BOVESPA'),

('RENT3', 'Localiza ON', 58.30, 'BOVESPA'),  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,-- Tabela: categories (Categorias de receitas/despesas)USE `fin_control`;USE `fin_control`;

('MGLU3', 'Magazine Luiza ON', 3.45, 'BOVESPA'),

('LREN3', 'Lojas Renner ON', 16.20, 'BOVESPA'),  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

('SUZB3', 'Suzano ON', 52.80, 'BOVESPA'),

('GGBR4', 'Gerdau PN', 23.40, 'BOVESPA'),  PRIMARY KEY (`id`),CREATE TABLE IF NOT EXISTS `categories` (

('EMBR3', 'Embraer ON', 28.65, 'BOVESPA'),

('BBAS3', 'Banco do Brasil ON', 26.50, 'BOVESPA'),  KEY `fk_transacao_usuario` (`usuario_id`),

('RADL3', 'Raia Drogasil ON', 24.90, 'BOVESPA');

  KEY `fk_transacao_categoria` (`categoria_id`),  `id` int(11) NOT NULL AUTO_INCREMENT,

SELECT 'Instalado com sucesso!' as Status;

SHOW TABLES;  KEY `idx_data` (`data`),


  KEY `idx_tipo` (`tipo`),  `usuario_id` int(11) NOT NULL,

  KEY `idx_ticker` (`ticker`),

  CONSTRAINT `fk_transacao_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,  `nome` varchar(50) NOT NULL,-- =====================================-- =====================================

  CONSTRAINT `fk_transacao_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;  `tipo` enum('receita','despesa') NOT NULL,



-- Tabela: stock_prices (Cotações de ações em tempo real)  `icone` varchar(50) DEFAULT NULL,-- TABLE STRUCTURE-- ESTRUTURA DAS TABELAS

CREATE TABLE IF NOT EXISTS `stock_prices` (

  `id` int(11) NOT NULL AUTO_INCREMENT,  `cor` varchar(20) DEFAULT NULL,

  `ticker` varchar(20) NOT NULL,

  `nome` varchar(100) NOT NULL COMMENT 'Nome da empresa',  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,-- =====================================-- =====================================

  `preco_atual` decimal(10,2) NOT NULL,

  `variacao_dia` decimal(5,2) DEFAULT 0.00,  PRIMARY KEY (`id`),

  `ultima_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  `mercado` varchar(20) DEFAULT 'BOVESPA',  KEY `fk_categoria_usuario` (`usuario_id`),

  PRIMARY KEY (`id`),

  UNIQUE KEY `idx_ticker` (`ticker`),  CONSTRAINT `fk_categoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE

  KEY `idx_ultima_atualizacao` (`ultima_atualizacao`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;-- Table: users (System users)-- Tabela: users (Usuários do sistema)



-- Tabela: password_resets (Tokens para recuperação de senha)

CREATE TABLE IF NOT EXISTS `password_resets` (

  `id` int(11) NOT NULL AUTO_INCREMENT,-- Tabela: transactions (Lançamentos: receitas, despesas e investimentos)CREATE TABLE IF NOT EXISTS `users` (CREATE TABLE IF NOT EXISTS `users` (

  `email` varchar(100) NOT NULL,

  `token` varchar(255) NOT NULL,CREATE TABLE IF NOT EXISTS `transactions` (

  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `expires_at` datetime NOT NULL,  `id` int(11) NOT NULL AUTO_INCREMENT,  `id` int(11) NOT NULL AUTO_INCREMENT,  `id` int(11) NOT NULL AUTO_INCREMENT,

  `used` tinyint(1) DEFAULT 0,

  PRIMARY KEY (`id`),  `usuario_id` int(11) NOT NULL,

  KEY `idx_email` (`email`),

  KEY `idx_token` (`token`)  `categoria_id` int(11) DEFAULT NULL,  `name` varchar(100) NOT NULL,  `nome` varchar(100) NOT NULL,

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  `tipo` enum('receita','despesa','ativo') NOT NULL,

-- Tabela: goals (Metas financeiras)

CREATE TABLE IF NOT EXISTS `goals` (  `descricao` varchar(255) NOT NULL,  `email` varchar(100) NOT NULL UNIQUE,  `email` varchar(100) NOT NULL UNIQUE,

  `id` int(11) NOT NULL AUTO_INCREMENT,

  `usuario_id` int(11) NOT NULL,  `valor` decimal(10,2) NOT NULL,

  `tipo` enum('patrimonio','ativo','provento') NOT NULL DEFAULT 'patrimonio',

  `nome` varchar(100) NOT NULL,  `data` date NOT NULL,  `password` varchar(255) NOT NULL,  `senha` varchar(255) NOT NULL,

  `valor_objetivo` decimal(10,2) NOT NULL,

  `aporte_mensal` decimal(10,2) DEFAULT 0.00,  `ticker` varchar(20) DEFAULT NULL COMMENT 'Código da ação (ex: PETR4, VALE3)',

  `variacao_anual` decimal(5,2) DEFAULT 0.00,

  `status` enum('em_andamento','concluida','cancelada') NOT NULL DEFAULT 'em_andamento',  `quantidade` decimal(10,4) DEFAULT NULL COMMENT 'Quantidade de ações',  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `data_inicio` date NOT NULL,

  `data_conclusao` date DEFAULT NULL,  `observacoes` text,

  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  KEY `fk_meta_usuario` (`usuario_id`),  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  KEY `idx_status` (`status`),

  CONSTRAINT `fk_meta_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE  PRIMARY KEY (`id`),  PRIMARY KEY (`id`),  PRIMARY KEY (`id`),

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  KEY `fk_transacao_usuario` (`usuario_id`),

-- =====================================

-- DADOS INICIAIS - AÇÕES BRASILEIRAS  KEY `fk_transacao_categoria` (`categoria_id`),  KEY `idx_email` (`email`)  KEY `idx_email` (`email`)

-- =====================================

INSERT INTO `stock_prices` (`ticker`, `nome`, `preco_atual`, `mercado`) VALUES  KEY `idx_data` (`data`),

('PETR4', 'Petrobras PN', 38.50, 'BOVESPA'),

('VALE3', 'Vale ON', 65.20, 'BOVESPA'),  KEY `idx_tipo` (`tipo`),) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

('ITUB4', 'Itaú Unibanco PN', 28.90, 'BOVESPA'),

('BBDC4', 'Bradesco PN', 14.75, 'BOVESPA'),  KEY `idx_ticker` (`ticker`),

('ABEV3', 'Ambev ON', 12.35, 'BOVESPA'),

('B3SA3', 'B3 ON', 11.80, 'BOVESPA'),  CONSTRAINT `fk_transacao_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,

('WEGE3', 'WEG ON', 42.15, 'BOVESPA'),

('RENT3', 'Localiza ON', 58.30, 'BOVESPA'),  CONSTRAINT `fk_transacao_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE

('MGLU3', 'Magazine Luiza ON', 3.45, 'BOVESPA'),

('LREN3', 'Lojas Renner ON', 16.20, 'BOVESPA'),) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;-- Table: categories (Income/Expense categories)-- Tabela: categories (Categorias de receitas/despesas)

('SUZB3', 'Suzano ON', 52.80, 'BOVESPA'),

('GGBR4', 'Gerdau PN', 23.40, 'BOVESPA'),

('EMBR3', 'Embraer ON', 28.65, 'BOVESPA'),

('BBAS3', 'Banco do Brasil ON', 26.50, 'BOVESPA'),-- Tabela: stock_prices (Cotações de ações em tempo real)CREATE TABLE IF NOT EXISTS `categories` (CREATE TABLE IF NOT EXISTS `categories` (

('RADL3', 'Raia Drogasil ON', 24.90, 'BOVESPA');

CREATE TABLE IF NOT EXISTS `stock_prices` (

-- =====================================

-- VERIFICAÇÃO  `id` int(11) NOT NULL AUTO_INCREMENT,  `id` int(11) NOT NULL AUTO_INCREMENT,  `id` int(11) NOT NULL AUTO_INCREMENT,

-- =====================================

SELECT 'Banco de dados instalado com sucesso!' as Status;  `ticker` varchar(20) NOT NULL,

SELECT 'Agora acesse: http://localhost/fin-control para criar sua conta!' as Proximo_Passo;

SHOW TABLES;  `nome` varchar(100) NOT NULL COMMENT 'Nome da empresa',  `user_id` int(11) NOT NULL,  `usuario_id` int(11) NOT NULL,


  `preco_atual` decimal(10,2) NOT NULL,

  `variacao_dia` decimal(5,2) DEFAULT 0.00,  `name` varchar(50) NOT NULL,  `nome` varchar(50) NOT NULL,

  `ultima_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  `mercado` varchar(20) DEFAULT 'BOVESPA',  `type` enum('income','expense') NOT NULL,  `tipo` enum('receita','despesa') NOT NULL,

  PRIMARY KEY (`id`),

  UNIQUE KEY `idx_ticker` (`ticker`),  `icon` varchar(50) DEFAULT NULL,  `icone` varchar(50) DEFAULT NULL,

  KEY `idx_ultima_atualizacao` (`ultima_atualizacao`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;  `color` varchar(20) DEFAULT NULL,  `cor` varchar(20) DEFAULT NULL,



-- Tabela: password_resets (Tokens para recuperação de senha)  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

CREATE TABLE IF NOT EXISTS `password_resets` (

  `id` int(11) NOT NULL AUTO_INCREMENT,  PRIMARY KEY (`id`),  PRIMARY KEY (`id`),

  `email` varchar(100) NOT NULL,

  `token` varchar(255) NOT NULL,  KEY `fk_category_user` (`user_id`),  KEY `fk_categoria_usuario` (`usuario_id`),

  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `expires_at` datetime NOT NULL,  CONSTRAINT `fk_category_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE  CONSTRAINT `fk_categoria_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE

  `used` tinyint(1) DEFAULT 0,

  PRIMARY KEY (`id`),) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  KEY `idx_email` (`email`),

  KEY `idx_token` (`token`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: transactions (Transactions: income, expenses and assets)-- Tabela: transactions (Lançamentos: receitas, despesas e ativos)

-- Tabela: goals (Metas financeiras)

CREATE TABLE IF NOT EXISTS `goals` (CREATE TABLE IF NOT EXISTS `transactions` (CREATE TABLE IF NOT EXISTS `transactions` (

  `id` int(11) NOT NULL AUTO_INCREMENT,

  `usuario_id` int(11) NOT NULL,  `id` int(11) NOT NULL AUTO_INCREMENT,  `id` int(11) NOT NULL AUTO_INCREMENT,

  `tipo` enum('patrimonio','ativo','provento') NOT NULL DEFAULT 'patrimonio',

  `nome` varchar(100) NOT NULL,  `user_id` int(11) NOT NULL,  `usuario_id` int(11) NOT NULL,

  `valor_objetivo` decimal(10,2) NOT NULL,

  `aporte_mensal` decimal(10,2) DEFAULT 0.00,  `category_id` int(11) DEFAULT NULL,  `categoria_id` int(11) DEFAULT NULL,

  `variacao_anual` decimal(5,2) DEFAULT 0.00,

  `status` enum('em_andamento','concluida','cancelada') NOT NULL DEFAULT 'em_andamento',  `type` enum('income','expense','asset') NOT NULL,  `tipo` enum('receita','despesa','ativo') NOT NULL,

  `data_inicio` date NOT NULL,

  `data_conclusao` date DEFAULT NULL,  `description` varchar(255) NOT NULL,  `descricao` varchar(255) NOT NULL,

  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  `amount` decimal(10,2) NOT NULL,  `valor` decimal(10,2) NOT NULL,

  PRIMARY KEY (`id`),

  KEY `fk_meta_usuario` (`usuario_id`),  `date` date NOT NULL,  `data` date NOT NULL,

  KEY `idx_status` (`status`),

  CONSTRAINT `fk_meta_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE  `ticker` varchar(20) DEFAULT NULL COMMENT 'Stock ticker symbol (e.g., PETR4, VALE3)',  `observacoes` text,

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  `quantity` decimal(10,4) DEFAULT NULL COMMENT 'Number of shares for stocks',  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

-- =====================================

-- DADOS INICIAIS - AÇÕES BRASILEIRAS  `notes` text,  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

-- =====================================

INSERT INTO `stock_prices` (`ticker`, `nome`, `preco_atual`, `mercado`) VALUES  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  PRIMARY KEY (`id`),

('PETR4', 'Petrobras PN', 38.50, 'BOVESPA'),

('VALE3', 'Vale ON', 65.20, 'BOVESPA'),  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  KEY `fk_transacao_usuario` (`usuario_id`),

('ITUB4', 'Itaú Unibanco PN', 28.90, 'BOVESPA'),

('BBDC4', 'Bradesco PN', 14.75, 'BOVESPA'),  PRIMARY KEY (`id`),  KEY `fk_transacao_categoria` (`categoria_id`),

('ABEV3', 'Ambev ON', 12.35, 'BOVESPA'),

('B3SA3', 'B3 ON', 11.80, 'BOVESPA'),  KEY `fk_transaction_user` (`user_id`),  KEY `idx_data` (`data`),

('WEGE3', 'WEG ON', 42.15, 'BOVESPA'),

('RENT3', 'Localiza ON', 58.30, 'BOVESPA'),  KEY `fk_transaction_category` (`category_id`),  KEY `idx_tipo` (`tipo`),

('MGLU3', 'Magazine Luiza ON', 3.45, 'BOVESPA'),

('LREN3', 'Lojas Renner ON', 16.20, 'BOVESPA'),  KEY `idx_date` (`date`),  CONSTRAINT `fk_transacao_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,

('SUZB3', 'Suzano ON', 52.80, 'BOVESPA'),

('GGBR4', 'Gerdau PN', 23.40, 'BOVESPA'),  KEY `idx_type` (`type`),  CONSTRAINT `fk_transacao_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE

('EMBR3', 'Embraer ON', 28.65, 'BOVESPA'),

('BBAS3', 'Banco do Brasil ON', 26.50, 'BOVESPA'),  KEY `idx_ticker` (`ticker`),) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

('RADL3', 'Raia Drogasil ON', 24.90, 'BOVESPA');

  CONSTRAINT `fk_transaction_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,

-- =====================================

-- VERIFICAÇÃO  CONSTRAINT `fk_transaction_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE-- Tabela: password_resets (Tokens para recuperação de senha)

-- =====================================

SELECT 'Banco de dados instalado com sucesso!' as Status;) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;CREATE TABLE IF NOT EXISTS `password_resets` (

SELECT 'Agora acesse: http://localhost/fin-control para criar sua conta!' as Proximo_Passo;

SHOW TABLES;  `id` int(11) NOT NULL AUTO_INCREMENT,


-- Table: stock_prices (Real-time stock prices)  `email` varchar(100) NOT NULL,

CREATE TABLE IF NOT EXISTS `stock_prices` (  `token` varchar(255) NOT NULL,

  `id` int(11) NOT NULL AUTO_INCREMENT,  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  `ticker` varchar(20) NOT NULL,  `expires_at` datetime NOT NULL,

  `name` varchar(100) NOT NULL COMMENT 'Company name',  `used` tinyint(1) DEFAULT 0,

  `current_price` decimal(10,2) NOT NULL,  PRIMARY KEY (`id`),

  `change_percent` decimal(5,2) DEFAULT 0.00,  KEY `idx_email` (`email`),

  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,  KEY `idx_token` (`token`)

  `market` varchar(20) DEFAULT 'BOVESPA',) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  PRIMARY KEY (`id`),

  UNIQUE KEY `idx_ticker` (`ticker`),-- Tabela: goals (Metas financeiras)

  KEY `idx_last_update` (`last_update`)CREATE TABLE IF NOT EXISTS `goals` (

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;  `id` int(11) NOT NULL AUTO_INCREMENT,

  `usuario_id` int(11) NOT NULL,

-- Table: password_resets (Password recovery tokens)  `tipo` enum('patrimonio','ativo','provento') NOT NULL DEFAULT 'patrimonio',

CREATE TABLE IF NOT EXISTS `password_resets` (  `nome` varchar(100) NOT NULL,

  `id` int(11) NOT NULL AUTO_INCREMENT,  `valor_objetivo` decimal(10,2) NOT NULL,

  `email` varchar(100) NOT NULL,  `aporte_mensal` decimal(10,2) DEFAULT 0.00,

  `token` varchar(255) NOT NULL,  `variacao_anual` decimal(5,2) DEFAULT 0.00,

  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,  `status` enum('em_andamento','concluida','cancelada') NOT NULL DEFAULT 'em_andamento',

  `expires_at` datetime NOT NULL,  `data_inicio` date NOT NULL,

  `used` tinyint(1) DEFAULT 0,  `data_conclusao` date DEFAULT NULL,

  PRIMARY KEY (`id`),  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  KEY `idx_email` (`email`),  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  KEY `idx_token` (`token`)  PRIMARY KEY (`id`),

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;  KEY `fk_meta_usuario` (`usuario_id`),

  KEY `idx_status` (`status`),

-- Table: goals (Financial goals)  CONSTRAINT `fk_meta_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE

CREATE TABLE IF NOT EXISTS `goals` () ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  `id` int(11) NOT NULL AUTO_INCREMENT,

  `user_id` int(11) NOT NULL,-- =====================================

  `type` enum('portfolio','asset','dividend') NOT NULL DEFAULT 'portfolio',-- ATUALIZAÇÃO (Para quem já tinha o sistema instalado)

  `name` varchar(100) NOT NULL,-- =====================================

  `target_amount` decimal(10,2) NOT NULL,-- Se você já tinha o banco e precisa adicionar o tipo 'ativo', execute:

  `monthly_contribution` decimal(10,2) DEFAULT 0.00,-- ALTER TABLE `transactions` MODIFY COLUMN `tipo` ENUM('receita','despesa','ativo') NOT NULL;

  `annual_return` decimal(5,2) DEFAULT 0.00,

  `status` enum('active','completed','cancelled') NOT NULL DEFAULT 'active',-- =====================================

  `start_date` date NOT NULL,-- VERIFICAÇÃO

  `completion_date` date DEFAULT NULL,-- =====================================

  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,SELECT 'Banco de dados instalado com sucesso!' as Status;

  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,SELECT 'Agora acesse: http://localhost/fin-control para criar sua conta!' as Proximo_Passo;

  PRIMARY KEY (`id`),SHOW TABLES;

  KEY `fk_goal_user` (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_goal_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================
-- SAMPLE BRAZILIAN STOCKS (for autocomplete)
-- =====================================
INSERT INTO `stock_prices` (`ticker`, `name`, `current_price`, `market`) VALUES
('PETR4', 'Petrobras PN', 38.50, 'BOVESPA'),
('VALE3', 'Vale ON', 65.20, 'BOVESPA'),
('ITUB4', 'Itaú Unibanco PN', 28.90, 'BOVESPA'),
('BBDC4', 'Bradesco PN', 14.75, 'BOVESPA'),
('ABEV3', 'Ambev ON', 12.35, 'BOVESPA'),
('B3SA3', 'B3 ON', 11.80, 'BOVESPA'),
('WEGE3', 'WEG ON', 42.15, 'BOVESPA'),
('RENT3', 'Localiza ON', 58.30, 'BOVESPA'),
('MGLU3', 'Magazine Luiza ON', 3.45, 'BOVESPA'),
('LREN3', 'Lojas Renner ON', 16.20, 'BOVESPA'),
('SUZB3', 'Suzano ON', 52.80, 'BOVESPA'),
('GGBR4', 'Gerdau PN', 23.40, 'BOVESPA'),
('EMBR3', 'Embraer ON', 28.65, 'BOVESPA'),
('BBAS3', 'Banco do Brasil ON', 26.50, 'BOVESPA'),
('RADL3', 'Raia Drogasil ON', 24.90, 'BOVESPA');

-- =====================================
-- VERIFICATION
-- =====================================
SELECT 'Database installed successfully!' as Status;
SELECT 'Now access: http://localhost/fin-control to create your account!' as Next_Step;
SHOW TABLES;
