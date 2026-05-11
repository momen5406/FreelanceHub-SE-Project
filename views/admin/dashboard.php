<?php require_once '../../views/partials/header.php'; ?>

<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Access Denied. Admin privileges required.</div></div>";
    require_once '../../views/partials/footer.php';
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "freelance_platform";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$error = '';
$allowedRoles = [
    'Admin' => 1,
    'Financial' => 2,
    'Dispute Mediator' => 3,
    'Tech Support' => 4,
    'Client' => 5,
    'Freelancer' => 6
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_account'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'Client';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Please fill in name, email, and password.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif (!array_key_exists($role, $allowedRoles)) {
        $error = 'Invalid role selected.';
    } else {
        $safeEmail = $conn->real_escape_string($email);
        $existingUser = $conn->query("SELECT id FROM Users WHERE email = '$safeEmail' LIMIT 1");

        if ($existingUser && $existingUser->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $safeName = $conn->real_escape_string($name);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $safePassword = $conn->real_escape_string($hashedPassword);
            $safeRole = $conn->real_escape_string($role);
            $roleId = $allowedRoles[$role];

            $insertQuery = "
                INSERT INTO Users (name, email, password, role, is_verified, role_id, created_at)
                VALUES ('$safeName', '$safeEmail', '$safePassword', '$safeRole', 1, $roleId, NOW())
            ";

            if ($conn->query($insertQuery)) {
                $message = 'Account created successfully.';
                $_POST = [];
            } else {
                $error = 'Error creating account: ' . $conn->error;
            }
        }
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $deleteQuery = "DELETE FROM Users WHERE id = $delete_id";
    if ($conn->query($deleteQuery)) {
        echo "<script>alert('User deleted successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting user!');</script>";
    }
}

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$whereClause = '';
if (!empty($search)) {
    $whereClause = "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%'";
}

$recentUsers = [];
$totalFreelancers = 0;
$totalClients = 0;
$totalFinancial = 0;
$totalTechSupport = 0;
$totalDisputeMediator = 0;
$totalAdmins = 0;

$usersQuery = "SELECT id, name, email, role, is_verified FROM Users $whereClause ORDER BY id DESC LIMIT 50";
$usersResult = $conn->query($usersQuery);

if ($usersResult && $usersResult->num_rows > 0) {
    while ($row = $usersResult->fetch_assoc()) {
        $recentUsers[] = $row;
    }
}

$freelancersQuery = "SELECT COUNT(*) as total FROM Users WHERE role = 'Freelancer'";
$freelancersResult = $conn->query($freelancersQuery);
if ($freelancersResult && $freelancersResult->num_rows > 0) {
    $row = $freelancersResult->fetch_assoc();
    $totalFreelancers = $row['total'];
}

$clientsQuery = "SELECT COUNT(*) as total FROM Users WHERE role = 'Client'";
$clientsResult = $conn->query($clientsQuery);
if ($clientsResult && $clientsResult->num_rows > 0) {
    $row = $clientsResult->fetch_assoc();
    $totalClients = $row['total'];
}

$financialQuery = "SELECT COUNT(*) as total FROM Users WHERE role = 'Financial'";
$financialResult = $conn->query($financialQuery);
if ($financialResult && $financialResult->num_rows > 0) {
    $row = $financialResult->fetch_assoc();
    $totalFinancial = $row['total'];
}

$techSupportQuery = "SELECT COUNT(*) as total FROM Users WHERE role = 'Tech Support'";
$techSupportResult = $conn->query($techSupportQuery);
if ($techSupportResult && $techSupportResult->num_rows > 0) {
    $row = $techSupportResult->fetch_assoc();
    $totalTechSupport = $row['total'];
}

$disputeMediatorQuery = "SELECT COUNT(*) as total FROM Users WHERE role = 'Dispute Mediator'";
$disputeMediatorResult = $conn->query($disputeMediatorQuery);
if ($disputeMediatorResult && $disputeMediatorResult->num_rows > 0) {
    $row = $disputeMediatorResult->fetch_assoc();
    $totalDisputeMediator = $row['total'];
}

$adminsQuery = "SELECT COUNT(*) as total FROM Users WHERE role = 'Admin'";
$adminsResult = $conn->query($adminsQuery);
if ($adminsResult && $adminsResult->num_rows > 0) {
    $row = $adminsResult->fetch_assoc();
    $totalAdmins = $row['total'];
}

