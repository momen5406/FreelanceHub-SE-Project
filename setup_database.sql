-- phpMyAdmin SQL Dump
-- Version: 5.2.1
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `freelance_platform`;

CREATE DATABASE `freelance_platform`;

USE `freelance_platform`;

CREATE TABLE `roles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `role_name` varchar(50) NOT NULL,
    `description` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `role_name` (`role_name`)
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
        NOW()
    ),
    (
        2,
        'Financial Admin',
        'Manage payments, escrow, fees, refunds',
        NOW()
    ),
    (
        3,
        'Dispute Mediator',
        'View and resolve disputes only',
        NOW()
    ),
    (
        4,
        'Tech Support',
        'Manage users, view logs, reset passwords',
        NOW()
    ),
    (
        5,
        'Client',
        'Post jobs and hire freelancers',
        NOW()
    ),
    (
        6,
        'Freelancer',
        'Apply for jobs and earn money',
        NOW()
    );

CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
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
    `is_verified` tinyint(1) NOT NULL DEFAULT 0,
    `role_id` int(11) DEFAULT 5,
    `wallet_balance` decimal(10, 2) DEFAULT 10000.00,
    `total_spent` decimal(10, 2) DEFAULT 0.00,
    `total_earned` decimal(10, 2) DEFAULT 0.00,
    `total_lifetime_value` decimal(10, 2) DEFAULT 0.00,
    `country_code` varchar(2) DEFAULT 'EG',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `role_id` (`role_id`),
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
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
        `wallet_balance`,
        `total_spent`,
        `total_earned`,
        `total_lifetime_value`,
        `country_code`,
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
        50000.00,
        0.00,
        0.00,
        0.00,
        'EG',
        NOW()
    ),
    (
        2,
        'Sarah Client',
        'sarah@business.com',
        'pass_123',
        'Client',
        4.50,
        1,
        5,
        25000.00,
        5000.00,
        0.00,
        5000.00,
        'US',
        NOW()
    ),
    (
        3,
        'Momen Freelancer',
        'momen@dev.com',
        'pass_123',
        'Freelancer',
        4.90,
        1,
        6,
        5000.00,
        0.00,
        2500.00,
        2500.00,
        'EG',
        NOW()
    ),
    (
        4,
        'John Freelancer',
        'john@dev.com',
        'pass_123',
        'Freelancer',
        4.80,
        1,
        6,
        3000.00,
        0.00,
        1500.00,
        1500.00,
        'UK',
        NOW()
    ),
    (
        5,
        'Alice Client',
        'alice@company.com',
        'pass_123',
        'Client',
        4.20,
        1,
        5,
        15000.00,
        2000.00,
        0.00,
        2000.00,
        'AE',
        NOW()
    ),
    (
        6,
        'TechCorp',
        'tech@corp.com',
        'pass_123',
        'Client',
        4.70,
        1,
        5,
        50000.00,
        10000.00,
        0.00,
        10000.00,
        'SA',
        NOW()
    );

CREATE TABLE `niche_categories` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `description` text DEFAULT NULL,
    `icon` varchar(50) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
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
        NOW()
    ),
    (
        2,
        'Legal',
        'Legal services, contracts, compliance',
        '⚖️',
        NOW()
    ),
    (
        3,
        'Web Development',
        'Websites, web apps, frontend, backend',
        '💻',
        NOW()
    ),
    (
        4,
        'Mobile Development',
        'iOS, Android, React Native',
        '📱',
        NOW()
    ),
    (
        5,
        'Design & Creative',
        'UI/UX, Graphic Design, Logo',
        '🎨',
        NOW()
    ),
    (
        6,
        'Writing & Translation',
        'Content, Copywriting, Translation',
        '✍️',
        NOW()
    ),
    (
        7,
        'Marketing & SEO',
        'Digital Marketing, SEO, Social Media',
        '📈',
        NOW()
    ),
    (
        8,
        'Data Science',
        'Data Analysis, BI, Analytics',
        '📊',
        NOW()
    ),
    (
        9,
        'Cybersecurity',
        'Security, Pen测试, Testing',
        '🔒',
        NOW()
    ),
    (
        10,
        'Blockchain',
        'Crypto, Web3, Smart Contracts',
        '⛓️',
        NOW()
    );

CREATE TABLE `niche_fields` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `niche_id` int(11) NOT NULL,
    `field_name` varchar(100) NOT NULL,
    `field_label` varchar(100) NOT NULL,
    `field_type` enum(
        'text',
        'number',
        'select',
        'multiselect',
        'textarea'
    ) DEFAULT 'text',
    `options` text DEFAULT NULL,
    `is_required` tinyint(1) DEFAULT 0,
    `placeholder` varchar(255) DEFAULT NULL,
    `order` int(11) DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `niche_id` (`niche_id`),
    FOREIGN KEY (`niche_id`) REFERENCES `niche_categories` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `niche_fields` (
        `niche_id`,
        `field_name`,
        `field_label`,
        `field_type`,
        `options`,
        `is_required`,
        `placeholder`,
        `order`
    )
VALUES (
        1,
        'ml_framework',
        'ML Framework',
        'select',
        '["TensorFlow","PyTorch","Scikit-learn","Keras"]',
        1,
        NULL,
        1
    ),
    (
        1,
        'data_stack',
        'Data Stack',
        'multiselect',
        '["Python","Pandas","NumPy","SQL","Spark"]',
        1,
        NULL,
        2
    ),
    (
        1,
        'algorithm_type',
        'Algorithm Type',
        'select',
        '["Classification","Regression","Clustering","NLP"]',
        1,
        NULL,
        3
    ),
    (
        2,
        'document_type',
        'Document Type',
        'select',
        '["Contract","NDA","Terms of Service","Privacy Policy"]',
        1,
        NULL,
        1
    ),
    (
        2,
        'jurisdiction',
        'Jurisdiction',
        'text',
        NULL,
        1,
        'e.g., Egypt, USA, UK',
        2
    ),
    (
        3,
        'frontend',
        'Frontend Technologies',
        'multiselect',
        '["React","Vue","Angular","HTML/CSS","JavaScript"]',
        1,
        NULL,
        1
    ),
    (
        3,
        'backend',
        'Backend Technologies',
        'multiselect',
        '["Node.js","PHP","Python","Java","C#"]',
        1,
        NULL,
        2
    ),
    (
        3,
        'database',
        'Database',
        'multiselect',
        '["MySQL","PostgreSQL","MongoDB","Firebase"]',
        0,
        NULL,
        3
    ),
    (
        4,
        'platform',
        'Platform',
        'select',
        '["iOS","Android","Both"]',
        1,
        NULL,
        1
    ),
    (
        4,
        'framework',
        'Framework',
        'select',
        '["React Native","Flutter","Swift","Kotlin"]',
        1,
        NULL,
        2
    ),
    (
        5,
        'software',
        'Design Software',
        'multiselect',
        '["Photoshop","Illustrator","Figma","InDesign","After Effects"]',
        1,
        NULL,
        1
    ),
    (
        5,
        'file_format',
        'Delivery Format',
        'select',
        '["PSD","AI","PDF","PNG","SVG","Figma"]',
        1,
        NULL,
        2
    ),
    (
        6,
        'source_language',
        'Source Language',
        'select',
        '["English","Arabic","French","Spanish","German"]',
        1,
        NULL,
        1
    ),
    (
        6,
        'target_language',
        'Target Language',
        'select',
        '["Arabic","English","French","Spanish","German"]',
        1,
        NULL,
        2
    ),
    (
        6,
        'word_count',
        'Word Count',
        'number',
        NULL,
        1,
        'e.g., 5000',
        3
    ),
    (
        7,
        'marketing_type',
        'Marketing Type',
        'select',
        '["SEO","Social Media","Email","PPC","Content"]',
        1,
        NULL,
        1
    ),
    (
        7,
        'platform',
        'Platform',
        'multiselect',
        '["Google","Facebook","Instagram","LinkedIn","Twitter"]',
        1,
        NULL,
        2
    ),
    (
        8,
        'data_stack',
        'Data Stack',
        'multiselect',
        '["Python","R","SQL","Tableau","Power BI"]',
        1,
        NULL,
        1
    ),
    (
        8,
        'analysis_type',
        'Analysis Type',
        'select',
        '["Descriptive","Predictive","Prescriptive","Diagnostic"]',
        1,
        NULL,
        2
    ),
    (
        9,
        'security_type',
        'Security Type',
        'select',
        '["Penetration Testing","Compliance","Audit","Risk Assessment"]',
        1,
        NULL,
        1
    ),
    (
        9,
        'certification',
        'Required Certification',
        'select',
        '["CISSP","CEH","CISM","Security+","None"]',
        0,
        NULL,
        2
    ),
    (
        10,
        'blockchain_type',
        'Blockchain Type',
        'select',
        '["Ethereum","Solana","Bitcoin","Hyperledger","Polygon"]',
        1,
        NULL,
        1
    ),
    (
        10,
        'smart_contract',
        'Smart Contract Language',
        'select',
        '["Solidity","Rust","Go","C++"]',
        1,
        NULL,
        2
    );

CREATE TABLE `jobs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `description` text NOT NULL,
    `status` enum(
        'Open',
        'In Progress',
        'Completed'
    ) DEFAULT 'Open',
    `client_id` int(11) NOT NULL,
    `niche_id` int(11) DEFAULT NULL,
    `budget` decimal(10, 2) DEFAULT NULL,
    `assigned_freelancer_id` int(11) DEFAULT NULL,
    `dynamic_fields` text DEFAULT NULL,
    `is_private` tinyint(1) DEFAULT 0,
    `invited_freelancers` text DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `client_id` (`client_id`),
    KEY `niche_id` (`niche_id`),
    KEY `assigned_freelancer_id` (`assigned_freelancer_id`),
    FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`niche_id`) REFERENCES `niche_categories` (`id`),
    FOREIGN KEY (`assigned_freelancer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `jobs` (
        `id`,
        `title`,
        `description`,
        `status`,
        `client_id`,
        `niche_id`,
        `budget`,
        `assigned_freelancer_id`,
        `dynamic_fields`,
        `is_private`,
        `invited_freelancers`,
        `created_at`
    )
VALUES (
        1,
        'AI-Powered Chatbot',
        'Build an AI chatbot for customer service',
        'In Progress',
        2,
        1,
        5000.00,
        3,
        '{"ml_framework":"TensorFlow","data_stack":["Python","TensorFlow"],"algorithm_type":"NLP"}',
        0,
        NULL,
        NOW()
    ),
    (
        2,
        'E-commerce Website',
        'Full stack e-commerce platform',
        'Open',
        5,
        3,
        3000.00,
        NULL,
        '{"frontend":["React"],"backend":["Node.js"],"database":"MongoDB"}',
        0,
        NULL,
        NOW()
    ),
    (
        3,
        'Legal Contract Review',
        'Review and draft legal contracts',
        'Open',
        2,
        2,
        1500.00,
        NULL,
        '{"document_type":"Contract","jurisdiction":"Egypt"}',
        0,
        NULL,
        NOW()
    ),
    (
        4,
        'Mobile App Development',
        'iOS and Android fitness app',
        'In Progress',
        6,
        4,
        8000.00,
        4,
        '{"platform":"Both","framework":"React Native"}',
        0,
        NULL,
        NOW()
    );

CREATE TABLE `proposals` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `bid_amount` int(11) NOT NULL,
    `description` text NOT NULL,
    `status` enum(
        'Pending',
        'Accepted',
        'Rejected'
    ) DEFAULT 'Pending',
    `freelancer_id` int(11) NOT NULL,
    `job_id` int(11) NOT NULL,
    `version` int(11) DEFAULT 1,
    `parent_version_id` int(11) DEFAULT NULL,
    `expires_at` timestamp NULL DEFAULT NULL,
    `withdrawn_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `freelancer_id` (`freelancer_id`),
    KEY `job_id` (`job_id`),
    KEY `parent_version_id` (`parent_version_id`),
    FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`parent_version_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `proposals` (
        `id`,
        `bid_amount`,
        `description`,
        `status`,
        `freelancer_id`,
        `job_id`,
        `version`,
        `parent_version_id`,
        `expires_at`,
        `withdrawn_at`,
        `created_at`
    )
VALUES (
        1,
        4500,
        'I will build the AI chatbot with TensorFlow',
        'Accepted',
        3,
        1,
        1,
        NULL,
        DATE_ADD(NOW(), INTERVAL 30 DAY),
        NULL,
        NOW()
    ),
    (
        2,
        4200,
        'Updated proposal with faster delivery',
        'Pending',
        3,
        1,
        2,
        1,
        DATE_ADD(NOW(), INTERVAL 30 DAY),
        NULL,
        NOW()
    ),
    (
        3,
        2800,
        'Full e-commerce solution with React',
        'Pending',
        4,
        2,
        1,
        NULL,
        DATE_ADD(NOW(), INTERVAL 30 DAY),
        NULL,
        NOW()
    );

CREATE TABLE `milestones` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `deadline_date` date NOT NULL,
    `status` enum(
        'Pending',
        'In Progress',
        'Awaiting Approval',
        'Approved',
        'Rescheduled',
        'Funds Locked',
        'Completed'
    ) DEFAULT 'Pending',
    `job_id` int(11) NOT NULL,
    `escrow_id` int(11) DEFAULT NULL,
    `amount` decimal(10, 2) NOT NULL DEFAULT 0,
    `partial_released` decimal(10, 2) DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `job_id` (`job_id`),
    KEY `escrow_id` (`escrow_id`),
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `milestones` (
        `id`,
        `title`,
        `deadline_date`,
        `status`,
        `job_id`,
        `escrow_id`,
        `amount`,
        `partial_released`,
        `created_at`
    )
VALUES (
        1,
        'Phase 1: AI Model Development',
        '2026-06-15',
        'Funds Locked',
        1,
        NULL,
        2500.00,
        0,
        NOW()
    ),
    (
        2,
        'Phase 2: Integration & Testing',
        '2026-07-01',
        'Pending',
        1,
        NULL,
        2500.00,
        0,
        NOW()
    ),
    (
        3,
        'UI/UX Design',
        '2026-06-10',
        'Approved',
        2,
        NULL,
        1500.00,
        0,
        NOW()
    ),
    (
        4,
        'Frontend Development',
        '2026-06-25',
        'Pending',
        2,
        NULL,
        1500.00,
        0,
        NOW()
    );

CREATE TABLE `escrow_transactions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `amount` decimal(10, 2) NOT NULL,
    `status` enum(
        'Locked',
        'Released',
        'Refunded'
    ) DEFAULT 'Locked',
    `milestone_id` int(11) NOT NULL,
    `partial_released` decimal(10, 2) DEFAULT 0,
    `released_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `milestone_id` (`milestone_id`),
    FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `escrow_transactions` (
        `id`,
        `amount`,
        `status`,
        `milestone_id`,
        `partial_released`,
        `released_at`,
        `created_at`
    )
VALUES (
        1,
        2500.00,
        'Locked',
        1,
        0,
        NULL,
        NOW()
    ),
    (
        2,
        1500.00,
        'Released',
        3,
        1500.00,
        NOW(),
        NOW()
    );

CREATE TABLE `partial_releases` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `escrow_id` int(11) NOT NULL,
    `percentage` int(11) NOT NULL,
    `amount` decimal(10, 2) NOT NULL,
    `reason` text DEFAULT NULL,
    `status` enum(
        'Pending',
        'Approved',
        'Released'
    ) DEFAULT 'Pending',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `released_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `escrow_id` (`escrow_id`),
    FOREIGN KEY (`escrow_id`) REFERENCES `escrow_transactions` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `dispute` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `reason` text NOT NULL,
    `status` enum(
        'Open',
        'Under Review',
        'Resolved',
        'Dismissed'
    ) DEFAULT 'Open',
    `job_id` int(11) NOT NULL,
    `raised_by_id` int(11) NOT NULL,
    `against_user_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `resolved_at` timestamp NULL DEFAULT NULL,
    `resolution_notes` text DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `job_id` (`job_id`),
    KEY `raised_by_id` (`raised_by_id`),
    KEY `against_user_id` (`against_user_id`),
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`raised_by_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`against_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `dispute` (
        `id`,
        `reason`,
        `status`,
        `job_id`,
        `raised_by_id`,
        `against_user_id`,
        `created_at`
    )
VALUES (
        1,
        'Delayed delivery of milestone 1',
        'Under Review',
        1,
        2,
        3,
        NOW()
    );

CREATE TABLE `dispute_messages` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `dispute_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `message` text NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `dispute_id` (`dispute_id`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`dispute_id`) REFERENCES `dispute` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `notifications` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `message` text NOT NULL,
    `is_read` tinyint(1) DEFAULT 0,
    `user_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `notifications` (
        `id`,
        `message`,
        `is_read`,
        `user_id`,
        `created_at`
    )
VALUES (
        1,
        'Your proposal for AI-Powered Chatbot was accepted!',
        0,
        3,
        NOW()
    ),
    (
        2,
        'Milestone 1 funds have been locked',
        0,
        3,
        NOW()
    );

CREATE TABLE `audit_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `username` varchar(100) DEFAULT NULL,
    `action` varchar(100) NOT NULL,
    `entity_type` varchar(50) DEFAULT NULL,
    `entity_id` int(11) DEFAULT NULL,
    `old_value` text DEFAULT NULL,
    `new_value` text DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `user_agent` text DEFAULT NULL,
    `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_action` (`action`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `fee_tiers` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `min_lifetime_value` decimal(10, 2) NOT NULL,
    `max_lifetime_value` decimal(10, 2) NOT NULL,
    `fee_percentage` decimal(5, 2) NOT NULL,
    `tier_name` varchar(50) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

INSERT INTO
    `fee_tiers` (
        `min_lifetime_value`,
        `max_lifetime_value`,
        `fee_percentage`,
        `tier_name`
    )
VALUES (0, 1000, 15.00, 'Bronze'),
    (1000, 5000, 12.00, 'Silver'),
    (5000, 20000, 10.00, 'Gold'),
    (
        20000,
        100000,
        8.00,
        'Platinum'
    ),
    (
        100000,
        9999999,
        5.00,
        'Diamond'
    );

CREATE TABLE `tax_records` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `transaction_id` int(11) DEFAULT NULL,
    `amount` decimal(10, 2) NOT NULL,
    `tax_rate` decimal(5, 2) NOT NULL,
    `tax_amount` decimal(10, 2) NOT NULL,
    `client_country` varchar(2) NOT NULL,
    `freelancer_country` varchar(2) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `interviews` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `job_id` int(11) NOT NULL,
    `client_id` int(11) NOT NULL,
    `freelancer_id` int(11) NOT NULL,
    `scheduled_at` timestamp NULL DEFAULT NULL,
    `meeting_link` varchar(255) DEFAULT NULL,
    `status` enum(
        'scheduled',
        'completed',
        'cancelled'
    ) DEFAULT 'scheduled',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `job_id` (`job_id`),
    KEY `client_id` (`client_id`),
    KEY `freelancer_id` (`freelancer_id`),
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `ndas` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `job_id` int(11) NOT NULL,
    `client_id` int(11) NOT NULL,
    `freelancer_id` int(11) NOT NULL,
    `content` text NOT NULL,
    `status` enum(
        'pending',
        'signed',
        'rejected'
    ) DEFAULT 'pending',
    `signed_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `job_id` (`job_id`),
    KEY `client_id` (`client_id`),
    KEY `freelancer_id` (`freelancer_id`),
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `activity_log` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) DEFAULT NULL,
    `action` varchar(100) NOT NULL,
    `details` text DEFAULT NULL,
    `ip_address` varchar(45) DEFAULT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    KEY `idx_user` (`user_id`),
    KEY `idx_action` (`action`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE `job_invitations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `job_id` int(11) NOT NULL,
    `freelancer_id` int(11) NOT NULL,
    `status` enum(
        'pending',
        'accepted',
        'declined'
    ) DEFAULT 'pending',
    `invited_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `responded_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `job_id` (`job_id`),
    KEY `freelancer_id` (`freelancer_id`),
    FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
    FOREIGN KEY (`freelancer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

COMMIT;