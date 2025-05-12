-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 14/12/2024 às 21:23
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
(129, 1, 1, 2, 'fechada', '2024-12-13 21:31:42', '2024-12-14 16:51:21'),
(130, 2, 1, 1, 'fechada', '2024-12-13 22:10:49', '2024-12-14 16:54:42'),
(131, 3, 1, 6, 'fechada', '2024-12-13 22:14:30', '2024-12-14 16:58:45'),
(132, 4, 1, 4, 'fechada', '2024-12-14 19:57:56', '2024-12-14 16:58:53'),
(133, 5, 1, 2, 'aberta', '2024-12-14 20:03:47', NULL);

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
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens`
--

INSERT INTO `itens` (`id`, `comanda_id`, `produto`, `descricao`, `quantidade`, `preco`, `criado_em`) VALUES
(36, 129, 'Coca 2l', 'Descrição', 5, 60.00, '2024-12-13 21:33:48'),
(37, 129, 'Salgadinhos', 'Coxinha', 1, 5.00, '2024-12-13 21:36:05'),
(38, 129, 'Salgadinhos', 'Descrição', 1, 5.00, '2024-12-13 21:41:40'),
(39, 130, 'Salgadinhos', 'coxi, bolinha,', 5, 25.00, '2024-12-13 22:11:25'),
(40, 130, 'Bacalhau', 'Descrição', 1, 15.00, '2024-12-13 22:11:50'),
(41, 131, 'Churros', 'Descrição', 1, 15.00, '2024-12-13 22:22:18'),
(42, 131, 'Salgadinhos', 'Descrição', 10, 50.00, '2024-12-14 19:57:03'),
(43, 132, 'guaravita', 'Descrição', 1, 3.00, '2024-12-14 19:58:34'),
(44, 133, 'Churros', 'Descrição', 1, 15.00, '2024-12-14 20:04:09');

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
(1, 'yuy', 10.00, '2024-12-11 00:24:20'),
(2, 'Coca 2l', 12.00, '2024-12-11 19:56:04'),
(9, 'Salgadinhos', 5.00, '2024-12-11 20:12:43'),
(10, 'Bacalhau', 15.00, '2024-12-11 20:13:55'),
(12, 'siri', 15.00, '2024-12-11 20:15:28'),
(13, 'Churros', 15.00, '2024-12-11 20:19:00'),
(14, 'Picolé', 3.00, '2024-12-11 20:19:46'),
(15, 'guaravita', 3.00, '2024-12-12 15:24:18'),
(16, 'Guarana ant.', 11.00, '2024-12-14 20:02:19'),
(17, 'BRHAMA 600', 11.00, '2024-12-14 20:02:37');

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
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comandas`
--
ALTER TABLE `comandas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT de tabela `itens`
--
ALTER TABLE `itens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
