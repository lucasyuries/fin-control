-- Fin-Control • Instalação do Banco (MySQL 8+)
-- Estrutura limpa, objetiva e sem tabela de ações (cotações via API).

DROP DATABASE IF EXISTS `fin_control`;
CREATE DATABASE `fin_control` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fin_control`;

-- users
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `senha` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- categories
CREATE TABLE `categories` (
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

-- transactions
CREATE TABLE `transactions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` INT UNSIGNED NOT NULL,
  `categoria_id` INT UNSIGNED DEFAULT NULL,
  `tipo` ENUM('receita','despesa','ativo') NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `valor` DECIMAL(12,2) NOT NULL,
  `data` DATE NOT NULL,
  `ticker` VARCHAR(20) DEFAULT NULL,
  `quantidade` DECIMAL(12,4) DEFAULT NULL,
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

-- password_resets
CREATE TABLE `password_resets` (
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

-- goals
CREATE TABLE `goals` (
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
;
