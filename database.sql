CREATE DATABASE IF NOT EXISTS bitacademy_quiz;
USE bitacademy_quiz;

CREATE TABLE moeilijkheden (
    moeilijkheid_id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE vragen (
    vraag_id INT AUTO_INCREMENT PRIMARY KEY,
    vraagtekst VARCHAR(255) NOT NULL,
    moeilijkheid_id INT,
    FOREIGN KEY (moeilijkheid_id) REFERENCES moeilijkheden(moeilijkheid_id)
);

CREATE TABLE antwoorden (
    antwoord_id INT AUTO_INCREMENT PRIMARY KEY,
    vraag_id INT NOT NULL,
    antwoordtekst VARCHAR(255) NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (vraag_id) REFERENCES vragen(vraag_id) ON DELETE CASCADE
);

CREATE TABLE categorieen (
    categorie_id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE vraag_categorie (
    vraag_id INT NOT NULL,
    categorie_id INT NOT NULL,
    PRIMARY KEY (vraag_id, categorie_id),
    FOREIGN KEY (vraag_id) REFERENCES vragen(vraag_id) ON DELETE CASCADE,
    FOREIGN KEY (categorie_id) REFERENCES categorieen(categorie_id) ON DELETE CASCADE
);

INSERT INTO moeilijkheden (naam) VALUES
('makkelijk'),
('gemiddeld'),
('moeilijk');

INSERT INTO vragen (vraagtekst, moeilijkheid_id) VALUES
('Hoe heet de eerste deep-dive van de bit-academy?', 1),
('Wat is de naam van de oprichter van de bit-academy?', 2),
('Welke skilltrack hoort bij web development?', 1),
('Wat betekent het behalen van een badge binnen Bit Academy?', 1),
('Welke rol bestaat binnen Bit Academy?', 2);

INSERT INTO antwoorden (vraag_id, antwoordtekst, is_correct) VALUES
(1, 'c', TRUE),
(1, 'Java', FALSE),
(1, 'Python', FALSE),
(1, 'Go', FALSE),

(2, 'Ties Noordhuis', FALSE),
(2, 'Rick Jonk', FALSE),
(2, 'Triple T', FALSE),
(2, 'Dennis Berkhof en Marco van der Werf.', TRUE),

(3, 'Frontend Development', TRUE),
(3, 'Cyber Security', FALSE),
(3, 'Data Science', FALSE),
(3, 'AI Engineering', FALSE),

(4, 'Je hebt een skill afgerond en beheerst deze voldoende', TRUE),
(4, 'Je hebt een toets gehaald', FALSE),
(4, 'Je hebt een project ingeleverd', FALSE),
(4, 'Je hebt een sprintweek afgerond', FALSE),

(5, 'Coach', TRUE),
(5, 'Scrum Owner', FALSE),
(5, 'Tech Wizard', FALSE),
(5, 'Learning Captain', FALSE);

INSERT INTO categorieen (naam) VALUES
('Bit Academy'),
('Programmeren'),
('Web Development'),
('Leersysteem');

INSERT INTO vraag_categorie (vraag_id, categorie_id) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 4),
(3, 3),
(4, 4),
(5, 1);
