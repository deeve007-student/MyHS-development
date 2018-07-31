/*
SQLyog Ultimate
MySQL - 5.6.40 : Database - cs
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `appointment_patient` */

DROP TABLE IF EXISTS `appointment_patient`;

CREATE TABLE `appointment_patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `treatment_pack_id` int(11) DEFAULT NULL,
  `treatment_note_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `new_patient` tinyint(1) DEFAULT NULL,
  `patient_arrived` tinyint(1) DEFAULT NULL,
  `no_show` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8BF9E5C35E2BCA99` (`treatment_note_id`),
  KEY `IDX_8BF9E5C3E5B533F9` (`appointment_id`),
  KEY `IDX_8BF9E5C36B899279` (`patient_id`),
  KEY `IDX_8BF9E5C32989F1FD` (`invoice_id`),
  KEY `IDX_8BF9E5C361DF70DB` (`treatment_pack_id`),
  KEY `IDX_8BF9E5C32B18554A` (`owner_user_id`),
  CONSTRAINT `FK_8BF9E5C32989F1FD` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_8BF9E5C32B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_8BF9E5C35E2BCA99` FOREIGN KEY (`treatment_note_id`) REFERENCES `treatment_note` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_8BF9E5C361DF70DB` FOREIGN KEY (`treatment_pack_id`) REFERENCES `treatment_pack_credit` (`id`),
  CONSTRAINT `FK_8BF9E5C36B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_8BF9E5C3E5B533F9` FOREIGN KEY (`appointment_id`) REFERENCES `event_appointment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `appointment_patient` */

/*Table structure for table `attachment` */

DROP TABLE IF EXISTS `attachment`;

CREATE TABLE `attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `origin_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_795FD9BB2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_795FD9BB2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `attachment` */

/*Table structure for table `bulk_patient_list` */

DROP TABLE IF EXISTS `bulk_patient_list`;

CREATE TABLE `bulk_patient_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `filters` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_975191432B18554A` (`owner_user_id`),
  CONSTRAINT `FK_975191432B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `bulk_patient_list` */

/*Table structure for table `bulk_patients` */

DROP TABLE IF EXISTS `bulk_patients`;

CREATE TABLE `bulk_patients` (
  `list_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  PRIMARY KEY (`list_id`,`patient_id`),
  KEY `IDX_4AFE5BE93DAE168B` (`list_id`),
  KEY `IDX_4AFE5BE96B899279` (`patient_id`),
  CONSTRAINT `FK_4AFE5BE93DAE168B` FOREIGN KEY (`list_id`) REFERENCES `bulk_patient_list` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4AFE5BE96B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `bulk_patients` */

/*Table structure for table `calendar_settings` */

DROP TABLE IF EXISTS `calendar_settings`;

CREATE TABLE `calendar_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `work_day_start` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `work_day_end` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `time_interval` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E02780AD2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_E02780AD2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `calendar_settings` */

insert  into `calendar_settings`(`id`,`owner_user_id`,`work_day_start`,`work_day_end`,`time_interval`,`created_at`,`updated_at`) values 
(1,1,'09:00 AM','05:00 PM','15','2018-07-31 06:52:11','2018-07-31 06:52:11'),
(2,2,'09:00 AM','05:00 PM','15','2018-07-31 06:52:11','2018-07-31 06:52:11');

/*Table structure for table `cancel_reason` */

DROP TABLE IF EXISTS `cancel_reason`;

CREATE TABLE `cancel_reason` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `cancel_reason` */

insert  into `cancel_reason`(`id`,`name`,`position`,`created_at`,`updated_at`) values 
(1,'Feeling better',0,'2018-07-31 06:52:10','2018-07-31 06:52:10'),
(2,'Condition worse',1,'2018-07-31 06:52:10','2018-07-31 06:52:10'),
(3,'Sick',2,'2018-07-31 06:52:10','2018-07-31 06:52:10'),
(4,'Away',3,'2018-07-31 06:52:10','2018-07-31 06:52:10'),
(5,'Work',4,'2018-07-31 06:52:10','2018-07-31 06:52:10'),
(6,'Other',5,'2018-07-31 06:52:10','2018-07-31 06:52:10');

/*Table structure for table `communication_event` */

DROP TABLE IF EXISTS `communication_event`;

CREATE TABLE `communication_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_patient_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BA2FCB121389D807` (`recipient_patient_id`),
  KEY `IDX_BA2FCB122B18554A` (`owner_user_id`),
  CONSTRAINT `FK_BA2FCB121389D807` FOREIGN KEY (`recipient_patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_BA2FCB122B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `communication_event` */

/*Table structure for table `communication_type` */

DROP TABLE IF EXISTS `communication_type`;

CREATE TABLE `communication_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `by_sms` tinyint(1) DEFAULT NULL,
  `by_email` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `communication_type` */

insert  into `communication_type`(`id`,`name`,`translation`,`by_sms`,`by_email`) values 
(1,'Email','send_email',NULL,1),
(2,'SMS','send_sms',1,NULL);

/*Table structure for table `communications_settings` */

DROP TABLE IF EXISTS `communications_settings`;

CREATE TABLE `communications_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `from_email_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `appointment_creation_email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `appointment_creation_sms` longtext COLLATE utf8_unicode_ci NOT NULL,
  `new_patient_first_appointment_email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `appointment_reminder_email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `appointment_reminder_sms` longtext COLLATE utf8_unicode_ci NOT NULL,
  `recall_email_subject` longtext COLLATE utf8_unicode_ci NOT NULL,
  `recall_email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `no_show_subject` longtext COLLATE utf8_unicode_ci NOT NULL,
  `no_show_email` longtext COLLATE utf8_unicode_ci NOT NULL,
  `recall_sms` longtext COLLATE utf8_unicode_ci NOT NULL,
  `when_remainder_email_sent_day` int(11) NOT NULL,
  `when_remainder_email_sent_time` datetime NOT NULL,
  `when_remainder_sms_sent_day` int(11) NOT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `origin_file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `when_remainder_sms_sent_time` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9C025E982B18554A` (`owner_user_id`),
  CONSTRAINT `FK_9C025E982B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `communications_settings` */

