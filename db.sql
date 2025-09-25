-- Tabela de usuários (para login)
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL
);

-- Insere o usuário admin inicial
INSERT INTO usuarios (username, senha) VALUES ('admin', '1234');

-- Tabela de pratos
CREATE TABLE pratos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  preco DECIMAL(10,2) NOT NULL,
  tempo_preparo VARCHAR(50),
  ativo BOOLEAN DEFAULT 1
);

-- Insere alguns pratos de exemplo
INSERT INTO pratos (nome, preco, tempo_preparo, ativo) VALUES
('Lasanha à Bolonhesa', 35.90, '45 min', 1),
('Risoto de Funghi', 45.50, '30 min', 1),
('Salmão Grelhado', 52.00, '25 min', 0);

