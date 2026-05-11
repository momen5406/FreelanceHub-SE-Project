<?php require_once '../../views/partials/header.php'; ?>

<?php

require_once "../../app/models/User.php";
require_once "../../app/core/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$profile = [];

if (!isset($_SESSION["user_id"])) {
    header("Location: ../../views/auth/login.php");
    exit();
} else {
    $userId = $_SESSION["user_id"];
    $db = new Database;
    if ($db->openConnection()) {
        $query = "SELECT * FROM users WHERE id='$userId'";
        $result = $db->select($query);
        if ($result === false || count($result) == 0) {
            echo "None";
            exit();
        } else {
            $user = new User;
            $profile = $user->getUserProfile($result[0]);
        }
    }
}



$is_own_profile = (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $profile['id']);
?>

<style>
    .fh-card { background: #fff; border: 1.5px solid #e2dfd8; border-radius: 12px; box-shadow: 0 4px 20px rgba(26,26,46,0.04); }
    
    .profile-avatar-lg { width: 120px; height: 120px; background: #1a1a2e; border: 4px solid #fff; box-shadow: 0 8px 25px rgba(26,26,46,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #e8a045; font-size: 3.5rem; margin: -60px auto 1rem; position: relative; z-index: 2; }
    .profile-header-bg { background: #1a1a2e; height: 100px; border-radius: 12px 12px 0 0; }
    
    .skill-badge { background: #f5f4f0; color: #1a1a2e; font-weight: 600; padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.85rem; border: 1px solid #e2dfd8; }
    
    .btn-fh-primary { background: #e8a045; color: #1a1a2e; border: none; border-radius: 8px; font-weight: 700; padding: 0.6rem 1.2rem; transition: all 0.2s; }
    .btn-fh-primary:hover { background: #d4903a; transform: translateY(-1px); color: #1a1a2e; }
    
    .btn-fh-outline { border: 2px solid #e2dfd8; color: #1a1a2e; background: transparent; border-radius: 8px; font-weight: 700; padding: 0.6rem 1.2rem; transition: all 0.2s; }
    .btn-fh-outline:hover { border-color: #1a1a2e; background: #1a1a2e; color: #fff; }
</style>

<div class="row g-4 mt-2 mb-5">
    
    <!-- LEFT SIDEBAR: Snapshot -->
    <div class="col-lg-4">
        <div class="fh-card text-center mb-4 pt-0">
            <div class="profile-header-bg"></div>
            <div class="profile-avatar-lg">
                <?= strtoupper(substr($profile['name'], 0, 1)) ?>
            </div>
            
            <div class="px-4 pb-4">
                <h4 class="fw-bold mb-0" style="color: #1a1a2e;"><?= htmlspecialchars($profile['name']) ?></h4>
                <div class="text-muted fw-bold small mb-3"><?= htmlspecialchars($profile['headline']) ?></div>
                
                <div class="d-flex justify-content-center align-items-center gap-2 mb-4">
                    <span class="text-warning fw-bold"><i class="bi bi-star-fill"></i> <?= $profile['rating'] ?></span>
                    <span class="text-muted">•</span>
                    <span class="text-muted small"><i class="bi bi-geo-alt-fill me-1"></i><?= htmlspecialchars($profile['location']) ?></span>
                </div>

                <div class="d-grid gap-2">
                    <?php if ($is_own_profile): ?>
                        <a href="../../views/profile/edit.php" class="btn btn-fh-outline"><i class="bi bi-pencil-square me-2"></i>Edit Profile</a>
                    <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'Client'): ?>
                        <button class="btn btn-fh-primary"><i class="bi bi-envelope me-2"></i>Hire & Message</button>
                    <?php else: ?>
                        <button class="btn btn-secondary disabled">Log in to contact</button>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="border-top px-4 py-3 bg-light rounded-bottom text-start d-flex justify-content-between">
                <div>
                    <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.7rem;">Hourly Rate</small>
                    <strong style="color: #1a1a2e;">$<?= $profile['hourly_rate'] ?>/hr</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="fh-card p-4 p-md-5 mb-4">
            <h5 class="fw-bold mb-4" style="color: #1a1a2e;">About <?= explode(' ', $profile['name'])[0] ?></h5>
            <div class="text-muted lh-lg" style="font-size: 1.05rem;">
                <?= nl2br(htmlspecialchars($profile['bio'])) ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>