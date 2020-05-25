-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table netel_adm.classificacao_contratacao
CREATE TABLE IF NOT EXISTS `classificacao_contratacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solic` int(11) DEFAULT NULL,
  `posicao` tinyint(4) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `exigencias` varchar(255) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `situacao` varchar(255) DEFAULT 'Classificado',
  PRIMARY KEY (`id`),
  KEY `FK_classificacao_contratacao_solicitacao` (`id_solic`),
  CONSTRAINT `FK_classificacao_contratacao_solicitacao` FOREIGN KEY (`id_solic`) REFERENCES `solicitacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.classificacao_contratacao: ~13 rows (approximately)
/*!40000 ALTER TABLE `classificacao_contratacao` DISABLE KEYS */;
INSERT INTO `classificacao_contratacao` (`id`, `id_solic`, `posicao`, `nome`, `exigencias`, `descricao`, `situacao`) VALUES
	(13, 24, 1, 'nome cand 1', '1', '1', 'Classificado'),
	(14, 24, 2, 'nome cand 2', '2', '2', 'Classificado'),
	(15, 29, 1, 'nome1', 'dsfsdf', 'asdfsdfsd', 'Contratado'),
	(16, 29, 2, 'nome cand 2', 'rtgret', 'dgrdfg', 'Classificado'),
	(19, 21, 1, 'peao1', '                   qqq     ', 'qqqwerwerewr', 'Contratado'),
	(20, 21, 2, 'peao 2', '                        4434234', '9809078908790', 'Contratado'),
	(21, 30, 1, 'peao 1', '                        fgsdg', 'fgdfg', 'Contratado'),
	(22, 30, 2, 'peao 2', '53245', '345345', 'Contratado'),
	(23, 22, 1, 'clt', '                        fdsg', 'dgdf', 'Contratado'),
	(24, 31, 1, 'Fábio Akira Baitola', '                        adfasdf', 'dfasfd', 'Contratado'),
	(25, 31, 2, 'Paulo Magalhaes', '                        afrfasfdasd', 'sdfasdg', 'Classificado'),
	(26, 32, 1, 'Zé Mané Clt', 'sjkdfhskdjhflkhkj                        ', 'jkhkjlhk', 'Contratado'),
	(27, 32, 2, 'Otário da Silva', '                        52345235y', 'hdhghfgh', 'Classificado');
/*!40000 ALTER TABLE `classificacao_contratacao` ENABLE KEYS */;

-- Dumping structure for table netel_adm.config
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `info` varchar(255) NOT NULL DEFAULT '0',
  `value` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Tabela de configurações da aplicação.';

-- Dumping data for table netel_adm.config: ~4 rows (approximately)
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` (`id`, `name`, `info`, `value`) VALUES
	(2, 'max_date_tutor_upload', 'Data (dia) limite para que tutores e professores enviem o relatório de atividades.', '30'),
	(3, 'min_date_tutor_upload', 'Data (dia) de início para que tutores e professores enviem o relatório de atividades.', '1'),
	(4, 'max_date_coord_upload', 'Data (dia) limite para que o coordenador do projeto abra uma solicitação de pagamento de bolsas para tutores e professores.', '29'),
	(5, 'min_date_coord_upload', 'Data (dia) de início para que o coordenador do projeto abra uma solicitação de pagamento de bolsas para tutores e professores.', '2');
/*!40000 ALTER TABLE `config` ENABLE KEYS */;

-- Dumping structure for table netel_adm.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) DEFAULT NULL,
  `file_hash` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_hash` (`file_hash`),
  KEY `FK_files_mensagens` (`msg_id`),
  CONSTRAINT `FK_files_mensagens` FOREIGN KEY (`msg_id`) REFERENCES `mensagens` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.files: ~16 rows (approximately)
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` (`id`, `msg_id`, `file_hash`, `file_name`) VALUES
	(13, 46, '0ECjMF6nq3wKlZ5X', 'csv_bulkenroll_test.txt'),
	(14, 46, 'LqoVapQjE2NfdePR', 'model.mwb'),
	(15, 48, 'iFA1t4CZxGTIchqK', 'aaa.jpg'),
	(16, 49, 'M2t7y0fA3CUV6r9d', 'Cadastrando quest?es.docx'),
	(17, 49, 'X7rTYoVKUAnfQlse', 'dados tidia.xlsx'),
	(18, 51, 'VXWELoSyQP8GrIlK', 'tutor_relatorio_generico.pdf'),
	(19, 51, 'RH1CJqYfOAda9TQV', 'tutor_relatorio_generico.pdf'),
	(20, 52, '7Hd2rI8BcXhMmVFz', 'moodle.txt'),
	(21, 52, 'RSux9FWGLMr14dpo', 'moodle doc nte.docx'),
	(24, NULL, 'uSqH5YjdE8ZCbVK2', 'clip.png'),
	(27, NULL, '1xgfjQiWZHlqA9Vt', 'avoz7yE_460svvp9.webm'),
	(28, NULL, 'eTv0ij5oNm7kFRhS', 'decolar2.PNG'),
	(29, NULL, 'gcadnmXzHlZNIO9C', 'moodle.txt'),
	(33, NULL, 'vD0F1k3lpadExINO', 'valvulas.pdf'),
	(34, NULL, 'iu0fMSTcDCIb6QhR', 'tutor_relatorio_generico.pdf'),
	(35, NULL, '9X6nIMorh0DBTpEd', '1_MODELO_CSV.csv'),
	(36, NULL, 'tsnC1uYr87QxGgR2', 'Para Testar.tsl');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Dumping structure for table netel_adm.mensagens
