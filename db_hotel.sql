/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 8.0.36-28 : Database - perhotelan_islandsame
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`perhotelan_islandsame` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `perhotelan_islandsame`;

/*Table structure for table `jabatan` */

DROP TABLE IF EXISTS `jabatan`;

CREATE TABLE `jabatan` (
  `id_jabatan` int NOT NULL AUTO_INCREMENT,
  `nama_jabatan` varchar(50) NOT NULL,
  `level` int NOT NULL,
  PRIMARY KEY (`id_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `jabatan` */

insert  into `jabatan`(`id_jabatan`,`nama_jabatan`,`level`) values 
(1,'General Manager',1),
(2,'Administrator',2),
(3,'Resepsionis',3),
(4,'Housekeeping',4);

/*Table structure for table `kamar` */

DROP TABLE IF EXISTS `kamar`;

CREATE TABLE `kamar` (
  `id_kamar` int NOT NULL AUTO_INCREMENT,
  `nomor_kamar` varchar(10) NOT NULL,
  `id_tipe` int NOT NULL,
  `status` enum('available','occupied','dirty') DEFAULT 'available',
  PRIMARY KEY (`id_kamar`),
  UNIQUE KEY `nomor_kamar` (`nomor_kamar`),
  KEY `fk_kamar_tipe` (`id_tipe`),
  CONSTRAINT `fk_kamar_tipe` FOREIGN KEY (`id_tipe`) REFERENCES `tipe_kamar` (`id_tipe`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `kamar` */

insert  into `kamar`(`id_kamar`,`nomor_kamar`,`id_tipe`,`status`) values 
(1,'101',1,'occupied'),
(2,'102',1,'available'),
(4,'767',1,'available'),
(6,'00001',6,'occupied'),
(7,'00002',6,'occupied'),
(8,'00003',6,'occupied');

/*Table structure for table `log_housekeeping` */

DROP TABLE IF EXISTS `log_housekeeping`;

CREATE TABLE `log_housekeeping` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_kamar` int NOT NULL,
  `status_sebelum` enum('dirty','occupied','available') NOT NULL DEFAULT 'dirty',
  `status_sesudah` enum('dirty','occupied','available') NOT NULL DEFAULT 'available',
  `waktu_log` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `fk_log_user` (`id_user`),
  KEY `fk_log_kamar` (`id_kamar`),
  CONSTRAINT `fk_log_kamar` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`) ON DELETE CASCADE,
  CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `log_housekeeping` */

insert  into `log_housekeeping`(`id_log`,`id_user`,`id_kamar`,`status_sebelum`,`status_sesudah`,`waktu_log`) values 
(1,1,2,'dirty','available','2025-12-10 01:40:14'),
(3,1,1,'occupied','dirty','2025-12-10 02:37:42'),
(4,1,2,'occupied','dirty','2025-12-10 02:37:43'),
(5,1,4,'occupied','dirty','2025-12-10 02:37:44'),
(6,1,4,'occupied','dirty','2025-12-11 03:47:05'),
(7,1,1,'dirty','available','2025-12-11 03:47:18'),
(8,1,4,'occupied','dirty','2025-12-11 03:48:34'),
(10,1,4,'dirty','available','2025-12-12 00:34:18'),
(11,1,1,'occupied','dirty','2025-12-12 07:01:38'),
(12,1,1,'dirty','available','2025-12-12 07:02:35'),
(13,1,2,'occupied','dirty','2025-12-17 07:37:05'),
(14,12,2,'dirty','available','2025-12-19 04:25:52');

/*Table structure for table `lost_found` */

DROP TABLE IF EXISTS `lost_found`;

CREATE TABLE `lost_found` (
  `id_barang` int NOT NULL AUTO_INCREMENT,
  `id_kamar` int NOT NULL,
  `id_user_penemu` int NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `deskripsi` text,
  `foto_barang` varchar(255) DEFAULT NULL,
  `tgl_ditemukan` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` enum('disimpan','diambil') DEFAULT 'disimpan',
  PRIMARY KEY (`id_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `lost_found` */

insert  into `lost_found`(`id_barang`,`id_kamar`,`id_user_penemu`,`nama_barang`,`deskripsi`,`foto_barang`,`tgl_ditemukan`,`status`) values 
(1,2,7,'handphone','ini handphone merk gatau apa males banget gua','1765456484_logo.png','2025-12-11 12:34:45','disimpan');

/*Table structure for table `master_layanan` */

DROP TABLE IF EXISTS `master_layanan`;

CREATE TABLE `master_layanan` (
  `id_layanan` int NOT NULL AUTO_INCREMENT,
  `nama_layanan` varchar(100) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `kategori` enum('makanan','minuman','jasa') NOT NULL,
  PRIMARY KEY (`id_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `master_layanan` */

insert  into `master_layanan`(`id_layanan`,`nama_layanan`,`harga_satuan`,`satuan`,`kategori`) values 
(1,'Nasi Goreng',25000.00,'porsi','makanan'),
(2,'Laundry',15000.00,'kg','jasa'),
(4,'tambah kasur',50000.00,'1','jasa');

/*Table structure for table `riwayat_pembayaran` */

DROP TABLE IF EXISTS `riwayat_pembayaran`;

CREATE TABLE `riwayat_pembayaran` (
  `id_pembayaran` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_user` int NOT NULL,
  `jumlah_bayar` decimal(10,2) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tgl_bayar` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pembayaran`),
  KEY `fk_bayar_trx` (`id_transaksi`),
  KEY `fk_bayar_user` (`id_user`),
  CONSTRAINT `fk_bayar_trx` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  CONSTRAINT `fk_bayar_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `riwayat_pembayaran` */

insert  into `riwayat_pembayaran`(`id_pembayaran`,`id_transaksi`,`id_user`,`jumlah_bayar`,`keterangan`,`tgl_bayar`) values 
(10,10,1,705000.00,'Pelunasan','2025-12-11 01:20:07'),
(11,14,1,45210000.00,'Pelunasan','2025-12-11 02:53:25'),
(12,15,1,300000.00,'Deposit / DP','2025-12-11 03:37:42'),
(13,15,1,100000.00,'Deposit / DP','2025-12-11 03:46:36'),
(14,15,1,305000.00,'Pelunasan','2025-12-11 03:46:51'),
(15,16,1,705000.00,'Pelunasan','2025-12-11 03:48:23'),
(16,17,1,705000.00,'Pelunasan','2025-12-12 07:01:23'),
(17,18,1,2115000.00,'Pelunasan','2025-12-17 07:36:49'),
(18,22,1,100.00,'Deposit / DP','2025-12-19 04:13:43'),
(19,22,1,100.00,'Deposit / DP','2025-12-19 04:13:48');

/*Table structure for table `tamu` */

DROP TABLE IF EXISTS `tamu`;

CREATE TABLE `tamu` (
  `id_tamu` int NOT NULL AUTO_INCREMENT,
  `nama_tamu` varchar(100) NOT NULL,
  `jenis_identitas` enum('KTP','SIM','PASSPORT') DEFAULT 'KTP',
  `no_identitas` varchar(50) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tamu`),
  UNIQUE KEY `no_identitas` (`no_identitas`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tamu` */

insert  into `tamu`(`id_tamu`,`nama_tamu`,`jenis_identitas`,`no_identitas`,`no_hp`,`email`,`created_at`) values 
(1,'hadiar putra nu ganteng','KTP','3213528374923','0842423497','hadiar@gmail.com','2025-12-09 12:45:19'),
(2,'ndut','KTP','324829358945839468396','0854574575',NULL,'2025-12-09 08:44:16'),
(3,'adi','KTP','0249304955','08084504508',NULL,'2025-12-09 09:29:10'),
(4,'renza','KTP','321243432882','0831379891344',NULL,'2025-12-10 01:29:24'),
(5,'tes','KTP','312323348545','0831323837828',NULL,'2025-12-17 14:30:04'),
(6,'bowo','KTP','0000000000000000','083148438324',NULL,'2025-12-19 03:54:58'),
(7,'bowo','KTP','0000','0831000',NULL,'2025-12-19 04:09:54');

/*Table structure for table `tipe_kamar` */

DROP TABLE IF EXISTS `tipe_kamar`;

CREATE TABLE `tipe_kamar` (
  `id_tipe` int NOT NULL AUTO_INCREMENT,
  `nama_tipe` varchar(50) NOT NULL,
  `harga_dasar` decimal(10,2) NOT NULL,
  `kapasitas` int DEFAULT '2',
  `deskripsi` text,
  `foto_kamar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_tipe`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tipe_kamar` */

insert  into `tipe_kamar`(`id_tipe`,`nama_tipe`,`harga_dasar`,`kapasitas`,`deskripsi`,`foto_kamar`) values 
(1,'Standard Room',705000.00,2,'Kenyamanan esensial dengan desain modern. Pilihan tepat untuk istirahat berkualitas.','https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1000&q=80'),
(2,'Deluxe Room',905000.00,2,'Sentuhan kemewahan dengan area santai dan mini bar untuk kenyamanan maksimal.','https://images.unsplash.com/photo-1591088398332-8a7791972843?auto=format&fit=crop&w=1000&q=80'),
(4,'Superior Room',805000.00,2,'Ruang lebih luas dengan fasilitas tambahan untuk pengalaman menginap yang lebih memanjakan.','https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1000&q=80'),
(6,'Executive Room',1105000.00,2,'Kamar terbaik kami dengan pemandangan pool, bathtub, dan ruang kerja eksklusif.','https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1000&q=80');

/*Table structure for table `transaksi` */

DROP TABLE IF EXISTS `transaksi`;

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `no_invoice` varchar(50) NOT NULL,
  `id_user_resepsionis` int NOT NULL,
  `id_tamu` int NOT NULL,
  `nama_tamu` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `tgl_checkin` datetime NOT NULL,
  `tgl_checkout` datetime DEFAULT NULL,
  `total_tagihan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status_transaksi` enum('active','finished','batal') DEFAULT 'active',
  `status_bayar` enum('belum_bayar','dp','lunas') DEFAULT 'belum_bayar',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`),
  UNIQUE KEY `no_invoice` (`no_invoice`),
  KEY `fk_transaksi_user` (`id_user_resepsionis`),
  KEY `fk_transaksi_tamu` (`id_tamu`),
  CONSTRAINT `fk_transaksi_tamu` FOREIGN KEY (`id_tamu`) REFERENCES `tamu` (`id_tamu`),
  CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`id_user_resepsionis`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `transaksi` */

insert  into `transaksi`(`id_transaksi`,`no_invoice`,`id_user_resepsionis`,`id_tamu`,`nama_tamu`,`no_hp`,`tgl_checkin`,`tgl_checkout`,`total_tagihan`,`status_transaksi`,`status_bayar`,`created_at`) values 
(10,'INV-2512118088',1,3,'adi',NULL,'2025-12-11 02:18:00','2025-12-11 01:20:19',705000.00,'finished','lunas','2025-12-11 01:19:16'),
(11,'INV-2512115006',1,2,'ndut',NULL,'2025-12-11 02:21:00',NULL,1410000.00,'batal','belum_bayar','2025-12-11 01:21:52'),
(12,'INV-2512114977',1,2,'ndut',NULL,'2025-12-11 02:23:00',NULL,705000.00,'batal','belum_bayar','2025-12-11 01:23:42'),
(13,'INV-2512114642',1,1,'hadiar putra nu ganteng',NULL,'2025-12-11 02:27:00',NULL,705000.00,'batal','belum_bayar','2025-12-11 01:27:46'),
(14,'INV-2512117874',1,3,'adi',NULL,'2025-12-11 03:51:00','2025-12-11 02:53:56',45210000.00,'finished','lunas','2025-12-11 02:51:55'),
(15,'INV-2512112155',1,4,'renza',NULL,'2025-12-11 04:26:00','2025-12-11 03:47:04',705000.00,'finished','lunas','2025-12-11 03:26:39'),
(16,'INV-2512111099',1,4,'renza',NULL,'2025-12-11 04:26:00','2025-12-11 03:48:33',705000.00,'finished','lunas','2025-12-11 03:26:42'),
(17,'INV-2512113091',1,2,'ndut',NULL,'2025-12-11 04:48:00','2025-12-12 07:01:37',705000.00,'finished','lunas','2025-12-11 03:49:04'),
(18,'INV-2512118269',1,3,'adi',NULL,'2025-12-11 14:18:00','2025-12-17 07:37:03',2115000.00,'finished','lunas','2025-12-11 13:19:37'),
(19,'INV-2512125706',1,1,'hadiar putra nu ganteng',NULL,'2025-12-12 01:29:00',NULL,3315000.00,'batal','belum_bayar','2025-12-12 00:29:37'),
(20,'INV-2512173425',1,5,'tes',NULL,'2025-12-17 15:26:00',NULL,705000.00,'active','belum_bayar','2025-12-17 14:30:19'),
(21,'INV-2512194087',1,6,'bowo',NULL,'2025-12-19 04:52:00',NULL,3315000.00,'active','belum_bayar','2025-12-19 03:56:25'),
(22,'INV-2512193032',1,7,'bowo',NULL,'2025-12-19 05:08:00',NULL,6680000.00,'active','dp','2025-12-19 04:11:01');

/*Table structure for table `transaksi_kamar` */

DROP TABLE IF EXISTS `transaksi_kamar`;

CREATE TABLE `transaksi_kamar` (
  `id_detail_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_kamar` int NOT NULL,
  `harga_per_malam` decimal(10,2) NOT NULL,
  `durasi_malam` int NOT NULL DEFAULT '1',
  `subtotal_kamar` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detail_transaksi`),
  KEY `fk_detail_trx` (`id_transaksi`),
  KEY `fk_detail_kamar` (`id_kamar`),
  CONSTRAINT `fk_detail_kamar` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`),
  CONSTRAINT `fk_detail_trx` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `transaksi_kamar` */

insert  into `transaksi_kamar`(`id_detail_transaksi`,`id_transaksi`,`id_kamar`,`harga_per_malam`,`durasi_malam`,`subtotal_kamar`) values 
(21,10,1,705000.00,1,705000.00),
(22,11,1,705000.00,1,705000.00),
(23,11,2,705000.00,1,705000.00),
(24,12,2,705000.00,1,705000.00),
(25,13,1,705000.00,1,705000.00),
(26,14,1,705000.00,31,21855000.00),
(27,14,2,705000.00,31,21855000.00),
(28,15,4,705000.00,1,705000.00),
(29,16,4,705000.00,1,705000.00),
(30,17,1,705000.00,1,705000.00),
(31,18,2,705000.00,3,2115000.00),
(32,19,6,1105000.00,1,1105000.00),
(33,19,7,1105000.00,1,1105000.00),
(34,19,8,1105000.00,1,1105000.00),
(35,20,1,705000.00,1,705000.00),
(36,21,6,1105000.00,3,3315000.00),
(37,22,7,1105000.00,3,3315000.00),
(38,22,8,1105000.00,3,3315000.00);

/*Table structure for table `transaksi_layanan` */

DROP TABLE IF EXISTS `transaksi_layanan`;

CREATE TABLE `transaksi_layanan` (
  `id_detail_layanan` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_layanan` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_saat_ini` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `waktu_pesan` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_detail_layanan`),
  KEY `fk_layanan_trx` (`id_transaksi`),
  KEY `fk_layanan_master` (`id_layanan`),
  CONSTRAINT `fk_layanan_master` FOREIGN KEY (`id_layanan`) REFERENCES `master_layanan` (`id_layanan`),
  CONSTRAINT `fk_layanan_trx` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `transaksi_layanan` */

insert  into `transaksi_layanan`(`id_detail_layanan`,`id_transaksi`,`id_layanan`,`jumlah`,`harga_saat_ini`,`subtotal`,`waktu_pesan`) values 
(5,14,1,60,25000.00,1500000.00,'2025-12-11 02:53:05'),
(6,22,1,1,25000.00,25000.00,'2025-12-19 04:11:30'),
(7,22,1,1,25000.00,25000.00,'2025-12-19 04:42:37');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `id_jabatan` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `gender` enum('L','P') DEFAULT 'L',
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  KEY `fk_user_jabatan` (`id_jabatan`),
  CONSTRAINT `fk_user_jabatan` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`id_user`,`id_jabatan`,`username`,`password`,`nama_lengkap`,`gender`) values 
(1,1,'admin','$2y$10$ryo8x2m6PBqv/C5a2nkI6eoA5eAXXmadl8.C7qvq7gLqvBbXk4ac.','Administrator Utama','L'),
(11,3,'rc','$2y$10$cvaXbDC4AnibvvGJnspa4.lHZpwOlXl7Qa4tw2qwL.X5F5tj/QwJe','Hadiar Putra','L'),
(12,4,'hk','$2y$10$vxppzC9PaZe6nU2GKwSUDesBvf/Ictz8ybYEbYWgE0jixAvoviluW','suharta','P');

/*Table structure for table `v_guest_history` */

DROP TABLE IF EXISTS `v_guest_history`;

/*!50001 DROP VIEW IF EXISTS `v_guest_history` */;
/*!50001 DROP TABLE IF EXISTS `v_guest_history` */;

/*!50001 CREATE TABLE  `v_guest_history`(
 `id_transaksi` int ,
 `no_invoice` varchar(50) ,
 `tgl_checkin` datetime ,
 `tgl_checkout` datetime ,
 `status_transaksi` enum('active','finished','batal') ,
 `total_tagihan` decimal(10,2) ,
 `nama_tamu` varchar(100) ,
 `list_kamar` text ,
 `status_bayar` enum('belum_bayar','dp','lunas') 
)*/;

/*Table structure for table `v_guest_inhouse` */

DROP TABLE IF EXISTS `v_guest_inhouse`;

/*!50001 DROP VIEW IF EXISTS `v_guest_inhouse` */;
/*!50001 DROP TABLE IF EXISTS `v_guest_inhouse` */;

/*!50001 CREATE TABLE  `v_guest_inhouse`(
 `id_transaksi` int ,
 `no_invoice` varchar(50) ,
 `tgl_checkin` datetime ,
 `total_tagihan` decimal(10,2) ,
 `nama_tamu` varchar(100) ,
 `no_hp` varchar(20) ,
 `list_kamar` text ,
 `list_tipe` text ,
 `total_terbayar` decimal(32,2) ,
 `durasi` int ,
 `status_bayar` enum('belum_bayar','dp','lunas') 
)*/;

/*Table structure for table `v_hk_checkout_today` */

DROP TABLE IF EXISTS `v_hk_checkout_today`;

/*!50001 DROP VIEW IF EXISTS `v_hk_checkout_today` */;
/*!50001 DROP TABLE IF EXISTS `v_hk_checkout_today` */;

/*!50001 CREATE TABLE  `v_hk_checkout_today`(
 `id_transaksi` int ,
 `no_invoice` varchar(50) ,
 `tgl_checkin` datetime ,
 `total_tagihan` decimal(10,2) ,
 `nama_tamu` varchar(100) ,
 `no_hp` varchar(20) ,
 `list_kamar` text ,
 `list_tipe` text ,
 `total_terbayar` decimal(32,2) ,
 `durasi` int ,
 `status_bayar` enum('belum_bayar','dp','lunas') 
)*/;

/*Table structure for table `v_hk_dirty` */

DROP TABLE IF EXISTS `v_hk_dirty`;

/*!50001 DROP VIEW IF EXISTS `v_hk_dirty` */;
/*!50001 DROP TABLE IF EXISTS `v_hk_dirty` */;

/*!50001 CREATE TABLE  `v_hk_dirty`(
 `id_kamar` int ,
 `nomor_kamar` varchar(10) ,
 `status` enum('available','occupied','dirty') ,
 `nama_tipe` varchar(50) 
)*/;

/*Table structure for table `v_housekeeping_tasks` */

DROP TABLE IF EXISTS `v_housekeeping_tasks`;

/*!50001 DROP VIEW IF EXISTS `v_housekeeping_tasks` */;
/*!50001 DROP TABLE IF EXISTS `v_housekeeping_tasks` */;

/*!50001 CREATE TABLE  `v_housekeeping_tasks`(
 `id_kamar` int ,
 `nomor_kamar` varchar(10) ,
 `status` enum('available','occupied','dirty') 
)*/;

/*Table structure for table `v_invoice_list` */

DROP TABLE IF EXISTS `v_invoice_list`;

/*!50001 DROP VIEW IF EXISTS `v_invoice_list` */;
/*!50001 DROP TABLE IF EXISTS `v_invoice_list` */;

/*!50001 CREATE TABLE  `v_invoice_list`(
 `id_transaksi` int ,
 `no_invoice` varchar(50) ,
 `tgl_checkin` datetime ,
 `tgl_checkout` datetime ,
 `status_transaksi` enum('active','finished','batal') ,
 `status_bayar` enum('belum_bayar','dp','lunas') ,
 `nama_tamu` varchar(100) ,
 `no_hp` varchar(20) ,
 `nomor_kamar` text ,
 `total_tagihan` decimal(10,2) ,
 `total_terbayar` decimal(32,2) ,
 `sisa_tagihan` decimal(33,2) ,
 `tgl_estimasi_checkout` datetime 
)*/;

/*Table structure for table `v_kamar_list` */

DROP TABLE IF EXISTS `v_kamar_list`;

/*!50001 DROP VIEW IF EXISTS `v_kamar_list` */;
/*!50001 DROP TABLE IF EXISTS `v_kamar_list` */;

/*!50001 CREATE TABLE  `v_kamar_list`(
 `id_kamar` int ,
 `nomor_kamar` varchar(10) ,
 `status` enum('available','occupied','dirty') ,
 `id_tipe` int ,
 `nama_tipe` varchar(50) ,
 `harga_dasar` decimal(10,2) 
)*/;

/*Table structure for table `v_laporan_bulanan` */

DROP TABLE IF EXISTS `v_laporan_bulanan`;

/*!50001 DROP VIEW IF EXISTS `v_laporan_bulanan` */;
/*!50001 DROP TABLE IF EXISTS `v_laporan_bulanan` */;

/*!50001 CREATE TABLE  `v_laporan_bulanan`(
 `periode_bulan` varchar(7) ,
 `tahun` varchar(4) ,
 `jum_transaksi` bigint ,
 `total_omset` decimal(32,2) 
)*/;

/*Table structure for table `v_menu_layanan` */

DROP TABLE IF EXISTS `v_menu_layanan`;

/*!50001 DROP VIEW IF EXISTS `v_menu_layanan` */;
/*!50001 DROP TABLE IF EXISTS `v_menu_layanan` */;

/*!50001 CREATE TABLE  `v_menu_layanan`(
 `id_layanan` int ,
 `nama_layanan` varchar(100) ,
 `harga_satuan` decimal(10,2) ,
 `satuan` varchar(20) ,
 `kategori` enum('makanan','minuman','jasa') 
)*/;

/*Table structure for table `v_room_dashboard` */

DROP TABLE IF EXISTS `v_room_dashboard`;

/*!50001 DROP VIEW IF EXISTS `v_room_dashboard` */;
/*!50001 DROP TABLE IF EXISTS `v_room_dashboard` */;

/*!50001 CREATE TABLE  `v_room_dashboard`(
 `id_kamar` int ,
 `nomor_kamar` varchar(10) ,
 `id_tipe` int ,
 `nama_tipe` varchar(50) ,
 `harga_dasar` decimal(10,2) ,
 `status_fisik` enum('available','occupied','dirty') ,
 `id_transaksi` int ,
 `nama_tamu` varchar(100) ,
 `tgl_checkin` datetime ,
 `tgl_checkout` datetime ,
 `status` varchar(9) 
)*/;

/*Table structure for table `v_user_list` */

DROP TABLE IF EXISTS `v_user_list`;

/*!50001 DROP VIEW IF EXISTS `v_user_list` */;
/*!50001 DROP TABLE IF EXISTS `v_user_list` */;

/*!50001 CREATE TABLE  `v_user_list`(
 `id_user` int ,
 `username` varchar(50) ,
 `nama_lengkap` varchar(100) ,
 `gender` enum('L','P') ,
 `role` varchar(50) 
)*/;

/*View structure for view v_guest_history */

/*!50001 DROP TABLE IF EXISTS `v_guest_history` */;
/*!50001 DROP VIEW IF EXISTS `v_guest_history` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_guest_history` AS select `t`.`id_transaksi` AS `id_transaksi`,`t`.`no_invoice` AS `no_invoice`,`t`.`tgl_checkin` AS `tgl_checkin`,`t`.`tgl_checkout` AS `tgl_checkout`,`t`.`status_transaksi` AS `status_transaksi`,`t`.`total_tagihan` AS `total_tagihan`,`tm`.`nama_tamu` AS `nama_tamu`,group_concat(distinct `k`.`nomor_kamar` separator ', ') AS `list_kamar`,`t`.`status_bayar` AS `status_bayar` from (((`transaksi` `t` join `tamu` `tm` on((`t`.`id_tamu` = `tm`.`id_tamu`))) join `transaksi_kamar` `tk` on((`t`.`id_transaksi` = `tk`.`id_transaksi`))) join `kamar` `k` on((`tk`.`id_kamar` = `k`.`id_kamar`))) group by `t`.`id_transaksi` */;

/*View structure for view v_guest_inhouse */

/*!50001 DROP TABLE IF EXISTS `v_guest_inhouse` */;
/*!50001 DROP VIEW IF EXISTS `v_guest_inhouse` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_guest_inhouse` AS select `t`.`id_transaksi` AS `id_transaksi`,`t`.`no_invoice` AS `no_invoice`,`t`.`tgl_checkin` AS `tgl_checkin`,`t`.`total_tagihan` AS `total_tagihan`,`tm`.`nama_tamu` AS `nama_tamu`,`tm`.`no_hp` AS `no_hp`,group_concat(distinct `k`.`nomor_kamar` separator ', ') AS `list_kamar`,group_concat(distinct `tp`.`nama_tipe` separator ', ') AS `list_tipe`,coalesce(sum(`rp`.`jumlah_bayar`),0) AS `total_terbayar`,max(`tk`.`durasi_malam`) AS `durasi`,`t`.`status_bayar` AS `status_bayar` from (((((`transaksi` `t` join `tamu` `tm` on((`t`.`id_tamu` = `tm`.`id_tamu`))) join `transaksi_kamar` `tk` on((`t`.`id_transaksi` = `tk`.`id_transaksi`))) join `kamar` `k` on((`tk`.`id_kamar` = `k`.`id_kamar`))) join `tipe_kamar` `tp` on((`k`.`id_tipe` = `tp`.`id_tipe`))) left join `riwayat_pembayaran` `rp` on((`t`.`id_transaksi` = `rp`.`id_transaksi`))) where (`t`.`status_transaksi` = 'active') group by `t`.`id_transaksi` */;

/*View structure for view v_hk_checkout_today */

/*!50001 DROP TABLE IF EXISTS `v_hk_checkout_today` */;
/*!50001 DROP VIEW IF EXISTS `v_hk_checkout_today` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_hk_checkout_today` AS select `v_guest_inhouse`.`id_transaksi` AS `id_transaksi`,`v_guest_inhouse`.`no_invoice` AS `no_invoice`,`v_guest_inhouse`.`tgl_checkin` AS `tgl_checkin`,`v_guest_inhouse`.`total_tagihan` AS `total_tagihan`,`v_guest_inhouse`.`nama_tamu` AS `nama_tamu`,`v_guest_inhouse`.`no_hp` AS `no_hp`,`v_guest_inhouse`.`list_kamar` AS `list_kamar`,`v_guest_inhouse`.`list_tipe` AS `list_tipe`,`v_guest_inhouse`.`total_terbayar` AS `total_terbayar`,`v_guest_inhouse`.`durasi` AS `durasi`,`v_guest_inhouse`.`status_bayar` AS `status_bayar` from `v_guest_inhouse` where ((`v_guest_inhouse`.`tgl_checkin` + interval `v_guest_inhouse`.`durasi` day) <= curdate()) */;

/*View structure for view v_hk_dirty */

/*!50001 DROP TABLE IF EXISTS `v_hk_dirty` */;
/*!50001 DROP VIEW IF EXISTS `v_hk_dirty` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_hk_dirty` AS select `k`.`id_kamar` AS `id_kamar`,`k`.`nomor_kamar` AS `nomor_kamar`,`k`.`status` AS `status`,`t`.`nama_tipe` AS `nama_tipe` from (`kamar` `k` join `tipe_kamar` `t` on((`k`.`id_tipe` = `t`.`id_tipe`))) where (`k`.`status` = 'dirty') */;

/*View structure for view v_housekeeping_tasks */

/*!50001 DROP TABLE IF EXISTS `v_housekeeping_tasks` */;
/*!50001 DROP VIEW IF EXISTS `v_housekeeping_tasks` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_housekeeping_tasks` AS select `kamar`.`id_kamar` AS `id_kamar`,`kamar`.`nomor_kamar` AS `nomor_kamar`,`kamar`.`status` AS `status` from `kamar` where (`kamar`.`status` in ('dirty','occupied')) */;

/*View structure for view v_invoice_list */

/*!50001 DROP TABLE IF EXISTS `v_invoice_list` */;
/*!50001 DROP VIEW IF EXISTS `v_invoice_list` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_invoice_list` AS select `tr`.`id_transaksi` AS `id_transaksi`,`tr`.`no_invoice` AS `no_invoice`,`tr`.`tgl_checkin` AS `tgl_checkin`,`tr`.`tgl_checkout` AS `tgl_checkout`,`tr`.`status_transaksi` AS `status_transaksi`,`tr`.`status_bayar` AS `status_bayar`,`tm`.`nama_tamu` AS `nama_tamu`,`tm`.`no_hp` AS `no_hp`,group_concat(distinct `k`.`nomor_kamar` separator ', ') AS `nomor_kamar`,`tr`.`total_tagihan` AS `total_tagihan`,coalesce(sum(`rp`.`jumlah_bayar`),0) AS `total_terbayar`,(`tr`.`total_tagihan` - coalesce(sum(`rp`.`jumlah_bayar`),0)) AS `sisa_tagihan`,(`tr`.`tgl_checkin` + interval max(`tk`.`durasi_malam`) day) AS `tgl_estimasi_checkout` from ((((`transaksi` `tr` join `tamu` `tm` on((`tr`.`id_tamu` = `tm`.`id_tamu`))) join `transaksi_kamar` `tk` on((`tr`.`id_transaksi` = `tk`.`id_transaksi`))) join `kamar` `k` on((`tk`.`id_kamar` = `k`.`id_kamar`))) left join `riwayat_pembayaran` `rp` on((`tr`.`id_transaksi` = `rp`.`id_transaksi`))) group by `tr`.`id_transaksi` */;

/*View structure for view v_kamar_list */

/*!50001 DROP TABLE IF EXISTS `v_kamar_list` */;
/*!50001 DROP VIEW IF EXISTS `v_kamar_list` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_kamar_list` AS select `k`.`id_kamar` AS `id_kamar`,`k`.`nomor_kamar` AS `nomor_kamar`,`k`.`status` AS `status`,`t`.`id_tipe` AS `id_tipe`,`t`.`nama_tipe` AS `nama_tipe`,`t`.`harga_dasar` AS `harga_dasar` from (`kamar` `k` join `tipe_kamar` `t` on((`k`.`id_tipe` = `t`.`id_tipe`))) */;

/*View structure for view v_laporan_bulanan */

/*!50001 DROP TABLE IF EXISTS `v_laporan_bulanan` */;
/*!50001 DROP VIEW IF EXISTS `v_laporan_bulanan` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_laporan_bulanan` AS select date_format(`t`.`tgl_checkout`,'%Y-%m') AS `periode_bulan`,date_format(`t`.`tgl_checkout`,'%Y') AS `tahun`,count(`t`.`id_transaksi`) AS `jum_transaksi`,sum(`t`.`total_tagihan`) AS `total_omset` from `transaksi` `t` where ((`t`.`status_bayar` = 'lunas') and (`t`.`status_transaksi` = 'finished')) group by date_format(`t`.`tgl_checkout`,'%Y-%m'),date_format(`t`.`tgl_checkout`,'%Y') */;

/*View structure for view v_menu_layanan */

/*!50001 DROP TABLE IF EXISTS `v_menu_layanan` */;
/*!50001 DROP VIEW IF EXISTS `v_menu_layanan` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_menu_layanan` AS select `master_layanan`.`id_layanan` AS `id_layanan`,`master_layanan`.`nama_layanan` AS `nama_layanan`,`master_layanan`.`harga_satuan` AS `harga_satuan`,`master_layanan`.`satuan` AS `satuan`,`master_layanan`.`kategori` AS `kategori` from `master_layanan` */;

/*View structure for view v_room_dashboard */

/*!50001 DROP TABLE IF EXISTS `v_room_dashboard` */;
/*!50001 DROP VIEW IF EXISTS `v_room_dashboard` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_room_dashboard` AS select `k`.`id_kamar` AS `id_kamar`,`k`.`nomor_kamar` AS `nomor_kamar`,`k`.`id_tipe` AS `id_tipe`,`t`.`nama_tipe` AS `nama_tipe`,`t`.`harga_dasar` AS `harga_dasar`,`k`.`status` AS `status_fisik`,max(`tr`.`id_transaksi`) AS `id_transaksi`,max(`tm`.`nama_tamu`) AS `nama_tamu`,max(`tr`.`tgl_checkin`) AS `tgl_checkin`,max(`tr`.`tgl_checkout`) AS `tgl_checkout`,(case when (`k`.`status` = 'dirty') then 'dirty' when (max(`tr`.`id_transaksi`) is not null) then 'occupied' else 'available' end) AS `status` from ((((`kamar` `k` join `tipe_kamar` `t` on((`k`.`id_tipe` = `t`.`id_tipe`))) left join `transaksi_kamar` `tk` on((`k`.`id_kamar` = `tk`.`id_kamar`))) left join `transaksi` `tr` on(((`tk`.`id_transaksi` = `tr`.`id_transaksi`) and (`tr`.`status_transaksi` = 'active')))) left join `tamu` `tm` on((`tr`.`id_tamu` = `tm`.`id_tamu`))) group by `k`.`id_kamar`,`k`.`nomor_kamar`,`k`.`id_tipe`,`k`.`status`,`t`.`nama_tipe`,`t`.`harga_dasar` */;

/*View structure for view v_user_list */

/*!50001 DROP TABLE IF EXISTS `v_user_list` */;
/*!50001 DROP VIEW IF EXISTS `v_user_list` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`perhotelan_islandsame`@`%` SQL SECURITY DEFINER VIEW `v_user_list` AS select `u`.`id_user` AS `id_user`,`u`.`username` AS `username`,`u`.`nama_lengkap` AS `nama_lengkap`,`u`.`gender` AS `gender`,`j`.`nama_jabatan` AS `role` from (`users` `u` join `jabatan` `j` on((`u`.`id_jabatan` = `j`.`id_jabatan`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
