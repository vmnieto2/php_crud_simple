
CREATE DATABASE /*!32312 IF NOT EXISTS*/`almacen` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `almacen`;

/*Table structure for table `categorias` */

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `categorias` */

INSERT  INTO `categorias`(`id`,`nombre`) VALUES 
(1,'Electrodoméstico'),
(2,'Alimento'),
(3,'Ropa'),
(4,'Higiene'),
(5,'Belleza'),
(6,'Limpieza');

/*Table structure for table `productos` */

DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(50) NOT NULL,
  `nombre` VARCHAR(100) NOT NULL,
  `id_categoria` BIGINT(20) NOT NULL,
  `precio` DOUBLE(10,2) NOT NULL,
  `cantidad` INT(10) NOT NULL,
  `img` TEXT DEFAULT NULL,
  `estado` TINYINT(1) NOT NULL DEFAULT 1,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `productos` */

INSERT  INTO `productos`(`id`,`codigo`,`nombre`,`id_categoria`,`precio`,`cantidad`,`img`,`estado`,`fecha_creacion`) VALUES 
(1,'ES2','Escoba',6,5000.00,50,'uploads/66e4eae2056fa_escoba.jpg',1,'2024-09-13 20:39:53'),
(2,'TR1','Trapero',6,3500.00,50,'uploads/66e4eed38a984_trapero.jpg',1,'2024-09-13 21:02:59'),
(3,'NV1','nevera',1,2500000.00,10,'uploads/66e4ef5c49165_nevera.jpg',1,'2024-09-13 21:05:16'),
(4,'CS1','Camisa',3,20000.00,20,'uploads/66e4fa087b3aa_camisa.jpg',1,'2024-09-13 21:50:48'),
(5,'ZP1','zapatos',3,150000.00,5,'uploads/66e508eba5045_zapatos.jpg',1,'2024-09-13 22:54:19');


/*Table structure for table `logs` */

DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `id_producto` BIGINT(20) NOT NULL,
  `modo` VARCHAR(20) NOT NULL,
  `cantidad` INT(10) NOT NULL,
  `fecha_creacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  KEY `id_producto` (`id_producto`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON UPDATE CASCADE
) ENGINE=INNODB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `logs` */

INSERT  INTO `logs`(`id`,`id_producto`,`modo`,`cantidad`,`fecha_creacion`) VALUES 
(1,1,'CREANDO',100,'2024-09-13 20:39:53'),
(2,1,'ACTUALIZANDO',50,'2024-09-13 20:59:36'),
(3,2,'CREANDO',50,'2024-09-13 21:02:59'),
(4,2,'ACTUALIZANDO',49,'2024-09-13 21:03:07'),
(5,2,'ACTUALIZANDO',50,'2024-09-13 21:03:20'),
(6,3,'CREANDO',20,'2024-09-13 21:05:16'),
(7,3,'ACTUALIZANDO',10,'2024-09-13 21:05:35'),
(8,4,'CREANDO',30,'2024-09-13 21:50:48'),
(9,4,'ACTUALIZANDO',10,'2024-09-13 22:03:28'),
(10,4,'ACTUALIZANDO',20,'2024-09-13 22:18:01'),
(11,5,'CREANDO',5,'2024-09-13 22:54:19');