insert  into `communications_settings`(`id`,`owner_user_id`,`from_email_address`,`appointment_creation_email`,`appointment_creation_sms`,`new_patient_first_appointment_email`,`appointment_reminder_email`,`appointment_reminder_sms`,`recall_email_subject`,`recall_email`,`no_show_subject`,`no_show_email`,`recall_sms`,`when_remainder_email_sent_day`,`when_remainder_email_sent_time`,`when_remainder_sms_sent_day`,`file_name`,`file_size`,`origin_file_name`,`when_remainder_sms_sent_time`,`created_at`,`updated_at`) values 
(1,1,'stepan@yudin.com','Dear {{ patientName }},\r\n\r\nThis email is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.\r\n\r\nRegards, \r\n{{ businessName }}','This message is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}. Thanks, {{ businessName }}','Dear {{ patientName }},\r\n\r\nThis email is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.\r\n\r\nRegards, \r\n{{ businessName }}','Dear {{ patientName }},\r\n\r\nThis email is to remind you of your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.\r\n\r\nPlease let us know if you will have any problems with attending this scheduled appointment.\r\n\r\nRegards, \r\n{{ businessName }}','This message is to remind you of your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}. Thanks, {{ businessName }}','Do you wanted to schedule an appointment with us soon?','Dear {{ patientName }},\r\n\r\nJust a quick message to see if you wanted to schedule an appointment with us soon? If so please let us know and we will book you in as soon as we can.\r\n\r\nRegards, \r\n{{ businessName }}','You missed your appointment','Dear {{ patientName }},\r\n\r\nYou missed your {{ treatmentType }} appointment on {{ appointmentDate }} at {{ appointmentTime }}. Please contact us so we can discuss and/or rebook.\r\n\r\nRegards,\r\n{{ businessName }}','Hi {{ patientName }}, just a quick message to see if you wanted to schedule an appointment with us soon? If so please let us know and we will book you in as soon as we can. Thanks, {{ businessName }}',1,'2018-07-31 08:00:00',1,NULL,NULL,NULL,'2018-07-31 08:00:00','2018-07-31 06:52:11','2018-07-31 06:52:11'),
(2,2,'david@rooney.com','Dear {{ patientName }},\r\n\r\nThis email is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.\r\n\r\nRegards, \r\n{{ businessName }}','This message is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}. Thanks, {{ businessName }}','Dear {{ patientName }},\r\n\r\nThis email is to confirm your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.\r\n\r\nRegards, \r\n{{ businessName }}','Dear {{ patientName }},\r\n\r\nThis email is to remind you of your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}.\r\n\r\nPlease let us know if you will have any problems with attending this scheduled appointment.\r\n\r\nRegards, \r\n{{ businessName }}','This message is to remind you of your appointment with {{ practitionerName }} on {{ appointmentDate }} at {{ appointmentTime }}. Thanks, {{ businessName }}','Do you wanted to schedule an appointment with us soon?','Dear {{ patientName }},\r\n\r\nJust a quick message to see if you wanted to schedule an appointment with us soon? If so please let us know and we will book you in as soon as we can.\r\n\r\nRegards, \r\n{{ businessName }}','You missed your appointment','Dear {{ patientName }},\r\n\r\nYou missed your {{ treatmentType }} appointment on {{ appointmentDate }} at {{ appointmentTime }}. Please contact us so we can discuss and/or rebook.\r\n\r\nRegards,\r\n{{ businessName }}','Hi {{ patientName }}, just a quick message to see if you wanted to schedule an appointment with us soon? If so please let us know and we will book you in as soon as we can. Thanks, {{ businessName }}',1,'2018-07-31 08:00:00',1,NULL,NULL,NULL,'2018-07-31 08:00:00','2018-07-31 06:52:11','2018-07-31 06:52:11');

/*Table structure for table `concession` */

DROP TABLE IF EXISTS `concession`;

CREATE TABLE `concession` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B517BD9D2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_B517BD9D2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `concession` */

insert  into `concession`(`id`,`owner_user_id`,`name`,`created_at`,`updated_at`) values 
(1,1,'Student','2018-07-31 06:52:12','2018-07-31 06:52:12'),
(2,1,'Pensioner','2018-07-31 06:52:12','2018-07-31 06:52:12'),
(3,2,'Student','2018-07-31 06:52:12','2018-07-31 06:52:12'),
(4,2,'Pensioner','2018-07-31 06:52:12','2018-07-31 06:52:12');

/*Table structure for table `concession_price` */

DROP TABLE IF EXISTS `concession_price`;

CREATE TABLE `concession_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `concession_price_owner_id` int(11) NOT NULL,
  `concession_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_70454A08C7E4FE2` (`concession_price_owner_id`),
  KEY `IDX_70454A04132BB14` (`concession_id`),
  KEY `IDX_70454A02B18554A` (`owner_user_id`),
  CONSTRAINT `FK_70454A02B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_70454A04132BB14` FOREIGN KEY (`concession_id`) REFERENCES `concession` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_70454A08C7E4FE2` FOREIGN KEY (`concession_price_owner_id`) REFERENCES `concession_price_owner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `concession_price` */

insert  into `concession_price`(`id`,`concession_price_owner_id`,`concession_id`,`owner_user_id`,`price`,`created_at`,`updated_at`) values 
(1,1,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(2,2,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(3,3,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(4,4,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(5,5,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(6,6,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(7,7,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(8,8,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(9,9,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(10,10,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(11,11,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(12,12,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(13,13,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(14,14,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(15,15,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(16,16,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(17,17,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(18,18,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(19,19,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(20,20,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(21,21,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(22,22,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(23,23,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(24,24,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(25,25,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(26,26,1,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(27,27,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(28,28,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(29,29,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(30,30,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(31,31,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(32,32,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(33,33,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(34,34,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(35,35,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(36,36,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(37,37,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(38,38,1,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(39,1,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(40,2,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(41,3,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(42,4,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(43,5,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(44,6,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(45,7,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(46,8,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(47,9,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(48,10,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(49,11,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(50,12,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(51,13,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(52,14,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(53,15,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(54,16,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(55,17,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(56,18,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(57,19,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(58,20,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(59,21,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(60,22,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(61,23,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(62,24,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(63,25,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(64,26,2,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(65,27,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(66,28,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(67,29,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(68,30,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(69,31,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(70,32,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(71,33,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(72,34,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(73,35,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(74,36,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(75,37,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(76,38,2,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(77,1,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(78,2,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(79,3,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(80,4,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(81,5,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(82,6,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(83,7,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(84,8,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(85,9,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(86,10,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(87,11,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(88,12,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(89,13,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(90,14,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(91,15,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(92,16,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(93,17,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(94,18,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(95,19,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(96,20,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(97,21,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(98,22,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(99,23,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(100,24,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(101,25,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(102,26,3,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(103,27,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(104,28,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(105,29,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(106,30,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(107,31,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(108,32,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(109,33,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(110,34,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(111,35,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(112,36,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(113,37,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(114,38,3,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(115,1,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(116,2,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(117,3,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(118,4,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(119,5,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(120,6,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(121,7,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(122,8,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(123,9,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(124,10,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(125,11,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(126,12,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(127,13,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(128,14,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(129,15,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(130,16,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(131,17,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(132,18,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(133,19,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(134,20,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(135,21,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(136,22,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(137,23,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(138,24,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(139,25,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(140,26,4,1,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(141,27,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(142,28,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(143,29,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(144,30,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(145,31,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(146,32,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(147,33,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(148,34,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(149,35,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(150,36,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(151,37,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(152,38,4,2,0.00,'2018-07-31 06:52:12','2018-07-31 06:52:12');

/*Table structure for table `concession_price_owner` */

DROP TABLE IF EXISTS `concession_price_owner`;

CREATE TABLE `concession_price_owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1BFAF3D72B18554A` (`owner_user_id`),
  CONSTRAINT `FK_1BFAF3D72B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `concession_price_owner` */

