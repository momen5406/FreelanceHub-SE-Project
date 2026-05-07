DROP DATABASE IF EXISTS freelance_platform;

CREATE DATABASE freelance_platform;

USE freelance_platform;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM(
        'Client',
        'Freelancer',
        'Admin'
    ) NOT NULL,
    reputation_score DECIMAL(3, 2) DEFAULT 0.00,
    is_verified BOOLEAN NOT NULL
);

CREATE TABLE Jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status ENUM(
        'Open',
        'In Progress',
        'Completed'
    ) DEFAULT 'Open',
    client_id INT NOT NULL,
    FOREIGN KEY (client_id) REFERENCES Users (id) ON DELETE CASCADE
);

CREATE TABLE Proposals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bid_amount INT NOT NULL,
    description TEXT NOT NULL,
    status ENUM(
        'Pending',
        'Accepted',
        'Rejected'
    ) DEFAULT 'Pending',
    freelancer_id INT NOT NULL,
    job_id INT NOT NULL,
    FOREIGN KEY (freelancer_id) REFERENCES Users (id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES Jobs (id) ON DELETE CASCADE
);

CREATE TABLE Milestones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    deadline_date DATE NOT NULL,
    status ENUM(
        'Pending',
        'Awaiting Approval',
        'Approved',
        'Rescheduled'
    ) DEFAULT 'Pending',
    job_id INT NOT NULL,
    FOREIGN KEY (job_id) REFERENCES Jobs (id) ON DELETE CASCADE
);

CREATE TABLE Escrow_Transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    amount INT NOT NULL,
    status ENUM(
        'Locked',
        'Released',
        'Refunded'
    ) DEFAULT 'Locked',
    milestone_id INT NOT NULL,
    FOREIGN KEY (milestone_id) REFERENCES Milestones (id) ON DELETE CASCADE
);

CREATE TABLE Notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT false,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE
);

CREATE TABLE Dispute (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reason TEXT NOT NULL,
    status ENUM(
        'Open',
        'Under Review',
        'Resolved',
        'Dismissed'
    ) DEFAULT 'Open',
    job_id INT NOT NULL,
    raised_by_id INT NOT NULL,
    against_user_id INT NOT NULL,
    FOREIGN KEY (job_id) REFERENCES Jobs (id) ON DELETE CASCADE,
    FOREIGN KEY (raised_by_id) REFERENCES Users (id) ON DELETE CASCADE,
    FOREIGN KEY (against_user_id) REFERENCES Users (id) ON DELETE CASCADE
);

INSERT INTO
    Users (
        name,
        email,
        password,
        role,
        reputation_score,
        is_verified
    )
VALUES (
        'System Admin',
        'admin@freelance.com',
        'pass_123',
        'Admin',
        5.00,
        true
    ),
    (
        'Sarah The Client',
        'sarah@business.com',
        'pass_123',
        'Client',
        4.50,
        true
    ),
    (
        'Momen',
        'momen@dev.com',
        'pass_123',
        'Freelancer',
        4.90,
        true
    ),
    (
    'M',
    'mom@dev.com',
    'pass_123',
    'Freelancer',
    4.90,
    true
)
(
    'Mon',
    'moen@dev.com',
    'pass_123',
    'Freelancer',
    4.90,
    true
)
(
    'Mon',
    'moen@dev.com',
    'pass_123',
    'Freelancer',
    4.90,
    true
);

INSERT INTO
    Jobs (
        title,
        description,
        status,
        client_id
    )
VALUES (
        'Next.js E-commerce Platform',
        'Need a high-performance e-commerce site with Tailwind CSS and a Node.js backend. Must integrate with Supabase.',
        'In Progress',
        2
    );

INSERT INTO
    Proposals (
        bid_amount,
        description,
        status,
        freelancer_id,
        job_id
    )
VALUES (
        1500,
        'I am a MERN stack developer with experience building platforms like CornerStone. I can deliver this within 30 days.',
        'Accepted',
        3,
        1
    );

INSERT INTO
    Milestones (
        title,
        deadline_date,
        status,
        job_id
    )
VALUES (
        'Phase 1: UI Design & Next.js Setup',
        '2026-06-15',
        'Approved',
        1
    ),
    (
        'Phase 2: Node.js Backend API',
        '2026-07-01',
        'In Progress',
        1
    );

INSERT INTO
    Escrow_Transactions (amount, status, milestone_id)
VALUES (500, 'Released', 1),
    (1000, 'Locked', 2);

INSERT INTO
    Notifications (message, is_read, user_id)
VALUES (
        'Your proposal for Next.js E-commerce Platform was accepted!',
        false,
        3
    ),
    (
        'Phase 1 Escrow funds have been successfully released.',
        true,
        2
    );

INSERT INTO
    Dispute (
        reason,
        status,
        job_id,
        raised_by_id,
        against_user_id
    )
VALUES (
        'The developer insists on using a native MongoDB driver instead of the agreed ORM structure.',
        'Under Review',
        1,
        2,
        3
    );

INSERT INTO
    Users (
        name,
        email,
        password,
        role,
        reputation_score,
        is_verified,
        created_at
    )
VALUES (
        'John Freelancer',
        'john@dev.com',
        'pass_123',
        'Freelancer',
        4.80,
        true,
        NOW()
    ),
    (
        'Alice Client',
        'alice@company.com',
        'pass_123',
        'Client',
        4.20,
        true,
        NOW()
    );

INSERT INTO
    Jobs (
        title,
        description,
        status,
        client_id,
        created_at,
        budget
    )
