-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.21-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table scooter_db.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table scooter_db.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT '',
  `last_name` varchar(50) DEFAULT '',
  `code_word` varchar(50) DEFAULT '',
  `password` varchar(50) DEFAULT '',
  `phone` varchar(50) DEFAULT '',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for procedure scooter_db.get_status_complete
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_status_complete`(
	IN `order_id` INT


)
BEGIN
  SELECT CASE WHEN (select  @count:= count(repair_id) from repairs where repairs.`order_id` = order_id AND status = 'Y') <
                 (select  @count:= count(repair_id) from repairs where repairs.`order_id` = order_id )
                  THEN false ELSE true END AS completed;
END//
DELIMITER ;

-- Dumping structure for procedure scooter_db.get_status_if_started
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_status_if_started`(
	IN `order_id` INT

)
BEGIN
  SELECT CASE WHEN (select  @count:= count(repair_id) from repairs where repairs.`order_id` = order_id AND status = 'Y') > 0
                  THEN true ELSE false END AS started;

END//
DELIMITER ;

-- Dumping structure for table scooter_db.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scooter_type` varchar(50) NOT NULL DEFAULT '',
  `status` enum('IN QUEUE','JOB STARTED','WAITING FOR CUSTOMER RESPONSE','JOB COMPLETED') NOT NULL DEFAULT 'IN QUEUE',
  `staff_id` int(4) NOT NULL DEFAULT '0',
  `customer_id` int(4) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table scooter_db.repairs
CREATE TABLE IF NOT EXISTS `repairs` (
  `repair_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(500) NOT NULL DEFAULT '',
  `price` int(11) NOT NULL DEFAULT '0',
  `status` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`repair_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table scooter_db.staff
CREATE TABLE IF NOT EXISTS `staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL DEFAULT '',
  `last_name` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`staff_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
