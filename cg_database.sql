-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: gcms_db
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `booking_channel` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `purpose` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requested_pt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_change_reason` text COLLATE utf8mb4_unicode_ci,
  `pt_changed_at` timestamp NULL DEFAULT NULL,
  `pt_changed_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_requested_pt_id_foreign` (`requested_pt_id`),
  KEY `appointments_pt_changed_by_foreign` (`pt_changed_by`),
  KEY `appointments_cancelled_by_foreign` (`cancelled_by`),
  KEY `appointments_created_by_foreign` (`created_by`),
  KEY `appointments_branch_id_appointment_date_status_index` (`branch_id`,`appointment_date`,`status`),
  KEY `appointments_pt_id_appointment_date_index` (`pt_id`,`appointment_date`),
  KEY `appointments_patient_id_status_index` (`patient_id`,`status`),
  CONSTRAINT `appointments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `appointments_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `appointments_pt_changed_by_foreign` FOREIGN KEY (`pt_changed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `appointments_requested_pt_id_foreign` FOREIGN KEY (`requested_pt_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES ('a06d81c4-4b5e-4f5e-8076-72ce3ceb53e0','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06ab509-c691-4fe3-9755-dae900810248',NULL,'2025-11-24','11:00:00','walk_in','completed','ช่องทาง: line\nอาการ: ปวดหลัง','PHYSICAL_THERAPY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:18:10','2025-11-23 18:18:27',NULL),('a06d8db0-363b-4de1-b6e7-c469ff1610a8','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06ab509-c691-4fe3-9755-dae900810248',NULL,'2025-11-24','10:00:00','walk_in','completed','ช่องทาง: line\nอาการ: ปวดคอ บ่า ไหล่','PHYSICAL_THERAPY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:51:30','2025-11-23 19:16:52',NULL),('a06d9712-3798-4bf0-84e5-f73c5b6c6a2f','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06ab509-c691-4fe3-9755-dae900810248',NULL,'2025-11-24','10:30:00','walk_in','completed',NULL,'PHYSICAL_THERAPY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:17:44','2025-11-23 19:18:16',NULL),('a06d97bd-48ac-48a4-86f0-7deb57fd6b6f','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06ab509-c691-4fe3-9755-dae900810248',NULL,'2025-11-24','09:30:00','walk_in','completed',NULL,'PHYSICAL_THERAPY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:19:36','2025-11-23 19:20:54',NULL),('a06d997a-a759-48eb-83e6-ecd233e8b979','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248',NULL,'2025-11-24','15:30:00','walk_in','completed','ช่องทาง: phone\nอาการ: ปวดหลัง','PHYSICAL_THERAPY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:24:28','2025-11-23 19:30:20',NULL),('a06d9bc2-6fa7-42ad-b0d7-f3400f46c7a9','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248',NULL,'2025-11-24','12:30:00','walk_in','completed',NULL,'FOLLOW_UP',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:30:50','2025-11-24 02:41:01',NULL),('a06e35eb-86fd-4695-9144-c48de3dc5582','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248',NULL,'2025-11-24','11:00:00','walk_in','completed',NULL,'PHYSICAL_THERAPY',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-24 02:41:54','2025-11-24 02:42:28',NULL);
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_branch_id_foreign` (`branch_id`),
  KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `audit_logs_module_action_created_at_index` (`module`,`action`,`created_at`),
  KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `audit_logs_created_at_index` (`created_at`),
  CONSTRAINT `audit_logs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES ('a06b2237-23e1-4601-9461-1ee3254f0e75','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','delete','course_purchases','App\\Models\\CoursePurchase','a06b2102-7ed6-4fa6-a731-097ae9e79920','{\"id\": \"a06b2102-7ed6-4fa6-a731-097ae9e79920\", \"status\": \"active\", \"package\": {\"id\": \"a06af170-9d9c-4660-9a8f-1c87ec73badb\", \"code\": \"CGSR001\", \"name\": \"กายฟื้นฟู\", \"price\": \"6000.00\", \"df_rate\": null, \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"is_active\": true, \"created_at\": \"2025-11-22T11:42:56.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"service_id\": \"a06aee14-6fc3-4bf1-983f-7721baa75159\", \"updated_at\": \"2025-11-22T11:42:56.000000Z\", \"description\": \"กายภาพพื้นฐานน\", \"paid_sessions\": 5, \"validity_days\": 36500, \"bonus_sessions\": 1, \"total_sessions\": 6, \"commission_rate\": \"2.00\", \"allow_buy_and_use\": true, \"allow_retroactive\": true, \"allow_buy_for_later\": true, \"per_session_commission_rate\": \"30.00\"}, \"patient\": {\"id\": \"a06ace71-7cfc-458c-b439-b3f3229a17a5\", \"name\": \"โอ๊ต\", \"email\": null, \"notes\": null, \"phone\": \"0815294152\", \"photo\": null, \"gender\": null, \"prefix\": null, \"address\": null, \"id_card\": null, \"line_id\": null, \"district\": null, \"province\": null, \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"hn_number\": \"HN000001\", \"last_name\": \"\", \"birth_date\": null, \"created_at\": \"2025-11-22T10:05:05.000000Z\", \"deleted_at\": null, \"first_name\": \"โอ๊ต\", \"updated_at\": \"2025-11-22T13:55:22.000000Z\", \"blood_group\": null, \"subdistrict\": null, \"converted_at\": \"2025-11-22T13:55:22.000000Z\", \"drug_allergy\": null, \"food_allergy\": null, \"is_temporary\": false, \"last_name_en\": null, \"date_of_birth\": null, \"first_name_en\": null, \"emergency_name\": null, \"insurance_type\": null, \"booking_channel\": \"facebook\", \"chief_complaint\": \"ปวดคอ บ่า ไหล่\", \"surgery_history\": null, \"chronic_diseases\": null, \"insurance_number\": null, \"emergency_contact\": null, \"first_visit_branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\"}, \"created_at\": \"2025-11-22T13:55:57.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"invoice_id\": \"a06b2102-7ad2-46fd-9cba-e58b0a2d0cb9\", \"package_id\": \"a06af170-9d9c-4660-9a8f-1c87ec73badb\", \"patient_id\": \"a06ace71-7cfc-458c-b439-b3f3229a17a5\", \"updated_at\": \"2025-11-22T13:55:57.000000Z\", \"expiry_date\": \"2125-10-29T00:00:00.000000Z\", \"cancelled_at\": null, \"cancelled_by\": null, \"course_number\": \"CRS-20251122-4759\", \"purchase_date\": \"2025-11-22T00:00:00.000000Z\", \"used_sessions\": 0, \"total_sessions\": 6, \"activation_date\": \"2025-11-22T00:00:00.000000Z\", \"allowed_branches\": null, \"purchase_pattern\": \"buy_and_use\", \"purchase_branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"remaining_sessions\": 6, \"cancellation_reason\": null, \"allow_branch_sharing\": false}',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','http://localhost:8000/course-purchases/a06b2102-7ed6-4fa6-a731-097ae9e79920','DELETE','ลบคอร์ส: กายฟื้นฟู (Patient: โอ๊ต) | เหตุผล: ลูกค้าขอคืนคอร์ส','a06ab509-c691-4fe3-9755-dae900810248','2025-11-22 06:59:19','2025-11-22 06:59:19'),('a06d99cf-570a-4057-8e03-788d3ad0a4b5','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','cancel_course','refunds','App\\Models\\CoursePurchase','a06d96c3-6b16-441d-aedf-42caac620ee9','{\"id\": \"a06d96c3-6b16-441d-aedf-42caac620ee9\", \"status\": \"active\", \"invoice\": {\"id\": \"a06d96c3-6854-46a7-bd2e-fec556c53f0f\", \"notes\": \"Payment method: transfer\", \"opd_id\": null, \"status\": \"paid\", \"due_date\": \"2025-11-23T17:00:00.000000Z\", \"subtotal\": \"2000.00\", \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"created_at\": \"2025-11-23T19:16:52.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"patient_id\": \"a06d8db0-32f1-4f07-993b-716cb7764d7a\", \"tax_amount\": \"0.00\", \"updated_at\": \"2025-11-23T19:16:52.000000Z\", \"paid_amount\": \"2000.00\", \"down_payment\": null, \"invoice_date\": \"2025-11-23T17:00:00.000000Z\", \"invoice_type\": \"walk_in\", \"total_amount\": \"2000.00\", \"invoice_number\": \"INV-20251124-5806\", \"discount_amount\": \"0.00\", \"installment_amount\": null, \"installment_months\": null, \"outstanding_amount\": \"0.00\"}, \"package\": {\"id\": \"a06af170-9d9c-4660-9a8f-1c87ec73badb\", \"code\": \"CGSR001\", \"name\": \"กายฟื้นฟู\", \"price\": \"6000.00\", \"df_rate\": null, \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"df_amount\": \"30.00\", \"is_active\": true, \"created_at\": \"2025-11-22T04:42:56.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"service_id\": \"a06aee14-6fc3-4bf1-983f-7721baa75159\", \"updated_at\": \"2025-11-23T16:59:43.000000Z\", \"description\": \"กายภาพพื้นฐานน\", \"paid_sessions\": 5, \"validity_days\": 36500, \"bonus_sessions\": 1, \"total_sessions\": 6, \"commission_rate\": \"2.00\", \"allow_buy_and_use\": true, \"allow_retroactive\": true, \"allow_buy_for_later\": true, \"per_session_commission_rate\": \"30.00\"}, \"patient\": {\"id\": \"a06d8db0-32f1-4f07-993b-716cb7764d7a\", \"name\": \"เทส2\", \"email\": null, \"notes\": null, \"phone\": \"0121555555\", \"photo\": null, \"gender\": null, \"prefix\": null, \"address\": null, \"id_card\": null, \"line_id\": null, \"district\": null, \"province\": null, \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"hn_number\": \"HN000002\", \"last_name\": \"\", \"birth_date\": null, \"created_at\": \"2025-11-23T18:51:30.000000Z\", \"deleted_at\": null, \"first_name\": \"เทส2\", \"updated_at\": \"2025-11-23T18:51:51.000000Z\", \"blood_group\": null, \"subdistrict\": null, \"converted_at\": \"2025-11-23T18:51:51.000000Z\", \"drug_allergy\": null, \"food_allergy\": null, \"is_temporary\": false, \"last_name_en\": null, \"date_of_birth\": null, \"first_name_en\": null, \"emergency_name\": null, \"insurance_type\": null, \"booking_channel\": \"line\", \"chief_complaint\": \"ปวดคอ บ่า ไหล่\", \"surgery_history\": null, \"chronic_diseases\": null, \"insurance_number\": null, \"emergency_contact\": null, \"first_visit_branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\"}, \"created_at\": \"2025-11-23T19:16:52.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"invoice_id\": \"a06d96c3-6854-46a7-bd2e-fec556c53f0f\", \"package_id\": \"a06af170-9d9c-4660-9a8f-1c87ec73badb\", \"patient_id\": \"a06d8db0-32f1-4f07-993b-716cb7764d7a\", \"seller_ids\": [\"a06af3c8-0999-4acc-b21c-acf7763ccb9d\"], \"updated_at\": \"2025-11-23T19:18:16.000000Z\", \"expiry_date\": \"2125-10-30T17:00:00.000000Z\", \"cancelled_at\": null, \"cancelled_by\": null, \"payment_type\": \"installment\", \"course_number\": \"CRS-20251124-1966\", \"purchase_date\": \"2025-11-23T17:00:00.000000Z\", \"used_sessions\": 2, \"total_sessions\": 6, \"activation_date\": \"2025-11-23T17:00:00.000000Z\", \"allowed_branches\": null, \"installment_paid\": 2, \"purchase_pattern\": \"buy_and_use\", \"installment_total\": 3, \"installment_amount\": \"2000.00\", \"purchase_branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"remaining_sessions\": 4, \"cancellation_reason\": null, \"allow_branch_sharing\": false}','{\"status\": \"cancelled\", \"refund_id\": \"a06d99cf-5520-4e19-82a4-a4576c9d35d8\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','http://localhost:8000/billing/cancel-course','POST','ยกเลิกคอร์ส: กายฟื้นฟู | คืนเงิน: 4,000.00 บาท | เหตุผล: แปออปแอปแอปแอปแอปป','a06ab509-c691-4fe3-9755-dae900810248','2025-11-23 19:25:23','2025-11-23 19:25:23'),('a06e3937-3ebc-4f1e-9586-7cf2fffd4089','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','cancel_course','refunds','App\\Models\\CoursePurchase','a06d9b93-65c5-4bf3-8c18-36805c05d6c7','{\"id\": \"a06d9b93-65c5-4bf3-8c18-36805c05d6c7\", \"status\": \"active\", \"invoice\": {\"id\": \"a06d9b93-639f-49bd-a643-dc652729df40\", \"notes\": \"Payment method: transfer\", \"opd_id\": null, \"status\": \"paid\", \"due_date\": \"2025-11-23T17:00:00.000000Z\", \"subtotal\": \"2000.00\", \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"created_at\": \"2025-11-23T19:30:20.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"patient_id\": \"a06d997a-a49c-4f5c-8f08-0365352ccbce\", \"tax_amount\": \"0.00\", \"updated_at\": \"2025-11-23T19:30:20.000000Z\", \"paid_amount\": \"2000.00\", \"down_payment\": null, \"invoice_date\": \"2025-11-23T17:00:00.000000Z\", \"invoice_type\": \"walk_in\", \"total_amount\": \"2000.00\", \"invoice_number\": \"INV-20251124-5903\", \"discount_amount\": \"0.00\", \"installment_amount\": null, \"installment_months\": null, \"outstanding_amount\": \"0.00\"}, \"package\": {\"id\": \"a06af170-9d9c-4660-9a8f-1c87ec73badb\", \"code\": \"CGSR001\", \"name\": \"กายฟื้นฟู\", \"price\": \"6000.00\", \"df_rate\": null, \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"df_amount\": \"30.00\", \"is_active\": true, \"created_at\": \"2025-11-22T04:42:56.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"service_id\": \"a06aee14-6fc3-4bf1-983f-7721baa75159\", \"updated_at\": \"2025-11-23T16:59:43.000000Z\", \"description\": \"กายภาพพื้นฐานน\", \"paid_sessions\": 5, \"validity_days\": 36500, \"bonus_sessions\": 1, \"total_sessions\": 6, \"commission_rate\": \"2.00\", \"allow_buy_and_use\": true, \"allow_retroactive\": true, \"allow_buy_for_later\": true, \"per_session_commission_rate\": \"30.00\"}, \"patient\": {\"id\": \"a06d997a-a49c-4f5c-8f08-0365352ccbce\", \"name\": \"กด\", \"email\": null, \"notes\": null, \"phone\": \"0212784212\", \"photo\": null, \"gender\": null, \"prefix\": null, \"address\": null, \"id_card\": null, \"line_id\": null, \"district\": null, \"province\": null, \"branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"hn_number\": \"HN000003\", \"last_name\": \"\", \"birth_date\": null, \"created_at\": \"2025-11-23T19:24:28.000000Z\", \"deleted_at\": null, \"first_name\": \"กด\", \"updated_at\": \"2025-11-23T19:29:59.000000Z\", \"blood_group\": null, \"subdistrict\": null, \"converted_at\": \"2025-11-23T19:29:59.000000Z\", \"drug_allergy\": null, \"food_allergy\": null, \"is_temporary\": false, \"last_name_en\": null, \"date_of_birth\": null, \"first_name_en\": null, \"emergency_name\": null, \"insurance_type\": null, \"booking_channel\": \"phone\", \"chief_complaint\": \"ปวดหลัง\", \"surgery_history\": null, \"chronic_diseases\": null, \"insurance_number\": null, \"emergency_contact\": null, \"first_visit_branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\"}, \"created_at\": \"2025-11-23T19:30:20.000000Z\", \"created_by\": \"a06ab54a-a5ca-4f14-9b95-cb24b32bad55\", \"deleted_at\": null, \"invoice_id\": \"a06d9b93-639f-49bd-a643-dc652729df40\", \"package_id\": \"a06af170-9d9c-4660-9a8f-1c87ec73badb\", \"patient_id\": \"a06d997a-a49c-4f5c-8f08-0365352ccbce\", \"seller_ids\": [\"a06af3c8-0999-4acc-b21c-acf7763ccb9d\"], \"updated_at\": \"2025-11-24T02:42:28.000000Z\", \"expiry_date\": \"2125-10-30T17:00:00.000000Z\", \"cancelled_at\": null, \"cancelled_by\": null, \"payment_type\": \"installment\", \"course_number\": \"CRS-20251124-2377\", \"purchase_date\": \"2025-11-23T17:00:00.000000Z\", \"used_sessions\": 3, \"total_sessions\": 6, \"activation_date\": \"2025-11-23T17:00:00.000000Z\", \"allowed_branches\": null, \"installment_paid\": 3, \"purchase_pattern\": \"buy_and_use\", \"installment_total\": 3, \"installment_amount\": \"2000.00\", \"purchase_branch_id\": \"a06ab509-c691-4fe3-9755-dae900810248\", \"remaining_sessions\": 3, \"cancellation_reason\": null, \"allow_branch_sharing\": false}','{\"status\": \"cancelled\", \"refund_id\": \"a06e3937-3657-4771-a027-54b605cff0ca\"}','127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36','http://localhost:8000/billing/cancel-course','POST','ยกเลิกคอร์ส: กายฟื้นฟู | คืนเงิน: 3,000.00 บาท | เหตุผล: กดกดกดกดก','a06ab509-c691-4fe3-9755-dae900810248','2025-11-24 02:51:07','2025-11-24 02:51:07');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES ('a06ab509-c691-4fe3-9755-dae900810248','สาขาหลัก','MAIN','Bangkok','02-000-0000',NULL,1,NULL,'2025-11-22 01:54:02','2025-11-22 01:54:02',NULL);
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commission_rates`
--

DROP TABLE IF EXISTS `commission_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commission_rates` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commission_percentage` decimal(5,2) DEFAULT NULL,
  `df_percentage` decimal(5,2) DEFAULT NULL,
  `fixed_amount` decimal(10,2) DEFAULT NULL,
  `effective_from` date DEFAULT NULL,
  `effective_to` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commission_rates_package_id_foreign` (`package_id`),
  KEY `commission_rates_pt_id_foreign` (`pt_id`),
  KEY `commission_rates_branch_id_foreign` (`branch_id`),
  KEY `commission_rates_created_by_foreign` (`created_by`),
  KEY `commission_rates_rate_type_is_active_index` (`rate_type`,`is_active`),
  KEY `commission_rates_service_id_pt_id_effective_from_index` (`service_id`,`pt_id`,`effective_from`),
  CONSTRAINT `commission_rates_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `commission_rates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `commission_rates_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `course_packages` (`id`),
  CONSTRAINT `commission_rates_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `commission_rates_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commission_rates`
--

LOCK TABLES `commission_rates` WRITE;
/*!40000 ALTER TABLE `commission_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `commission_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commission_splits`
--

DROP TABLE IF EXISTS `commission_splits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commission_splits` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `split_percentage` decimal(5,2) NOT NULL,
  `split_amount` decimal(10,2) NOT NULL,
  `split_reason` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `paid_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clawed_back_at` timestamp NULL DEFAULT NULL,
  `clawed_back_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `commission_splits_paid_by_foreign` (`paid_by`),
  KEY `commission_splits_clawed_back_by_foreign` (`clawed_back_by`),
  KEY `commission_splits_created_by_foreign` (`created_by`),
  KEY `commission_splits_commission_id_index` (`commission_id`),
  KEY `commission_splits_pt_id_status_index` (`pt_id`,`status`),
  CONSTRAINT `commission_splits_clawed_back_by_foreign` FOREIGN KEY (`clawed_back_by`) REFERENCES `users` (`id`),
  CONSTRAINT `commission_splits_commission_id_foreign` FOREIGN KEY (`commission_id`) REFERENCES `commissions` (`id`),
  CONSTRAINT `commission_splits_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `commission_splits_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`),
  CONSTRAINT `commission_splits_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commission_splits`
--

LOCK TABLES `commission_splits` WRITE;
/*!40000 ALTER TABLE `commission_splits` DISABLE KEYS */;
/*!40000 ALTER TABLE `commission_splits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commissions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commission_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_amount` decimal(10,2) NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `commission_date` date NOT NULL,
  `is_clawback_eligible` tinyint(1) NOT NULL DEFAULT '1',
  `clawed_back_at` timestamp NULL DEFAULT NULL,
  `clawed_back_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clawback_reason` text COLLATE utf8mb4_unicode_ci,
  `clawback_refund_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `paid_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commissions_commission_number_unique` (`commission_number`),
  KEY `commissions_invoice_item_id_foreign` (`invoice_item_id`),
  KEY `commissions_treatment_id_foreign` (`treatment_id`),
  KEY `commissions_clawed_back_by_foreign` (`clawed_back_by`),
  KEY `commissions_clawback_refund_id_foreign` (`clawback_refund_id`),
  KEY `commissions_paid_by_foreign` (`paid_by`),
  KEY `commissions_created_by_foreign` (`created_by`),
  KEY `commissions_pt_id_commission_date_status_index` (`pt_id`,`commission_date`,`status`),
  KEY `commissions_branch_id_commission_date_index` (`branch_id`,`commission_date`),
  KEY `commissions_invoice_id_index` (`invoice_id`),
  KEY `commissions_status_commission_date_index` (`status`,`commission_date`),
  CONSTRAINT `commissions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `commissions_clawback_refund_id_foreign` FOREIGN KEY (`clawback_refund_id`) REFERENCES `refunds` (`id`),
  CONSTRAINT `commissions_clawed_back_by_foreign` FOREIGN KEY (`clawed_back_by`) REFERENCES `users` (`id`),
  CONSTRAINT `commissions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `commissions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `commissions_invoice_item_id_foreign` FOREIGN KEY (`invoice_item_id`) REFERENCES `invoice_items` (`id`),
  CONSTRAINT `commissions_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`),
  CONSTRAINT `commissions_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `commissions_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
INSERT INTO `commissions` VALUES ('a06d8f35-e75e-47cf-9a95-83e793143b0f','COM-20251124-5838','a06af39a-6d63-4944-a7dc-035c37cc596c','a06d81de-8ead-4702-8b56-b08e2835f74e','a06d8a41-8634-4a82-bfdf-8bb2f73f4f4b',NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,120.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู',NULL,'2025-11-23 18:55:45','2025-11-23 18:56:38','2025-11-23 18:56:38'),('a06d93d3-7863-45b6-ab83-e51e31cf1702','COM-20251124-6240','a06af3c8-0999-4acc-b21c-acf7763ccb9d','a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,120.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:08:39','2025-11-23 19:09:01','2025-11-23 19:09:01'),('a06d93f4-05a4-4688-904b-28af20753955','COM-20251124-7229','a06af39a-6d63-4944-a7dc-035c37cc596c','a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,120.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:09:01','2025-11-23 19:09:13','2025-11-23 19:09:13'),('a06d9407-1949-4d72-a586-7035bbf8b534','COM-20251124-5895','a06af3c8-0999-4acc-b21c-acf7763ccb9d','a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,120.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:09:13','2025-11-23 19:09:25','2025-11-23 19:09:25'),('a06d9419-7d2c-437e-b378-237df937efee','COM-20251124-8637','a06af3c8-0999-4acc-b21c-acf7763ccb9d','a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,60.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู (แบ่ง 2 คน)','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:09:25','2025-11-23 19:09:25',NULL),('a06d9419-7e94-43b7-8f13-5a5c8e896d00','COM-20251124-2435','a06af39a-6d63-4944-a7dc-035c37cc596c','a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,60.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู (แบ่ง 2 คน)','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:09:25','2025-11-23 19:09:25',NULL),('a06d96c3-6c38-46cb-9209-f71f75b08b94','COM-20251124-9039','a06af3c8-0999-4acc-b21c-acf7763ccb9d','a06d96c3-6854-46a7-bd2e-fec556c53f0f','a06d96c3-6b86-4a77-990d-4a4041bc61cf',NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,120.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:16:52','2025-11-23 19:16:52',NULL),('a06d9b93-66f1-41b9-8c96-87b87f90cf9e','COM-20251124-9079','a06af3c8-0999-4acc-b21c-acf7763ccb9d','a06d9b93-639f-49bd-a643-dc652729df40','a06d9b93-663a-44ad-a30b-f7607c08d74b',NULL,'a06ab509-c691-4fe3-9755-dae900810248','package_sale',6000.00,2.00,120.00,'pending','2025-11-24',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ค่าคอมขายคอร์ส: กายฟื้นฟู','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:30:20','2025-11-23 19:30:20',NULL);
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `confirmation_lists`
--

DROP TABLE IF EXISTS `confirmation_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `confirmation_lists` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `confirmation_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `confirmed_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmation_notes` text COLLATE utf8mb4_unicode_ci,
  `call_attempts` int NOT NULL DEFAULT '0',
  `last_call_attempt_at` timestamp NULL DEFAULT NULL,
  `is_auto_generated` tinyint(1) NOT NULL DEFAULT '1',
  `generated_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `confirmation_lists_appointment_id_foreign` (`appointment_id`),
  KEY `confirmation_lists_patient_id_foreign` (`patient_id`),
  KEY `confirmation_lists_confirmed_by_foreign` (`confirmed_by`),
  KEY `idx_conf_branch_date_status` (`branch_id`,`appointment_date`,`confirmation_status`),
  KEY `idx_conf_gen_date_status` (`generated_date`,`confirmation_status`),
  CONSTRAINT `confirmation_lists_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `confirmation_lists_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `confirmation_lists_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `confirmation_lists_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `confirmation_lists`
--

LOCK TABLES `confirmation_lists` WRITE;
/*!40000 ALTER TABLE `confirmation_lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `confirmation_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_packages`
--

DROP TABLE IF EXISTS `course_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_packages` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `total_sessions` int NOT NULL,
  `validity_days` int NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_sessions` int NOT NULL DEFAULT '0',
  `bonus_sessions` int NOT NULL DEFAULT '0',
  `commission_rate` decimal(5,2) DEFAULT NULL,
  `per_session_commission_rate` decimal(5,2) DEFAULT NULL,
  `df_rate` decimal(5,2) DEFAULT NULL,
  `df_amount` decimal(10,2) DEFAULT NULL COMMENT 'ค่ามือ PT (บาท)',
  `allow_buy_and_use` tinyint(1) NOT NULL DEFAULT '1',
  `allow_buy_for_later` tinyint(1) NOT NULL DEFAULT '1',
  `allow_retroactive` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_packages_code_unique` (`code`),
  KEY `course_packages_service_id_foreign` (`service_id`),
  KEY `course_packages_created_by_foreign` (`created_by`),
  KEY `course_packages_is_active_index` (`is_active`),
  KEY `course_packages_branch_id_foreign` (`branch_id`),
  CONSTRAINT `course_packages_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `course_packages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_packages_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_packages`
--

LOCK TABLES `course_packages` WRITE;
/*!40000 ALTER TABLE `course_packages` DISABLE KEYS */;
INSERT INTO `course_packages` VALUES ('a06af170-9d9c-4660-9a8f-1c87ec73badb','กายฟื้นฟู','CGSR001','กายภาพพื้นฐานน',6000.00,6,36500,1,'a06aee14-6fc3-4bf1-983f-7721baa75159',5,1,2.00,30.00,NULL,30.00,1,1,1,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','a06ab509-c691-4fe3-9755-dae900810248','2025-11-22 04:42:56','2025-11-23 16:59:43',NULL);
/*!40000 ALTER TABLE `course_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_purchases`
--

DROP TABLE IF EXISTS `course_purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_purchases` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `package_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_pattern` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `activation_date` date DEFAULT NULL,
  `expiry_date` date NOT NULL,
  `total_sessions` int NOT NULL,
  `used_sessions` int NOT NULL DEFAULT '0',
  `remaining_sessions` int GENERATED ALWAYS AS ((`total_sessions` - `used_sessions`)) STORED,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full',
  `installment_total` int NOT NULL DEFAULT '0',
  `installment_paid` int NOT NULL DEFAULT '0',
  `installment_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `allow_branch_sharing` tinyint(1) NOT NULL DEFAULT '1',
  `allowed_branches` json DEFAULT NULL,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_ids` json DEFAULT NULL COMMENT 'รายชื่อคนขาย (สำหรับแบ่งคอมมิชชั่น)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_purchases_course_number_unique` (`course_number`),
  KEY `course_purchases_package_id_foreign` (`package_id`),
  KEY `course_purchases_invoice_id_foreign` (`invoice_id`),
  KEY `course_purchases_cancelled_by_foreign` (`cancelled_by`),
  KEY `course_purchases_created_by_foreign` (`created_by`),
  KEY `course_purchases_patient_id_status_index` (`patient_id`,`status`),
  KEY `course_purchases_purchase_branch_id_purchase_date_index` (`purchase_branch_id`,`purchase_date`),
  KEY `course_purchases_status_expiry_date_index` (`status`,`expiry_date`),
  KEY `course_purchases_purchase_pattern_index` (`purchase_pattern`),
  CONSTRAINT `course_purchases_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_purchases_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_purchases_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `course_purchases_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `course_packages` (`id`),
  CONSTRAINT `course_purchases_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `course_purchases_purchase_branch_id_foreign` FOREIGN KEY (`purchase_branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_purchases`
--

LOCK TABLES `course_purchases` WRITE;
/*!40000 ALTER TABLE `course_purchases` DISABLE KEYS */;
INSERT INTO `course_purchases` (`id`, `course_number`, `patient_id`, `package_id`, `invoice_id`, `purchase_branch_id`, `purchase_pattern`, `purchase_date`, `activation_date`, `expiry_date`, `total_sessions`, `used_sessions`, `status`, `payment_type`, `installment_total`, `installment_paid`, `installment_amount`, `allow_branch_sharing`, `allowed_branches`, `cancellation_reason`, `cancelled_at`, `cancelled_by`, `created_by`, `seller_ids`, `created_at`, `updated_at`, `deleted_at`) VALUES ('a06d8a41-8466-4a35-8e05-3858acef9f98','CRS-20251124-8947','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06af170-9d9c-4660-9a8f-1c87ec73badb','a06d81de-8ead-4702-8b56-b08e2835f74e','a06ab509-c691-4fe3-9755-dae900810248','buy_for_later','2025-11-24','2025-11-24','2125-10-31',6,1,'active','full',0,0,0.00,0,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','[\"a06af3c8-0999-4acc-b21c-acf7763ccb9d\", \"a06af39a-6d63-4944-a7dc-035c37cc596c\"]','2025-11-23 18:41:54','2025-11-23 19:20:54',NULL),('a06d96c3-6b16-441d-aedf-42caac620ee9','CRS-20251124-1966','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06af170-9d9c-4660-9a8f-1c87ec73badb','a06d96c3-6854-46a7-bd2e-fec556c53f0f','a06ab509-c691-4fe3-9755-dae900810248','buy_and_use','2025-11-24','2025-11-24','2125-10-31',6,2,'active','installment',3,2,2000.00,0,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','[\"a06af3c8-0999-4acc-b21c-acf7763ccb9d\"]','2025-11-23 19:16:52','2025-11-23 19:25:23','2025-11-23 19:25:23'),('a06d9b93-65c5-4bf3-8c18-36805c05d6c7','CRS-20251124-2377','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06af170-9d9c-4660-9a8f-1c87ec73badb','a06d9b93-639f-49bd-a643-dc652729df40','a06ab509-c691-4fe3-9755-dae900810248','buy_and_use','2025-11-24','2025-11-24','2125-10-31',6,3,'active','installment',3,3,2000.00,0,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','[\"a06af3c8-0999-4acc-b21c-acf7763ccb9d\"]','2025-11-23 19:30:20','2025-11-24 02:51:07','2025-11-24 02:51:07');
/*!40000 ALTER TABLE `course_purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_renewals`
--

DROP TABLE IF EXISTS `course_renewals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_renewals` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `renewal_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_purchase_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `renewal_date` date NOT NULL,
  `old_expiry_date` date NOT NULL,
  `new_expiry_date` date NOT NULL,
  `extension_days` int NOT NULL,
  `renewal_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `renewal_reason` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `course_renewals_renewal_number_unique` (`renewal_number`),
  KEY `course_renewals_invoice_id_foreign` (`invoice_id`),
  KEY `course_renewals_created_by_foreign` (`created_by`),
  KEY `course_renewals_course_purchase_id_renewal_date_index` (`course_purchase_id`,`renewal_date`),
  KEY `course_renewals_patient_id_renewal_date_index` (`patient_id`,`renewal_date`),
  KEY `course_renewals_branch_id_renewal_date_index` (`branch_id`,`renewal_date`),
  CONSTRAINT `course_renewals_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `course_renewals_course_purchase_id_foreign` FOREIGN KEY (`course_purchase_id`) REFERENCES `course_purchases` (`id`),
  CONSTRAINT `course_renewals_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_renewals_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `course_renewals_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_renewals`
--

LOCK TABLES `course_renewals` WRITE;
/*!40000 ALTER TABLE `course_renewals` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_renewals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_shared_users`
--

DROP TABLE IF EXISTS `course_shared_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_shared_users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_purchase_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shared_patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relationship` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `max_sessions` int DEFAULT NULL,
  `used_sessions` int NOT NULL DEFAULT '0',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_course_shared_unique` (`course_purchase_id`,`shared_patient_id`),
  KEY `course_shared_users_created_by_foreign` (`created_by`),
  KEY `course_shared_users_owner_patient_id_is_active_index` (`owner_patient_id`,`is_active`),
  KEY `course_shared_users_shared_patient_id_is_active_index` (`shared_patient_id`,`is_active`),
  CONSTRAINT `course_shared_users_course_purchase_id_foreign` FOREIGN KEY (`course_purchase_id`) REFERENCES `course_purchases` (`id`) ON DELETE CASCADE,
  CONSTRAINT `course_shared_users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_shared_users_owner_patient_id_foreign` FOREIGN KEY (`owner_patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `course_shared_users_shared_patient_id_foreign` FOREIGN KEY (`shared_patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_shared_users`
--

LOCK TABLES `course_shared_users` WRITE;
/*!40000 ALTER TABLE `course_shared_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_shared_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_sharing`
--

DROP TABLE IF EXISTS `course_sharing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_sharing` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_purchase_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `max_sessions` int DEFAULT NULL,
  `used_sessions` int NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_course_branch_unique` (`course_purchase_id`,`to_branch_id`),
  KEY `course_sharing_from_branch_id_foreign` (`from_branch_id`),
  KEY `course_sharing_approved_by_foreign` (`approved_by`),
  KEY `course_sharing_created_by_foreign` (`created_by`),
  KEY `course_sharing_course_purchase_id_is_active_index` (`course_purchase_id`,`is_active`),
  KEY `course_sharing_to_branch_id_is_active_index` (`to_branch_id`,`is_active`),
  CONSTRAINT `course_sharing_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_sharing_course_purchase_id_foreign` FOREIGN KEY (`course_purchase_id`) REFERENCES `course_purchases` (`id`),
  CONSTRAINT `course_sharing_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_sharing_from_branch_id_foreign` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `course_sharing_to_branch_id_foreign` FOREIGN KEY (`to_branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_sharing`
--

LOCK TABLES `course_sharing` WRITE;
/*!40000 ALTER TABLE `course_sharing` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_sharing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course_usage_logs`
--

DROP TABLE IF EXISTS `course_usage_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `course_usage_logs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course_purchase_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sessions_used` int NOT NULL DEFAULT '1',
  `usage_date` date NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'used',
  `is_cross_branch` tinyint(1) NOT NULL DEFAULT '0',
  `purchase_branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `course_usage_logs_treatment_id_foreign` (`treatment_id`),
  KEY `course_usage_logs_purchase_branch_id_foreign` (`purchase_branch_id`),
  KEY `course_usage_logs_cancelled_by_foreign` (`cancelled_by`),
  KEY `course_usage_logs_created_by_foreign` (`created_by`),
  KEY `course_usage_logs_course_purchase_id_usage_date_index` (`course_purchase_id`,`usage_date`),
  KEY `course_usage_logs_patient_id_usage_date_index` (`patient_id`,`usage_date`),
  KEY `course_usage_logs_branch_id_usage_date_index` (`branch_id`,`usage_date`),
  KEY `course_usage_logs_pt_id_usage_date_index` (`pt_id`,`usage_date`),
  KEY `course_usage_logs_status_index` (`status`),
  CONSTRAINT `course_usage_logs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `course_usage_logs_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_usage_logs_course_purchase_id_foreign` FOREIGN KEY (`course_purchase_id`) REFERENCES `course_purchases` (`id`),
  CONSTRAINT `course_usage_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `course_usage_logs_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `course_usage_logs_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `course_usage_logs_purchase_branch_id_foreign` FOREIGN KEY (`purchase_branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `course_usage_logs_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course_usage_logs`
--

LOCK TABLES `course_usage_logs` WRITE;
/*!40000 ALTER TABLE `course_usage_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `course_usage_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_calls`
--

DROP TABLE IF EXISTS `crm_calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `crm_calls` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `call_type` enum('confirmation','follow_up') COLLATE utf8mb4_unicode_ci NOT NULL,
  `scheduled_date` date NOT NULL,
  `cutoff_time` time DEFAULT NULL,
  `status` enum('pending','called','no_answer','confirmed','cancelled','rescheduled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `patient_feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `called_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `called_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `crm_calls_branch_id_foreign` (`branch_id`),
  KEY `crm_calls_appointment_id_foreign` (`appointment_id`),
  KEY `crm_calls_called_by_foreign` (`called_by`),
  KEY `crm_calls_scheduled_date_call_type_status_index` (`scheduled_date`,`call_type`,`status`),
  KEY `crm_calls_patient_id_call_type_index` (`patient_id`,`call_type`),
  CONSTRAINT `crm_calls_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_calls_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `crm_calls_called_by_foreign` FOREIGN KEY (`called_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `crm_calls_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_calls`
--

LOCK TABLES `crm_calls` WRITE;
/*!40000 ALTER TABLE `crm_calls` DISABLE KEYS */;
INSERT INTO `crm_calls` VALUES ('a06b9a72-b1cb-44e3-b058-f024ff4133b3','a06ace71-7cfc-458c-b439-b3f3229a17a5','a06ab509-c691-4fe3-9755-dae900810248',NULL,'a06b20cc-a580-46d8-aa90-eb156bb0ab18','follow_up','2025-11-23',NULL,'pending',NULL,NULL,NULL,NULL,'2025-11-22 19:35:31','2025-11-22 19:35:31'),('a06b9a72-b463-40a6-98ab-872ae4851335','a06adc01-3b02-41aa-a9d7-352c15aaa927','a06ab509-c691-4fe3-9755-dae900810248',NULL,'a06b23e0-faee-4953-abd3-571a5c025e75','follow_up','2025-11-23',NULL,'pending',NULL,NULL,NULL,NULL,'2025-11-22 19:35:31','2025-11-22 19:35:31');
/*!40000 ALTER TABLE `crm_calls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `df_payments`
--

DROP TABLE IF EXISTS `df_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `df_payments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_purchase_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `source_type` enum('per_session','course_usage') COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `df_payments_treatment_id_foreign` (`treatment_id`),
  KEY `df_payments_pt_id_foreign` (`pt_id`),
  KEY `df_payments_service_id_foreign` (`service_id`),
  KEY `df_payments_course_purchase_id_foreign` (`course_purchase_id`),
  KEY `df_payments_branch_id_foreign` (`branch_id`),
  CONSTRAINT `df_payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `df_payments_course_purchase_id_foreign` FOREIGN KEY (`course_purchase_id`) REFERENCES `course_purchases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `df_payments_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `df_payments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  CONSTRAINT `df_payments_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `df_payments`
--

LOCK TABLES `df_payments` WRITE;
/*!40000 ALTER TABLE `df_payments` DISABLE KEYS */;
INSERT INTO `df_payments` VALUES ('a06d81de-941b-44dc-86ce-6fd8476715e3','a06d81d3-2191-4542-9188-f3c0a5ab98c4','a06af3c8-0999-4acc-b21c-acf7763ccb9d','a06aee14-6fc3-4bf1-983f-7721baa75159',NULL,'a06ab509-c691-4fe3-9755-dae900810248',40.00,'per_session','2025-11-24','ค่ามือรายครั้ง: กายภาพพื้นฐาน [แก้ไข: ย้ายจาก PT a06af3c8-0999-4acc-b21c-acf7763ccb9d] [แก้ไข: ย้ายจาก PT a06af39a-6d63-4944-a7dc-035c37cc596c] [แก้ไข: ย้ายจาก PT a06af3c8-0999-4acc-b21c-acf7763ccb9d] [แก้ไข: ย้ายจาก PT a06af39a-6d63-4944-a7dc-035c37cc596c] [แก้ไข: ย้ายจาก PT a06af3c8-0999-4acc-b21c-acf7763ccb9d] [แก้ไข: ย้ายจาก PT a06af39a-6d63-4944-a7dc-035c37cc596c]','2025-11-23 18:18:27','2025-11-23 19:09:13'),('a06d9743-9556-44e3-b90b-be4d3984d63d','a06d9720-c76c-4ba4-8f7a-c268778f9fc9','a06af39a-6d63-4944-a7dc-035c37cc596c',NULL,'a06d96c3-6b16-441d-aedf-42caac620ee9','a06ab509-c691-4fe3-9755-dae900810248',30.00,'course_usage','2025-11-24','ค่ามือใช้คอร์ส: CRS-20251124-1966 - N/A','2025-11-23 19:18:16','2025-11-23 19:18:16'),('a06d9835-0c29-4499-a7ae-8375dcbeed1a','a06d97d6-681b-4fb4-83fb-29dd52175538','a06af3c8-0999-4acc-b21c-acf7763ccb9d',NULL,'a06d8a41-8466-4a35-8e05-3858acef9f98','a06ab509-c691-4fe3-9755-dae900810248',30.00,'course_usage','2025-11-24','ค่ามือใช้คอร์ส: CRS-20251124-8947 - N/A','2025-11-23 19:20:54','2025-11-23 19:20:54'),('a06e359a-ef59-42c9-ba24-1315652e14e2','a06d9bcf-a34c-421c-a2a0-53715e9139e3','a06af39a-6d63-4944-a7dc-035c37cc596c',NULL,'a06d9b93-65c5-4bf3-8c18-36805c05d6c7','a06ab509-c691-4fe3-9755-dae900810248',30.00,'course_usage','2025-11-24','ค่ามือใช้คอร์ส: CRS-20251124-2377 - N/A','2025-11-24 02:41:01','2025-11-24 02:41:01'),('a06e361f-538c-45a7-829d-edf1cc30f205','a06e3600-0c43-4f60-96b1-7bf9f62112b8','a06af39a-6d63-4944-a7dc-035c37cc596c',NULL,'a06d9b93-65c5-4bf3-8c18-36805c05d6c7','a06ab509-c691-4fe3-9755-dae900810248',30.00,'course_usage','2025-11-24','ค่ามือใช้คอร์ส: CRS-20251124-2377 - N/A','2025-11-24 02:42:28','2025-11-24 02:42:28');
/*!40000 ALTER TABLE `df_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `document_date` date NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_document_number_unique` (`document_number`),
  KEY `documents_payment_id_foreign` (`payment_id`),
  KEY `documents_patient_id_foreign` (`patient_id`),
  KEY `documents_created_by_foreign` (`created_by`),
  KEY `documents_document_type_document_date_index` (`document_type`,`document_date`),
  KEY `documents_invoice_id_index` (`invoice_id`),
  KEY `documents_branch_id_document_date_index` (`branch_id`,`document_date`),
  CONSTRAINT `documents_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `documents_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `documents_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `documents_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipment` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `purchase_date` date DEFAULT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warranty_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warranty_expiry` date DEFAULT NULL,
  `last_maintenance_date` date DEFAULT NULL,
  `next_maintenance_date` date DEFAULT NULL,
  `maintenance_interval_days` int DEFAULT NULL,
  `current_value` decimal(10,2) DEFAULT NULL,
  `useful_life_years` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipment_equipment_code_unique` (`equipment_code`),
  KEY `equipment_created_by_foreign` (`created_by`),
  KEY `equipment_branch_id_status_index` (`branch_id`,`status`),
  KEY `equipment_category_status_index` (`category`,`status`),
  KEY `equipment_next_maintenance_date_index` (`next_maintenance_date`),
  CONSTRAINT `equipment_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `equipment_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipment`
--

LOCK TABLES `equipment` WRITE;
/*!40000 ALTER TABLE `equipment` DISABLE KEYS */;
/*!40000 ALTER TABLE `equipment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evaluations` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `evaluator_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `evaluation_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `evaluation_date` date NOT NULL,
  `evaluation_period` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ratings` json DEFAULT NULL,
  `overall_score` decimal(3,1) DEFAULT NULL,
  `overall_rating` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strengths` text COLLATE utf8mb4_unicode_ci,
  `areas_for_improvement` text COLLATE utf8mb4_unicode_ci,
  `goals` text COLLATE utf8mb4_unicode_ci,
  `action_plan` text COLLATE utf8mb4_unicode_ci,
  `evaluator_comments` text COLLATE utf8mb4_unicode_ci,
  `staff_comments` text COLLATE utf8mb4_unicode_ci,
  `next_evaluation_date` date DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `evaluations_created_by_foreign` (`created_by`),
  KEY `evaluations_staff_id_evaluation_date_index` (`staff_id`,`evaluation_date`),
  KEY `evaluations_evaluator_id_evaluation_date_index` (`evaluator_id`,`evaluation_date`),
  KEY `evaluations_branch_id_evaluation_date_index` (`branch_id`,`evaluation_date`),
  CONSTRAINT `evaluations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `evaluations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `evaluations_evaluator_id_foreign` FOREIGN KEY (`evaluator_id`) REFERENCES `users` (`id`),
  CONSTRAINT `evaluations_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evaluations`
--

LOCK TABLES `evaluations` WRITE;
/*!40000 ALTER TABLE `evaluations` DISABLE KEYS */;
/*!40000 ALTER TABLE `evaluations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_date` date NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  KEY `expenses_branch_id_expense_date_index` (`branch_id`,`expense_date`),
  KEY `expenses_category_index` (`category`),
  CONSTRAINT `expenses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow_up_lists`
--

DROP TABLE IF EXISTS `follow_up_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follow_up_lists` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `follow_up_date` date NOT NULL,
  `priority` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `contacted_at` timestamp NULL DEFAULT NULL,
  `contacted_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_notes` text COLLATE utf8mb4_unicode_ci,
  `appointment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `follow_up_lists_patient_id_foreign` (`patient_id`),
  KEY `follow_up_lists_treatment_id_foreign` (`treatment_id`),
  KEY `follow_up_lists_contacted_by_foreign` (`contacted_by`),
  KEY `follow_up_lists_appointment_id_foreign` (`appointment_id`),
  KEY `follow_up_lists_created_by_foreign` (`created_by`),
  KEY `follow_up_lists_branch_id_follow_up_date_status_index` (`branch_id`,`follow_up_date`,`status`),
  KEY `follow_up_lists_pt_id_status_index` (`pt_id`,`status`),
  KEY `follow_up_lists_priority_status_index` (`priority`,`status`),
  CONSTRAINT `follow_up_lists_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `follow_up_lists_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `follow_up_lists_contacted_by_foreign` FOREIGN KEY (`contacted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `follow_up_lists_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `follow_up_lists_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `follow_up_lists_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `follow_up_lists_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow_up_lists`
--

LOCK TABLES `follow_up_lists` WRITE;
/*!40000 ALTER TABLE `follow_up_lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow_up_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_treatment_id_foreign` (`treatment_id`),
  KEY `invoice_items_invoice_id_index` (`invoice_id`),
  KEY `invoice_items_service_id_index` (`service_id`),
  KEY `invoice_items_package_id_index` (`package_id`),
  KEY `invoice_items_pt_id_index` (`pt_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `invoice_items_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `course_packages` (`id`),
  CONSTRAINT `invoice_items_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `invoice_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  CONSTRAINT `invoice_items_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES ('a06d81de-9107-4e3e-8e92-c1f930343818','a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,NULL,NULL,'service','กายภาพพื้นฐาน',1,1200.00,0.00,1200.00,NULL,'2025-11-23 18:18:27','2025-11-23 18:18:27',NULL),('a06d8a41-8634-4a82-bfdf-8bb2f73f4f4b','a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,NULL,NULL,'course_package','กายฟื้นฟู',1,6000.00,0.00,6000.00,NULL,'2025-11-23 18:41:54','2025-11-23 18:41:54',NULL),('a06d96c3-6b86-4a77-990d-4a4041bc61cf','a06d96c3-6854-46a7-bd2e-fec556c53f0f',NULL,NULL,NULL,'course_package','กายฟื้นฟู (ผ่อนงวด 1/3)',1,2000.00,0.00,2000.00,NULL,'2025-11-23 19:16:52','2025-11-23 19:16:52',NULL),('a06d9743-92ec-4873-9dd1-e4cf9eaf06cd','a06d9743-8ea8-49db-9f43-2e3418da57f4',NULL,NULL,NULL,'course_installment','กายฟื้นฟู (ผ่อนงวด 2/3)',1,2000.00,0.00,2000.00,NULL,'2025-11-23 19:18:16','2025-11-23 19:18:16',NULL),('a06d9b93-663a-44ad-a30b-f7607c08d74b','a06d9b93-639f-49bd-a643-dc652729df40',NULL,NULL,NULL,'course_package','กายฟื้นฟู (ผ่อนงวด 1/3)',1,2000.00,0.00,2000.00,NULL,'2025-11-23 19:30:20','2025-11-23 19:30:20',NULL),('a06e359a-e06f-4240-ad4b-9911138282e7','a06e359a-65f5-4304-a70f-edfc3f60afd9',NULL,NULL,NULL,'course_installment','กายฟื้นฟู (ผ่อนงวด 2/3)',1,2000.00,0.00,2000.00,NULL,'2025-11-24 02:41:01','2025-11-24 02:41:01',NULL),('a06e361f-51b0-4ee1-b4e5-28f1dacec76c','a06e361f-4e36-4910-a55e-2fb48167514c',NULL,NULL,NULL,'course_installment','กายฟื้นฟู (ผ่อนงวด 3/3)',1,2000.00,0.00,2000.00,NULL,'2025-11-24 02:42:28','2025-11-24 02:42:28',NULL);
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opd_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `outstanding_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `installment_months` int DEFAULT NULL,
  `installment_amount` decimal(10,2) DEFAULT NULL,
  `down_payment` decimal(10,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  KEY `invoices_opd_id_foreign` (`opd_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  KEY `invoices_patient_id_status_index` (`patient_id`,`status`),
  KEY `invoices_branch_id_invoice_date_index` (`branch_id`,`invoice_date`),
  KEY `invoices_status_due_date_index` (`status`,`due_date`),
  CONSTRAINT `invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opd_records` (`id`),
  CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES ('a06d81de-8ead-4702-8b56-b08e2835f74e','INV-20251124-8294','a06d81c4-48dc-4739-a7ee-772e0e292d3a',NULL,'a06ab509-c691-4fe3-9755-dae900810248','walk_in',7200.00,0.00,0.00,7200.00,7200.00,0.00,'paid','2025-11-24','2025-11-24',NULL,NULL,NULL,'Payment method: transfer','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:18:27','2025-11-23 18:41:54',NULL),('a06d96c3-6854-46a7-bd2e-fec556c53f0f','INV-20251124-5806','a06d8db0-32f1-4f07-993b-716cb7764d7a',NULL,'a06ab509-c691-4fe3-9755-dae900810248','walk_in',2000.00,0.00,0.00,2000.00,2000.00,0.00,'paid','2025-11-24','2025-11-24',NULL,NULL,NULL,'Payment method: transfer','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:16:52','2025-11-23 19:16:52',NULL),('a06d9743-8ea8-49db-9f43-2e3418da57f4','INV-20251124-6230','a06d8db0-32f1-4f07-993b-716cb7764d7a',NULL,'a06ab509-c691-4fe3-9755-dae900810248','walk_in',0.00,0.00,0.00,0.00,0.00,0.00,'paid','2025-11-24','2025-11-24',NULL,NULL,NULL,'Payment method: none','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:18:16','2025-11-23 19:18:16',NULL),('a06d9835-0752-4b3d-af04-c92e46a0d394','INV-20251124-3821','a06d81c4-48dc-4739-a7ee-772e0e292d3a',NULL,'a06ab509-c691-4fe3-9755-dae900810248','walk_in',0.00,0.00,0.00,0.00,0.00,0.00,'paid','2025-11-24','2025-11-24',NULL,NULL,NULL,'Payment method: none','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:20:54','2025-11-23 19:20:54',NULL),('a06d9b93-639f-49bd-a643-dc652729df40','INV-20251124-5903','a06d997a-a49c-4f5c-8f08-0365352ccbce',NULL,'a06ab509-c691-4fe3-9755-dae900810248','walk_in',2000.00,0.00,0.00,2000.00,2000.00,0.00,'paid','2025-11-24','2025-11-24',NULL,NULL,NULL,'Payment method: transfer','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:30:20','2025-11-23 19:30:20',NULL),('a06e359a-65f5-4304-a70f-edfc3f60afd9','INV-20251124-4947','a06d997a-a49c-4f5c-8f08-0365352ccbce',NULL,'a06ab509-c691-4fe3-9755-dae900810248','walk_in',2000.00,0.00,0.00,2000.00,2000.00,0.00,'paid','2025-11-24','2025-11-24',NULL,NULL,NULL,'Payment method: transfer','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-24 02:41:01','2025-11-24 02:41:01',NULL),('a06e361f-4e36-4910-a55e-2fb48167514c','INV-20251124-8529','a06d997a-a49c-4f5c-8f08-0365352ccbce',NULL,'a06ab509-c691-4fe3-9755-dae900810248','walk_in',2000.00,0.00,0.00,2000.00,2000.00,0.00,'paid','2025-11-24','2025-11-24',NULL,NULL,NULL,'Payment method: transfer','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-24 02:42:28','2025-11-24 02:42:28',NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave_requests`
--

DROP TABLE IF EXISTS `leave_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leave_requests` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `leave_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `approval_notes` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leave_requests_leave_number_unique` (`leave_number`),
  KEY `leave_requests_approved_by_foreign` (`approved_by`),
  KEY `leave_requests_created_by_foreign` (`created_by`),
  KEY `leave_requests_staff_id_status_index` (`staff_id`,`status`),
  KEY `leave_requests_branch_id_start_date_index` (`branch_id`,`start_date`),
  KEY `leave_requests_status_start_date_index` (`status`,`start_date`),
  CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `leave_requests_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `leave_requests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `leave_requests_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave_requests`
--

LOCK TABLES `leave_requests` WRITE;
/*!40000 ALTER TABLE `leave_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_points`
--

DROP TABLE IF EXISTS `loyalty_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_points` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_points_earned` int NOT NULL DEFAULT '0',
  `total_points_redeemed` int NOT NULL DEFAULT '0',
  `current_balance` int GENERATED ALWAYS AS ((`total_points_earned` - `total_points_redeemed`)) STORED,
  `membership_tier` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bronze',
  `tier_start_date` date DEFAULT NULL,
  `points_to_next_tier` int DEFAULT NULL,
  `expiring_points` int NOT NULL DEFAULT '0',
  `next_expiry_date` date DEFAULT NULL,
  `lifetime_spending` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_visits` int NOT NULL DEFAULT '0',
  `last_transaction_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loyalty_points_patient_id_unique` (`patient_id`),
  KEY `loyalty_points_membership_tier_index` (`membership_tier`),
  KEY `loyalty_points_current_balance_index` (`current_balance`),
  CONSTRAINT `loyalty_points_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_points`
--

LOCK TABLES `loyalty_points` WRITE;
/*!40000 ALTER TABLE `loyalty_points` DISABLE KEYS */;
/*!40000 ALTER TABLE `loyalty_points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_rewards`
--

DROP TABLE IF EXISTS `loyalty_rewards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_rewards` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reward_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `reward_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points_required` int NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `max_redemptions` int DEFAULT NULL,
  `max_per_patient` int DEFAULT NULL,
  `current_redemptions` int NOT NULL DEFAULT '0',
  `minimum_tier` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `allowed_branches` json DEFAULT NULL,
  `terms_and_conditions` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loyalty_rewards_reward_code_unique` (`reward_code`),
  KEY `loyalty_rewards_service_id_foreign` (`service_id`),
  KEY `loyalty_rewards_created_by_foreign` (`created_by`),
  KEY `loyalty_rewards_is_active_valid_from_valid_to_index` (`is_active`,`valid_from`,`valid_to`),
  KEY `loyalty_rewards_reward_type_is_active_index` (`reward_type`,`is_active`),
  KEY `loyalty_rewards_points_required_index` (`points_required`),
  CONSTRAINT `loyalty_rewards_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `loyalty_rewards_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_rewards`
--

LOCK TABLES `loyalty_rewards` WRITE;
/*!40000 ALTER TABLE `loyalty_rewards` DISABLE KEYS */;
/*!40000 ALTER TABLE `loyalty_rewards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loyalty_transactions`
--

DROP TABLE IF EXISTS `loyalty_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loyalty_transactions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL,
  `balance_before` int NOT NULL,
  `balance_after` int NOT NULL,
  `transaction_date` date NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spending_amount` decimal(10,2) DEFAULT NULL,
  `points_rate` decimal(5,2) DEFAULT NULL,
  `reward_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_expired` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loyalty_transactions_transaction_number_unique` (`transaction_number`),
  KEY `loyalty_transactions_invoice_id_foreign` (`invoice_id`),
  KEY `loyalty_transactions_reward_id_foreign` (`reward_id`),
  KEY `loyalty_transactions_created_by_foreign` (`created_by`),
  KEY `idx_loyalty_tx_patient_date` (`patient_id`,`transaction_date`),
  KEY `idx_loyalty_tx_branch_date` (`branch_id`,`transaction_date`),
  KEY `idx_loyalty_tx_type_date` (`transaction_type`,`transaction_date`),
  KEY `idx_loyalty_tx_expiry` (`expiry_date`,`is_expired`),
  CONSTRAINT `loyalty_transactions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `loyalty_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `loyalty_transactions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `loyalty_transactions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `loyalty_transactions_reward_id_foreign` FOREIGN KEY (`reward_id`) REFERENCES `loyalty_rewards` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loyalty_transactions`
--

LOCK TABLES `loyalty_transactions` WRITE;
/*!40000 ALTER TABLE `loyalty_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `loyalty_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `maintenance_logs`
--

DROP TABLE IF EXISTS `maintenance_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `maintenance_logs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maintenance_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maintenance_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maintenance_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `work_performed` text COLLATE utf8mb4_unicode_ci,
  `performed_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `next_maintenance_date` date DEFAULT NULL,
  `parts_used` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maintenance_logs_maintenance_number_unique` (`maintenance_number`),
  KEY `maintenance_logs_created_by_foreign` (`created_by`),
  KEY `maintenance_logs_equipment_id_maintenance_date_index` (`equipment_id`,`maintenance_date`),
  KEY `maintenance_logs_branch_id_maintenance_date_index` (`branch_id`,`maintenance_date`),
  KEY `maintenance_logs_status_maintenance_date_index` (`status`,`maintenance_date`),
  CONSTRAINT `maintenance_logs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `maintenance_logs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `maintenance_logs_equipment_id_foreign` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `maintenance_logs`
--

LOCK TABLES `maintenance_logs` WRITE;
/*!40000 ALTER TABLE `maintenance_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `maintenance_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_11_01_000001_create_branches_table',1),(6,'2024_11_01_000002_create_roles_table',1),(7,'2024_11_01_000003_create_permissions_table',1),(8,'2024_11_01_000004_create_role_permissions_table',1),(9,'2024_11_01_000005_add_role_id_to_users_table',1),(10,'2024_11_01_000006_create_patients_table',1),(11,'2024_11_01_000007_create_services_table',1),(12,'2024_11_01_000008_create_opd_records_table',1),(13,'2024_11_01_000009_create_patient_notes_table',1),(14,'2024_11_01_000010_create_appointments_table',1),(15,'2024_11_01_000011_create_course_packages_table',1),(16,'2024_11_01_000012_create_pt_service_rates_table',1),(17,'2024_11_01_000013_create_staff_table',1),(18,'2024_11_01_000014_create_queues_table',1),(19,'2024_11_01_000015_create_confirmation_lists_table',1),(20,'2024_11_01_000016_create_commission_rates_table',1),(21,'2024_11_01_000017_create_pt_requests_table',1),(22,'2024_11_01_000018_create_schedules_table',1),(23,'2024_11_01_000019_create_leave_requests_table',1),(24,'2024_11_01_000020_create_evaluations_table',1),(25,'2024_11_01_000021_create_invoices_table',1),(26,'2024_11_01_000022_create_loyalty_points_table',1),(27,'2024_11_01_000023_create_equipment_table',1),(28,'2024_11_01_000024_create_stock_items_table',1),(29,'2024_11_01_000025_create_treatments_table',1),(30,'2024_11_01_000026_create_payments_table',1),(31,'2024_11_01_000027_create_course_purchases_table',1),(32,'2024_11_01_000028_create_loyalty_rewards_table',1),(33,'2024_11_01_000029_create_maintenance_logs_table',1),(34,'2024_11_01_000030_create_treatment_audit_logs_table',1),(35,'2024_11_01_000031_create_follow_up_lists_table',1),(36,'2024_11_01_000032_create_invoice_items_table',1),(37,'2024_11_01_000033_create_documents_table',1),(38,'2024_11_01_000034_create_course_usage_logs_table',1),(39,'2024_11_01_000035_create_course_sharing_table',1),(40,'2024_11_01_000036_create_loyalty_transactions_table',1),(41,'2024_11_01_000037_create_stock_transactions_table',1),(42,'2024_11_01_000038_create_refunds_table',1),(43,'2024_11_01_000039_create_course_renewals_table',1),(44,'2024_11_01_000040_create_pt_replacements_table',1),(45,'2024_11_01_000041_create_commissions_table',1),(46,'2024_11_01_000042_create_df_payments_table',1),(47,'2024_11_01_000043_create_commission_splits_table',1),(48,'2024_11_01_000044_create_audit_logs_table',1),(49,'2024_11_01_000045_create_notifications_table',1),(50,'2025_11_18_150717_create_course_shared_users_table',1),(51,'2025_11_18_200739_add_missing_fields_to_patients_table',1),(52,'2025_11_18_210328_create_revenue_adjustments_table',1),(53,'2025_11_19_034504_add_branch_id_to_patients_services_course_packages_tables',1),(54,'2025_11_20_052305_add_purpose_to_appointments_table',1),(55,'2025_11_21_194724_add_paid_bonus_sessions_to_course_packages_table',1),(56,'2025_11_22_093156_add_is_temporary_and_hn_to_patients_table',2),(57,'2025_11_22_102801_create_service_categories_table',3),(58,'2025_11_22_150939_add_installment_fields_to_course_purchases_table',4),(59,'2025_11_22_190850_create_crm_calls_table',5),(60,'2025_11_23_024114_create_expenses_table',6),(61,'2025_11_23_120907_add_salary_to_users_table',7),(62,'2025_11_23_124411_add_df_amount_to_services_and_course_packages',8),(63,'2025_11_23_124554_add_sellers_to_course_purchases',9),(64,'2025_11_23_233328_add_df_amount_to_treatments_table',10),(65,'2025_11_24_005142_create_df_payments_table',11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notification_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `priority` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `channel` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_app',
  `action_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_text` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `sent_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_created_by_foreign` (`created_by`),
  KEY `notifications_user_id_is_read_created_at_index` (`user_id`,`is_read`,`created_at`),
  KEY `notifications_notification_type_created_at_index` (`notification_type`,`created_at`),
  KEY `notifications_is_sent_sent_at_index` (`is_sent`,`sent_at`),
  KEY `notifications_expires_at_index` (`expires_at`),
  CONSTRAINT `notifications_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opd_records`
--

DROP TABLE IF EXISTS `opd_records`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `opd_records` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opd_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `chief_complaint` text COLLATE utf8mb4_unicode_ci,
  `is_temporary` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_opd_number_unique` (`opd_number`),
  KEY `opd_records_patient_id_foreign` (`patient_id`),
  KEY `opd_records_branch_id_foreign` (`branch_id`),
  KEY `opd_records_created_by_foreign` (`created_by`),
  CONSTRAINT `opd_records_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `opd_records_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `opd_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opd_records`
--

LOCK TABLES `opd_records` WRITE;
/*!40000 ALTER TABLE `opd_records` DISABLE KEYS */;
INSERT INTO `opd_records` VALUES ('a06d81d3-1fee-497f-9753-ef27361d1ae3','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06ab509-c691-4fe3-9755-dae900810248','OPD-20251124-3866','active',NULL,0,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:18:19','2025-11-23 18:18:19',NULL),('a06d8dbf-a01d-4e40-8ba9-51f515333a65','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06ab509-c691-4fe3-9755-dae900810248','OPD-20251124-6259','active',NULL,0,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:51:40','2025-11-23 18:51:46','2025-11-23 18:51:46'),('a06d8dd1-6a49-40f6-b5e8-c189ca532942','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06ab509-c691-4fe3-9755-dae900810248','OPD-20251124-9653','active',NULL,0,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:51:51','2025-11-23 18:51:51',NULL),('a06d9b74-7c51-4f56-9ebf-3feb76b8b8d7','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248','OPD-20251124-2568','active',NULL,0,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:29:59','2025-11-23 19:29:59',NULL);
/*!40000 ALTER TABLE `opd_records` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patient_notes`
--

DROP TABLE IF EXISTS `patient_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patient_notes` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `note_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_important` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_notes_created_by_foreign` (`created_by`),
  KEY `patient_notes_patient_id_created_at_index` (`patient_id`,`created_at`),
  CONSTRAINT `patient_notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `patient_notes_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patient_notes`
--

LOCK TABLES `patient_notes` WRITE;
/*!40000 ALTER TABLE `patient_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `patient_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_temporary` tinyint(1) NOT NULL DEFAULT '1',
  `hn_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `converted_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name_en` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name_en` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_card` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blood_group` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `subdistrict` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `chronic_diseases` text COLLATE utf8mb4_unicode_ci,
  `drug_allergy` text COLLATE utf8mb4_unicode_ci,
  `food_allergy` text COLLATE utf8mb4_unicode_ci,
  `surgery_history` text COLLATE utf8mb4_unicode_ci,
  `chief_complaint` text COLLATE utf8mb4_unicode_ci,
  `insurance_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `insurance_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_channel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_visit_branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `patients_id_card_unique` (`id_card`),
  UNIQUE KEY `patients_hn_number_unique` (`hn_number`),
  KEY `patients_first_visit_branch_id_foreign` (`first_visit_branch_id`),
  KEY `patients_phone_index` (`phone`),
  KEY `patients_branch_id_foreign` (`branch_id`),
  CONSTRAINT `patients_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `patients_first_visit_branch_id_foreign` FOREIGN KEY (`first_visit_branch_id`) REFERENCES `branches` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patients`
--

LOCK TABLES `patients` WRITE;
/*!40000 ALTER TABLE `patients` DISABLE KEYS */;
INSERT INTO `patients` VALUES ('a06d81c4-48dc-4739-a7ee-772e0e292d3a',0,'HN000001','2025-11-23 18:18:19','0965212121',NULL,'เทส1','',NULL,NULL,NULL,'เทส1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ปวดหลัง',NULL,NULL,'line',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06ab509-c691-4fe3-9755-dae900810248','2025-11-23 18:18:10','2025-11-23 18:18:19',NULL),('a06d8db0-32f1-4f07-993b-716cb7764d7a',0,'HN000002','2025-11-23 18:51:51','0121555555',NULL,'เทส2','',NULL,NULL,NULL,'เทส2',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ปวดคอ บ่า ไหล่',NULL,NULL,'line',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06ab509-c691-4fe3-9755-dae900810248','2025-11-23 18:51:30','2025-11-23 18:51:51',NULL),('a06d997a-a49c-4f5c-8f08-0365352ccbce',0,'HN000003','2025-11-23 19:29:59','0212784212',NULL,'กด','',NULL,NULL,NULL,'กด',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'ปวดหลัง',NULL,NULL,'phone',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06ab509-c691-4fe3-9755-dae900810248','2025-11-23 19:24:28','2025-11-23 19:29:59',NULL);
/*!40000 ALTER TABLE `patients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `payment_date` date NOT NULL,
  `reference_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_last_4` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `installment_number` int DEFAULT NULL,
  `total_installments` int DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_payment_number_unique` (`payment_number`),
  KEY `payments_created_by_foreign` (`created_by`),
  KEY `payments_invoice_id_index` (`invoice_id`),
  KEY `payments_patient_id_payment_date_index` (`patient_id`,`payment_date`),
  KEY `payments_branch_id_payment_date_index` (`branch_id`,`payment_date`),
  KEY `payments_status_payment_date_index` (`status`,`payment_date`),
  CONSTRAINT `payments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `payments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES ('a06c91ee-9bb0-4899-baf3-e87fadf07281','PAY-20251123-6343','a06c91ee-33ba-451c-990e-47b0d9d9e98f','a06ace71-7cfc-458c-b439-b3f3229a17a5','a06ab509-c691-4fe3-9755-dae900810248',6000.00,'transfer','completed','2025-11-23',NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 07:07:32','2025-11-23 07:07:32',NULL),('a06cf7e3-2ee3-4248-9103-dce085ff87f5','PAY-20251123-7069','a06cf7e3-2d82-45c7-92b5-64b59b0e70fc','a06ace71-7cfc-458c-b439-b3f3229a17a5','a06ab509-c691-4fe3-9755-dae900810248',6000.00,'transfer','completed','2025-11-23',NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 11:52:37','2025-11-23 11:52:37',NULL),('a06cfa70-8774-42bd-bf8c-ee2792cb9c65','PAY-20251123-6749','a06cfa70-85da-43e1-b2c4-4083882892b3','a06ace71-7cfc-458c-b439-b3f3229a17a5','a06ab509-c691-4fe3-9755-dae900810248',6000.00,'transfer','completed','2025-11-23',NULL,NULL,NULL,NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 11:59:45','2025-11-23 11:59:45',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_module_action_unique` (`module`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES ('a06b9ec9-5470-4faa-9564-107013d4f98b','patients','access','Access to patients','2025-11-22 19:47:39','2025-11-22 19:47:39'),('a06b9ec9-54f8-467d-bcee-26fd6d037b30','appointments','access','Access to appointments','2025-11-22 19:47:39','2025-11-22 19:47:39'),('a06b9ec9-5581-433d-8901-f659dd2f2847','billing','access','Access to billing','2025-11-22 19:47:39','2025-11-22 19:47:39'),('a06b9ec9-55ed-46ea-89c9-0a71f4e206be','services','access','Access to services','2025-11-22 19:47:39','2025-11-22 19:47:39'),('a06b9ec9-565d-4672-8281-dbbe5be1ee3e','reports','access','Access to reports','2025-11-22 19:47:39','2025-11-22 19:47:39'),('a06b9ec9-56bc-4f54-9ed9-bd471a472902','settings','access','Access to settings','2025-11-22 19:47:39','2025-11-22 19:47:39');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pt_replacements`
--

DROP TABLE IF EXISTS `pt_replacements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pt_replacements` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `replacement_pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `replacement_date` date NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `commission_handling` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'replacement',
  `commission_split_percentage` decimal(5,2) DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pt_replacements_appointment_id_foreign` (`appointment_id`),
  KEY `pt_replacements_treatment_id_foreign` (`treatment_id`),
  KEY `pt_replacements_created_by_foreign` (`created_by`),
  KEY `pt_replacements_original_pt_id_replacement_date_index` (`original_pt_id`,`replacement_date`),
  KEY `pt_replacements_replacement_pt_id_replacement_date_index` (`replacement_pt_id`,`replacement_date`),
  KEY `pt_replacements_branch_id_replacement_date_index` (`branch_id`,`replacement_date`),
  CONSTRAINT `pt_replacements_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `pt_replacements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `pt_replacements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `pt_replacements_original_pt_id_foreign` FOREIGN KEY (`original_pt_id`) REFERENCES `staff` (`id`),
  CONSTRAINT `pt_replacements_replacement_pt_id_foreign` FOREIGN KEY (`replacement_pt_id`) REFERENCES `staff` (`id`),
  CONSTRAINT `pt_replacements_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pt_replacements`
--

LOCK TABLES `pt_replacements` WRITE;
/*!40000 ALTER TABLE `pt_replacements` DISABLE KEYS */;
/*!40000 ALTER TABLE `pt_replacements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pt_requests`
--

DROP TABLE IF EXISTS `pt_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pt_requests` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_pt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requested_pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `requested_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `processed_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pt_requests_patient_id_foreign` (`patient_id`),
  KEY `pt_requests_branch_id_foreign` (`branch_id`),
  KEY `pt_requests_original_pt_id_foreign` (`original_pt_id`),
  KEY `pt_requests_processed_by_foreign` (`processed_by`),
  KEY `pt_requests_created_by_foreign` (`created_by`),
  KEY `pt_requests_appointment_id_status_index` (`appointment_id`,`status`),
  KEY `pt_requests_requested_pt_id_status_index` (`requested_pt_id`,`status`),
  CONSTRAINT `pt_requests_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `pt_requests_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `pt_requests_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `pt_requests_original_pt_id_foreign` FOREIGN KEY (`original_pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `pt_requests_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `pt_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `pt_requests_requested_pt_id_foreign` FOREIGN KEY (`requested_pt_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pt_requests`
--

LOCK TABLES `pt_requests` WRITE;
/*!40000 ALTER TABLE `pt_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `pt_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pt_service_rates`
--

DROP TABLE IF EXISTS `pt_service_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pt_service_rates` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `commission_rate` decimal(5,2) DEFAULT NULL,
  `df_rate` decimal(5,2) DEFAULT NULL,
  `effective_from` date DEFAULT NULL,
  `effective_to` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_pt_service_branch_unique` (`pt_id`,`service_id`,`branch_id`),
  KEY `pt_service_rates_service_id_foreign` (`service_id`),
  KEY `pt_service_rates_branch_id_foreign` (`branch_id`),
  KEY `pt_service_rates_created_by_foreign` (`created_by`),
  KEY `pt_service_rates_pt_id_is_active_index` (`pt_id`,`is_active`),
  CONSTRAINT `pt_service_rates_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `pt_service_rates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `pt_service_rates_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `pt_service_rates_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pt_service_rates`
--

LOCK TABLES `pt_service_rates` WRITE;
/*!40000 ALTER TABLE `pt_service_rates` DISABLE KEYS */;
/*!40000 ALTER TABLE `pt_service_rates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queues`
--

DROP TABLE IF EXISTS `queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queues` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue_number` int NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `queued_at` timestamp NULL DEFAULT NULL,
  `called_at` timestamp NULL DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `waiting_time_minutes` int DEFAULT NULL,
  `is_overtime` tinyint(1) NOT NULL DEFAULT '0',
  `overtime_warning_sent_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `queues_appointment_id_foreign` (`appointment_id`),
  KEY `queues_patient_id_foreign` (`patient_id`),
  KEY `queues_created_by_foreign` (`created_by`),
  KEY `queues_branch_id_created_at_status_index` (`branch_id`,`created_at`,`status`),
  KEY `queues_pt_id_status_index` (`pt_id`,`status`),
  KEY `queues_status_queued_at_index` (`status`,`queued_at`),
  CONSTRAINT `queues_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `queues_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `queues_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `queues_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `queues_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queues`
--

LOCK TABLES `queues` WRITE;
/*!40000 ALTER TABLE `queues` DISABLE KEYS */;
INSERT INTO `queues` VALUES ('a06d81c4-4be4-44c1-b9b4-b4181584b15b','a06d81c4-4b5e-4f5e-8076-72ce3ceb53e0','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06ab509-c691-4fe3-9755-dae900810248',NULL,1,'in_treatment','2025-11-23 18:18:10',NULL,NULL,NULL,NULL,0,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:18:10','2025-11-23 18:18:16',NULL),('a06d8db0-3708-49df-b219-02bc00c4796d','a06d8db0-363b-4de1-b6e7-c469ff1610a8','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06ab509-c691-4fe3-9755-dae900810248',NULL,2,'in_treatment','2025-11-23 18:51:30',NULL,NULL,NULL,NULL,0,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:51:30','2025-11-23 18:51:37',NULL),('a06d9712-3970-48a1-9885-e46070adbdf7','a06d9712-3798-4bf0-84e5-f73c5b6c6a2f','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06ab509-c691-4fe3-9755-dae900810248',NULL,3,'in_treatment','2025-11-23 19:17:44',NULL,NULL,NULL,NULL,0,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:17:44','2025-11-23 19:17:51',NULL),('a06d97bd-4a75-45de-b69c-dffddf5a74a3','a06d97bd-48ac-48a4-86f0-7deb57fd6b6f','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06ab509-c691-4fe3-9755-dae900810248',NULL,4,'in_treatment','2025-11-23 19:19:36',NULL,NULL,NULL,NULL,0,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:19:36','2025-11-23 19:19:49',NULL),('a06d997a-a7f9-4804-adaa-088fe123beb6','a06d997a-a759-48eb-83e6-ecd233e8b979','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248',NULL,5,'in_treatment','2025-11-23 19:24:28',NULL,NULL,NULL,NULL,0,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:24:28','2025-11-23 19:29:56',NULL),('a06d9bc2-721b-4ad9-829d-82856dd12dea','a06d9bc2-6fa7-42ad-b0d7-f3400f46c7a9','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248',NULL,6,'in_treatment','2025-11-23 19:30:50',NULL,NULL,NULL,NULL,0,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:30:50','2025-11-23 19:30:56',NULL),('a06e35eb-8898-4cca-9ee3-85374a3d7231','a06e35eb-86fd-4695-9144-c48de3dc5582','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248',NULL,7,'in_treatment','2025-11-24 02:41:54',NULL,NULL,NULL,NULL,0,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-24 02:41:54','2025-11-24 02:42:04',NULL);
/*!40000 ALTER TABLE `queues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refunds` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refund_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refund_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refund_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `refund_date` date NOT NULL,
  `original_amount` decimal(10,2) NOT NULL,
  `used_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `penalty_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `calculation_notes` text COLLATE utf8mb4_unicode_ci,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `refund_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refunds_refund_number_unique` (`refund_number`),
  KEY `refunds_approved_by_foreign` (`approved_by`),
  KEY `refunds_created_by_foreign` (`created_by`),
  KEY `refunds_invoice_id_index` (`invoice_id`),
  KEY `refunds_patient_id_status_index` (`patient_id`,`status`),
  KEY `refunds_branch_id_refund_date_index` (`branch_id`,`refund_date`),
  KEY `refunds_status_refund_date_index` (`status`,`refund_date`),
  CONSTRAINT `refunds_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `refunds_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `refunds_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `refunds_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `refunds_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refunds`
--

LOCK TABLES `refunds` WRITE;
/*!40000 ALTER TABLE `refunds` DISABLE KEYS */;
INSERT INTO `refunds` VALUES ('a06b38ab-f35c-4252-8daf-c66cbef861ab','REF-20251122-57252','a06b3886-ac10-4d4a-b010-3b6efceae66b','a06adc01-3b02-41aa-a9d7-352c15aaa927','a06ab509-c691-4fe3-9755-dae900810248','course_cancellation',4800.00,'approved','2025-11-22',6000.00,1200.00,0.00,'Used sessions: 1/6. Full price per session: 1200.00','ยกเลิกเทส','2025-11-22 08:02:07','a06ab54a-a5ca-4f14-9b95-cb24b32bad55',NULL,'bank_transfer',NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-22 08:02:07','2025-11-22 08:02:07',NULL),('a06d99cf-5520-4e19-82a4-a4576c9d35d8','REF-20251124-0001','a06d96c3-6854-46a7-bd2e-fec556c53f0f','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06ab509-c691-4fe3-9755-dae900810248','course_cancellation',4000.00,'completed','2025-11-24',6000.00,2000.00,0.00,'คืนเงิน 4/6 ครั้ง @ 1,000.00 บาท/ครั้ง','แปออปแอปแอปแอปแอปป','2025-11-23 19:25:23','a06ab54a-a5ca-4f14-9b95-cb24b32bad55',NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:25:23','2025-11-23 19:25:23',NULL),('a06e3937-3657-4771-a027-54b605cff0ca','REF-20251124-0002','a06d9b93-639f-49bd-a643-dc652729df40','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06ab509-c691-4fe3-9755-dae900810248','course_cancellation',2400.00,'completed','2025-11-24',6000.00,3600.00,0.00,'จ่ายจริง 6,000.00 บาท | ใช้ 3 ครั้ง × 1,200.00 = 3,600.00 บาท | คืน 2,400.00 บาท','กดกดกดกดก','2025-11-24 02:51:07','a06ab54a-a5ca-4f14-9b95-cb24b32bad55',NULL,NULL,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-24 02:51:07','2025-11-24 03:13:46',NULL);
/*!40000 ALTER TABLE `refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revenue_adjustments`
--

DROP TABLE IF EXISTS `revenue_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `revenue_adjustments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refund_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adjustment_type` enum('refund','discount','correction') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'refund',
  `adjustment_amount` decimal(10,2) NOT NULL,
  `effective_date` date NOT NULL,
  `adjustment_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revenue_adjustments_invoice_id_foreign` (`invoice_id`),
  KEY `revenue_adjustments_refund_id_foreign` (`refund_id`),
  KEY `revenue_adjustments_created_by_foreign` (`created_by`),
  KEY `revenue_adjustments_effective_date_index` (`effective_date`),
  KEY `revenue_adjustments_adjustment_date_index` (`adjustment_date`),
  KEY `revenue_adjustments_branch_id_effective_date_index` (`branch_id`,`effective_date`),
  CONSTRAINT `revenue_adjustments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `revenue_adjustments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `revenue_adjustments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `revenue_adjustments_refund_id_foreign` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revenue_adjustments`
--

LOCK TABLES `revenue_adjustments` WRITE;
/*!40000 ALTER TABLE `revenue_adjustments` DISABLE KEYS */;
INSERT INTO `revenue_adjustments` VALUES ('a06b38ab-f5c8-46f3-95e5-5f80f9255d0b','a06b3886-ac10-4d4a-b010-3b6efceae66b','a06b38ab-f35c-4252-8daf-c66cbef861ab','a06ab509-c691-4fe3-9755-dae900810248','refund',-4800.00,'2025-11-22','2025-11-22','Revenue adjustment for refund REF-20251122-57252','Original invoice: INV-20251122-8383. Refund amount: 4,800.00','a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-22 08:02:07','2025-11-22 08:02:07',NULL);
/*!40000 ALTER TABLE `revenue_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_role_permission_unique` (`role_id`,`permission_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES ('a06ab4dc-1b9c-4bf8-9007-fc33bc30c723','Admin','System Administrator - Full access to all features',1,'2025-11-22 01:53:33','2025-11-22 01:53:33',NULL),('a06ab4dc-1da9-464d-b953-f9e610d11efe','Manager','Branch Manager - Manage branch operations and reports',1,'2025-11-22 01:53:33','2025-11-22 01:53:33',NULL),('a06ab4dc-1e2f-41b8-ac39-0289ca6345ea','PT','Physical Therapist - Provide treatments and manage patient care',1,'2025-11-22 01:53:33','2025-11-22 01:53:33',NULL),('a06ab4dc-1e99-4d36-bd86-06cd4ea589d4','Receptionist','Receptionist - Handle appointments, queue, and patient registration',1,'2025-11-22 01:53:33','2025-11-22 01:53:33',NULL),('a06ab4dc-1f04-402f-8394-eabd40be18f8','Accountant','Accountant - Manage billing, invoices, and financial reports',1,'2025-11-22 01:53:33','2025-11-22 01:53:33',NULL),('a06b9ec9-5262-4f8b-8249-52543a70aa74','Area Manger','ผู้จัดการสาขาทั้งหมด',0,'2025-11-22 19:47:39','2025-11-22 19:47:39',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedules` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `schedule_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'regular',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurrence_pattern` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recurrence_end_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_created_by_foreign` (`created_by`),
  KEY `schedules_staff_id_schedule_date_index` (`staff_id`,`schedule_date`),
  KEY `schedules_branch_id_schedule_date_is_available_index` (`branch_id`,`schedule_date`,`is_available`),
  KEY `schedules_schedule_date_status_index` (`schedule_date`,`status`),
  CONSTRAINT `schedules_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `schedules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `schedules_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_categories`
--

DROP TABLE IF EXISTS `service_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_categories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_categories`
--

LOCK TABLES `service_categories` WRITE;
/*!40000 ALTER TABLE `service_categories` DISABLE KEYS */;
INSERT INTO `service_categories` VALUES ('75fcb374-17ce-43bc-bc74-fdbb1a867c18','ฉีดเข่า','INJ',NULL,1,2,'2025-11-22 03:30:22','2025-11-22 03:30:22'),('79a60939-5c79-4e15-aa16-915af15466af','แพทย์แผนจีน','TCM',NULL,1,3,'2025-11-22 03:30:22','2025-11-22 03:30:22'),('ec9c1bdf-d90b-4e66-b28b-ebf4eba17efa','กายภาพบำบัด','PT',NULL,1,1,'2025-11-22 03:30:22','2025-11-22 03:30:22');
/*!40000 ALTER TABLE `service_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_price` decimal(10,2) NOT NULL,
  `default_duration_minutes` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_package` tinyint(1) NOT NULL DEFAULT '0',
  `package_sessions` int DEFAULT NULL,
  `package_validity_days` int DEFAULT NULL,
  `default_commission_rate` decimal(5,2) DEFAULT NULL,
  `default_df_rate` decimal(5,2) DEFAULT NULL,
  `df_amount` decimal(10,2) DEFAULT NULL COMMENT 'ค่ามือ PT (บาท)',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_code_unique` (`code`),
  KEY `services_created_by_foreign` (`created_by`),
  KEY `services_category_is_active_index` (`category`,`is_active`),
  KEY `services_is_package_is_active_index` (`is_package`,`is_active`),
  KEY `services_branch_id_foreign` (`branch_id`),
  KEY `services_category_id_foreign` (`category_id`),
  CONSTRAINT `services_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `services_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES ('a06aed59-24bc-4afa-adc1-31416ef2496e','กายภาพพื้นฐาน (โปรโมชั่นลูกค้าไหม่ครั้งแรก)','GSR001','อัลตราซาวด์','กายภาพบำบัด','ec9c1bdf-d90b-4e66-b28b-ebf4eba17efa',999.00,60,1,0,NULL,NULL,NULL,20.00,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','a06ab509-c691-4fe3-9755-dae900810248','2025-11-22 04:31:30','2025-11-23 17:27:17',NULL),('a06aee14-6fc3-4bf1-983f-7721baa75159','กายภาพพื้นฐาน','GSR002','อัลตราซาวด์','กายภาพบำบัด','ec9c1bdf-d90b-4e66-b28b-ebf4eba17efa',1200.00,60,1,0,NULL,NULL,NULL,40.00,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','a06ab509-c691-4fe3-9755-dae900810248','2025-11-22 04:33:32','2025-11-23 17:27:10',NULL),('a06aee4d-0270-455d-9272-26ed5c6e2916','กายภาพพื้นฐาน + PMS','GSR003','กายภาพพื้นฐาน + PMS','กายภาพบำบัด','ec9c1bdf-d90b-4e66-b28b-ebf4eba17efa',1800.00,60,1,0,NULL,NULL,NULL,40.00,NULL,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','a06ab509-c691-4fe3-9755-dae900810248','2025-11-22 04:34:10','2025-11-22 04:34:10',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hire_date` date NOT NULL,
  `termination_date` date DEFAULT NULL,
  `employment_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `employment_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_expiry` date DEFAULT NULL,
  `certifications` json DEFAULT NULL,
  `base_salary` decimal(10,2) DEFAULT NULL,
  `salary_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `staff_user_id_unique` (`user_id`),
  UNIQUE KEY `staff_employee_id_unique` (`employee_id`),
  KEY `staff_created_by_foreign` (`created_by`),
  KEY `staff_branch_id_employment_status_index` (`branch_id`,`employment_status`),
  KEY `staff_position_employment_status_index` (`position`,`employment_status`),
  CONSTRAINT `staff_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `staff_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `staff_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_items`
--

DROP TABLE IF EXISTS `stock_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_on_hand` int NOT NULL DEFAULT '0',
  `minimum_quantity` int NOT NULL DEFAULT '0',
  `maximum_quantity` int DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_item_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_low_stock` tinyint(1) GENERATED ALWAYS AS ((`quantity_on_hand` <= `minimum_quantity`)) STORED,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_items_item_code_unique` (`item_code`),
  KEY `stock_items_created_by_foreign` (`created_by`),
  KEY `stock_items_branch_id_is_active_index` (`branch_id`,`is_active`),
  KEY `stock_items_category_is_active_index` (`category`,`is_active`),
  KEY `stock_items_is_low_stock_index` (`is_low_stock`),
  CONSTRAINT `stock_items_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `stock_items_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_items`
--

LOCK TABLES `stock_items` WRITE;
/*!40000 ALTER TABLE `stock_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_transactions`
--

DROP TABLE IF EXISTS `stock_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_transactions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_item_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `quantity_before` int NOT NULL,
  `quantity_after` int NOT NULL,
  `transaction_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `from_branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `total_cost` decimal(10,2) DEFAULT NULL,
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_transactions_transaction_number_unique` (`transaction_number`),
  KEY `stock_transactions_from_branch_id_foreign` (`from_branch_id`),
  KEY `stock_transactions_to_branch_id_foreign` (`to_branch_id`),
  KEY `stock_transactions_treatment_id_foreign` (`treatment_id`),
  KEY `stock_transactions_created_by_foreign` (`created_by`),
  KEY `stock_transactions_stock_item_id_transaction_date_index` (`stock_item_id`,`transaction_date`),
  KEY `stock_transactions_branch_id_transaction_date_index` (`branch_id`,`transaction_date`),
  KEY `stock_transactions_transaction_type_transaction_date_index` (`transaction_type`,`transaction_date`),
  CONSTRAINT `stock_transactions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `stock_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `stock_transactions_from_branch_id_foreign` FOREIGN KEY (`from_branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `stock_transactions_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`),
  CONSTRAINT `stock_transactions_to_branch_id_foreign` FOREIGN KEY (`to_branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `stock_transactions_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_transactions`
--

LOCK TABLES `stock_transactions` WRITE;
/*!40000 ALTER TABLE `stock_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatment_audit_logs`
--

DROP TABLE IF EXISTS `treatment_audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treatment_audit_logs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `treatment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `changes` json DEFAULT NULL,
  `performed_by` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_audit_logs_treatment_id_created_at_index` (`treatment_id`,`created_at`),
  KEY `treatment_audit_logs_performed_by_created_at_index` (`performed_by`,`created_at`),
  KEY `treatment_audit_logs_action_index` (`action`),
  CONSTRAINT `treatment_audit_logs_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`),
  CONSTRAINT `treatment_audit_logs_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatment_audit_logs`
--

LOCK TABLES `treatment_audit_logs` WRITE;
/*!40000 ALTER TABLE `treatment_audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `treatment_audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `treatments`
--

DROP TABLE IF EXISTS `treatments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `treatments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `opd_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `patient_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `appointment_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pt_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chief_complaint` text COLLATE utf8mb4_unicode_ci,
  `vital_signs` json DEFAULT NULL,
  `assessment` text COLLATE utf8mb4_unicode_ci,
  `diagnosis` text COLLATE utf8mb4_unicode_ci,
  `treatment_plan` text COLLATE utf8mb4_unicode_ci,
  `treatment_notes` text COLLATE utf8mb4_unicode_ci,
  `home_program` text COLLATE utf8mb4_unicode_ci,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `course_purchase_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `df_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'ค่ามือ PT ต่อครั้ง',
  `created_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatments_opd_id_foreign` (`opd_id`),
  KEY `treatments_appointment_id_foreign` (`appointment_id`),
  KEY `treatments_queue_id_foreign` (`queue_id`),
  KEY `treatments_service_id_foreign` (`service_id`),
  KEY `treatments_invoice_id_foreign` (`invoice_id`),
  KEY `treatments_created_by_foreign` (`created_by`),
  KEY `treatments_patient_id_created_at_index` (`patient_id`,`created_at`),
  KEY `treatments_pt_id_created_at_index` (`pt_id`,`created_at`),
  KEY `treatments_branch_id_created_at_index` (`branch_id`,`created_at`),
  KEY `treatments_billing_status_index` (`billing_status`),
  CONSTRAINT `treatments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  CONSTRAINT `treatments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `treatments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `treatments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `treatments_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opd_records` (`id`),
  CONSTRAINT `treatments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `treatments_pt_id_foreign` FOREIGN KEY (`pt_id`) REFERENCES `users` (`id`),
  CONSTRAINT `treatments_queue_id_foreign` FOREIGN KEY (`queue_id`) REFERENCES `queues` (`id`),
  CONSTRAINT `treatments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `treatments`
--

LOCK TABLES `treatments` WRITE;
/*!40000 ALTER TABLE `treatments` DISABLE KEYS */;
INSERT INTO `treatments` VALUES ('a06d81d3-2191-4542-9188-f3c0a5ab98c4','a06d81d3-1fee-497f-9753-ef27361d1ae3','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06d81c4-4b5e-4f5e-8076-72ce3ceb53e0',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06af3c8-0999-4acc-b21c-acf7763ccb9d','a06aee14-6fc3-4bf1-983f-7721baa75159',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-23 18:18:16','2025-11-23 18:18:19',0,'a06d81de-8ead-4702-8b56-b08e2835f74e',NULL,'paid',40.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:18:19','2025-11-23 19:09:13',NULL),('a06d8dbf-a299-4651-b963-8bf3250e2f49','a06d8dbf-a01d-4e40-8ba9-51f515333a65','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06d8db0-363b-4de1-b6e7-c469ff1610a8',NULL,'a06ab509-c691-4fe3-9755-dae900810248',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-23 18:51:37','2025-11-23 18:51:40',0,NULL,NULL,'pending',0.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:51:40','2025-11-23 18:51:46','2025-11-23 18:51:46'),('a06d8dd1-6c92-44b2-a13d-edaecb68123c','a06d8dd1-6a49-40f6-b5e8-c189ca532942','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06d8db0-363b-4de1-b6e7-c469ff1610a8',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06af3c8-0999-4acc-b21c-acf7763ccb9d',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-23 18:51:46','2025-11-23 18:51:51',0,'a06d96c3-6854-46a7-bd2e-fec556c53f0f',NULL,'paid',0.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 18:51:51','2025-11-23 19:16:52',NULL),('a06d9720-c76c-4ba4-8f7a-c268778f9fc9','a06d8dd1-6a49-40f6-b5e8-c189ca532942','a06d8db0-32f1-4f07-993b-716cb7764d7a','a06d9712-3798-4bf0-84e5-f73c5b6c6a2f',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06af39a-6d63-4944-a7dc-035c37cc596c',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-23 19:17:51','2025-11-23 19:17:53',0,'a06d9743-8ea8-49db-9f43-2e3418da57f4','a06d96c3-6b16-441d-aedf-42caac620ee9','paid',30.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:17:53','2025-11-23 19:18:16',NULL),('a06d97d6-681b-4fb4-83fb-29dd52175538','a06d81d3-1fee-497f-9753-ef27361d1ae3','a06d81c4-48dc-4739-a7ee-772e0e292d3a','a06d97bd-48ac-48a4-86f0-7deb57fd6b6f',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06af3c8-0999-4acc-b21c-acf7763ccb9d',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-23 19:19:49','2025-11-23 19:19:52',0,'a06d9835-0752-4b3d-af04-c92e46a0d394','a06d8a41-8466-4a35-8e05-3858acef9f98','paid',30.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:19:52','2025-11-23 19:20:54',NULL),('a06d9b74-7dde-4942-9fe0-480022508bba','a06d9b74-7c51-4f56-9ebf-3feb76b8b8d7','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06d997a-a759-48eb-83e6-ecd233e8b979',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06af39a-6d63-4944-a7dc-035c37cc596c',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-23 19:29:56','2025-11-23 19:29:59',0,'a06d9b93-639f-49bd-a643-dc652729df40',NULL,'paid',0.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:29:59','2025-11-23 19:30:20',NULL),('a06d9bcf-a34c-421c-a2a0-53715e9139e3','a06d9b74-7c51-4f56-9ebf-3feb76b8b8d7','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06d9bc2-6fa7-42ad-b0d7-f3400f46c7a9',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06af39a-6d63-4944-a7dc-035c37cc596c',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-23 19:30:56','2025-11-23 19:30:59',0,'a06e359a-65f5-4304-a70f-edfc3f60afd9','a06d9b93-65c5-4bf3-8c18-36805c05d6c7','paid',30.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-23 19:30:59','2025-11-24 02:41:01',NULL),('a06e3600-0c43-4f60-96b1-7bf9f62112b8','a06d9b74-7c51-4f56-9ebf-3feb76b8b8d7','a06d997a-a49c-4f5c-8f08-0365352ccbce','a06e35eb-86fd-4695-9144-c48de3dc5582',NULL,'a06ab509-c691-4fe3-9755-dae900810248','a06af39a-6d63-4944-a7dc-035c37cc596c',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-11-24 02:42:04','2025-11-24 02:42:08',0,'a06e361f-4e36-4910-a55e-2fb48167514c','a06d9b93-65c5-4bf3-8c18-36805c05d6c7','paid',30.00,'a06ab54a-a5ca-4f14-9b95-cb24b32bad55','2025-11-24 02:42:08','2025-11-24 02:42:28',NULL);
/*!40000 ALTER TABLE `treatments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary` decimal(12,2) DEFAULT NULL COMMENT 'เงินเดือน',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_role_id_is_active_index` (`role_id`,`is_active`),
  KEY `users_branch_id_is_active_index` (`branch_id`,`is_active`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('a06ab54a-a5ca-4f14-9b95-cb24b32bad55','admin','Admin GCMS','admin@gcms.com',1,'2025-11-24 05:43:07',NULL,'$2y$12$bxfZw.KqPPLUnUw.B.kMiut59EcyWieFLAHjZM1Gc3vveS8oo3.la',NULL,'2025-11-22 01:54:45','2025-11-24 05:44:28','a06b9ec9-5262-4f8b-8249-52543a70aa74','a06ab509-c691-4fe3-9755-dae900810248',42000.00,NULL),('a06af39a-6d63-4944-a7dc-035c37cc596c','matoy','มะตอย','matoy@gmail.com',1,NULL,NULL,'$2y$12$cJUO.Y.CLQ9FZQWtEOu1HOQcHRGB5oBn2H2Ek7pPQ2FP013KY/cti',NULL,'2025-11-22 04:48:59','2025-11-23 05:33:55','a06ab4dc-1e2f-41b8-ac39-0289ca6345ea','a06ab509-c691-4fe3-9755-dae900810248',19000.00,NULL),('a06af3c8-0999-4acc-b21c-acf7763ccb9d','jajapt','jaja','jaja@gmail.com',1,NULL,NULL,'$2y$12$dSgA8MDa6c3JVNSw7s1Ci.HR1Fbg75lv32L3Rbfz95bzEEnJKDQLO',NULL,'2025-11-22 04:49:29','2025-11-23 05:33:22','a06ab4dc-1e2f-41b8-ac39-0289ca6345ea','a06ab509-c691-4fe3-9755-dae900810248',19000.00,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-24 19:34:57
