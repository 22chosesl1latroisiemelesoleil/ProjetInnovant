-- Céline Lecomte - 18909721 - M1 Informatique Université Paris 8
-- Connection db : 
-- function dbConnect()
-- ligne 192 de model.php




CREATE DATABASE IF NOT EXISTS projetInnovant CHARACTER SET 'utf8';
USE projetInnovant; 


CREATE TABLE IF NOT EXISTS source (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    langue ENUM('FR', 'UK', 'Autre'),
    nbMots INT,
    source VARCHAR(100)
)
ENGINE=InnoDB;  



CREATE TABLE IF NOT EXISTS indexation (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    mot VARCHAR(100),
    occurence INT,
    source VARCHAR(100)
)
ENGINE=InnoDB;  




CREATE TABLE IF NOT EXISTS lienSemantique (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    sourceA VARCHAR(100),
    sourceB VARCHAR(100),
    motsCommuns TEXT
)
ENGINE=InnoDB;  



