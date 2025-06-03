-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 02/06/2025 às 04:32
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `fmu_gym`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` enum('masculino','feminino','outro') DEFAULT NULL,
  `saude` text DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `treino_selecionado` varchar(20) DEFAULT NULL,
  `tipo_treino` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`id`, `nome`, `email`, `telefone`, `cpf`, `data_nascimento`, `genero`, `saude`, `senha`, `criado_em`, `treino_selecionado`, `tipo_treino`) VALUES
(1, 'Wellington Santana', 'wellingtox8@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$CO0YdeCJiWxm78NVPtzPrOFwBQUEffyvf4LgwWTMdHRtYNCv3P.xC', '2025-06-01 18:57:57', NULL, NULL),
(2, 'Wellington Caetano Santana', 'wellingtonx8@gmail.com', '11981543990', '444.555.555-55', '2025-06-28', 'masculino', NULL, '$2y$10$qdq238FfH4CRKrBh9xBQku/L5gmITxvNnHuUd2lClR4Q0ivMHidUG', '2025-06-01 19:14:25', 'emagrecimento', NULL),
(3, 'Joao', 'joao@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$10$doEHlYW5tQaeIlC9UPfKm.aTR3VgKzHWXlntIhEwo5StRNIqH5zS6', '2025-06-01 22:02:34', NULL, NULL),
(4, 'Erik Felipe', 'erik@gmail.com', '(11) 99999-9999', '555.555.555-55', '2025-06-17', 'masculino', NULL, '$2y$10$HreZv3cWe7A8ksx/Nm4aeO12uWvbYknwKbCd5FOfzunxUfHLYDieS', '2025-06-01 22:55:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `planos`
--

CREATE TABLE `planos` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) DEFAULT NULL,
  `tipo` enum('mensal','trimestral','anual','experimental') NOT NULL,
  `pagamento` varchar(20) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `cartao_nome` varchar(100) DEFAULT NULL,
  `cartao_num` varchar(20) DEFAULT NULL,
  `cartao_validade` varchar(5) DEFAULT NULL,
  `cartao_cvv` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `planos`
--

INSERT INTO `planos` (`id`, `aluno_id`, `tipo`, `pagamento`, `data_inicio`, `data_fim`, `status`, `ativo`, `cartao_nome`, `cartao_num`, `cartao_validade`, `cartao_cvv`) VALUES
(1, 2, 'experimental', 'cartao', '2025-06-01', '0000-00-00', 'ativo', 0, '', '', '', ''),
(2, 2, 'experimental', 'cartao', '2025-06-01', '0000-00-00', 'ativo', 0, 'w', '', '', ''),
(3, 2, '', 'pix', '2025-06-01', '0000-00-00', 'ativo', 0, NULL, NULL, NULL, NULL),
(4, 2, '', 'pix', '2025-06-01', '0000-00-00', 'ativo', 0, NULL, NULL, NULL, NULL),
(5, 2, 'experimental', 'nenhum', '2025-06-02', '0000-00-00', 'ativo', 0, NULL, NULL, NULL, NULL),
(6, 2, 'experimental', 'nenhum', '2025-06-02', '0000-00-00', 'ativo', 0, NULL, NULL, NULL, NULL),
(7, 2, 'experimental', 'nenhum', '2025-06-02', '0000-00-00', 'ativo', 0, NULL, NULL, NULL, NULL),
(8, 2, 'experimental', 'nenhum', '2025-06-02', '0000-00-00', 'ativo', 0, NULL, NULL, NULL, NULL),
(9, 4, '', 'cartao', '2025-06-02', '0000-00-00', 'ativo', 0, 'erik', '5555555555555555', '2025-', '000'),
(10, 2, '', 'cartao', '2025-06-02', '0000-00-00', 'ativo', 0, 'w', '5555555555555555', '2025-', '000'),
(11, 2, '', 'cartao', '2025-06-02', '0000-00-00', 'ativo', 0, 'w', '5555555555555555', '2025-', '500'),
(12, 2, '', 'cartao', '2025-06-02', '0000-00-00', 'ativo', 0, 'w', '0000000000000000', '2025-', '000'),
(13, 2, '', 'cartao', '2025-06-02', '0000-00-00', 'ativo', 0, 'w', '0000000000000000', '2025-', '000'),
(14, 2, 'anual', 'cartao', '2025-06-02', '0000-00-00', 'ativo', 0, 'w', '0000000000000000', '2025-', '000'),
(15, 2, 'trimestral', 'pix', '2025-06-02', '0000-00-00', 'ativo', 0, '', '', '', ''),
(16, 2, 'trimestral', 'pix', '2025-06-02', '0000-00-00', 'ativo', 0, '', '', '', ''),
(17, 2, 'trimestral', 'pix', '2025-06-02', '0000-00-00', 'ativo', 0, '', '', '', ''),
(18, 2, 'experimental', 'nenhum', '2025-06-02', '0000-00-00', 'ativo', 0, NULL, NULL, NULL, NULL),
(19, 2, 'trimestral', 'pix', '2025-06-02', '0000-00-00', 'ativo', 0, '', '', '', ''),
(20, 4, 'experimental', 'nenhum', '2025-06-02', '0000-00-00', 'ativo', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinos`
--

