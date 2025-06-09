/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 10.4.32-MariaDB : Database - grab-system
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`grab-system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `grab-system`;

/*Table structure for table `cars` */

DROP TABLE IF EXISTS `cars`;

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `driver` varchar(300) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`car_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `cars` */

insert  into `cars`(`car_id`,`plate_number`,`model`,`driver`,`contact_number`,`user_id`,`date`,`status`) values 
(22,'KAU1968','Toyota Hilux','wayne barb','09544744872',NULL,'2025-06-08',NULL);

/*Table structure for table `transactions` */

DROP TABLE IF EXISTS `transactions`;

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `car_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pick_up` text DEFAULT NULL,
  `drop_off` text DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `pickup_time` time DEFAULT NULL,
  `dropoff_date` date DEFAULT NULL,
  `dropoff_time` time DEFAULT NULL,
  `distance` varchar(255) DEFAULT NULL,
  `fare` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `transactions` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(500) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `plain_password` varchar(255) DEFAULT NULL,
  `driver_status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`user_id`,`first_name`,`last_name`,`contact_number`,`username`,`password`,`role`,`car_id`,`plain_password`,`driver_status`) values 
(36,'Buff','MeUp','123456789','admin','$2y$10$00ejVGPrJzugd5MMkTf7b.Xue4Wi7ebcoXvsxvVtds8zAE6irh4t6','admin',NULL,'123456789',NULL),
(37,'kenneth','gamolo','09602201644','kenneth','$2y$10$8aVOgt9iL5u/V5LvgNCtvuBP2pzVCcq0yWNPR3v9hgejF6L2uHqXW','user',NULL,'12345',NULL),
(38,'wayne','barb','09544744872','waynebarb','$2y$10$to53xlGQ.rJwBrORIE45A..W2cQ2wj4HNXKY02haH1G5xS1yTBZdK','driver',22,'12345','accepted');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
