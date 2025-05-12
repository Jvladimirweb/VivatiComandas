-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/04/2025 às 18:37
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bar`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comandas`
--

CREATE TABLE `comandas` (
  `id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `aberta` tinyint(1) DEFAULT 1,
  `mesa` int(11) DEFAULT NULL,
  `status` enum('aberta','fechada') NOT NULL DEFAULT 'aberta',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_fechamento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comandas`
--

INSERT INTO `comandas` (`id`, `numero`, `aberta`, `mesa`, `status`, `criado_em`, `data_fechamento`) VALUES
(244, 1, 1, 100, 'fechada', '2025-04-21 22:40:41', '2025-04-21 19:41:11'),
(245, 2, 1, 100, 'fechada', '2025-04-21 22:41:21', '2025-04-21 20:16:13'),
(246, 3, 1, 101, 'fechada', '2025-04-21 22:51:40', '2025-04-21 20:04:34'),
(247, 4, 1, 101, 'fechada', '2025-04-21 23:04:44', '2025-04-21 20:05:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens`
--

CREATE TABLE `itens` (
  `id` int(11) NOT NULL,
  `comanda_id` int(11) NOT NULL,
  `produto` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `quantidade` int(11) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `atendido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `comanda_id`, `produto`, `descricao`, `quantidade`, `preco`, `criado_em`, `atendido`) VALUES
(240, 244, '10 S Festa', '50 Salgadinhos', 5, 25.00, '2025-04-21 22:40:54', 0),
(241, 244, 'Coca-cola 2l', '1 Coca 2l', 1, 13.50, '2025-04-21 22:41:07', 0),
(242, 245, '10 S Festa', '50 Bol. 50 Cox', 10, 50.00, '2025-04-21 22:41:38', 0),
(243, 246, '10 S Festa', 'Lis - 100 Salgadinhos', 10, 50.00, '2025-04-21 22:51:58', 0),
(244, 246, 'Coca-cola Zero 2l', '1 Coca 0', 1, 13.50, '2025-04-21 23:04:31', 0),
(245, 247, 'Coca-cola 2l', '1 Coca 2l', 1, 13.50, '2025-04-21 23:04:56', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `preco`, `criado_em`) VALUES
(33, '10 S Festa', 5.00, '2025-04-16 19:35:00'),
(34, '10 Festa+guaravita', 6.50, '2025-04-16 19:35:54'),
(35, '5 unid Bacalhau', 7.50, '2025-04-16 19:36:24'),
(36, '5 S camarao', 10.00, '2025-04-16 19:37:05'),
(37, '5 B siri', 4.00, '2025-04-16 19:37:35'),
(38, '5 D provolone', 5.00, '2025-04-16 19:38:08'),
(39, '5 mini churros', 5.00, '2025-04-16 19:38:39'),
(40, '100 S Festa congelado', 35.00, '2025-04-16 19:40:30'),
(41, '40 B Bacalhau cong', 35.00, '2025-04-16 19:41:16'),
(42, 'batata frita', 13.50, '2025-04-16 19:41:59'),
(43, 'Queijo coalho', 35.00, '2025-04-16 19:42:29'),
(44, 'porção camarão', 35.00, '2025-04-16 19:42:52'),
(45, 'torresmo, Q. coalho e linguica', 40.00, '2025-04-16 19:43:58'),
(46, 'Coca-cola 2l', 13.50, '2025-04-16 23:00:37'),
(47, 'Guaraná 2l', 13.50, '2025-04-16 23:00:57'),
(48, 'Fanta 2l', 13.50, '2025-04-16 23:01:20'),
(49, 'Coca lata', 6.00, '2025-04-16 23:01:38'),
(50, 'Guaraná Lata', 6.00, '2025-04-16 23:01:59'),
(51, 'Guaravita', 2.50, '2025-04-16 23:02:10'),
(52, 'Guaraviton', 6.00, '2025-04-16 23:02:28'),
(53, 'Água S.g 500ml', 3.00, '2025-04-16 23:03:04'),
(54, 'Água C.g 500ml', 3.50, '2025-04-16 23:03:30'),
(55, 'Brhama 600', 10.00, '2025-04-16 23:03:53'),
(56, 'Antarctica 600', 10.00, '2025-04-16 23:04:04'),
(57, 'Henineken Long', 8.00, '2025-04-16 23:04:23'),
(58, 'Stella Long', 8.00, '2025-04-16 23:04:39'),
(59, 'Coca-cola Zero 2l', 13.50, '2025-04-17 20:31:57'),
(60, 'Mista - Torresminho + Linguiça + Queijo C.', 40.00, '2025-04-18 00:19:57');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `papel` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `senha`, `papel`) VALUES
(1, 'loja', '$2y$10$4/X/9C9XWWjRIfDYD3IbHe90e2Ef9IxGWXpRIy0/lMWTnuH7g1dci', 'admin'),
(3, 'atendente1', '$2y$10$hSLdZtuDGdvf9f1nFbU8Hek7Kq.cXXrqLTmu9SDWvxYipc4rfBcey', 'admin'),
(4, 'admin', '123456', 'admin'),
(5, 'Vivati', '*3218558BD4B879AB4E1E005E990992B6AAB441BC', 'admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comandas`
--
ALTER TABLE `comandas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `idx_comanda_aberta` (`aberta`);

--
-- Índices de tabela `itens`
--
ALTER TABLE `itens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_item_comanda` (`comanda_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `idx_produto_nome` (`nome`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens`
--
ALTER TABLE `itens`
  ADD CONSTRAINT `itens_ibfk_1` FOREIGN KEY (`comanda_id`) REFERENCES `comandas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