CREATE TABLE IF NOT EXISTS `mensagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int(11) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `FK_mensagens_solicitacao` (`solicitacao_id`),
  KEY `FK_mensagens_user` (`created_by`),
  CONSTRAINT `FK_mensagens_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacao` (`id`),
  CONSTRAINT `FK_mensagens_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.mensagens: ~51 rows (approximately)
/*!40000 ALTER TABLE `mensagens` DISABLE KEYS */;
INSERT INTO `mensagens` (`id`, `solicitacao_id`, `mensagem`, `created_by`, `created_at`) VALUES
	(2, 1, 'mensagem de teste da solicitacao id 1', 1, '2019-09-12 17:08:22'),
	(3, 1, 'mensagem mais nova', 8, '2019-09-12 17:24:31'),
	(4, 1, 'msg mais nova 2', 1, '2019-09-13 11:37:05'),
	(5, 1, 'asdfgafdgadfg', 1, '2019-09-13 11:39:23'),
	(6, 4, 'teste', 1, '2019-09-13 11:41:53'),
	(7, 7, 'mensgaemsmrsnjfhsgush', 1, '2019-09-13 15:15:18'),
	(8, 1, 'Prezado camarada, favor abrir a solicitação corretamente, seu animal.', 1, '2019-09-16 14:06:35'),
	(9, 1, 'hjfdgjgjdafg ', 1, '2019-09-19 14:34:40'),
	(10, 1, 'teste again', 1, '2019-09-20 10:24:38'),
	(11, 1, 'fdadfgdfg', 1, '2019-09-20 10:26:31'),
	(12, 1, 'fechar', 1, '2019-09-20 10:45:21'),
	(13, 4, 'cancelado', 1, '2019-09-20 10:49:35'),
	(14, 15, 'cancelando solicitacao por que eu quero', 1, '2019-09-20 11:05:39'),
	(15, 12, 'dgsghfgh', 1, '2019-09-20 11:08:24'),
	(16, 7, 'fghfgh', 1, '2019-09-20 11:08:59'),
	(17, 6, 'hyrtyerty', 1, '2019-09-20 11:11:29'),
	(18, 16, 'iouiouio', 1, '2019-09-20 11:13:36'),
	(19, 1, 'fecha', 1, '2019-09-20 11:29:21'),
	(20, 10, 'fgddf fgdfj dgfdf', 1, '2019-10-09 13:54:34'),
	(21, 20, 'fghfghgdfgdfgsdfsdfg', 1, '2019-10-09 16:20:00'),
	(22, 13, 'msg tetse', 1, '2019-10-10 14:01:52'),
	(23, 1, 'asshole', 1, '2019-10-10 14:16:06'),
	(24, 1, 'mothafoca', 1, '2019-10-10 14:23:20'),
	(25, 1, 'iurioerutpoiert', 1, '2019-10-10 14:28:39'),
	(26, 1, 'popopopop', 1, '2019-10-10 14:31:46'),
	(27, 1, 'nmnmnmnmnmnmnmn', 1, '2019-10-10 14:33:33'),
	(28, 1, 'Corpo da mensagem bwoah.\r\nMotherfucker yeaheyaehayeahey.', 1, '2019-10-10 14:39:31'),
	(29, 1, 'mjfdlkgjdfklgjdf\r\ndfjghdjkfgdfg\r\njaponeiz baitola', 1, '2019-10-10 14:42:07'),
	(30, 1, 'teste fechar solic', 1, '2019-10-10 14:46:49'),
	(31, 1, 'teste cancelar', 1, '2019-10-10 14:48:04'),
	(32, 1, 'asadgaafsdf', 1, '2019-10-14 13:49:20'),
	(33, 1, 'asadgaafsdf', 1, '2019-10-14 13:50:58'),
	(34, 1, 'tertert', 1, '2019-10-14 13:58:44'),
	(35, 1, 'tertertlhklkl', 1, '2019-10-14 14:00:01'),
	(36, 1, 'tertertlhklklpppppp', 1, '2019-10-14 14:00:48'),
	(37, 1, 'oooooooooooo', 1, '2019-10-14 14:08:13'),
	(38, 1, 'tryeryeryrty', 1, '2019-10-14 14:10:37'),
	(39, 1, 'bybybyby', 1, '2019-10-14 14:11:11'),
	(40, 1, 'aaaassssss', 1, '2019-10-14 14:42:26'),
	(41, 1, 'yyyytytyt', 1, '2019-10-14 14:43:49'),
	(42, 1, '2412341234', 1, '2019-10-14 14:46:50'),
	(43, 1, 'iuouiouyo', 1, '2019-10-14 14:49:02'),
	(44, 1, 'iopiopiopiop', 1, '2019-10-14 14:50:26'),
	(45, 23, 'msg anexo teste', 1, '2019-10-14 15:29:53'),
	(46, 24, 'anexo msg etxtessyuwgefkhsg', 1, '2019-10-14 15:33:11'),
	(47, 21, 'fgfgjhgfhjdg hjfghjdfgdfg', 1, '2019-10-15 16:01:34'),
	(48, 21, 'trwrtwyry', 1, '2019-10-15 16:02:05'),
	(49, 29, 'curriculos', 1, '2019-10-17 15:19:28'),
	(50, 29, 'asdfdsf', 1, '2019-10-18 15:38:59'),
	(51, 30, 'anexando curriculos dos candidatos', 1, '2019-10-30 10:37:02'),
	(52, 31, 'curriculos', 15, '2019-10-31 15:11:52');
