DROP DATABASE IF EXISTS `results`;

CREATE DATABASE `results`;

USE `results`;

CREATE TABLE `questions` (
    id int AUTO_INCREMENT PRIMARY KEY,
    question varchar(100),
    answer varchar(100),
    input varchar(100)
);