CREATE TABLE IF NOT EXISTS `users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
`fullname` varchar(100) NOT NULL,
 `email` varchar(150) NOT NULL,
 `username` varchar(100) NOT NULL,
 `password` varchar(100) NOT NULL,
 `create_at` datetime NOT NULL,
 PRIMARY KEY (`id`)
);