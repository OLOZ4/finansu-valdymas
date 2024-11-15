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
date DATE,
amount DECIMAL(10, 2),
category VARCHAR(50),
FOREIGN KEY (user_id) REFERENCES users (id)
);

-- Create table for income

CREATE TABLE IF NOT EXISTS income (
id INT PRIMARY KEY AUTO_INCREMENT,
user_id INT,
date DATE,
amount DECIMAL(10, 2),
category VARCHAR(50),
FOREIGN KEY (user_id) REFERENCES users (id)
);

-- Create table combining user's expenses and income 

CREATE TABLE IF NOT EXISTS user_balance ( 
user_id INT, 
date DATE, 
balance DECIMAL(10, 2), 
PRIMARY KEY (user_id, date), 
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

-- Inserting sample data for expenses and and income tables

INSERT INTO expenses (user_id, date, amount, category) VALUES
(1, '2022-01-01', 100.00, 'Food'),
(1, '2022-01-15', 50.00, 'Transportation'),
(2, '2022-02-01', 200.00, 'Rent'),
(2, '2022-02-15', 50.00, 'Utilities'),
(2, '2022-03-01', 300.00, 'Salary'),
(2, '2022-03-15', 25.00, 'Food'),
(1, '2022-04-01', 150.00, 'Shopping'),
(2, '2022-04-15', 100.00, 'Entertainment')

ON DUPLICATE KEY UPDATE
  user_id = VALUES(user_id),
  date = VALUES(date),
  amount = VALUES(amount),
  category = VALUES(category);

INSERT INTO income (user_id, date, amount, category) VALUES
(1, '2022-01-01', 500.00, 'Salary'),
(1, '2022-02-15', 1000.00, 'Salary'),
(2, '2022-03-01', 800.00, 'Salary'),
(1, '2022-04-01', 1200.00, 'Salary'),
(2, '2022-05-01', 600.00, 'Investments')

ON DUPLICATE KEY UPDATE
  user_id = VALUES(user_id),
  date = VALUES(date),
  amount = VALUES(amount),
  category = VALUES(category);