/*!40000 ALTER TABLE `mensagens` ENABLE KEYS */;

-- Dumping structure for table netel_adm.pagamento_autonomo
CREATE TABLE IF NOT EXISTS `pagamento_autonomo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classificado` int(11) DEFAULT NULL,
  `parcela_num` int(11) DEFAULT NULL,
  `valor_pag` float DEFAULT NULL,
  `data_pag` date DEFAULT NULL,
  `status_pag` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_pagamento_autonomo_classificacao_contratacao` (`id_classificado`),
  CONSTRAINT `FK_pagamento_autonomo_classificacao_contratacao` FOREIGN KEY (`id_classificado`) REFERENCES `classificacao_contratacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.pagamento_autonomo: ~10 rows (approximately)
/*!40000 ALTER TABLE `pagamento_autonomo` DISABLE KEYS */;
INSERT INTO `pagamento_autonomo` (`id`, `id_classificado`, `parcela_num`, `valor_pag`, `data_pag`, `status_pag`) VALUES
	(1, 15, 1, 1000, '2019-10-21', 'Pago'),
	(16, 19, 1, 2000, '2019-10-28', 'Autorizado'),
	(17, 19, 2, 4000, '2019-10-30', 'Pago'),
	(18, 20, 1, 3000, '2019-10-28', 'Pago'),
	(19, 20, 2, 1000, '2019-10-29', 'Aguardando autorização'),
	(20, 20, 3, 2000, '2019-10-31', 'Aguardando autorização'),
	(27, 15, 2, 11000, '2019-12-01', 'Aguardando autorização'),
	(28, 21, 1, 6000, '2019-10-31', 'Pago'),
	(29, 22, 1, 6000, '2019-10-31', 'Aguardando autorização'),
	(33, 24, 1, 6000, '2019-11-30', 'Pago');
/*!40000 ALTER TABLE `pagamento_autonomo` ENABLE KEYS */;

-- Dumping structure for table netel_adm.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.roles: ~6 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `title`, `description`) VALUES
	(1, 'SystemAdmin', 'System Administrators'),
	(2, 'NetelAdministrativo', 'Administrativo Netel'),
	(3, 'CoordenadorUAB', 'Coordenado de Projeto UAB'),
	(4, 'AssistenteUAB', 'Assistente de Projeto UAB'),
	(5, 'TutorUAB', 'Tutor de Projeto UAB'),
	(6, 'DocenteUAB', 'Docente de Projeto UAB');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table netel_adm.solicitacao
CREATE TABLE IF NOT EXISTS `solicitacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `FK_solicitacao_uab_project` (`project_id`),
  KEY `FK_solicitacao_user` (`created_by`),
  CONSTRAINT `FK_solicitacao_uab_project` FOREIGN KEY (`project_id`) REFERENCES `uab_project` (`id`),
  CONSTRAINT `FK_solicitacao_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.solicitacao: ~24 rows (approximately)
