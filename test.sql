/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - test
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`test` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;

USE `test`;

/*Table structure for table `articulos` */

DROP TABLE IF EXISTS `articulos`;

CREATE TABLE `articulos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_categoria` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `stock` int(20) NOT NULL DEFAULT 1,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `articulos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `articulos` */

insert  into `articulos`(`id`,`id_categoria`,`nombre`,`descripcion`,`stock`,`estado`,`fecha_creacion`) values 
(1,1,'nevera','bajo consumo',4,1,'2024-08-05 12:47:48'),
(3,1,'televisor','40 pulgadas',10,0,'2024-08-05 13:19:39'),
(4,3,'camisa','tela lino',50,1,'2024-08-05 13:31:11'),
(5,2,'manzana','roja y fresca',30,1,'2024-08-05 14:04:42'),
(6,5,'crema de rostro','hidrata la piel',20,0,'2024-08-05 14:05:11'),
(7,4,'cepillo ','para los dientes',11,1,'2024-08-05 14:31:45');

/*Table structure for table `categorias` */

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `categorias` */

insert  into `categorias`(`id`,`nombre`) values 
(1,'Electrodoméstico'),
(2,'Alimento'),
(3,'Ropa'),
(4,'Higiene'),
(5,'Belleza');

/*Table structure for table `historial_mantenimiento` */

DROP TABLE IF EXISTS `historial_mantenimiento`;

CREATE TABLE `historial_mantenimiento` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_articulo` bigint(20) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(100) NOT NULL,
  `costo` float(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_articulo` (`id_articulo`),
  CONSTRAINT `historial_mantenimiento_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

/*Data for the table `historial_mantenimiento` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