VALUES (
        'Mobile App Development',
        'Build a React Native mobile app',
        'Open',
        2,
        NOW(),
        3000
    ),
    (
        'API Integration',
        'Integrate payment gateway',
        'In Progress',
        5,
        NOW(),
        1200
    );

ALTER TABLE Jobs
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN budget INT DEFAULT NULL,
ADD COLUMN assigned_freelancer_id INT DEFAULT NULL,
ADD FOREIGN KEY (assigned_freelancer_id) REFERENCES Users (id) ON DELETE SET NULL;

ALTER TABLE Users
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN total_spent INT DEFAULT 0,
ADD COLUMN total_earned INT DEFAULT 0;

ALTER TABLE Milestones
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN amount INT NOT NULL DEFAULT 0;

ALTER TABLE Escrow_Transactions
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN transaction_hash VARCHAR(255) DEFAULT NULL,
ADD COLUMN released_at TIMESTAMP NULL,
ADD INDEX idx_status (status),
ADD INDEX idx_milestone_id (milestone_id);

ALTER TABLE Dispute
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
ADD COLUMN resolved_at TIMESTAMP NULL,
ADD COLUMN resolution_notes TEXT NULL,
ADD INDEX idx_status (status),
ADD INDEX idx_job_id (job_id);

CREATE TABLE Contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    client_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    total_amount INT NOT NULL,
    status ENUM(
        'active',
        'completed',
        'cancelled',
        'disputed'
    ) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (job_id) REFERENCES Jobs (id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES Users (id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES Users (id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_client (client_id),
    INDEX idx_freelancer (freelancer_id)
);

CREATE TABLE Dispute_Messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dispute_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (dispute_id) REFERENCES Dispute (id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE,
    INDEX idx_dispute (dispute_id)
);

CREATE TABLE Activity_Log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_created (created_at)
);
ALTER TABLE Dispute
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
CREATE INDEX idx_jobs_status_budget ON Jobs (status, budget);

CREATE INDEX idx_jobs_client_created ON Jobs (client_id, created_at);

CREATE INDEX idx_escrow_status_amount ON Escrow_Transactions (status, amount);

CREATE INDEX idx_dispute_status_created ON Dispute (status, created_at);

CREATE INDEX idx_users_role_created ON Users (role, created_at);

ALTER TABLE Jobs
ADD COLUMN IF NOT EXISTS category VARCHAR(100) DEFAULT 'Other';

ALTER TABLE Escrow_Transactions
ADD COLUMN IF NOT EXISTS platform_fee INT DEFAULT 0;

UPDATE Users SET role = 'client' WHERE LOWER(role) = 'client';

UPDATE Users
SET role = 'freelancer'
WHERE
    LOWER(role) = 'freelancer';

UPDATE Users SET role = 'admin' WHERE LOWER(role) = 'admin';

CREATE OR REPLACE VIEW marketplace_health_snapshot AS
SELECT
    COUNT(
        CASE
            WHEN j.status = 'In Progress' THEN 1
        END
    ) as active_contracts,
    COUNT(
        CASE
            WHEN j.status = 'Completed' THEN 1
        END
    ) as completed_contracts,
    COALESCE(
        SUM(
            CASE
                WHEN e.status = 'Locked' THEN e.amount
                ELSE 0
            END
        ),
        0
    ) as total_escrowed_value,
    COALESCE(
        SUM(
            CASE
                WHEN e.status = 'Released' THEN e.platform_fee
                ELSE 0
            END
        ),
        0
    ) as platform_fees,
    ROUND(
        (
            COUNT(
                DISTINCT CASE
                    WHEN d.status IN ('Open', 'Under Review') THEN d.job_id
                END
            ) * 100.0
        ) / NULLIF(
            COUNT(
                DISTINCT CASE
                    WHEN j.status IN ('In Progress', 'Completed') THEN j.id
                END
            ),
            0
        ),
        2
    ) as dispute_rate,
    (
        SELECT COUNT(*)
        FROM Users
        WHERE
            LOWER(role) = 'freelancer'
    ) as total_freelancers,
    (
        SELECT COUNT(*)
        FROM Users
        WHERE
            LOWER(role) = 'client'
    ) as total_clients,
    (
        SELECT COALESCE(AVG(budget), 0)
        FROM Jobs
        WHERE
            status != 'Open'
    ) as average_job_value
FROM
    Jobs j
    LEFT JOIN Escrow_Transactions e ON j.id = e.milestone_id
    LEFT JOIN Dispute d ON j.id = d.job_id;

DELIMITER / /

CREATE OR REPLACE PROCEDURE GetWeeklyTrends()
BEGIN
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as new_jobs,
        COALESCE(SUM(budget), 0) as total_value
    FROM Jobs
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC;
END //

DELIMITER;

DELIMITER / /

CREATE OR REPLACE PROCEDURE GetTopCategories(IN limit_count INT)
BEGIN
    SELECT 
        COALESCE(category, 'Uncategorized') as category,
        COUNT(*) as job_count,
        COALESCE(AVG(budget), 0) as avg_budget
    FROM Jobs
    WHERE status != 'Open'
    GROUP BY category
    ORDER BY job_count DESC
    LIMIT limit_count;
END //

DELIMITER;

UPDATE Jobs SET category = 'Web Development' WHERE id = 1;

UPDATE Jobs SET category = 'Mobile Development' WHERE id = 2;

UPDATE Jobs SET category = 'API Integration' WHERE id = 3;

UPDATE Jobs SET category = 'Web Development' WHERE category IS NULL;