/*!40000 ALTER TABLE `solicitacao` DISABLE KEYS */;
INSERT INTO `solicitacao` (`id`, `project_id`, `created_by`, `created_at`, `tipo`, `status`, `closed_at`, `closed_by`) VALUES
	(1, 1, 1, '2019-09-09 11:44:11', 'Encontro', 'Aberto', '2019-10-10 14:48:04', 1),
	(4, 1, 1, '2019-09-10 13:53:36', 'Servico', 'Cancelado', '2019-09-20 15:49:31', 1),
	(6, 1, 1, '2019-09-09 11:44:14', 'Compra', 'Fechado', '2019-09-20 16:11:29', 1),
	(7, 2, 1, '2019-09-09 11:44:15', 'Encontro', 'Cancelado', '2019-09-20 16:08:49', 1),
	(10, 1, 1, '2019-09-16 16:28:26', 'Encontro', 'Aberto', NULL, NULL),
	(12, 1, 1, '2019-09-10 15:58:11', 'Servico', 'Fechado', '2019-09-20 16:07:38', 1),
	(13, 1, 1, '2019-09-10 16:13:39', 'Compra', 'Aberto', NULL, NULL),
	(15, 1, 1, '2019-09-12 10:52:59', 'Bolsa', 'Aberto', '2019-09-20 16:05:36', NULL),
	(16, 3, 1, '2019-09-16 15:10:40', 'Servico', 'Fechado', '2019-09-20 11:13:33', 1),
	(20, 26, 1, '2019-10-04 14:58:29', 'Contratacao', 'Aberto', NULL, NULL),
	(21, 1, 1, '2019-10-04 15:17:00', 'Contratacao', 'Aberto', NULL, NULL),
	(22, 3, 1, '2019-10-04 16:14:49', 'Contratacao', 'Aberto', NULL, NULL),
	(23, 26, 1, '2019-10-07 13:40:18', 'Contratacao', 'Aberto', NULL, NULL),
	(24, 26, 1, '2019-10-07 13:54:51', 'Contratacao', 'Aberto', NULL, NULL),
	(25, 3, 1, '2019-10-09 14:40:52', 'Compra', 'Aberto', NULL, NULL),
	(26, 3, 1, '2019-10-09 14:51:58', 'Encontro', 'Aberto', NULL, NULL),
	(27, 26, 1, '2019-10-09 15:31:23', 'Encontro', 'Aberto', NULL, NULL),
	(28, 1, 1, '2019-10-16 16:43:15', 'Contratacao', 'Aberto', NULL, NULL),
	(29, 26, 1, '2019-10-17 15:17:57', 'Contratacao', 'Aberto', '2019-10-18 15:38:59', 1),
	(30, 1, 1, '2019-10-30 10:35:23', 'Contratacao', 'Aberto', NULL, NULL),
	(31, 26, 1, '2019-10-31 15:10:21', 'Contratacao', 'Aberto', NULL, NULL),
	(32, 1, 1, '2019-11-04 11:40:17', 'Contratacao', 'Aberto', NULL, NULL),
	(36, 26, 1, '2019-11-18 15:16:16', 'Bolsa', 'Aberto', NULL, NULL),
	(37, 26, 1, '2020-01-08 13:53:33', 'Bolsa', 'Aberto', NULL, NULL);
/*!40000 ALTER TABLE `solicitacao` ENABLE KEYS */;

-- Dumping structure for table netel_adm.solicitacao_bolsa
CREATE TABLE IF NOT EXISTS `solicitacao_bolsa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int(11) DEFAULT NULL,
  `mes_ano` date NOT NULL,
  `tutor_ou_docente` varchar(50) NOT NULL,
  `coord_report_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `solicitacao_id` (`solicitacao_id`),
  KEY `FK_solicitacao_bolsa_files` (`coord_report_id`),
  CONSTRAINT `FK_solicitacao_bolsa_files` FOREIGN KEY (`coord_report_id`) REFERENCES `files` (`id`),
  CONSTRAINT `FK_solicitacao_bolsa_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.solicitacao_bolsa: ~3 rows (approximately)
/*!40000 ALTER TABLE `solicitacao_bolsa` DISABLE KEYS */;
INSERT INTO `solicitacao_bolsa` (`id`, `solicitacao_id`, `mes_ano`, `tutor_ou_docente`, `coord_report_id`) VALUES
	(12, 15, '2019-11-01', 'tutor', NULL),
	(16, 36, '2019-10-01', 'tutor', 33),
	(17, 37, '2019-05-01', 'tutor', 35);
/*!40000 ALTER TABLE `solicitacao_bolsa` ENABLE KEYS */;

-- Dumping structure for table netel_adm.solicitacao_bolsista
CREATE TABLE IF NOT EXISTS `solicitacao_bolsista` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bolsista_id` int(11) NOT NULL,
  `solicitacao_bolsa_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `FK_solicitacao_bolsista_user` (`bolsista_id`),
  KEY `FK_solicitacao_bolsista_solicitacao_bolsa` (`solicitacao_bolsa_id`),
  CONSTRAINT `FK_solicitacao_bolsista_solicitacao_bolsa` FOREIGN KEY (`solicitacao_bolsa_id`) REFERENCES `solicitacao_bolsa` (`id`),
  CONSTRAINT `FK_solicitacao_bolsista_user` FOREIGN KEY (`bolsista_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.solicitacao_bolsista: ~7 rows (approximately)
