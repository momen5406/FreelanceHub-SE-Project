<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    require_once "../../app/controllers/AuthController.php";
    $auth = new AuthController();
    $auth->logout();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FreelanceHub</title>
    <link href="../../public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f5f4f0;
        font-family: 'Segoe UI', system-ui, sans-serif;
    }

    .fh-navbar {
        background-color: #1a1a2e !important;
        border-bottom: 3px solid #e8a045;
        padding: 0;
        min-height: 64px;
    }

    .navbar-brand {
        font-size: 1.35rem;
        font-weight: 800;
        color: #fff !important;
        letter-spacing: -0.5px;
    }

    .navbar-brand .brand-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #e8a045;
        border-radius: 50%;
        margin-right: 6px;
        vertical-align: middle;
    }

    .navbar-brand .brand-accent {
        color: #e8a045;
    }

    /* Nav links */
    .fh-navbar .nav-link {
        color: rgba(255, 255, 255, 0.72) !important;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.45rem 0.85rem !important;
        border-radius: 6px;
        transition: background 0.15s, color 0.15s;
    }

    .fh-navbar .nav-link:hover {
        background: rgba(255, 255, 255, 0.09);
        color: #fff !important;
    }

    /* Login ghost button */
    .btn-fh-login {
        color: rgba(255, 255, 255, 0.8) !important;
        background: transparent;
        border: 1.5px solid rgba(255, 255, 255, 0.22) !important;
        border-radius: 6px !important;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.42rem 1rem !important;
        transition: all 0.15s;
    }

    .btn-fh-login:hover {
        border-color: rgba(255, 255, 255, 0.5) !important;
        color: #fff !important;
        background: rgba(255, 255, 255, 0.05) !important;
    }

    /* Sign up amber button */
    .btn-fh-signup {
        background: #e8a045 !important;
        color: #1a1a2e !important;
        border: none !important;
        border-radius: 6px !important;
        font-size: 0.85rem;
        font-weight: 700;
        padding: 0.42rem 1.1rem !important;
        transition: background 0.15s, transform 0.1s;
    }

    .btn-fh-signup:hover {
        background: #d4903a !important;
        transform: translateY(-1px);
        color: #1a1a2e !important;
    }

    /* Admin badge */
    .btn-fh-admin {
        background: rgba(232, 160, 69, 0.15) !important;
        border: 1.5px solid rgba(232, 160, 69, 0.35) !important;
        color: #e8a045 !important;
        border-radius: 6px !important;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 0.38rem 0.85rem !important;
        transition: background 0.15s;
    }

    .btn-fh-admin:hover {
        background: rgba(232, 160, 69, 0.25) !important;
    }

    /* Profile pill dropdown toggle */
    .btn-fh-profile {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff !important;
        background: rgba(255, 255, 255, 0.08) !important;
        border: 1.5px solid rgba(255, 255, 255, 0.14) !important;
        border-radius: 50px !important;
        padding: 0.32rem 0.9rem 0.32rem 0.4rem !important;
        font-size: 0.82rem;
        font-weight: 600;
        transition: background 0.15s, border-color 0.15s;
    }

    .btn-fh-profile:hover {
        background: rgba(255, 255, 255, 0.14) !important;
        border-color: rgba(255, 255, 255, 0.28) !important;
        color: #fff !important;
    }

    .fh-avatar {
        width: 28px;
        height: 28px;
        background: #e8a045;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        color: #1a1a2e;
        flex-shrink: 0;
    }

    /* Dropdown menu */
    .fh-navbar .dropdown-menu {
        background: #fff;
        border: 1px solid #e2dfd8;
        border-radius: 10px;
        box-shadow: 0 8px 28px rgba(26, 26, 46, 0.16);
        padding: 0.35rem 0;
        min-width: 190px;
    }

    .fh-navbar .dropdown-header {
        padding: 0.65rem 1rem 0.5rem;
        border-bottom: 1px solid #f0ede7;
        font-size: 0.8rem;
        color: #888;
    }

    .fh-navbar .dropdown-item {
        color: #3a3a5c;
        font-size: 0.875rem;
        padding: 0.6rem 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.12s;
    }

    .fh-navbar .dropdown-item:hover {
        background: #f7f5f1;
        color: #1a1a2e;
    }

    .fh-navbar .dropdown-item.text-danger:hover {
        background: #fdf3f3;
        color: #c0392b !important;
    }

    .fh-navbar .dropdown-divider {
        border-color: #f0ede7;
        margin: 0.2rem 0;
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg fh-navbar  mb-0">
        <div class="container">

            <a class="navbar-brand" href="../../views/home/index.php">
                <span class="brand-dot"></span>
                Freelance<span class="brand-accent">Hub</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                style="filter: invert(1);">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">

                <ul class="navbar-nav me-auto gap-1">
                    <li class="nav-item">
                        <a class="nav-link" href="../../views/jobs/index.php">
                            <i class="bi bi-grid me-1"></i>Browse Jobs
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-center gap-2">

                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="btn btn-fh-login" href="../../views/auth/login.php">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Log In
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-fh-signup" href="../../views/auth/register.php">
                            <i class="bi bi-person-plus me-1"></i>Sign Up
                        </a>
                    </li>

                    <?php else: ?>

                    <?php if ($_SESSION['role'] === 'Client'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../../views/jobs/create.php">
                            <i class="bi bi-plus-circle me-1"></i>Post a Job
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../views/jobs/my-postings.php">
                            <i class="bi bi-folder2-open me-1"></i>My Jobs
                        </a>
                    </li>

                    <?php elseif ($_SESSION['role'] === 'Freelancer'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../proposals/my-proposals.php">
                            <i class="bi bi-file-text me-1"></i>My Proposals
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../escrow/wallet.php">
                            <i class="bi bi-wallet2 me-1"></i>Wallet
                        </a>
                    </li>

                    <<?php elseif ($_SESSION['role'] === 'Admin'): ?> <li class="nav-item">
                        <a class="btn btn-fh-admin" href="../../views/admin/dashboard.php">
                            <i class="bi bi-shield-check me-1"></i>Admin Panel
                        </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-fh-admin" href="../../views/admin/manage-roles.php">
                                <i class="bi bi-person-badge me-1"></i>Manage Roles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-fh-admin" href="../../public/marketplace-health.php">
                                <i class="bi bi-graph-up me-1"></i>Marketplace Health
                            </a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown">
                            <a class="btn-fh-profile dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fh-avatar">
                                    <?= strtoupper(substr($_SESSION['username'] ?? 'U', 0, 2)) ?>
                                </span>
                                <?= htmlspecialchars($_SESSION['username'] ?? 'Profile') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <h6 class="dropdown-header">
                                        <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
                                        <br><small class="text-muted"><?= $_SESSION['role'] ?? '' ?></small>
                                    </h6>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="../../views/profile/view.php">
                                        <i class="bi bi-gear"></i> Settings
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="" method="POST">
                                        <input type="hidden" name="logout" value="true">
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right"></i> Log Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>

                        <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container-fluid px-0">
        <div class="container main-content min-vh-100 py-4">