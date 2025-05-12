-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 08/03/2025 às 01:33
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

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
(136, 1, 1, 5, 'fechada', '2024-12-18 17:15:22', '2024-12-18 20:15:28'),
(137, 2, 1, 2, 'fechada', '2024-12-25 14:04:11', '2025-01-23 17:26:57'),
(138, 3, 1, 2, 'fechada', '2024-12-25 14:04:50', '2024-12-25 11:08:28'),
(139, 4, 1, 2, 'aberta', '2025-01-23 20:26:17', NULL);

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
  `atendido` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `comanda_id`, `produto`, `descricao`, `quantidade`, `preco`, `criado_em`, `atendido`) VALUES
(50, 136, 'Porção 10 Salgadinhos Festa', 'misto', 1, 5.00, '2024-12-18 18:39:15', 1),
(51, 137, 'Porção 10 Salgadinhos Festa', 'bbbb', 1, 5.00, '2024-12-25 14:06:34', 1);

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
(18, 'Porção 10 Salgadinhos Festa', 5.00, '2024-12-18 18:38:47'),
(19, 'Coca 2l', 12.00, '2024-12-27 19:03:59'),
(20, '5 Bolinho de Bacalhau', 6.50, '2024-12-27 19:05:59'),
(21, '5 B. Camarão', 4.00, '2024-12-27 19:06:31'),
(22, '5 B. Sirí', 4.00, '2024-12-27 19:06:49'),
(23, '5 D. Provolone', 4.00, '2024-12-27 19:07:05'),
(24, '5 M. Churros', 4.00, '2024-12-27 19:07:21'),
(25, '10 Salgadinho 1 Guarav.', 6.50, '2024-12-27 19:07:58'),
(26, 'Guaravita', 2.50, '2024-12-27 19:08:13'),
(27, '100 Salg. Cong.', 35.00, '2024-12-27 19:09:06'),
(28, '40 B. Bacalhau Cong.', 35.00, '2024-12-27 19:09:25'),
(29, 'Coca Lata', 5.00, '2024-12-27 19:09:39'),
(30, 'Guaraná Ant.', 11.00, '2024-12-27 19:09:56'),
(31, 'Picolé', 3.00, '2024-12-27 19:10:14');

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
(1, 'Vivati', 'Jvb@142420', 'admin'),
(3, 'atendente1', '$2y$10$hSLdZtuDGdvf9f1nFbU8Hek7Kq.cXXrqLTmu9SDWvxYipc4rfBcey', 'admin'),
(4, 'admin', '$2y$10$nB5tNWbYVGZg1ueB8PNsDefljscqW/MYh9g.y/rZP1YTRKSWAuHcO', 'admin');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
