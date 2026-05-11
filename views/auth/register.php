<?php 

require_once "../../app/controllers/AuthController.php";
require_once "../../app/models/User.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION["user_id"])) {
  if ($_SESSION['role'] === 'Client') {
    header("Location: ../../views/jobs/my-postings.php");
    exit();
  } elseif ($_SESSION['role'] === 'Freelancer') {
    header("Location: ../../views/jobs/index.php");
    exit();
  } elseif ($_SESSION['role'] === 'Admin') {
    header("Location: ../../views/admin/dashboard.php");
    exit();
  } else {
    header("Location: ../../views/home/index.php");
    exit();
  }
}

if (isset($_SESSION["userId"])) {
  session_start();
}

$errMsg = "";

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {
  if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role'])) {
    $user = new User;
    $auth = new AuthController;
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->password = $_POST['password'];
    $user->role = $_POST['role'];

    if ($auth->register($user)) {
      header("location: ../../views/auth/login.php");
    } else {
      $errMsg = $_SESSION["errMsg"];
    }

  } else {
    $errMsg = "All fields must be filled.";
  }
}

?>


<?php require_once '../../views/partials/header.php'; ?>


<style>
  .fh-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(26, 26, 46, 0.06);
    overflow: hidden;
    background: #fff;
  }

  .fh-card-header {
    background-color: #1a1a2e;
    padding: 1.5rem;
    text-align: center;
    border-bottom: 3px solid #e8a045;
  }

  .fh-card-title {
    color: #fff;
    font-weight: 800;
    margin: 0;
    font-size: 1.4rem;
    letter-spacing: -0.5px;
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

  .form-select-fh {
    border-radius: 8px;
    border: 1.5px solid #e2dfd8;
    padding: 0.65rem 1rem;
    font-weight: 500;
    cursor: pointer;
  }

  .form-select-fh:focus {
    border-color: #e8a045;
    box-shadow: 0 0 0 0.25rem rgba(232, 160, 69, 0.25);
  }

  .form-label {
    font-weight: 600;
    color: #1a1a2e;
    font-size: 0.9rem;
  }

  .btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.65rem;
    transition: all 0.2s;
  }

  .btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-1px);
    color: #1a1a2e;
  }
</style>

<div class="row justify-content-center my-5">
  <div class="col-md-6 col-lg-5">
    <div class="card fh-card">
      <div class="fh-card-header">
        <h4 class="fh-card-title">Create an Account</h4>
      </div>
      <div class="card-body p-4 p-md-5">

        <form action="register.php" method="POST">

        <?php 
          if ($errMsg != "") {
            ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $errMsg ?>
            </div>
            <?php
          }
        ?>

          <div class="mb-3">
            <label for="name" class="form-label"><i class="bi bi-person me-1"></i> Full Name</label>
            <input type="text" class="form-control form-control-fh" id="name" name="name" required placeholder="e.g. John Doe">
          </div>

          <div class="mb-3">
            <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i> Email Address</label>
            <input type="email" class="form-control form-control-fh" id="email" name="email" required placeholder="name@example.com">
          </div>

          <div class="mb-3">
            <label for="password" class="form-label"><i class="bi bi-lock me-1"></i> Password</label>
            <input type="password" class="form-control form-control-fh" id="password" name="password" required minlength="6" placeholder="Create a strong password">
          </div>

          <div class="mb-4">
            <label for="role" class="form-label"><i class="bi bi-briefcase me-1"></i> I want to join as a:</label>
            <select class="form-select form-select-fh" id="role" name="role" required>
              <option value="" disabled selected>Select your role...</option>
              <option value="Client">Client (Hire talent for a project)</option>
              <option value="Freelancer">Freelancer (Find work & bid on jobs)</option>
            </select>
          </div>

          <button type="submit" class="btn btn-fh-primary w-100 mt-2">
            <i class="bi bi-person-plus me-1"></i> Create Account
          </button>

        </form>

      </div>
      <div class="card-footer bg-transparent border-0 text-center pb-4 pt-0">
        <small style="color: #6c757d; font-weight: 500;">
          Already have an account?
          <a href="../../views/auth/login.php" style="color: #e8a045; text-decoration: none; font-weight: 700;">Log in here</a>
        </small>
      </div>
    </div>
  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>