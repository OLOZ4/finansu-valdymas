-- Create the database
CREATE DATABASE IF NOT EXISTS db;
USE db;

-- Create the tables
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  password VARCHAR(255)
);

-- Insert some initial data
INSERT INTO users (name, email, password) VALUES
  ('John Doe', 'john@example.com', 'password123'),
  ('Benas Tenas', 'jane@example.com', 'password123');