DROP DATABASE IF EXISTS freelance_platform;
CREATE DATABASE freelance_platform;
USE freelance_platform;

CREATE TABLE Users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('Client', 'Freelancer', 'Admin') NOT NULL,
  reputation_score DECIMAL(3,2) DEFAULT 0.00,
  is_verified BOOLEAN NOT NULL
);

CREATE TABLE Jobs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  status ENUM('Open', 'In Progress', 'Completed') DEFAULT 'Open',
  client_id INT NOT NULL,
  FOREIGN KEY (client_id) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE Proposals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  bid_amount INT NOT NULL,
  description TEXT NOT NULL,
  status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
  freelancer_id INT NOT NULL,
  job_id INT NOT NULL,
  FOREIGN KEY (freelancer_id) REFERENCES Users(id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES Jobs(id) ON DELETE CASCADE
);

CREATE TABLE Milestones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100) NOT NULL,
  deadline_date DATE NOT NULL,
  status ENUM('Pending', 'Awaiting Approval', 'Approved', 'Rescheduled') DEFAULT 'Pending',
  job_id INT NOT NULL,
  FOREIGN KEY (job_id) REFERENCES Jobs(id) ON DELETE CASCADE
);

CREATE TABLE Escrow_Transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  amount INT NOT NULL,
  status ENUM('Locked', 'Released', 'Refunded') DEFAULT 'Locked',
  milestone_id INT NOT NULL,
  FOREIGN KEY (milestone_id) REFERENCES Milestones(id) ON DELETE CASCADE
);

CREATE TABLE Notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message TEXT NOT NULL,
  is_read BOOLEAN DEFAULT false,
  user_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);

CREATE TABLE Dispute (
  id INT AUTO_INCREMENT PRIMARY KEY,
  reason TEXT NOT NULL,
  status ENUM('Open', 'Under Review', 'Resolved', 'Dismissed') DEFAULT 'Open',
  job_id INT NOT NULL,
  raised_by_id INT NOT NULL,
  against_user_id INT NOT NULL,
  FOREIGN KEY (job_id) REFERENCES Jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (raised_by_id) REFERENCES Users(id) ON DELETE CASCADE,
  FOREIGN KEY (against_user_id) REFERENCES Users(id) ON DELETE CASCADE
);


INSERT INTO Users (name, email, password, role, reputation_score, is_verified) VALUES
('System Admin', 'admin@freelance.com', 'pass_123', 'Admin', 5.00, true),
('Sarah The Client', 'sarah@business.com', 'pass_123', 'Client', 4.50, true),
('Momen', 'momen@dev.com', 'pass_123', 'Freelancer', 4.90, true);

INSERT INTO Jobs (title, description, status, client_id) VALUES
('Next.js E-commerce Platform', 'Need a high-performance e-commerce site with Tailwind CSS and a Node.js backend. Must integrate with Supabase.', 'In Progress', 2);

INSERT INTO Proposals (bid_amount, description, status, freelancer_id, job_id) VALUES
(1500, 'I am a MERN stack developer with experience building platforms like CornerStone. I can deliver this within 30 days.', 'Accepted', 3, 1);

INSERT INTO Milestones (title, deadline_date, status, job_id) VALUES
('Phase 1: UI Design & Next.js Setup', '2026-06-15', 'Approved', 1),
('Phase 2: Node.js Backend API', '2026-07-01', 'In Progress', 1);

INSERT INTO Escrow_Transactions (amount, status, milestone_id) VALUES
(500, 'Released', 1),
(1000, 'Locked', 2);

INSERT INTO Notifications (message, is_read, user_id) VALUES
('Your proposal for Next.js E-commerce Platform was accepted!', false, 3),
('Phase 1 Escrow funds have been successfully released.', true, 2);

INSERT INTO Dispute (reason, status, job_id, raised_by_id, against_user_id) VALUES
('The developer insists on using a native MongoDB driver instead of the agreed ORM structure.', 'Under Review', 1, 2, 3);