/*!40000 ALTER TABLE `solicitacao_bolsista` DISABLE KEYS */;
INSERT INTO `solicitacao_bolsista` (`id`, `bolsista_id`, `solicitacao_bolsa_id`) VALUES
	(2, 7, 12),
	(3, 6, 12),
	(4, 5, 12),
	(11, 1, 16),
	(12, 10, 16),
	(13, 12, 16),
	(14, 1, 17);
/*!40000 ALTER TABLE `solicitacao_bolsista` ENABLE KEYS */;

-- Dumping structure for table netel_adm.solicitacao_compra
CREATE TABLE IF NOT EXISTS `solicitacao_compra` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int(11) NOT NULL,
  `item_compra` varchar(255) NOT NULL,
  `especificacao_compra` varchar(255) NOT NULL,
  `unidade_compra` varchar(50) NOT NULL,
  `quantidade_compra` int(11) NOT NULL,
  `valor_compra` float NOT NULL,
  `motivacao_compra` varchar(255) NOT NULL,
  `conexao_compra` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `solicitacao_id` (`solicitacao_id`),
  UNIQUE KEY `id` (`id`),
  CONSTRAINT `FK_solicitacao_compra_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.solicitacao_compra: ~3 rows (approximately)
/*!40000 ALTER TABLE `solicitacao_compra` DISABLE KEYS */;
INSERT INTO `solicitacao_compra` (`id`, `solicitacao_id`, `item_compra`, `especificacao_compra`, `unidade_compra`, `quantidade_compra`, `valor_compra`, `motivacao_compra`, `conexao_compra`) VALUES
	(1, 6, 'item de compra', '0', 'unidade', 24, 69, '0', '0'),
	(2, 13, 'TYRTY', 'RTYRTY', 'RTTRY', 535, 1.34, 'TY', 'TRYTRY'),
	(3, 25, 'compra rhsifhsfh', 'jhfdjkhafdgklj jkh', 'ass', 24, 0.5, 'assssss', 'dfjghjfldkhgdgh');
/*!40000 ALTER TABLE `solicitacao_compra` ENABLE KEYS */;

-- Dumping structure for table netel_adm.solicitacao_contratacao
CREATE TABLE IF NOT EXISTS `solicitacao_contratacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int(11) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `tempo_estimado` int(11) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `req_obrig` varchar(255) DEFAULT NULL,
  `req_desej` varchar(255) DEFAULT NULL,
  `dias_divulgacao` int(11) DEFAULT NULL,
  `tipo_selecao` varchar(255) DEFAULT NULL,
  `remuneracao_mensal` float DEFAULT NULL,
  `remuneracao_bruta` float DEFAULT NULL,
  `local_trabalho` varchar(255) DEFAULT NULL,
  `horario_trabalho` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_solicitacao_contratacao_solicitacao` (`solicitacao_id`),
  CONSTRAINT `FK_solicitacao_contratacao_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.solicitacao_contratacao: ~10 rows (approximately)
/*!40000 ALTER TABLE `solicitacao_contratacao` DISABLE KEYS */;
INSERT INTO `solicitacao_contratacao` (`id`, `solicitacao_id`, `tipo`, `status`, `titulo`, `quantidade`, `tempo_estimado`, `descricao`, `req_obrig`, `req_desej`, `dias_divulgacao`, `tipo_selecao`, `remuneracao_mensal`, `remuneracao_bruta`, `local_trabalho`, `horario_trabalho`) VALUES
	(1, 20, 'Autonomo', 'Aguardando Classificacao', 'autonomo peao', 2, 1234, 'jfkdghajdkfghkjhfd jkdlsgsd', 'rwetwertwer', 'twertwetr', 23, 'Curriculo,Entrevistas', NULL, 6000, NULL, NULL),
	(2, 21, 'Autonomo', 'Aguardando Netel', 'asdfasdf', 1234123, 12, '42242 rwerwerqwer rwerqwe', 'rqwerqwerwqer rw', 'qerwerqw', 32, 'Curriculo,Provas', NULL, 6000, NULL, NULL),
	(3, 22, 'Celetista', 'Aguardando Netel', 'c.ttlt', 4, NULL, 'rew rqerwerw rwe', 'rwer', 'werqwer', 32, 'Curriculo,Provas', 34123, NULL, 'Santo André', '134124'),
	(4, 23, 'Estagiario', 'Aguardando Curriculos', 'asdfasdfssssss', 3, NULL, 'gfhfghfgh fghfghfgh fghfh', 'fghfghf', '', 11, 'Curriculo,Provas,Entrevistas', NULL, NULL, 'Ribeirão Pires', '134124'),
	(5, 24, 'Bolsista', 'Aguardando Netel', 'bolsistaaaa', 1, NULL, 'erwwwwwwwwwwwwwww', '2221wwww', 'wwww', 9, 'Curriculo,Provas,Entrevistas', 24000, NULL, 'hell', '12'),
	(6, 28, 'Estagiario', 'Aguardando Classificacao', 'estag', 1, NULL, 'wfeaawf', 'asdfasdf', 'sdafasdf', 7, 'Curriculo,Provas,Entrevistas', NULL, NULL, 'Santo André', '134124'),
	(7, 29, 'Autonomo', 'Aguardando Netel', 'gdsgdfg', 43, 2345, 'trgfdsg', 'dfgdsfg', 'dsfgsd', 2345, 'Curriculo,Provas', NULL, 12000, NULL, NULL),
	(8, 30, 'Autonomo', 'Aguardando Netel', 'Autonomo', 2, 12, 'descrisjihjfjihg gfj', 'kfdjgkjç gj', 'kjklfjg', 7, 'Curriculo,Provas,Entrevistas', NULL, 6000, NULL, NULL),
	(9, 31, 'Autonomo', 'Aguardando Netel', 'contratacao autonomo', 2, 12, 'kjgkdfjkdfjgfjkl', 'dfkgljsdlkfg', 'sdfjkghjkgh', 7, 'Curriculo,Provas,Entrevistas', NULL, 6000, NULL, NULL),
	(10, 32, 'Celetista', 'Aguardando Netel', 'celetista teste', 2, NULL, 'afsdf', 'asdfasf', 'asdfasdf', 7, 'Curriculo,Provas,Entrevistas', 2000, NULL, 'Santo André', '8');
