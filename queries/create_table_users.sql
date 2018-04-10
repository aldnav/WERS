/*--
Table structure for table `users`
--*/

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
	  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
	  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
	  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,  /*https://stackoverflow.com/a/23262531/3792514*/
	  `created_at` timestamp NULL DEFAULT NULL,
	  `updated_at` timestamp NULL DEFAULT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
