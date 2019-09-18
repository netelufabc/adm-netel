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


-- Dumping database structure for netel_adm
CREATE DATABASE IF NOT EXISTS `netel_adm` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `netel_adm`;

-- Dumping structure for table netel_adm.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.roles: ~5 rows (approximately)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
REPLACE INTO `roles` (`id`, `title`, `description`) VALUES
	(1, 'SystemAdmin', 'System Administrators'),
	(2, 'NetelAdministrativo', 'Administrativo Netel'),
	(3, 'CoordenadorUAB', 'Coordenado de Projeto UAB'),
	(4, 'AssistenteUAB', 'Assistente de Projeto UAB'),
	(5, 'TutorUAB', 'Tutor de Projeto UAB');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

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

-- Dumping data for table netel_adm.uab_project: ~3 rows (approximately)
/*!40000 ALTER TABLE `uab_project` DISABLE KEYS */;
REPLACE INTO `uab_project` (`id`, `project_number`, `title`, `description`, `create_time`) VALUES
	(1, '666', 'Projeto Teste', 'projeto para testes bwoah', '2019-08-28 15:05:03'),
	(2, '674587', 'asshole', 'fgfdg', '2019-08-29 09:56:42'),
	(3, 'eteyerty5', 'yet367gv3767', '1111111111115435345345345', '2019-08-29 09:56:53'),
	(26, 'rgf256', 'adfgdfhh', 'mmmmmmmmmmmmmmmmmmmmmmmmvbcyheyhye', '2019-08-29 11:03:16');
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.user: ~3 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
REPLACE INTO `user` (`id`, `login`, `name`, `email`, `create_time`, `created_by`) VALUES
	(1, 'gustavo.castilho', 'Gustavo', 'gustavo.castilho@ufabc.edu.br', '2019-08-27 13:07:47', NULL),
	(2, 'tertwte', 'eytwert', NULL, '2019-08-29 11:33:47', 'gustavo.castilho'),
	(5, 'asdsddds', 'rtyrtyrty', 'gustac@gmail.com', '2019-08-29 11:36:50', 'gustavo.castilho'),
	(6, 'tutor.um', 'Tutor um', 'tutor.um@uiaydiuaf', '2019-09-02 13:55:19', 'gustavo.castilho'),
	(7, 'tutor.dois', 'Tutor Dois', 'dkjsfhjkg@kjk', '2019-09-02 13:55:49', 'gustavo.castilho'),
	(8, 'assit.um', 'Assitente Um', 'assitum@iujioj', '2019-09-02 13:56:15', 'gustavo.castilho'),
	(9, 'assist.dois', 'Assit Dois', 'jkshjk@iouioiuoiou', '2019-09-02 13:56:38', 'gustavo.castilho'),
	(10, 'fabio.akira', 'Fábio Akira', 'baitola@master.gay', '2019-09-03 10:47:05', NULL);
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
  KEY `FK_adm_user_role_user` (`user_id`),
  KEY `FK_adm_user_role_roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table netel_adm.user_role: ~5 rows (approximately)
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
REPLACE INTO `user_role` (`id`, `user_id`, `role_id`, `project_id`, `create_time`, `created_by`) VALUES
	(1, 1, 2, 0, '2019-09-02 13:42:32', NULL),
	(2, 2, 3, 1, '2019-09-02 13:42:32', NULL),
	(3, 5, 3, 2, '2019-09-02 13:42:32', NULL),
	(4, 2, 3, 3, '2019-09-02 13:42:32', NULL),
	(5, 5, 3, 26, '2019-09-02 13:42:32', NULL),
	(6, 8, 4, 1, '2019-09-02 14:27:28', NULL),
	(8, 7, 5, 1, '2019-09-02 15:46:44', NULL),
	(11, 9, 4, 1, '2019-09-02 17:37:20', NULL);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
