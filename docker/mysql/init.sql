-- Create the database
CREATE DATABASE IF NOT EXISTS db;
USE db;

-- Create the tables
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255),
  UNIQUE (username, email)
);

-- Create table for expenses

CREATE TABLE IF NOT EXISTS expenses (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT,
amount DECIMAL(50, 2),
category VARCHAR(50),
description VARCHAR(50),
FOREIGN KEY (user_id) REFERENCES users (id)
);

-- Create table for income

CREATE TABLE IF NOT EXISTS income (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT,
amount DECIMAL(50, 2),
description VARCHAR(50),
FOREIGN KEY (user_id) REFERENCES users (id)
);

-- Create table combining user's expenses and income 

CREATE TABLE IF NOT EXISTS user_balance ( 
id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT, 
balance DECIMAL(10, 2),
description VARCHAR(50),
FOREIGN KEY (user_id) REFERENCES users (id) 
); 

-- Insert some initial data
INSERT INTO users (username, email, password) VALUES 
('testas', 'johndoe@example.com', 'testas'),
('test', 'test@test','$2y$10$CDNvcCIAHw0d1OxgRt/2luDIhktH54CXMgwb5dhSuUpoE1rClv8Yu')

ON DUPLICATE KEY UPDATE
username = VALUES(username),
email = VALUES(email),
password = VALUES(password);

