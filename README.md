# CodeIgniter 3 - PHP Simple Login System
A user login, logout, register ready to use for CodeIgniter 3

## Requirements
- PHP 7.2 or greater

## Includes
- [CodeIgniter 3.1.11](https://codeigniter.com/)
- [SB Admin 2](https://startbootstrap.com/themes/sb-admin-2/)

## Installation
1. Open `config.php` and edit your domain, and database settings.
2. In your database server, open a SQL terminal paste this and execute:
```
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
    `id` varchar(128) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` int(10) UNSIGNED DEFAULT 0 NOT NULL,
    `data` blob NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `remember_token` varchar(255) DEFAULT NULL,
    `created_at` int(11) NOT NULL DEFAULT 0,
    `updated_at` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
);
```
3. Go to http://example.com/register and create a user account

## Usage
It is just a starter for user login logout register functionalities.

We do not recommend you use this, this is only a example and maybe not safe.
