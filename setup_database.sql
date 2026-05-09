-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2026 at 01:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `freelance_platform`;

USE `freelance_platform`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Database: `freelance_platform`
--

-- --------------------------------------------------------
-- Table structure for table `audit_log`
-- --------------------------------------------------------

CREATE TABLE `audit_log` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `username` varchar(100) DEFAULT NULL,
    `action` varchar(100) NOT NULL,
    `entity_type` varchar(50) DEFAULT NULL,
    `entity_id` int(11) DEFAULT NULL,
    `old_value` text DEFAULT NULL,
    `new_value` text DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `audit_log` (
        `id`,
        `user_id`,
        `username`,
        `action`,
        `entity_type`,
        `entity_id`,
        `old_value`,
        `new_value`,
        `ip_address`,
        `user_agent`,
        `timestamp`
    )
VALUES (
        1,
        1,
        'System Admin',
        'login_success',
        'Auth',
        1,
        '',
        'User logged in',
        '127.0.0.1',
        NULL,
        '2026-05-07 18:32:36'
    );

-- --------------------------------------------------------
-- Table structure for table `dispute`
-- --------------------------------------------------------

CREATE TABLE `dispute` (
    `id` int(11) NOT NULL,
    `reason` text NOT NULL,
    `status` enum(
        'Open',
        'Under Review',
        'Resolved',
        'Dismissed'
    ) DEFAULT 'Open',
    `job_id` int(11) NOT NULL,
    `raised_by_id` int(11) NOT NULL,
    `against_user_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `escrow_transactions`
-- --------------------------------------------------------

CREATE TABLE `escrow_transactions` (
    `id` int(11) NOT NULL,
    `amount` int(11) NOT NULL,
    `status` enum(
        'Locked',
        'Released',
        'Refunded'
    ) DEFAULT 'Locked',
    `milestone_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `expert_search_index`
-- --------------------------------------------------------

CREATE TABLE `expert_search_index` (
    `id` int(11) NOT NULL,
    `freelancer_id` int(11) NOT NULL,
    `freelancer_name` varchar(100) DEFAULT NULL,
    `freelancer_email` varchar(100) DEFAULT NULL,
    `reputation_score` decimal(3, 2) DEFAULT NULL,
    `total_projects_completed` int(11) DEFAULT 0,
    `skills` text DEFAULT NULL,
    `rating_avg` decimal(3, 2) DEFAULT 0.00,
    `total_earned` int(11) DEFAULT 0,
    `last_updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `jobs`
-- --------------------------------------------------------

CREATE TABLE `jobs` (
    `id` int(11) NOT NULL,
    `title` varchar(100) NOT NULL,
    `description` text NOT NULL,
    `status` enum(
        'Open',
        'In Progress',
        'Completed'
    ) DEFAULT 'Open',
    `client_id` int(11) NOT NULL,
    `niche_id` int(11) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `milestones`
-- --------------------------------------------------------

CREATE TABLE `milestones` (
    `id` int(11) NOT NULL,
    `title` varchar(100) NOT NULL,
    `deadline_date` date NOT NULL,
    `status` enum(
        'Pending',
        'Awaiting Approval',
        'Approved',
        'Rescheduled'
    ) DEFAULT 'Pending',
    `job_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `niche_categories`
-- --------------------------------------------------------

CREATE TABLE `niche_categories` (
    `id` int(11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `icon` varchar(50) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `niche_categories` (
        `id`,
        `name`,
        `description`,
        `icon`,
        `created_at`
    )
VALUES (
        1,
        'AI & Machine Learning',
        'Artificial Intelligence, ML, Deep Learning',
        '🤖',
        '2026-05-08 12:29:07'
    ),
    (
        2,
        'Legal',
        'Legal services, contracts, compliance',
        '⚖️',
        '2026-05-08 12:29:07'
    ),
    (
        3,
        'Web Development',
        'Websites, web apps, frontend, backend',
        '💻',
        '2026-05-08 12:29:07'
    ),
    (
        4,
        'Mobile Development',
        'iOS, Android, React Native',
        '📱',
        '2026-05-08 12:29:07'
    ),
    (
        5,
        'Design & Creative',
        'UI/UX, Graphic Design, Logo',
        '🎨',
        '2026-05-08 12:29:07'
    ),
    (
        6,
        'Writing & Translation',
        'Content, Copywriting, Translation',
        '✍️',
        '2026-05-08 12:29:07'
    ),
    (
        7,
        'Marketing & SEO',
        'Digital Marketing, SEO, Social Media',
        '📈',
        '2026-05-08 12:29:07'
    ),
    (
        8,
        'Data Science',
        'Data Analysis, BI, Analytics',
        '📊',
        '2026-05-08 12:29:07'
    ),
    (
        9,
        'Cybersecurity',
        'Security, Penetration Testing',
        '🔒',
        '2026-05-08 12:29:07'
    ),
    (
        10,
        'Blockchain',
        'Crypto, Web3, Smart Contracts',
        '⛓️',
        '2026-05-08 12:29:07'
    );

-- --------------------------------------------------------
-- Table structure for table `niche_performance`
-- --------------------------------------------------------

CREATE TABLE `niche_performance` (
    `id` int(11) NOT NULL,
    `niche_id` int(11) NOT NULL,
    `total_jobs` int(11) DEFAULT 0,
    `active_jobs` int(11) DEFAULT 0,
    `completed_jobs` int(11) DEFAULT 0,
    `total_proposals` int(11) DEFAULT 0,
    `total_spent` int(11) DEFAULT 0,
    `avg_budget` decimal(10, 2) DEFAULT 0.00,
    `growth_rate` decimal(5, 2) DEFAULT 0.00,
    `month_year` date NOT NULL,
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `notifications`
-- --------------------------------------------------------

CREATE TABLE `notifications` (
    `id` int(11) NOT NULL,
    `message` text NOT NULL,
    `is_read` tinyint(1) DEFAULT 0,
    `user_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `notifications` (
        `id`,
        `message`,
        `is_read`,
        `user_id`
    )
VALUES (
        1,
        'Your proposal for Next.js E-commerce Platform was accepted!',
        0,
        3
    );

-- --------------------------------------------------------
-- Table structure for table `proposals`
-- --------------------------------------------------------

CREATE TABLE `proposals` (
    `id` int(11) NOT NULL,
    `bid_amount` int(11) NOT NULL,
    `description` text NOT NULL,
    `status` enum(
        'Pending',
        'Accepted',
        'Rejected'
    ) DEFAULT 'Pending',
    `freelancer_id` int(11) NOT NULL,
    `job_id` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table structure for table `roles`
-- --------------------------------------------------------

CREATE TABLE `roles` (
    `id` int(11) NOT NULL,
    `role_name` varchar(50) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `roles` (
        `id`,
        `role_name`,
        `description`,
        `created_at`
    )
VALUES (
        1,
        'Super Admin',
        'Full system access - all permissions',
        '2026-05-07 19:41:20'
    ),
    (
        2,
        'Financial Admin',
        'Manage payments, escrow, fees, refunds',
        '2026-05-07 19:41:20'
    ),
    (
        3,
        'Dispute Mediator',
        'View and resolve disputes only',
        '2026-05-07 19:41:20'
    ),
    (
        4,
        'Tech Support',
        'Manage users, view logs, reset passwords',
        '2026-05-07 19:41:20'
    ),
    (
        5,
        'Client',
        'Post jobs and hire freelancers',
        '2026-05-07 19:41:20'
    ),
    (
        6,
        'Freelancer',
        'Apply for jobs and earn money',
        '2026-05-07 19:41:20'
    );

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
    `id` int(11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL,
    `role` enum(
        'Admin',
        'Client',
        'Freelancer',
        'Financial',
        'Tech Support',
        'Dispute Mediator'
    ) NOT NULL DEFAULT 'Client',
    `reputation_score` decimal(3, 2) DEFAULT 0.00,
    `is_verified` tinyint(1) NOT NULL,
    `role_id` int(11) DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `users` (
        `id`,
        `name`,
        `email`,
        `password`,
        `role`,
        `reputation_score`,
        `is_verified`,
        `role_id`,
        `created_at`
    )
VALUES (
        1,
        'System Admin',
        'admin@freelance.com',
        'pass_123',
        'Admin',
        5.00,
        1,
        1,
        '2026-05-07 19:23:44'
    ),
    (
        3,
        'Momen',
        'momen@dev.com',
        'pass_123',
        'Tech Support',
        4.90,
        1,
        6,
        '2026-05-07 19:23:44'
    ),
    (
        5,
        'mimi',
        'mimi@gmail.com',
        '123456789',
        'Client',
        0.00,
        1,
        5,
        '2026-05-07 19:23:44'
    ),
    (
        6,
        'aza',
        'aza@freelance.com',
        '123456789',
        'Dispute Mediator',
        0.00,
        1,
        6,
        '2026-05-07 19:23:44'
    ),
    (
        7,
        'www',
        'www@gmail.com',
        '123456789',
        'Freelancer',
        0.00,
        1,
        6,
        '2026-05-07 19:23:44'
    ),
    (
        8,
        'aw',
        'aw@freelance.com',
        '123456789',
        'Client',
        0.00,
        1,
        5,
        '2026-05-07 19:23:44'
    ),
    (
        9,
        'ww',
        'ww@freelance.com',
        'pass_123',
        'Freelancer',
        0.00,
        1,
        6,
        '2026-05-07 19:23:44'
    ),
    (
        24,
        'qwqw',
        'qwqw@freelance.com',
        '123456',
        'Admin',
        0.00,
        1,
        1,
        '2026-05-08 12:00:41'
    );

-- --------------------------------------------------------
-- Indexes
-- --------------------------------------------------------

ALTER TABLE `audit_log`
ADD PRIMARY KEY (`id`),
ADD KEY `idx_user` (`user_id`),
ADD KEY `idx_action` (`action`),
ADD KEY `idx_entity` (`entity_type`, `entity_id`),
ADD KEY `idx_timestamp` (`timestamp`);

ALTER TABLE `dispute`
ADD PRIMARY KEY (`id`),
ADD KEY `job_id` (`job_id`),
ADD KEY `raised_by_id` (`raised_by_id`),
ADD KEY `against_user_id` (`against_user_id`);

ALTER TABLE `escrow_transactions`
ADD PRIMARY KEY (`id`),
ADD KEY `milestone_id` (`milestone_id`);

ALTER TABLE `expert_search_index`
ADD PRIMARY KEY (`id`),
ADD KEY `freelancer_id` (`freelancer_id`),
ADD KEY `idx_reputation` (`reputation_score`),
ADD KEY `idx_completed` (`total_projects_completed`),
ADD KEY `idx_rating` (`rating_avg`);

ALTER TABLE `expert_search_index`
ADD FULLTEXT KEY `idx_skills` (`skills`);

ALTER TABLE `jobs`
ADD PRIMARY KEY (`id`),
ADD KEY `client_id` (`client_id`),
ADD KEY `niche_id` (`niche_id`);

ALTER TABLE `milestones`
ADD PRIMARY KEY (`id`),
ADD KEY `job_id` (`job_id`);

ALTER TABLE `niche_categories`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `niche_performance`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `unique_niche_month` (`niche_id`, `month_year`);

ALTER TABLE `notifications`
ADD PRIMARY KEY (`id`),
ADD KEY `user_id` (`user_id`);

ALTER TABLE `proposals`
ADD PRIMARY KEY (`id`),
ADD KEY `freelancer_id` (`freelancer_id`),
ADD KEY `job_id` (`job_id`);

ALTER TABLE `roles`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `role_name` (`role_name`);

ALTER TABLE `users`
ADD PRIMARY KEY (`id`),
ADD KEY `role_id` (`role_id`);

-- --------------------------------------------------------
-- AUTO_INCREMENT
-- --------------------------------------------------------

ALTER TABLE `audit_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

ALTER TABLE `dispute`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

ALTER TABLE `escrow_transactions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

ALTER TABLE `expert_search_index`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `jobs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

ALTER TABLE `milestones`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

ALTER TABLE `niche_categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 21;

ALTER TABLE `niche_performance`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `notifications`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

ALTER TABLE `proposals`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

ALTER TABLE `roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 13;

ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 25;

-- --------------------------------------------------------
-- Foreign Key Constraints
-- --------------------------------------------------------

ALTER TABLE `audit_log`
ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `dispute`
ADD CONSTRAINT `dispute_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `dispute_ibfk_2` FOREIGN KEY (`raised_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `dispute_ibfk_3` FOREIGN KEY (`against_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `escrow_transactions`
ADD CONSTRAINT `escrow_transactions_ibfk_1` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE CASCADE;

ALTER TABLE `expert_search_index`
ADD CONSTRAINT `expert_search_index_ibfk_1` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `jobs`
ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`niche_id`) REFERENCES `niche_categories` (`id`),
ADD CONSTRAINT `jobs_ibfk_3` FOREIGN KEY (`niche_id`) REFERENCES `niche_categories` (`id`);

ALTER TABLE `milestones`
ADD CONSTRAINT `milestones_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

ALTER TABLE `niche_performance`
ADD CONSTRAINT `niche_performance_ibfk_1` FOREIGN KEY (`niche_id`) REFERENCES `niche_categories` (`id`);

ALTER TABLE `notifications`
ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `proposals`
ADD CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `proposals_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE;

ALTER TABLE `users`
ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;