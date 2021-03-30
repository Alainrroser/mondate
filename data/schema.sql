DROP DATABASE IF EXISTS mondate;

CREATE DATABASE mondate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mondate;

CREATE TABLE user (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    email VARCHAR(450) NOT NULL,
    password VARCHAR(50),
    UNIQUE KEY (email)
);

CREATE TABLE appointment (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    start DATETIME NOT NULL,
    end DATETIME,
    name VARCHAR(50) NOT NULL,
    description TEXT(1000),
    creator_id INT NOT NULL
);

CREATE TABLE tag (
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    color CHAR(6) NOT NULL
);

CREATE TABLE appointment_user (
    appointment_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (appointment_id, user_id)
);

CREATE TABLE appointment_tag (
    appointment_id INT NOT NULL,
    tag_id INT NOT NULL,
    FOREIGN KEY (appointment_id) REFERENCES appointment (id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (appointment_id, tag_id)
);