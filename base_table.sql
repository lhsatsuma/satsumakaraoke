-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 23-Ago-2021 às 08:31
-- Versão do servidor: 8.0.21
-- versão do PHP: 7.3.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `karaoke`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `arquivos`
--

CREATE TABLE `arquivos` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `arquivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mimetype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registro` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tabela` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `campo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletado` tinyint(1) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `usuario_criacao` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `usuario_modificacao` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `musicas`
--

CREATE TABLE `musicas` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletado` tinyint(1) NOT NULL DEFAULT '0',
  `data_criacao` datetime DEFAULT NULL,
  `usuario_criacao` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `usuario_modificacao` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `origem` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigo` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `md5` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `musicas_favorites`
--

CREATE TABLE `musicas_favorites` (
  `id` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletado` tinyint(1) DEFAULT NULL,
  `data_criacao` datetime DEFAULT NULL,
  `usuario_criacao` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `usuario_modificacao` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `musica_id` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `musicas_fila`
--

CREATE TABLE `musicas_fila` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletado` tinyint(1) NOT NULL DEFAULT '0',
  `data_criacao` datetime DEFAULT NULL,
  `usuario_criacao` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `usuario_modificacao` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `musica_id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deletado` tinyint(1) NOT NULL DEFAULT '0',
  `data_criacao` datetime DEFAULT NULL,
  `usuario_criacao` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_modificacao` datetime DEFAULT NULL,
  `usuario_modificacao` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '1',
  `last_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_connected` datetime DEFAULT NULL,
  `hash_esqueci_senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ultima_troca_senha` datetime DEFAULT NULL,
  `dark_mode` tinyint(1) DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_del` (`id`,`deletado`),
  ADD KEY `idx_name_del` (`nome`,`deletado`);

--
-- Índices para tabela `musicas`
--
ALTER TABLE `musicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tipo` (`deletado`,`tipo`);

--
-- Índices para tabela `musicas_favorites`
--
ALTER TABLE `musicas_favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_id_del` (`id`,`deletado`),
  ADD KEY `idx_name_del` (`nome`,`deletado`),
  ADD KEY `idx_musica_usuario` (`musica_id`,`usuario_criacao`,`deletado`);

--
-- Índices para tabela `musicas_fila`
--
ALTER TABLE `musicas_fila`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_deletado_status` (`deletado`,`status`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
