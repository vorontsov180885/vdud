-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.19 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              11.0.0.5958
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных vdud
CREATE DATABASE IF NOT EXISTS `vdud` ;
USE `vdud`;

-- Дамп структуры для таблица vdud.donats
CREATE TABLE IF NOT EXISTS `donats` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `project_id` int unsigned DEFAULT NULL,
  `name` text,
  `date` date DEFAULT NULL,
  `price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=UTF8MB4;

-- Дамп данных таблицы vdud.donats: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `donats` DISABLE KEYS */;
/*!40000 ALTER TABLE `donats` ENABLE KEYS */;

-- Дамп структуры для таблица vdud.projects
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text,
  `required_price` float DEFAULT NULL,
  `done_price` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 ;

-- Дамп данных таблицы vdud.projects: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` (`id`, `name`, `required_price`, `done_price`) VALUES
	(1, 'Сбор средств на первую цель', 111, 42),
	(2, 'Сбор средств на вторую цель', 222, 54),
	(3, 'Сбор средств на третью цель', 333, 61);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