CREATE TABLE `treinos` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) DEFAULT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `treinos`
--

INSERT INTO `treinos` (`id`, `aluno_id`, `titulo`, `descricao`, `criado_em`) VALUES
(1, 2, 'condicionamento', 'Dia A – Cardio + Core\n - Corrida leve na esteira – 10 min\n - Burpees – 3x15\n - Prancha abdominal – 3x30s\n - Abdominal bicicleta – 3x20\n - Agachamento com salto – 3x15\n - Corrida HIIT (20s rápido / 40s lento) – 10 min\n\nDia B – Corpo todo funcional\n - Polichinelos – 3x30\n - Flexão de braço – 3x15\n - Agachamento com kettlebell – 3x15\n - Mountain climbers – 3x30s\n - Tríceps banco – 3x15\n - Subida em banco – 3x15 (cada perna)\n\nDia C – Resistência muscular\n - Remada no TRX ou elástico – 3x12\n - Agachamento isométrico (na parede) – 3x30s\n - Abdominal prancha lateral – 3x30s cada lado\n - Afundo alternado – 3x12 cada perna\n - Flexão com apoio de joelhos – 3x15\n - Corrida leve para recuperação – 10 min\n\n', '2025-06-02 00:19:11'),
(2, 2, 'emagrecimento', 'Dia A – Aeróbico Intervalado + Abdômen\n - Corrida HIIT (30s forte / 30s leve) – 15 min\n - Prancha abdominal – 3x40s\n - Abdominal oblíquo – 3x20\n - Agachamento com salto – 3x15\n - Abdominal remador – 3x20\n\nDia B – Circuito Funcional Total\n - Polichinelos – 3x40\n - Flexão de braço – 3x15\n - Afundo com peso corporal – 3x12 por perna\n - Mountain climbers – 3x30s\n - Tríceps banco – 3x15\n - Corrida leve – 10 min\n\nDia C – Musculação para definição\n - Agachamento com halteres – 3x15\n - Remada baixa – 3x15\n - Leg Press – 3x12\n - Desenvolvimento de ombro – 3x15\n - Rosca direta – 3x15\n - Bicicleta ergométrica – 15 min\n\n', '2025-06-02 00:27:48'),
(3, 4, 'condicionamento', 'Dia A – Cardio + Core\n - Corrida leve na esteira – 10 min\n - Burpees – 3x15\n - Prancha abdominal – 3x30s\n - Abdominal bicicleta – 3x20\n - Agachamento com salto – 3x15\n - Corrida HIIT (20s rápido / 40s lento) – 10 min\n\nDia B – Corpo todo funcional\n - Polichinelos – 3x30\n - Flexão de braço – 3x15\n - Agachamento com kettlebell – 3x15\n - Mountain climbers – 3x30s\n - Tríceps banco – 3x15\n - Subida em banco – 3x15 (cada perna)\n\nDia C – Resistência muscular\n - Remada no TRX – 3x12\n - Agachamento isométrico – 3x30s\n - Abdominal prancha lateral – 3x30s\n - Afundo alternado – 3x12\n - Flexão com apoio – 3x15\n - Corrida leve – 10 min\n\n', '2025-06-02 02:23:15');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `planos`
--
ALTER TABLE `planos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- Índices de tabela `treinos`
--
ALTER TABLE `treinos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `planos`
--
ALTER TABLE `planos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `treinos`
--
ALTER TABLE `treinos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `planos`
--
ALTER TABLE `planos`
  ADD CONSTRAINT `planos_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`);

--
-- Restrições para tabelas `treinos`
--
ALTER TABLE `treinos`
  ADD CONSTRAINT `treinos_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
