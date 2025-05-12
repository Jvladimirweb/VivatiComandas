-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS bar CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE bar;

-- Tabela de comandas
CREATE TABLE IF NOT EXISTS comandas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero INT NOT NULL UNIQUE,
    aberta BOOLEAN DEFAULT TRUE,
    mesa int,
    status ENUM('aberta', 'fechada') NOT NULL DEFAULT 'aberta',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de itens
CREATE TABLE IF NOT EXISTS itens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comanda_id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comanda_id) REFERENCES comandas(id) ON DELETE CASCADE
);

-- Tabela de produtos (opcional para cadastrar produtos fixos)
CREATE TABLE IF NOT EXISTS produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL UNIQUE,
    preco DECIMAL(10, 2) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_comanda_aberta ON comandas (aberta);
CREATE INDEX idx_item_comanda ON itens (comanda_id);
CREATE INDEX idx_produto_nome ON produtos (nome);