$conn->close();
?>

<style>
.fh-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
}

.admin-metric-card {
    padding: 1.5rem;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 1px solid #e2dfd8;
    background: #fff;
    transition: all 0.2s;
}

.admin-metric-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(26, 26, 46, 0.08);
    border-color: #1a1a2e;
}

.metric-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.icon-users {
    background: rgba(26, 26, 46, 0.1);
    color: #1a1a2e;
}

.table-fh th {
    background-color: #f5f4f0;
    color: #6c757d;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    padding: 1rem;
    border-bottom: 2px solid #e2dfd8;
}

.table-fh td {
    padding: 1rem;
    vertical-align: middle;
    color: #1a1a2e;
    font-weight: 500;
    border-bottom: 1px solid #e2dfd8;
}

.badge-role-client {
    background: rgba(26, 26, 46, 0.1);
    color: #1a1a2e;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-role-freelancer {
    background: rgba(232, 160, 69, 0.2);
    color: #d35400;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-role-admin {
    background: rgba(231, 76, 60, 0.15);
    color: #c0392b;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-role-financial {
    background: rgba(243, 156, 18, 0.2);
    color: #e67e22;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-role-tech {
    background: rgba(155, 89, 182, 0.15);
    color: #8e44ad;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-role-mediator {
    background: rgba(26, 188, 156, 0.15);
    color: #16a085;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.search-box {
    padding: 8px 15px;
    border: 1px solid #e2dfd8;
    border-radius: 8px;
    width: 250px;
    font-size: 14px;
}

.btn-search {
    background: #1a1a2e;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    margin-left: 10px;
}

.btn-search:hover {
    background: #e8a045;
    color: #1a1a2e;
}

.btn-delete {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
}

.btn-delete:hover {
    background: #c82333;
}

.btn-clear {
    background: #6c757d;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    margin-left: 5px;
}

.btn-clear:hover {
    background: #5a6268;
}

.btn-export {
    background: #28a745;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 8px;
    text-decoration: none;
}

.btn-export:hover {
    background: #218838;
    color: white;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.create-account-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
    gap: 1rem;
}

.form-control-fh,
.form-select-fh {
    width: 100%;
    padding: 0.65rem 0.8rem;
    border: 1px solid #e2dfd8;
    border-radius: 8px;
    font-size: 0.9rem;
}

.form-control-fh:focus,
.form-select-fh:focus {
    border-color: #e8a045;
    outline: none;
    box-shadow: 0 0 0 3px rgba(232, 160, 69, 0.15);
}

.form-label-fh {
    color: #1a1a2e;
    font-size: 0.85rem;
    font-weight: 700;
    margin-bottom: 0.35rem;
}

.btn-create-account {
    background: #27ae60;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.65rem 1rem;
}

.btn-create-account:hover {
    background: #219a52;
}
</style>

<nav aria-label="breadcrumb" class="mb-3 mt-2">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/" class="text-muted text-decoration-none">Home</a></li>
        <li class="breadcrumb-item text-muted fw-bold" aria-current="page">Admin Control Panel</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-end mb-4 pb-3 border-bottom">
    <div>
        <h2 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">System Dashboard</h2>
        <p class="text-muted mb-0">Platform overview, user moderation, and financial health.</p>
    </div>
    <div>
        <a href="export_users.php" class="btn-export">
            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export Users Report
        </a>
    </div>
</div>

