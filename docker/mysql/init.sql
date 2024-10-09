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

-- Insert some initial data
INSERT INTO users (username, email, password) VALUES 
('testas', 'johndoe@example.com', 'testas'), ('test', 'test@test','$2y$10$CDNvcCIAHw0d1OxgRt/2luDIhktH54CXMgwb5dhSuUpoE1rClv8Yu')
ON DUPLICATE KEY UPDATE
    username = VALUES(username),
    email = VALUES(email),
    password = VALUES(password);