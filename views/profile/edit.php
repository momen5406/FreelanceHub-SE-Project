<?php require_once '../../views/partials/header.php'; ?>

<?php

require_once "../../app/models/User.php";
require_once "../../app/core/database.php";

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$profile = [];
$successMsg = "";
$errorMsg = "";

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
  if (isset($_POST['name']) || isset($_POST['hourly_rate']) || isset($_POST['headline']) || isset($_POST['bio'])) {
    $user = new User;
    $user->name = $_POST['name'];
    $user->hourly_rate = $_POST['hourly_rate'];
    $user->headline = $_POST['headline'];
    $user->bio = $_POST['bio'];
    $query = "UPDATE users SET name = '$user->name', hourly_rate = '$user->hourly_rate', headline = '$user->headline', bio = '$user->bio' WHERE id = '$userId'";
    $result = $db->update($query);
    if ($result) {
      $successMsg = "Profile updated successfully!";
    } else {
      $errorMsg = "Error updating profile. Please try again.";
    }
    $query = "SELECT * FROM users WHERE id='$userId'";
    $result = $db->select($query);
    if ($result === false || count($result) == 0) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Profile not found.</div></div>";
        exit();
    } else {
        $userObj = new User;
        $profile = $userObj->getUserProfile($result[0]);
    }
  }
}
?>
<style>
  .fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
  }

  .form-control-fh {
    border-radius: 8px;
    padding: 0.65rem 1rem;
    border: 1.5px solid #e2dfd8;
    background-color: #faf9f6;
    transition: all 0.2s;
  }

  .form-control-fh:focus {
    border-color: #e8a045;
    box-shadow: 0 0 0 0.25rem rgba(232, 160, 69, 0.25);
    background-color: #fff;
  }

  .form-label {
    font-weight: 700;
    color: #1a1a2e;
    font-size: 0.95rem;
  }

  .btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s;
  }

  .btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-1px);
    color: #1a1a2e;
  }

  .btn-fh-outline {
    border: 2px solid #e2dfd8;
    color: #1a1a2e;
    background: transparent;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
    transition: all 0.2s;
  }

  .btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
  }
</style>

<nav aria-label="breadcrumb" class="mb-4 mt-2">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../../views/profile/view.php ?>" class="text-muted text-decoration-none">My Profile</a></li>
    <li class="breadcrumb-item text-muted fw-bold" aria-current="page">Edit Settings</li>
  </ol>
</nav>

<div class="row justify-content-center mb-5">
  <div class="col-lg-8">

    <div class="mb-4">
      <h2 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">Edit Profile</h2>
      <p class="text-muted">Update your personal information to attract better clients.</p>
    </div>

    <?php if (!empty($successMsg)): ?>
      <div class="alert alert-success fw-bold border-2 shadow-sm mb-4" role="alert" style="background-color: rgba(46, 204, 113, 0.1); border-color: #27ae60; color: #27ae60;">
        <i class="bi bi-check-circle-fill me-2"></i><?= $successMsg ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($errorMsg)): ?>
      <div class="alert alert-danger fw-bold border-2 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $errorMsg ?>
      </div>
    <?php endif; ?>

    <div class="fh-card p-4 p-md-5">
      <form action="edit.php" method="POST">

        <div class="row mb-4">
          <div class="col-md-7 mb-4 mb-md-0">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control form-control-fh" id="name" name="name" value="<?= htmlspecialchars($profile['name']) ?>" required>
          </div>

          <div class="col-md-5">
            <label for="hourly_rate" class="form-label">Hourly Rate ($)</label>
            <div class="input-group">
              <span class="input-group-text border-end-0 bg-transparent border-2" style="border-color: #e2dfd8; color: #888;">$</span>
              <input type="number" class="form-control border-start-0 border-2 shadow-none" id="hourly_rate" name="hourly_rate" value="<?= htmlspecialchars($profile['hourly_rate']) ?>" min="5" required style="border-color: #e2dfd8; padding: 0.65rem;">
            </div>
          </div>
        </div>

        <div class="mb-4">
          <label for="headline" class="form-label">Professional Headline</label>
          <input type="text" class="form-control form-control-fh" id="headline" name="headline" value="<?= htmlspecialchars($profile['headline']) ?>" placeholder="e.g. Full-Stack MERN Developer" required>
          <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i> This is the first thing clients see next to your name. Keep it punchy.</div>
        </div>

        <div class="mb-5">
          <label for="bio" class="form-label">About You (Bio)</label>
          <textarea class="form-control form-control-fh" id="bio" name="bio" rows="7" required placeholder="Tell clients about your expertise, your workflow, and what makes you unique..."><?= htmlspecialchars($profile['bio']) ?></textarea>
        </div>

        <div class="d-flex justify-content-end gap-3 border-top pt-4">
          <a href="/profile/view.php?id=<?= $_SESSION['user_id'] ?>" class="btn btn-fh-outline">Cancel</a>
          <button type="submit" class="btn btn-fh-primary">
            <i class="bi bi-save me-2"></i> Save Changes
          </button>
        </div>

      </form>
    </div>

  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>