<div class="stats-grid mb-4">
    <div class="admin-metric-card">
        <div>
            <div class="text-muted small mb-1">Total Admins</div>
            <div class="fs-2 fw-bold" style="color: #1a1a2e;"><?php echo $totalAdmins; ?></div>
        </div>
        <div class="metric-icon icon-users">
            <i class="bi bi-shield-lock"></i>
        </div>
    </div>
    <div class="admin-metric-card">
        <div>
            <div class="text-muted small mb-1">Total Freelancers</div>
            <div class="fs-2 fw-bold" style="color: #1a1a2e;"><?php echo $totalFreelancers; ?></div>
        </div>
        <div class="metric-icon icon-users">
            <i class="bi bi-people"></i>
        </div>
    </div>
    <div class="admin-metric-card">
        <div>
            <div class="text-muted small mb-1">Total Clients</div>
            <div class="fs-2 fw-bold" style="color: #1a1a2e;"><?php echo $totalClients; ?></div>
        </div>
        <div class="metric-icon icon-users">
            <i class="bi bi-building"></i>
        </div>
    </div>
    <div class="admin-metric-card">
        <div>
            <div class="text-muted small mb-1">Financial Team</div>
            <div class="fs-2 fw-bold" style="color: #1a1a2e;"><?php echo $totalFinancial; ?></div>
        </div>
        <div class="metric-icon icon-users">
            <i class="bi bi-cash-stack"></i>
        </div>
    </div>
    <div class="admin-metric-card">
        <div>
            <div class="text-muted small mb-1">Tech Support</div>
            <div class="fs-2 fw-bold" style="color: #1a1a2e;"><?php echo $totalTechSupport; ?></div>
        </div>
        <div class="metric-icon icon-users">
            <i class="bi bi-tools"></i>
        </div>
    </div>
    <div class="admin-metric-card">
        <div>
            <div class="text-muted small mb-1">Dispute Mediators</div>
            <div class="fs-2 fw-bold" style="color: #1a1a2e;"><?php echo $totalDisputeMediator; ?></div>
        </div>
        <div class="metric-icon icon-users">
            <i class="bi bi-balance-scale"></i>
        </div>
    </div>
</div>

<?php if ($message): ?>
<div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="fh-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div>
            <h5 class="fw-bold mb-1" style="color: #1a1a2e;">Add Account</h5>
        </div>
    </div>

    <form method="POST" action="">
        <div class="create-account-grid">
            <div>
                <label class="form-label-fh">Name</label>
                <input type="text" name="name" class="form-control-fh" required
                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            <div>
                <label class="form-label-fh">Email</label>
                <input type="email" name="email" class="form-control-fh" required
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            <div>
                <label class="form-label-fh">Password</label>
                <input type="password" name="password" class="form-control-fh" required minlength="6">
            </div>
            <div>
                <label class="form-label-fh">Role</label>
                <select name="role" class="form-select-fh" required>
                    <?php foreach ($allowedRoles as $roleName => $roleId): ?>
                    <option value="<?php echo htmlspecialchars($roleName); ?>"
                        <?php echo (($_POST['role'] ?? 'Client') === $roleName) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($roleName); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" name="create_account" class="btn-create-account">
                <i class="bi bi-person-plus me-1"></i> Create Account
            </button>
        </div>
    </form>
</div>

<div class="row g-4 mb-5">
    <div class="col-xl-12">
        <div class="fh-card h-100 overflow-hidden">
            <div class="p-4 border-bottom bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0" style="color: #1a1a2e;">Recent Registrations</h5>
                    <form method="GET" action="" class="d-flex">
                        <input type="text" name="search" class="search-box"
                            placeholder="Search by name, email or role..."
                            value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="btn-search"><i class="bi bi-search"></i> Search</button>
                        <?php if (!empty($search)): ?>
                        <a href="dashboard.php" class="btn-clear"><i class="bi bi-x-circle"></i> Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-fh mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Verified</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentUsers)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No users found</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($recentUsers as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php
                                        if ($user['role'] == 'Client') {
                                            echo '<span class="badge-role-client">👤 Client</span>';
                                        } elseif ($user['role'] == 'Freelancer') {
                                            echo '<span class="badge-role-freelancer">💼 Freelancer</span>';
                                        } elseif ($user['role'] == 'Admin') {
                                            echo '<span class="badge-role-admin">👑 Admin</span>';
                                        } elseif ($user['role'] == 'Financial') {
                                            echo '<span class="badge-role-financial">💰 Financial</span>';
                                        } elseif ($user['role'] == 'Tech Support') {
                                            echo '<span class="badge-role-tech">🔧 Tech Support</span>';
                                        } elseif ($user['role'] == 'Dispute Mediator') {
                                            echo '<span class="badge-role-mediator">⚖️ Dispute Mediator</span>';
                                        } else {
                                            echo '<span class="badge-role-client">' . htmlspecialchars($user['role']) . '</span>';
                                        }
                                        ?>
                            </td>
                            <td>
                                <?php if ($user['is_verified'] == 1): ?>
                                <span class="text-success">✓ Verified</span>
                                <?php else: ?>
                                <span class="text-danger">✗ Not verified</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="?delete_id=<?php echo $user['id']; ?>" class="btn-delete"
                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>
