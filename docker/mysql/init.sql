-- Create the database
CREATE DATABASE IF NOT EXISTS db;
USE db;

-- Create the tables
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255),
  UNIQUE (name, email)
);

-- Insert some initial data
INSERT INTO users (name, email, password) VALUES 
('Testas', 'johndoe@example.com', 'pass123')
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    email = VALUES(email),
    password = VALUES(password);