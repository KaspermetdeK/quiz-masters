DROP DATABASE IF EXISTS `results`;

CREATE DATABASE `results`;

USE `results`;

CREATE TABLE `questions` (
    id int AUTO_INCREMENT PRIMARY KEY,
    question varchar(100),
    answer varchar(100),
    input varchar(100)
);

INSERT INTO questions (`question`, `answer`, `input`) values ('1', 'true', 'true');
INSERT INTO questions (`question`, `answer`, `input`) values ('2', 'false', 'false');
INSERT INTO questions (`question`, `answer`, `input`) values ('3', 'true', 'false');
INSERT INTO questions (`question`, `answer`, `input`) values ('4', 'false', 'true');

