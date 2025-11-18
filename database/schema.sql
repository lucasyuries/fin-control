-- Arquivo: database/schema.sql
-- Descrição: Estrutura inicial do banco de dados FinControl

-- 1. Configuração Inicial do Banco
DROP DATABASE IF EXISTS `fin_control`;
CREATE DATABASE `fin_control` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fin_control`;

-- 2. Tabela de Usuários
-- Armazena os dados de acesso e perfil
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nome` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `senha` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_users_email` (`email`)
) ENGINE=InnoDB;

-- 3. Tabela de Categorias
-- Categorias de receitas e despesas por usuário
CREATE TABLE `categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id` INT UNSIGNED NOT NULL,
    `nome` VARCHAR(50) NOT NULL,
    `tipo` ENUM('receita', 'despesa') NOT NULL,
    `icone` VARCHAR(50) DEFAULT NULL, -- Ex: classe de ícone ou emoji
    `cor` VARCHAR(20) DEFAULT NULL,     -- Ex: Hex code (#FF0000)
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Tabela de Transações (Lançamentos)
-- Registro financeiro principal
CREATE TABLE `transactions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id` INT UNSIGNED NOT NULL,
    `categoria_id` INT UNSIGNED NULL,
    `tipo` ENUM('receita', 'despesa') NOT NULL,
    `descricao` VARCHAR(255) NOT NULL,
    `valor` DECIMAL(12,2) NOT NULL,
    `data` DATE NOT NULL,
    `observacoes` TEXT DEFAULT NULL,
    `compensada_por` INT UNSIGNED DEFAULT NULL, -- Para vincular transações (ex: estorno)
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_transactions_data` (`data`),
    INDEX `idx_transactions_tipo` (`tipo`),
    FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`categoria_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`compensada_por`) REFERENCES `transactions`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Tabela de Metas Financeiras
-- Objetivos definidos pelo usuário
CREATE TABLE `goals` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `usuario_id` INT UNSIGNED NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `tipo` ENUM('patrimonio') NOT NULL DEFAULT 'patrimonio',
    `valor_objetivo` DECIMAL(12,2) NOT NULL,
    `aporte_mensal` DECIMAL(12,2) DEFAULT 0.00,
    `variacao_anual` DECIMAL(6,2) DEFAULT 0.00, -- Porcentagem esperada
    `status` ENUM('em_andamento', 'concluida', 'cancelada') NOT NULL DEFAULT 'em_andamento',
    `data_inicio` DATE DEFAULT (CURRENT_DATE),
    `data_conclusao` DATE DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`usuario_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Tabela de Recuperação de Senha
-- Tokens temporários para reset de senha
CREATE TABLE `password_resets` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(100) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `used` BOOLEAN DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_resets_token` (`token`),
    INDEX `idx_resets_email` (`email`)
) ENGINE=InnoDB;