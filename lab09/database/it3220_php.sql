sql
CREATE DATABASE IF NOT EXISTS it3220_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE it3220_php;
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) NOT NULL UNIQUE,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  dob DATE NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO students (code, full_name, email, dob) VALUES
('SV001','Nguyen Van A','a@example.com','1999-01-01'),
('SV002','Tran Thi B','b@example.com','2000-02-02'),
('SV003','Le Van C','c@example.com','1998-03-03'),
('SV004','Pham Thi D','d@example.com','1997-04-04'),
('SV005','Hoang Van E','e@example.com','1996-05-05');