insert  into `concession_price_owner`(`id`,`owner_user_id`,`price`,`created_at`,`updated_at`,`discr`) values 
(1,1,25.00,'2018-07-31 06:52:11','2018-07-31 06:52:11','treatment'),
(2,2,25.00,'2018-07-31 06:52:11','2018-07-31 06:52:11','treatment'),
(3,1,80.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(4,1,55.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(5,1,13.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(6,1,9.70,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(7,1,19.95,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(8,1,6.95,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(9,2,80.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(10,2,55.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(11,2,13.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(12,2,9.70,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(13,2,19.95,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(14,2,6.95,'2018-07-31 06:52:12','2018-07-31 06:52:12','product'),
(15,1,75.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(16,1,105.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(17,1,95.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(18,1,91.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(19,1,95.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(20,1,105.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(21,1,95.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(22,1,92.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(23,1,98.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(24,1,58.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(25,1,98.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(26,1,58.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(27,2,75.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(28,2,105.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(29,2,95.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(30,2,91.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(31,2,95.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(32,2,105.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(33,2,95.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(34,2,92.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(35,2,98.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(36,2,58.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(37,2,98.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment'),
(38,2,58.00,'2018-07-31 06:52:12','2018-07-31 06:52:12','treatment');

/*Table structure for table `country` */

DROP TABLE IF EXISTS `country`;

CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iso_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `country` */

insert  into `country`(`id`,`name`,`iso_code`,`created_at`,`updated_at`) values 
(1,'Australia','AU','2018-07-31 06:52:10','2018-07-31 06:52:10');

/*Table structure for table `document` */

DROP TABLE IF EXISTS `document`;

CREATE TABLE `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_category_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `origin_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D8698A7690EFAA88` (`document_category_id`),
  KEY `IDX_D8698A762B18554A` (`owner_user_id`),
  CONSTRAINT `FK_D8698A762B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_D8698A7690EFAA88` FOREIGN KEY (`document_category_id`) REFERENCES `document_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `document` */

/*Table structure for table `document_category` */

DROP TABLE IF EXISTS `document_category`;

CREATE TABLE `document_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_category` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_898DE8982B18554A` (`owner_user_id`),
  CONSTRAINT `FK_898DE8982B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `document_category` */

insert  into `document_category`(`id`,`owner_user_id`,`name`,`default_category`,`created_at`,`updated_at`) values 
(1,1,'app.document_category.general',1,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(2,2,'app.document_category.general',1,'2018-07-31 06:52:11','2018-07-31 06:52:11');

/*Table structure for table `event` */

DROP TABLE IF EXISTS `event`;

CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_resource_id` int(11) NOT NULL,
  `event_recurrency_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `is_mirror` tinyint(1) DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA722BB4EA5` (`event_resource_id`),
  KEY `IDX_3BAE0AA712D71463` (`event_recurrency_id`),
  KEY `IDX_3BAE0AA72B18554A` (`owner_user_id`),
  CONSTRAINT `FK_3BAE0AA712D71463` FOREIGN KEY (`event_recurrency_id`) REFERENCES `event_recurrency` (`id`),
  CONSTRAINT `FK_3BAE0AA722BB4EA5` FOREIGN KEY (`event_resource_id`) REFERENCES `event_resource` (`id`),
  CONSTRAINT `FK_3BAE0AA72B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `event` */

/*Table structure for table `event_appointment` */

DROP TABLE IF EXISTS `event_appointment`;

CREATE TABLE `event_appointment` (
  `id` int(11) NOT NULL,
  `treatment_id` int(11) NOT NULL,
  `treatment_note_id` int(11) DEFAULT NULL,
  `cancel_reason_id` int(11) DEFAULT NULL,
  `last_event_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_event_prev_class` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_55B12BC45E2BCA99` (`treatment_note_id`),
  KEY `IDX_55B12BC4471C0366` (`treatment_id`),
  KEY `IDX_55B12BC4EE1A430C` (`cancel_reason_id`),
  CONSTRAINT `FK_55B12BC4471C0366` FOREIGN KEY (`treatment_id`) REFERENCES `treatment` (`id`),
  CONSTRAINT `FK_55B12BC45E2BCA99` FOREIGN KEY (`treatment_note_id`) REFERENCES `treatment_note` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_55B12BC4BF396750` FOREIGN KEY (`id`) REFERENCES `event` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_55B12BC4EE1A430C` FOREIGN KEY (`cancel_reason_id`) REFERENCES `cancel_reason` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `event_appointment` */

/*Table structure for table `event_recurrency` */

DROP TABLE IF EXISTS `event_recurrency`;

CREATE TABLE `event_recurrency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `custom_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `last_event_date` date DEFAULT NULL,
  `weekdays` longtext COLLATE utf8_unicode_ci,
  `every` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_535877412B18554A` (`owner_user_id`),
  CONSTRAINT `FK_535877412B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `event_recurrency` */

/*Table structure for table `event_resource` */

DROP TABLE IF EXISTS `event_resource`;

CREATE TABLE `event_resource` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `calendar_settings_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `default_resource` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FA7D1DC6296826C3` (`calendar_settings_id`),
  KEY `IDX_FA7D1DC62B18554A` (`owner_user_id`),
  CONSTRAINT `FK_FA7D1DC6296826C3` FOREIGN KEY (`calendar_settings_id`) REFERENCES `calendar_settings` (`id`),
  CONSTRAINT `FK_FA7D1DC62B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `event_resource` */

insert  into `event_resource`(`id`,`calendar_settings_id`,`owner_user_id`,`name`,`position`,`default_resource`,`created_at`,`updated_at`) values 
(1,1,1,'Column 1',1,1,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(2,1,1,'Column 2',2,0,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(3,2,2,'Column 1',1,1,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(4,2,2,'Column 2',2,0,'2018-07-31 06:52:11','2018-07-31 06:52:11');

/*Table structure for table `event_unavailable_block` */

DROP TABLE IF EXISTS `event_unavailable_block`;

CREATE TABLE `event_unavailable_block` (
  `id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_AA0C0F8DBF396750` FOREIGN KEY (`id`) REFERENCES `event` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `event_unavailable_block` */

/*Table structure for table `goal` */

DROP TABLE IF EXISTS `goal`;

CREATE TABLE `goal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `goal` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_step` longtext COLLATE utf8_unicode_ci,
  `when_value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FCDCEB2E2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_FCDCEB2E2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `goal` */

/*Table structure for table `invoice` */

DROP TABLE IF EXISTS `invoice`;

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auto_created` tinyint(1) DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `patient_address` longtext COLLATE utf8_unicode_ci,
  `notes` longtext COLLATE utf8_unicode_ci,
  `date` date NOT NULL,
  `reminder_frequency` int(11) DEFAULT NULL,
  `due_date` int(11) NOT NULL,
  `paid_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_906517446B899279` (`patient_id`),
  KEY `IDX_906517442B18554A` (`owner_user_id`),
  CONSTRAINT `FK_906517442B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_906517446B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice` */

/*Table structure for table `invoice_logo` */

DROP TABLE IF EXISTS `invoice_logo`;

CREATE TABLE `invoice_logo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `origin_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E64BF8762B18554A` (`owner_user_id`),
  CONSTRAINT `FK_E64BF8762B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice_logo` */

/*Table structure for table `invoice_payment` */

DROP TABLE IF EXISTS `invoice_payment`;

CREATE TABLE `invoice_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_payment_method_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_9FF1B2DE591FDD9A` (`invoice_payment_method_id`),
  KEY `IDX_9FF1B2DE2989F1FD` (`invoice_id`),
  KEY `IDX_9FF1B2DE2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_9FF1B2DE2989F1FD` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  CONSTRAINT `FK_9FF1B2DE2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_9FF1B2DE591FDD9A` FOREIGN KEY (`invoice_payment_method_id`) REFERENCES `invoice_payment_method` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice_payment` */

/*Table structure for table `invoice_payment_method` */

DROP TABLE IF EXISTS `invoice_payment_method`;

CREATE TABLE `invoice_payment_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice_payment_method` */

insert  into `invoice_payment_method`(`id`,`name`,`created_at`,`updated_at`) values 
(1,'Credit card','2018-07-31 06:52:12','2018-07-31 06:52:12'),
(2,'Cash','2018-07-31 06:52:12','2018-07-31 06:52:12'),
(3,'Cheque','2018-07-31 06:52:12','2018-07-31 06:52:12'),
(4,'Bank transfer','2018-07-31 06:52:12','2018-07-31 06:52:12'),
(5,'Hicaps','2018-07-31 06:52:12','2018-07-31 06:52:12');

/*Table structure for table `invoice_product` */

DROP TABLE IF EXISTS `invoice_product`;

CREATE TABLE `invoice_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `original_invoice_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `from_other_invoice` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2193327E2989F1FD` (`invoice_id`),
  KEY `IDX_2193327E4584665A` (`product_id`),
  KEY `IDX_2193327E443E8669` (`original_invoice_id`),
  KEY `IDX_2193327E2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_2193327E2989F1FD` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  CONSTRAINT `FK_2193327E2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2193327E443E8669` FOREIGN KEY (`original_invoice_id`) REFERENCES `invoice` (`id`),
  CONSTRAINT `FK_2193327E4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice_product` */

/*Table structure for table `invoice_refund` */

DROP TABLE IF EXISTS `invoice_refund`;

CREATE TABLE `invoice_refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_payment_method_id` int(11) NOT NULL,
  `refund_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E01A890B591FDD9A` (`invoice_payment_method_id`),
  KEY `IDX_E01A890B189801D5` (`refund_id`),
  KEY `IDX_E01A890B2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_E01A890B189801D5` FOREIGN KEY (`refund_id`) REFERENCES `refund` (`id`),
  CONSTRAINT `FK_E01A890B2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_E01A890B591FDD9A` FOREIGN KEY (`invoice_payment_method_id`) REFERENCES `invoice_payment_method` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice_refund` */

/*Table structure for table `invoice_settings` */

DROP TABLE IF EXISTS `invoice_settings`;

CREATE TABLE `invoice_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logo_attachment_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `invoice_number` int(11) NOT NULL,
  `invoice_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `invoice_notes` longtext COLLATE utf8_unicode_ci,
  `invoice_email` longtext COLLATE utf8_unicode_ci,
  `due_within` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FA6DFA9D81CF3622` (`logo_attachment_id`),
  KEY `IDX_FA6DFA9D2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_FA6DFA9D2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_FA6DFA9D81CF3622` FOREIGN KEY (`logo_attachment_id`) REFERENCES `invoice_logo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice_settings` */

insert  into `invoice_settings`(`id`,`logo_attachment_id`,`owner_user_id`,`invoice_number`,`invoice_title`,`invoice_notes`,`invoice_email`,`due_within`,`created_at`,`updated_at`) values 
(1,NULL,1,1,'Invoice',NULL,'Dear {{ patientName }},\r\n\r\nPlease find attached invoice #{{ invoiceNumber }} for the amount of {{ invoiceTotal }}.\r\n\r\nIf you have questions about this invoice, please contact us immediately.\r\n\r\nRegards,\r\n{{ businessName }}',0,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(2,NULL,2,1,'Invoice',NULL,'Dear {{ patientName }},\r\n\r\nPlease find attached invoice #{{ invoiceNumber }} for the amount of {{ invoiceTotal }}.\r\n\r\nIf you have questions about this invoice, please contact us immediately.\r\n\r\nRegards,\r\n{{ businessName }}',0,'2018-07-31 06:52:11','2018-07-31 06:52:11');

/*Table structure for table `invoice_treatment` */

DROP TABLE IF EXISTS `invoice_treatment`;

CREATE TABLE `invoice_treatment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `treatment_id` int(11) NOT NULL,
  `original_invoice_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `from_other_invoice` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FDAECDAD2989F1FD` (`invoice_id`),
  KEY `IDX_FDAECDAD471C0366` (`treatment_id`),
  KEY `IDX_FDAECDAD443E8669` (`original_invoice_id`),
  KEY `IDX_FDAECDAD2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_FDAECDAD2989F1FD` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  CONSTRAINT `FK_FDAECDAD2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_FDAECDAD443E8669` FOREIGN KEY (`original_invoice_id`) REFERENCES `invoice` (`id`),
  CONSTRAINT `FK_FDAECDAD471C0366` FOREIGN KEY (`treatment_id`) REFERENCES `treatment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `invoice_treatment` */

/*Table structure for table `manual_communication` */

DROP TABLE IF EXISTS `manual_communication`;

CREATE TABLE `manual_communication` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `bulk_patient_list_id` int(11) DEFAULT NULL,
  `communication_type_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_53B4F36C60AB2C07` (`bulk_patient_list_id`),
  KEY `IDX_53B4F36C6B899279` (`patient_id`),
  KEY `IDX_53B4F36CB09DA5C9` (`communication_type_id`),
  KEY `IDX_53B4F36C2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_53B4F36C2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_53B4F36C60AB2C07` FOREIGN KEY (`bulk_patient_list_id`) REFERENCES `bulk_patient_list` (`id`),
  CONSTRAINT `FK_53B4F36C6B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_53B4F36CB09DA5C9` FOREIGN KEY (`communication_type_id`) REFERENCES `communication_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `manual_communication` */

/*Table structure for table `manual_communication_attachment` */

DROP TABLE IF EXISTS `manual_communication_attachment`;

CREATE TABLE `manual_communication_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `manual_communication_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_size` int(11) NOT NULL,
  `origin_file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75807F12D41132CC` (`manual_communication_id`),
  KEY `IDX_75807F122B18554A` (`owner_user_id`),
  CONSTRAINT `FK_75807F122B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_75807F12D41132CC` FOREIGN KEY (`manual_communication_id`) REFERENCES `manual_communication` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `manual_communication_attachment` */

/*Table structure for table `message` */

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipient_patient_id` int(11) DEFAULT NULL,
  `manual_communication_id` int(11) DEFAULT NULL,
  `recipient_user_id` int(11) DEFAULT NULL,
  `parent_message_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivered` tinyint(1) NOT NULL,
  `bounced` tinyint(1) NOT NULL,
  `returned` tinyint(1) NOT NULL,
  `compiled` tinyint(1) NOT NULL,
  `sent` tinyint(1) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `recipient_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci,
  `error` longtext COLLATE utf8_unicode_ci,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `route_data` longtext COLLATE utf8_unicode_ci,
  `tag` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307F1389D807` (`recipient_patient_id`),
  KEY `IDX_B6BD307FD41132CC` (`manual_communication_id`),
  KEY `IDX_B6BD307FB15EFB97` (`recipient_user_id`),
  KEY `IDX_B6BD307F14399779` (`parent_message_id`),
  KEY `IDX_B6BD307F2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_B6BD307F1389D807` FOREIGN KEY (`recipient_patient_id`) REFERENCES `patient` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_B6BD307F14399779` FOREIGN KEY (`parent_message_id`) REFERENCES `message` (`id`),
  CONSTRAINT `FK_B6BD307F2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B6BD307FB15EFB97` FOREIGN KEY (`recipient_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_B6BD307FD41132CC` FOREIGN KEY (`manual_communication_id`) REFERENCES `manual_communication` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `message` */

/*Table structure for table `message_type` */

DROP TABLE IF EXISTS `message_type`;

CREATE TABLE `message_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `translation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `by_call` tinyint(1) DEFAULT NULL,
  `by_sms` tinyint(1) DEFAULT NULL,
  `by_email` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `message_type` */

insert  into `message_type`(`id`,`name`,`translation`,`by_call`,`by_sms`,`by_email`) values 
(1,'Email','send_email',NULL,NULL,1),
(2,'Phone','call_made',1,NULL,NULL),
(3,'SMS','send_sms',NULL,1,NULL),
(4,'Email & SMS','send_sms_and_email',NULL,1,1);

/*Table structure for table `no_show_message` */

DROP TABLE IF EXISTS `no_show_message`;

CREATE TABLE `no_show_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appointment_patient_id` int(11) NOT NULL,
  `communication_type_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_26175C1912C0A0B3` (`appointment_patient_id`),
  KEY `IDX_26175C19B09DA5C9` (`communication_type_id`),
  KEY `IDX_26175C192B18554A` (`owner_user_id`),
  CONSTRAINT `FK_26175C1912C0A0B3` FOREIGN KEY (`appointment_patient_id`) REFERENCES `appointment_patient` (`id`),
  CONSTRAINT `FK_26175C192B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_26175C19B09DA5C9` FOREIGN KEY (`communication_type_id`) REFERENCES `communication_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `no_show_message` */

/*Table structure for table `patient` */

DROP TABLE IF EXISTS `patient`;

CREATE TABLE `patient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state_id` int(11) DEFAULT NULL,
  `concession_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `patient_number` int(11) NOT NULL,
  `patient_number_formatted` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `preferred_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_first` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address_second` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auto_remind_sms` tinyint(1) DEFAULT NULL,
  `auto_remind_email` tinyint(1) DEFAULT NULL,
  `booking_confirmation_email` tinyint(1) DEFAULT NULL,
  `occupation` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `health_fund` longtext COLLATE utf8_unicode_ci,
  `referrer` longtext COLLATE utf8_unicode_ci NOT NULL,
  `mobile_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1ADAD7EB5D83CC1` (`state_id`),
  KEY `IDX_1ADAD7EB4132BB14` (`concession_id`),
  KEY `IDX_1ADAD7EB2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_1ADAD7EB2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_1ADAD7EB4132BB14` FOREIGN KEY (`concession_id`) REFERENCES `concession` (`id`),
  CONSTRAINT `FK_1ADAD7EB5D83CC1` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `patient` */

insert  into `patient`(`id`,`state_id`,`concession_id`,`owner_user_id`,`first_name`,`patient_number`,`patient_number_formatted`,`last_name`,`title`,`preferred_name`,`email`,`city`,`post_code`,`address_first`,`address_second`,`date_of_birth`,`gender`,`auto_remind_sms`,`auto_remind_email`,`booking_confirmation_email`,`occupation`,`emergency_contact`,`health_fund`,`referrer`,`mobile_phone`,`notes`,`created_at`,`updated_at`) values 
(1,4,NULL,1,'Taddie',1,'P000001','Lines',NULL,NULL,'taddie@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1997-03-15','Female',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0403 617 810',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(2,1,NULL,1,'Nicholas',2,'P000002','Wood',NULL,NULL,'nick@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1977-01-10','Male',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0402 829 081',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(3,3,NULL,1,'Ximena',3,'P000003','Flanagan',NULL,NULL,'ximena@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1992-08-24','Female',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0412 425 122',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(4,1,NULL,1,'Rene',4,'P000004','Manzanera',NULL,NULL,'rene@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1993-02-17','Female',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0425 329 361',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(5,3,NULL,2,'Taddie',1,'P000001','Lines',NULL,NULL,'taddie@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1997-03-15','Female',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0403 617 810',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(6,5,NULL,2,'Nicholas',2,'P000002','Wood',NULL,NULL,'nick@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1977-01-10','Male',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0402 829 081',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(7,5,NULL,2,'Ximena',3,'P000003','Flanagan',NULL,NULL,'ximena@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1992-08-24','Female',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0412 425 122',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12'),
(8,6,NULL,2,'Rene',4,'P000004','Manzanera',NULL,NULL,'rene@healthspaceclinics.com.au',NULL,NULL,NULL,NULL,'1993-02-17','Female',NULL,NULL,NULL,NULL,NULL,NULL,'Staff','0425 329 361',NULL,'2018-07-31 06:52:12','2018-07-31 06:52:12');

/*Table structure for table `patient_alert` */

DROP TABLE IF EXISTS `patient_alert`;

CREATE TABLE `patient_alert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_82CB9D0E6B899279` (`patient_id`),
  KEY `IDX_82CB9D0E2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_82CB9D0E2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_82CB9D0E6B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `patient_alert` */

/*Table structure for table `patient_attachment` */

DROP TABLE IF EXISTS `patient_attachment`;

CREATE TABLE `patient_attachment` (
  `patient_id` int(11) NOT NULL,
  `attachment_id` int(11) NOT NULL,
  PRIMARY KEY (`patient_id`,`attachment_id`),
  UNIQUE KEY `UNIQ_CF9F7F54464E68B` (`attachment_id`),
  KEY `IDX_CF9F7F546B899279` (`patient_id`),
  CONSTRAINT `FK_CF9F7F54464E68B` FOREIGN KEY (`attachment_id`) REFERENCES `attachment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_CF9F7F546B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `patient_attachment` */

/*Table structure for table `patient_related` */

DROP TABLE IF EXISTS `patient_related`;

CREATE TABLE `patient_related` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `main_patient_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `patient_relationship_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3D34257B5371E61A` (`main_patient_id`),
  KEY `IDX_3D34257B6B899279` (`patient_id`),
  KEY `IDX_3D34257BCDF55FC1` (`patient_relationship_id`),
  CONSTRAINT `FK_3D34257B5371E61A` FOREIGN KEY (`main_patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_3D34257B6B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_3D34257BCDF55FC1` FOREIGN KEY (`patient_relationship_id`) REFERENCES `patient_relationship` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `patient_related` */

/*Table structure for table `patient_relationship` */

DROP TABLE IF EXISTS `patient_relationship`;

CREATE TABLE `patient_relationship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reverse_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `patient_relationship` */

insert  into `patient_relationship`(`id`,`name`,`reverse_name`,`created_at`,`updated_at`) values 
(1,'Parent','Child','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(2,'Child','Parent','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(3,'Sibling','Sibling','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(4,'Partner','Partner','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(5,'Spouse','Spouse','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(6,'Relative','Relative','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(7,'Other','Other','2018-07-31 06:52:10','2018-07-31 06:52:10');

/*Table structure for table `phone` */

DROP TABLE IF EXISTS `phone`;

CREATE TABLE `phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_444F97DD6B899279` (`patient_id`),
  CONSTRAINT `FK_444F97DD6B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `phone` */

/*Table structure for table `product` */

DROP TABLE IF EXISTS `product`;

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `treatment_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `stock_level` int(11) DEFAULT NULL,
  `pack_amount` int(11) DEFAULT NULL,
  `single_treatment_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04AD471C0366` (`treatment_id`),
  CONSTRAINT `FK_D34A04AD471C0366` FOREIGN KEY (`treatment_id`) REFERENCES `treatment` (`id`),
  CONSTRAINT `FK_D34A04ADBF396750` FOREIGN KEY (`id`) REFERENCES `concession_price_owner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `product` */

insert  into `product`(`id`,`treatment_id`,`name`,`code`,`supplier`,`cost_price`,`stock_level`,`pack_amount`,`single_treatment_price`) values 
(3,NULL,'Metagenics Adaptan 120t','MetAd120','Metagenics',60.00,NULL,NULL,NULL),
(4,NULL,'Denneroll Lumbar Large ','DenLumLg','Denneroll',29.15,NULL,NULL,NULL),
(5,NULL,'Loving Earth Cacao Nibs 250g','LECacNib','Loving Earth',9.00,NULL,NULL,NULL),
(6,NULL,'Star Anise Activated Cashews 120g','SEActCas','Star Anise',6.00,NULL,NULL,NULL),
(7,NULL,'Acure Dark Dry Shampoo 48g','DrkDSham','Acure',10.00,NULL,NULL,NULL),
(8,NULL,'Buchi Kombucha','Buchi','Buchi',4.00,NULL,NULL,NULL),
(9,NULL,'Metagenics Adaptan 120t','MetAd120','Metagenics',60.00,NULL,NULL,NULL),
(10,NULL,'Denneroll Lumbar Large ','DenLumLg','Denneroll',29.15,NULL,NULL,NULL),
(11,NULL,'Loving Earth Cacao Nibs 250g','LECacNib','Loving Earth',9.00,NULL,NULL,NULL),
(12,NULL,'Star Anise Activated Cashews 120g','SEActCas','Star Anise',6.00,NULL,NULL,NULL),
(13,NULL,'Acure Dark Dry Shampoo 48g','DrkDSham','Acure',10.00,NULL,NULL,NULL),
(14,NULL,'Buchi Kombucha','Buchi','Buchi',4.00,NULL,NULL,NULL);

/*Table structure for table `recall` */

DROP TABLE IF EXISTS `recall`;

CREATE TABLE `recall` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `recall_type_id` int(11) DEFAULT NULL,
  `recall_for_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `completed` tinyint(1) DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sms` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4B3005766B899279` (`patient_id`),
  KEY `IDX_4B300576A25AB4AB` (`recall_type_id`),
  KEY `IDX_4B300576E7492DD6` (`recall_for_id`),
  KEY `IDX_4B3005762B18554A` (`owner_user_id`),
  CONSTRAINT `FK_4B3005762B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_4B3005766B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_4B300576A25AB4AB` FOREIGN KEY (`recall_type_id`) REFERENCES `message_type` (`id`),
  CONSTRAINT `FK_4B300576E7492DD6` FOREIGN KEY (`recall_for_id`) REFERENCES `recall_for` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `recall` */

/*Table structure for table `recall_for` */

DROP TABLE IF EXISTS `recall_for`;

CREATE TABLE `recall_for` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `recall_for` */

insert  into `recall_for`(`id`,`name`) values 
(1,'FTKA'),
(2,'Did not reschedule'),
(3,'Care call'),
(4,'Cancelled'),
(5,'Check notes'),
(6,'Asked us to call to reschedule'),
(7,'Due for next appointment');

/*Table structure for table `refund` */

DROP TABLE IF EXISTS `refund`;

CREATE TABLE `refund` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `reason` tinytext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5B2C14582989F1FD` (`invoice_id`),
  KEY `IDX_5B2C14582B18554A` (`owner_user_id`),
  CONSTRAINT `FK_5B2C14582989F1FD` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`),
  CONSTRAINT `FK_5B2C14582B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `refund` */

/*Table structure for table `reschedule` */

DROP TABLE IF EXISTS `reschedule`;

CREATE TABLE `reschedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_38D837AA71F7E88B` (`event_id`),
  CONSTRAINT `FK_38D837AA71F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `reschedule` */

/*Table structure for table `sms_cost` */

DROP TABLE IF EXISTS `sms_cost`;

CREATE TABLE `sms_cost` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `inbound_cost` decimal(10,2) DEFAULT NULL,
  `outbound_cost` decimal(10,2) DEFAULT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A30B08AAF92F3E70` (`country_id`),
  CONSTRAINT `FK_A30B08AAF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sms_cost` */

/*Table structure for table `state` */

DROP TABLE IF EXISTS `state`;

CREATE TABLE `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A393D2FBF92F3E70` (`country_id`),
  CONSTRAINT `FK_A393D2FBF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `state` */

insert  into `state`(`id`,`country_id`,`name`,`created_at`,`updated_at`) values 
(1,1,'NSW','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(2,1,'VIC','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(3,1,'QLD','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(4,1,'TAS','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(5,1,'SA','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(6,1,'WA','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(7,1,'NT','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(8,1,'ACT','2018-07-31 06:52:10','2018-07-31 06:52:10');

/*Table structure for table `subscription` */

DROP TABLE IF EXISTS `subscription`;

CREATE TABLE `subscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `subscription` */

insert  into `subscription`(`id`,`name`,`price`,`duration`,`created_at`,`updated_at`) values 
(1,'Trial',0.00,'month','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(2,'Month',10.00,'month','2018-07-31 06:52:10','2018-07-31 06:52:10'),
(3,'Year',100.00,'year','2018-07-31 06:52:10','2018-07-31 06:52:10');

/*Table structure for table `task` */

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recurring_task_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `completed` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_527EDB255540940F` (`recurring_task_id`),
  KEY `IDX_527EDB252B18554A` (`owner_user_id`),
  CONSTRAINT `FK_527EDB252B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_527EDB255540940F` FOREIGN KEY (`recurring_task_id`) REFERENCES `task_recurring` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `task` */

/*Table structure for table `task_recurring` */

DROP TABLE IF EXISTS `task_recurring`;

CREATE TABLE `task_recurring` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `repeats` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interval_week` int(11) DEFAULT NULL,
  `interval_month` int(11) DEFAULT NULL,
  `interval_year` int(11) DEFAULT NULL,
  `repeat_days` longtext COLLATE utf8_unicode_ci,
  `repeat_month` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1346CFBC2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_1346CFBC2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `task_recurring` */

/*Table structure for table `treatment` */

DROP TABLE IF EXISTS `treatment`;

CREATE TABLE `treatment` (
  `id` int(11) NOT NULL,
  `parent_treatment_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `calendar_colour` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `parent` tinyint(1) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `attachment_file_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_show_fee` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_98013C3117725E48` (`parent_treatment_id`),
  CONSTRAINT `FK_98013C3117725E48` FOREIGN KEY (`parent_treatment_id`) REFERENCES `treatment` (`id`) ON DELETE SET NULL,
  CONSTRAINT `FK_98013C31BF396750` FOREIGN KEY (`id`) REFERENCES `concession_price_owner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `treatment` */

insert  into `treatment`(`id`,`parent_treatment_id`,`name`,`code`,`description`,`calendar_colour`,`parent`,`duration`,`attachment_file_name`,`no_show_fee`) values 
(1,NULL,'No show fee',NULL,NULL,NULL,0,NULL,NULL,1),
(2,NULL,'No show fee',NULL,NULL,NULL,0,NULL,NULL,1),
(15,NULL,'Chiropractic Standard ','CS 1005',NULL,'',0,15,NULL,0),
(16,NULL,'Chiropractic Initial ','CI 1001',NULL,'#FFC300',0,45,NULL,0),
(17,NULL,'Chiropractic Review of Findings ','CROF 1006',NULL,'#cc0000',0,30,NULL,0),
(18,NULL,'Chiropractic Extended ','CEXT 1006',NULL,'#86f488',0,30,NULL,0),
(19,NULL,'Chiropractic Progress Exam ','CPE 1006',NULL,'#3E8FC1',0,30,NULL,0),
(20,NULL,'Acupuncture Initial','AI 103',NULL,'#FFC300',0,60,NULL,0),
(21,NULL,'Acupuncture Review of Findings ','AROF 203',NULL,'#cc0000',0,60,NULL,0),
(22,NULL,'Acupuncture Standard','AS 203',NULL,'',0,60,NULL,0),
(23,NULL,'Massage Initial 60 mins','MAI60 105',NULL,'#FFC300',0,60,NULL,0),
(24,NULL,'Massage Initial 30 mins','MAI30 105',NULL,'#FFC300',0,30,NULL,0),
(25,NULL,'Massage Standard 60 mins','MAS60 205',NULL,'',0,60,NULL,0),
(26,NULL,'Massage Standard 30 mins','MAS30 205',NULL,'',0,30,NULL,0),
(27,NULL,'Chiropractic Standard ','CS 1005',NULL,'',0,15,NULL,0),
(28,NULL,'Chiropractic Initial ','CI 1001',NULL,'#FFC300',0,45,NULL,0),
(29,NULL,'Chiropractic Review of Findings ','CROF 1006',NULL,'#cc0000',0,30,NULL,0),
(30,NULL,'Chiropractic Extended ','CEXT 1006',NULL,'#86f488',0,30,NULL,0),
(31,NULL,'Chiropractic Progress Exam ','CPE 1006',NULL,'#3E8FC1',0,30,NULL,0),
(32,NULL,'Acupuncture Initial','AI 103',NULL,'#FFC300',0,60,NULL,0),
(33,NULL,'Acupuncture Review of Findings ','AROF 203',NULL,'#cc0000',0,60,NULL,0),
(34,NULL,'Acupuncture Standard','AS 203',NULL,'',0,60,NULL,0),
(35,NULL,'Massage Initial 60 mins','MAI60 105',NULL,'#FFC300',0,60,NULL,0),
(36,NULL,'Massage Initial 30 mins','MAI30 105',NULL,'#FFC300',0,30,NULL,0),
(37,NULL,'Massage Standard 60 mins','MAS60 205',NULL,'',0,60,NULL,0),
(38,NULL,'Massage Standard 30 mins','MAS30 205',NULL,'',0,30,NULL,0);

/*Table structure for table `treatment_note` */

DROP TABLE IF EXISTS `treatment_note`;

CREATE TABLE `treatment_note` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auto_created` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C7E36056B899279` (`patient_id`),
  CONSTRAINT `FK_C7E36056B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_C7E3605BF396750` FOREIGN KEY (`id`) REFERENCES `treatment_note_field_owner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `treatment_note` */

/*Table structure for table `treatment_note_field` */

DROP TABLE IF EXISTS `treatment_note_field`;

CREATE TABLE `treatment_note_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `treatment_note_field_owner_id` int(11) NOT NULL,
  `template_field_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci,
  `position` int(11) NOT NULL,
  `mandatory` tinyint(1) NOT NULL,
  `notes` longtext COLLATE utf8_unicode_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2D9B5B9A51F63787` (`treatment_note_field_owner_id`),
  KEY `IDX_2D9B5B9A1B6137C3` (`template_field_id`),
  KEY `IDX_2D9B5B9A2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_2D9B5B9A1B6137C3` FOREIGN KEY (`template_field_id`) REFERENCES `treatment_note_field` (`id`),
  CONSTRAINT `FK_2D9B5B9A2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_2D9B5B9A51F63787` FOREIGN KEY (`treatment_note_field_owner_id`) REFERENCES `treatment_note_field_owner` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `treatment_note_field` */

insert  into `treatment_note_field`(`id`,`treatment_note_field_owner_id`,`template_field_id`,`owner_user_id`,`name`,`value`,`position`,`mandatory`,`notes`,`created_at`,`updated_at`) values 
(1,1,NULL,1,'Note summary',NULL,1,1,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(2,1,NULL,1,'Presenting complaint',NULL,2,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(3,1,NULL,1,'Complaint history',NULL,3,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(4,1,NULL,1,'Assessment',NULL,4,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(5,1,NULL,1,'Treatment',NULL,5,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(6,1,NULL,1,'Exercise',NULL,6,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(7,1,NULL,1,'Supplements & home advice',NULL,7,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(8,2,NULL,2,'Note summary',NULL,1,1,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(9,2,NULL,2,'Presenting complaint',NULL,2,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(10,2,NULL,2,'Complaint history',NULL,3,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(11,2,NULL,2,'Assessment',NULL,4,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(12,2,NULL,2,'Treatment',NULL,5,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(13,2,NULL,2,'Exercise',NULL,6,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11'),
(14,2,NULL,2,'Supplements & home advice',NULL,7,0,NULL,'2018-07-31 06:52:11','2018-07-31 06:52:11');

/*Table structure for table `treatment_note_field_owner` */

DROP TABLE IF EXISTS `treatment_note_field_owner`;

CREATE TABLE `treatment_note_field_owner` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template_id` int(11) DEFAULT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `discr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ECB272095DA0FB8` (`template_id`),
  KEY `IDX_ECB272092B18554A` (`owner_user_id`),
  CONSTRAINT `FK_ECB272092B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_ECB272095DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `treatment_note_field_owner` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `treatment_note_field_owner` */

insert  into `treatment_note_field_owner`(`id`,`template_id`,`owner_user_id`,`created_at`,`updated_at`,`discr`) values 
(1,NULL,1,'2018-07-31 06:52:11','2018-07-31 06:52:11','treatmentnotetemplate'),
(2,NULL,2,'2018-07-31 06:52:11','2018-07-31 06:52:11','treatmentnotetemplate');

/*Table structure for table `treatment_note_template` */

DROP TABLE IF EXISTS `treatment_note_template`;

CREATE TABLE `treatment_note_template` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `default_template` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `FK_7B002E5BBF396750` FOREIGN KEY (`id`) REFERENCES `treatment_note_field_owner` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `treatment_note_template` */

insert  into `treatment_note_template`(`id`,`name`,`default_template`) values 
(1,'Default',1),
(2,'Default',1);

/*Table structure for table `treatment_pack_credit` */

DROP TABLE IF EXISTS `treatment_pack_credit`;

CREATE TABLE `treatment_pack_credit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_product_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `owner_user_id` int(11) DEFAULT NULL,
  `amount_spend` int(11) NOT NULL,
  `refunded_amount` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_56BF4188BC5816C4` (`invoice_product_id`),
  KEY `IDX_56BF41886B899279` (`patient_id`),
  KEY `IDX_56BF41882B18554A` (`owner_user_id`),
  CONSTRAINT `FK_56BF41882B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_56BF41886B899279` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `FK_56BF4188BC5816C4` FOREIGN KEY (`invoice_product_id`) REFERENCES `invoice_product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `treatment_pack_credit` */

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_id` int(11) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `first_login` tinyint(1) NOT NULL,
  `api_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `business_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `provider_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `patient_number` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  KEY `IDX_8D93D649F92F3E70` (`country_id`),
  KEY `IDX_8D93D6499A1887DC` (`subscription_id`),
  CONSTRAINT `FK_8D93D6499A1887DC` FOREIGN KEY (`subscription_id`) REFERENCES `subscription` (`id`),
  CONSTRAINT `FK_8D93D649F92F3E70` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`country_id`,`subscription_id`,`username`,`username_canonical`,`email`,`email_canonical`,`enabled`,`salt`,`password`,`last_login`,`locked`,`expired`,`expires_at`,`confirmation_token`,`password_requested_at`,`roles`,`credentials_expired`,`credentials_expire_at`,`first_login`,`api_key`,`business_name`,`provider_number`,`patient_number`,`title`,`timezone`,`first_name`,`last_name`,`slug`) values 
(1,1,1,'stepan@yudin.com','stepan@yudin.com','stepan@yudin.com','stepan@yudin.com',1,'bw7sjxt06y0owswk4o8g40ssscs48g0','$2y$13$bw7sjxt06y0owswk4o8g4uRQ6Ew7wLUxS/5iw60iwEiBFTPUFghZq',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,1,'329c11a80828bfe3053ea5639e840a25','Schneider-Ward',NULL,4,'Dr','Australia/Melbourne','Stepan','Yudin','schneider-ward'),
(2,1,1,'david@rooney.com','david@rooney.com','david@rooney.com','david@rooney.com',1,'swunvetwlm8sggcssw8gw0go8kk40ww','$2y$13$swunvetwlm8sggcssw8gwulhqUnJTHgGgG4hvF3YkUgTMitAV5W0W',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,1,'316614aac648fea3352e922e31584d71','Quigley and Sons',NULL,4,'Dr','Australia/Melbourne','David','Rooney','quigley-and-sons');

/*Table structure for table `widget_state` */

DROP TABLE IF EXISTS `widget_state`;

CREATE TABLE `widget_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F8D58BED2B18554A` (`owner_user_id`),
  CONSTRAINT `FK_F8D58BED2B18554A` FOREIGN KEY (`owner_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `widget_state` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