/*!40000 ALTER TABLE `solicitacao_contratacao` ENABLE KEYS */;

-- Dumping structure for table netel_adm.solicitacao_encontro
CREATE TABLE IF NOT EXISTS `solicitacao_encontro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int(11) NOT NULL,
  `polo` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fim` time DEFAULT NULL,
  `professores` varchar(255) DEFAULT NULL,
  `tutores` varchar(255) DEFAULT NULL,
  `quantidade_sala` int(11) DEFAULT NULL,
  `capacidade_sala` int(11) DEFAULT NULL,
  `quantidade_lab` int(11) DEFAULT NULL,
  `capacidade_lab` int(11) DEFAULT NULL,
  `auditorio` varchar(50) DEFAULT NULL,
  `equip` varchar(255) DEFAULT NULL,
  `obs` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `solicitacao_id` (`solicitacao_id`),
  UNIQUE KEY `id` (`id`),
  CONSTRAINT `FK_solicitacao_encontro_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.solicitacao_encontro: ~5 rows (approximately)
/*!40000 ALTER TABLE `solicitacao_encontro` DISABLE KEYS */;
INSERT INTO `solicitacao_encontro` (`id`, `solicitacao_id`, `polo`, `data`, `hora_inicio`, `hora_fim`, `professores`, `tutores`, `quantidade_sala`, `capacidade_sala`, `quantidade_lab`, `capacidade_lab`, `auditorio`, `equip`, `obs`) VALUES
	(1, 1, 'santo andré', '2019-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 7, 'mauá', '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 10, 'arujá', '2019-09-05', '04:43:00', '04:34:00', 'fasdfdsfg', 'asdgsdgfads', 123, 123, 3123, 23, '', '', ''),
	(5, 26, 'hell', '2019-01-01', '00:00:00', '23:59:00', 'dfasdfg', 'jhjkhjkh', 4, 3, 3, 3, 'nao', 'ttgr', 'hgfghfgh'),
	(6, 27, 'pqp', '2019-10-17', '22:01:00', '01:00:00', 'dfsdfsdf', 'sdfsdf', 324, 234, 234, 234, '324', '234234234', 'rewrwer');
/*!40000 ALTER TABLE `solicitacao_encontro` ENABLE KEYS */;

-- Dumping structure for table netel_adm.solicitacao_servico
CREATE TABLE IF NOT EXISTS `solicitacao_servico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `solicitacao_id` int(11) NOT NULL,
  `tipo_servico` varchar(255) DEFAULT NULL,
  `motivacao_servico` varchar(255) NOT NULL,
  `conexao_servico` varchar(255) NOT NULL,
  `prazo_servico` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `solicitacao_id` (`solicitacao_id`),
  UNIQUE KEY `id` (`id`),
  CONSTRAINT `FK_solicitacao_servico_solicitacao` FOREIGN KEY (`solicitacao_id`) REFERENCES `solicitacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.solicitacao_servico: ~3 rows (approximately)
/*!40000 ALTER TABLE `solicitacao_servico` DISABLE KEYS */;
INSERT INTO `solicitacao_servico` (`id`, `solicitacao_id`, `tipo_servico`, `motivacao_servico`, `conexao_servico`, `prazo_servico`) VALUES
	(1, 4, NULL, 'motivacao servico', 'descricao servico', '2019-09-09'),
	(2, 12, 'Tipo do serviiço', 'motivação do serviço', 'conexao do serviço', '2019-09-04'),
	(3, 16, 'gdfgsdfgsdfg', 'dsfgsdfg', 'dsfgsdfg', '2019-09-04');
/*!40000 ALTER TABLE `solicitacao_servico` ENABLE KEYS */;

-- Dumping structure for table netel_adm.tutor_report
CREATE TABLE IF NOT EXISTS `tutor_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tutor_id` int(11) NOT NULL DEFAULT '0',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `month_year` date NOT NULL,
  `file_id` int(11) NOT NULL DEFAULT '0',
  `upload_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(50) NOT NULL DEFAULT '0',
  `accept_or_deny_by` int(11) DEFAULT NULL,
  `accept_or_deny_at` datetime DEFAULT NULL,
  `deny_reason` varchar(255) NOT NULL DEFAULT '0',
  `solic_bolsa_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_tutor_report_user` (`tutor_id`),
  KEY `FK_tutor_report_uab_project` (`project_id`),
  KEY `FK_tutor_report_user_2` (`accept_or_deny_by`),
  KEY `FK_tutor_report_files` (`file_id`),
  KEY `FK_tutor_report_solicitacao_bolsa` (`solic_bolsa_id`),
  CONSTRAINT `FK_tutor_report_files` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`),
  CONSTRAINT `FK_tutor_report_solicitacao_bolsa` FOREIGN KEY (`solic_bolsa_id`) REFERENCES `solicitacao_bolsa` (`id`),
  CONSTRAINT `FK_tutor_report_uab_project` FOREIGN KEY (`project_id`) REFERENCES `uab_project` (`id`),
  CONSTRAINT `FK_tutor_report_user` FOREIGN KEY (`tutor_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_tutor_report_user_2` FOREIGN KEY (`accept_or_deny_by`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.tutor_report: ~9 rows (approximately)
/*!40000 ALTER TABLE `tutor_report` DISABLE KEYS */;
INSERT INTO `tutor_report` (`id`, `tutor_id`, `project_id`, `month_year`, `file_id`, `upload_date`, `status`, `accept_or_deny_by`, `accept_or_deny_at`, `deny_reason`, `solic_bolsa_id`) VALUES
	(4, 1, 26, '2019-05-00', 13, '2019-09-24 14:03:00', 'aprovado', 1, '2020-01-08 14:53:33', '', 17),
	(6, 1, 26, '2019-03-00', 24, '2019-09-25 16:37:16', 'aprovado', 10, '2019-09-25 16:37:11', '0', NULL),
	(7, 1, 26, '2019-10-00', 19, '2019-09-25 16:37:16', 'aprovado', 1, '2019-11-18 16:16:19', '', 16),
	(8, 1, 26, '2019-01-00', 16, '2019-09-24 14:03:00', 'negado', 10, '2019-09-24 14:03:35', 'permanente', NULL),
	(10, 1, 2, '2019-10-01', 24, '2019-11-07 12:51:05', 'pendente', NULL, NULL, '0', NULL),
	(13, 1, 2, '2019-09-01', 27, '2019-11-07 13:48:40', 'pendente', NULL, NULL, '0', NULL),
	(14, 12, 26, '2019-10-00', 20, '2019-09-24 14:03:00', 'aprovado', 1, '2019-11-18 16:17:09', '', 16),
	(15, 10, 26, '2019-10-00', 28, '2019-11-08 14:44:13', 'aprovado', 1, '2019-11-18 16:17:08', '', 16),
	(16, 10, 26, '2019-09-00', 29, '2019-11-08 14:44:40', 'reprovado', 1, '2019-11-18 17:34:25', 'errado', NULL),
	(17, 1, 2, '2020-04-01', 36, '2020-05-25 14:26:43', 'pendente', NULL, NULL, '0', NULL);
/*!40000 ALTER TABLE `tutor_report` ENABLE KEYS */;

-- Dumping structure for table netel_adm.uab_project
CREATE TABLE IF NOT EXISTS `uab_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_number` varchar(45) NOT NULL,
  `title` varchar(75) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `project_number_UNIQUE` (`project_number`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.uab_project: ~4 rows (approximately)
/*!40000 ALTER TABLE `uab_project` DISABLE KEYS */;
INSERT INTO `uab_project` (`id`, `project_number`, `title`, `description`, `create_time`) VALUES
	(1, '666', 'A Projeto Teste', 'projeto para testes bwoah', '2019-08-28 15:05:03'),
	(2, '674587', 'Projeto Qualé', 'numero 2 project', '2019-08-29 09:56:42'),
	(3, 'eteyerty5', 'Curso de PN', '1111111111115435345345345', '2019-08-29 09:56:53'),
	(26, 'ASDF1234', 'Projeto ASDF', 'mmmmmmmmmmmmmmmmmmmmmmmmvbcyheyhye', '2018-11-29 11:03:16');
/*!40000 ALTER TABLE `uab_project` ENABLE KEYS */;

-- Dumping structure for table netel_adm.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(30) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_UNIQUE` (`login`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.user: ~13 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `login`, `name`, `email`, `create_time`, `created_by`) VALUES
	(1, 'gustavo.castilho', 'Gustavo Uruguay Castilho', 'gustavo.castilho@ufabc.edu.br', '2019-08-27 13:07:47', NULL),
	(2, 'tertwte', 'eytwert', NULL, '2019-08-29 11:33:47', 'gustavo.castilho'),
	(5, 'asdsddds', 'rtyrtyrty', 'gustac@gmail.com', '2019-08-29 11:36:50', 'gustavo.castilho'),
	(6, 'tutor.um', 'Tutor um', 'tutor.um@uiaydiuaf', '2019-09-02 13:55:19', 'gustavo.castilho'),
	(7, 'tutor.dois', 'Tutor Dois', 'dkjsfhjkg@kjk', '2019-09-02 13:55:49', 'gustavo.castilho'),
	(8, 'assit.um', 'Assitente Um', 'assitum@iujioj', '2019-09-02 13:56:15', 'gustavo.castilho'),
	(9, 'assist.dois', 'Assit Dois', 'jkshjk@iouioiuoiou', '2019-09-02 13:56:38', 'gustavo.castilho'),
	(10, 'fabio.akira', 'Fábio Akira', 'baitola@master.gay', '2019-09-03 10:47:05', NULL),
	(11, 'professor.um', 'Professor', 'proeposek@kjss', '2019-09-11 14:39:17', 'gustavo.castilho'),
	(12, 'vitoria.mariana', 'Vitoria Mariana Maia', 'vitoria.mariana@ufabc.edu.br', '2019-09-11 14:53:37', 'gustavo.castilho'),
	(13, 'user.semrole', 'Usuário', 'Sem Role', '2019-09-17 15:18:34', 'gustavo.castilho'),
	(14, 'p.magalhaes', 'sfgsdf', 'gsdfg', '2019-09-17 15:21:39', 'gustavo.castilho'),
	(15, 'thais.braga', 'Thais Braga', 'thais.braga@ufabc.edu.br', '2019-09-18 14:22:04', NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

-- Dumping structure for table netel_adm.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT '0',
  `create_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_role_user` (`user_id`),
  KEY `FK_user_role_roles` (`role_id`),
  KEY `FK_user_role_uab_project` (`project_id`),
  CONSTRAINT `FK_user_role_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `FK_user_role_uab_project` FOREIGN KEY (`project_id`) REFERENCES `uab_project` (`id`),
  CONSTRAINT `FK_user_role_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.user_role: ~22 rows (approximately)
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` (`id`, `user_id`, `role_id`, `project_id`, `create_time`, `created_by`) VALUES
	(1, 1, 2, 1, '2019-09-02 13:42:32', NULL),
	(2, 1, 3, 1, '2019-09-02 13:42:32', NULL),
	(3, 5, 3, 2, '2019-09-02 13:42:32', NULL),
	(4, 2, 3, 3, '2019-09-02 13:42:32', NULL),
	(5, 1, 3, 26, '2019-09-02 13:42:32', NULL),
	(6, 8, 4, 1, '2019-09-02 14:27:28', NULL),
	(8, 7, 5, 1, '2019-09-02 15:46:44', NULL),
	(11, 9, 4, 1, '2019-09-02 17:37:20', NULL),
	(12, 11, 6, 1, '2019-09-11 14:39:27', NULL),
	(13, 10, 4, 1, '2019-09-11 15:18:05', NULL),
	(14, 2, 6, 1, '2019-09-11 15:28:59', NULL),
	(15, 6, 5, 1, '2019-09-11 15:29:34', NULL),
	(16, 5, 5, 1, '2019-09-11 15:30:57', NULL),
	(17, 1, 4, 3, '2019-09-17 15:01:55', NULL),
	(18, 1, 6, 3, '2019-09-17 15:02:10', NULL),
	(19, 1, 6, 26, '2019-09-17 15:02:30', NULL),
	(20, 1, 5, 26, '2018-09-17 15:02:35', NULL),
	(21, 15, 2, 1, '2019-09-18 14:22:42', NULL),
	(22, 1, 1, 1, '2019-09-18 14:30:45', NULL),
	(23, 1, 5, 2, '2019-08-24 15:10:55', NULL),
	(24, 10, 5, 26, '2019-01-08 11:04:45', NULL),
	(25, 12, 5, 26, '2019-11-08 12:04:50', NULL),
	(26, 10, 2, 1, '2020-05-25 13:31:02', 'gustavo.castilho');
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
