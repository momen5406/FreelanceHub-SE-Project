<?php
session_start();
require_once '../../app/helpers/RoleHelper.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../../app/core/database.php';
$db = new Database();
$db->openConnection();

$message = '';
$error = '';

$availableRoles = [
    'Admin' => ['display' => '👑 Admin', 'class' => 'badge-admin'],
    'Client' => ['display' => '👤 Client', 'class' => 'badge-client'],
    'Freelancer' => ['display' => '💼 Freelancer', 'class' => 'badge-freelancer'],
    'Financial' => ['display' => '💰 Financial', 'class' => 'badge-financial'],
    'Tech Support' => ['display' => '🔧 Tech Support', 'class' => 'badge-tech'],
    'Dispute Mediator' => ['display' => '⚖️ Dispute Mediator', 'class' => 'badge-mediator']
];

if (isset($_POST['update_role'])) {
    $user_id = (int)$_POST['user_id'];
    $new_role = $_POST['role'];

    if (array_key_exists($new_role, $availableRoles)) {
        if (RoleHelper::updateUserRole($user_id, $new_role, $db)) {
            $message = "User role updated successfully to $new_role!";
        } else {
            $error = "Error updating role!";
        }
    } else {
        $error = "Invalid role selected!";
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    if ($delete_id != $_SESSION['user_id']) {
        $deleteQuery = "DELETE FROM Users WHERE id = $delete_id";
        $db->delete($deleteQuery);
        $message = "User deleted successfully!";
        header("Location: manage-roles.php");
        exit();
    }
}

$usersQuery = "SELECT id, name, email, role, is_verified FROM Users ORDER BY id DESC";
$users = $db->select($usersQuery);
?>

<?php require_once '../../views/partials/header.php'; ?>

<style>
.role-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.btn-update {
    background: #27ae60;
    color: white;
    border: none;
    padding: 5px 15px;
    border-radius: 4px;
    cursor: pointer;
}

.btn-update:hover {
    background: #219a52;
}

.btn-delete {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    font-size: 12px;
}

.btn-delete:hover {
    background: #c82333;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th,
td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

th {
    background: #1a1a2e;
    color: white;
}

.badge-admin {
    background: #e74c3c;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-client {
    background: #3498db;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-freelancer {
    background: #2ecc71;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-financial {
    background: #f39c12;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-tech {
    background: #9b59b6;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

.badge-mediator {
    background: #1abc9c;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

select {
    padding: 5px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.roles-container {
    display: flex;
    gap: 15px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.role-tag {
    padding: 8px 20px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: bold;
}

.role-admin {
    background: #e74c3c;
    color: white;
}

.role-client {
    background: #3498db;
    color: white;
}

.role-freelancer {
    background: #2ecc71;
    color: white;
}

.role-financial {
    background: #f39c12;
    color: white;
}

.role-tech {
    background: #9b59b6;
    color: white;
}

.role-mediator {
    background: #1abc9c;
    color: white;
}
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-badge"></i> User Role Management</h2>
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
    <div class="alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="role-card">
                <h4><i class="bi bi-tags"></i> Available Roles</h4>
                <div class="roles-container">
                    <span class="role-tag role-admin">👑 Admin</span>
                    <span class="role-tag role-client">👤 Client</span>
                    <span class="role-tag role-freelancer">💼 Freelancer</span>
                    <span class="role-tag role-financial">💰 Financial</span>
                    <span class="role-tag role-tech">🔧 Tech Support</span>
                    <span class="role-tag role-mediator">⚖️ Dispute Mediator</span>
                </div>
                <div style="margin-top: 15px;">
                    <p><strong>Permissions:</strong></p>
                    <ul>
                        <li><strong>Admin:</strong> Full system access</li>
                        <li><strong>Client:</strong> Post jobs, hire freelancers</li>
                        <li><strong>Freelancer:</strong> Apply for jobs, earn money</li>
                        <li><strong>Financial:</strong> Manage escrow, payments, withdrawals</li>
                        <li><strong>Tech Support:</strong> Handle technical tickets, platform issues</li>
                        <li><strong>Dispute Mediator:</strong> Resolve contract disputes between parties</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="role-card">
                <h4><i class="bi bi-people"></i> All Users</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Current Role</th>
                                <th>Change Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($users && count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($user['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <?php
                                            $badge_class = '';
                                            if ($user['role'] == 'Admin') $badge_class = 'badge-admin';
                                            elseif ($user['role'] == 'Client') $badge_class = 'badge-client';
                                            elseif ($user['role'] == 'Freelancer') $badge_class = 'badge-freelancer';
                                            elseif ($user['role'] == 'Financial') $badge_class = 'badge-financial';
                                            elseif ($user['role'] == 'Tech Support') $badge_class = 'badge-tech';
                                            elseif ($user['role'] == 'Dispute Mediator') $badge_class = 'badge-mediator';
                                            else $badge_class = 'badge-client';
                                            ?>
                                    <span
                                        class="<?php echo $badge_class; ?>"><?php echo htmlspecialchars($user['role']); ?></span>
                                </td>
                                <td>
                                    <form method="POST" style="display: flex; gap: 5px;">
                                        <select name="role">
                                            <option value="Admin"
                                                <?php echo ($user['role'] == 'Admin') ? 'selected' : ''; ?>>Admin
                                            </option>
                                            <option value="Client"
                                                <?php echo ($user['role'] == 'Client') ? 'selected' : ''; ?>>Client
                                            </option>
                                            <option value="Freelancer"
                                                <?php echo ($user['role'] == 'Freelancer') ? 'selected' : ''; ?>>
                                                Freelancer</option>
                                            <option value="Financial"
                                                <?php echo ($user['role'] == 'Financial') ? 'selected' : ''; ?>>
                                                Financial</option>
                                            <option value="Tech Support"
                                                <?php echo ($user['role'] == 'Tech Support') ? 'selected' : ''; ?>>Tech
                                                Support</option>
                                            <option value="Dispute Mediator"
                                                <?php echo ($user['role'] == 'Dispute Mediator') ? 'selected' : ''; ?>>
                                                Dispute Mediator</option>
                                        </select>
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" name="update_role" class="btn-update">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <a href="?delete_id=<?php echo $user['id']; ?>" class="btn-delete"
                                        onclick="return confirm('Delete this user?')">Delete</a>
                                    <?php else: ?>
                                    <span class="text-muted">You</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No users found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>