DROP DATABASE IF EXISTS quizmaker;
CREATE DATABASE quizmaker;
USE quizmaker;

CREATE TABLE quizzes (
    quiz_id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(255) NOT NULL,
    beschrijving TEXT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vragen (
    vraag_id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    vraagtekst TEXT NOT NULL,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(quiz_id) ON DELETE CASCADE
);

CREATE TABLE antwoorden (
    antwoord_id INT AUTO_INCREMENT PRIMARY KEY,
    vraag_id INT NOT NULL,
    antwoordtekst VARCHAR(255) NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (vraag_id) REFERENCES vragen(vraag_id) ON DELETE CASCADE
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
