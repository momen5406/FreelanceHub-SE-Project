<?php
session_start();

require_once __DIR__ . '/../../app/core/database.php';
require_once __DIR__ . '/../partials/header.php';

$db = new Database();
$db->openConnection();

$search = $_GET['search'] ?? '';
$nicheFilter = $_GET['niche'] ?? '';
$minBudget = $_GET['min_budget'] ?? '';
$maxBudget = $_GET['max_budget'] ?? '';

$query = "SELECT j.*, u.name as client_name, n.name as niche_name,
          (SELECT COUNT(*) FROM proposals WHERE job_id = j.id) as proposals_count
          FROM jobs j
          LEFT JOIN users u ON j.client_id = u.id
          LEFT JOIN niche_categories n ON j.niche_id = n.id
          WHERE j.status = 'Open'";

if (!empty($search)) {
  $search = $db->connection->real_escape_string($search);
  $query .= " AND (j.title LIKE '%$search%' OR j.description LIKE '%$search%')";
}
if (!empty($nicheFilter)) {
  $query .= " AND j.niche_id = $nicheFilter";
}
if (!empty($minBudget)) {
  $query .= " AND j.budget >= $minBudget";
}
if (!empty($maxBudget)) {
  $query .= " AND j.budget <= $maxBudget";
}

$query .= " ORDER BY j.created_at DESC";

$jobs = $db->select($query);
$niches = $db->select("SELECT * FROM niche_categories ORDER BY name");

$db->closeConnection();
?>

<style>
.fh-search-bar {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 20px rgba(26, 26, 46, 0.04);
    border: 1px solid #e2dfd8;
}

.job-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.job-card:hover {
    border-color: #e8a045;
    box-shadow: 0 8px 25px rgba(232, 160, 69, 0.15);
    transform: translateY(-3px);
}

.job-badge {
    background: rgba(232, 160, 69, 0.15);
    color: #e8a045;
    font-weight: 700;
    font-size: 0.8rem;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
}

.btn-fh-primary {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-fh-primary:hover {
    background: #d4903a;
    transform: translateY(-2px);
    color: #1a1a2e;
}

.btn-fh-outline {
    color: #1a1a2e;
    border: 2px solid #e2dfd8;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
    text-decoration: none;
    display: inline-block;
}

.btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
}

.filter-select {
    border: 1.5px solid #e2dfd8;
    border-radius: 8px;
    padding: 0.5rem;
    width: 100%;
}

.filter-input {
    border: 1.5px solid #e2dfd8;
    border-radius: 8px;
    padding: 0.5rem;
    width: 100%;
}

.no-jobs {
    text-align: center;
    padding: 3rem;
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
}
</style>

<div class="row mb-4">
    <div class="col-12">
        <form method="GET" action="">
            <div class="fh-search-bar">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold small">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0" style="border-color: #e2dfd8;"><i
                                    class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 shadow-none"
                                style="border-color: #e2dfd8; padding: 0.6rem;" placeholder="Search for projects..."
                                value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Niche</label>
                        <select name="niche" class="filter-select">
                            <option value="">All Niches</option>
                            <?php foreach ($niches as $niche): ?>
                            <option value="<?= $niche['id'] ?>" <?= $nicheFilter == $niche['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($niche['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small">Budget Range</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="min_budget" class="filter-input" placeholder="Min $"
                                    value="<?= htmlspecialchars($minBudget) ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="max_budget" class="filter-input" placeholder="Max $"
                                    value="<?= htmlspecialchars($maxBudget) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn-fh-primary w-100"><i class="bi bi-funnel me-2"></i>Apply
                            Filter</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-end mb-4">
    <h3 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">Latest Opportunities</h3>
    <span class="text-muted fw-semibold">Showing <?= count($jobs) ?> jobs</span>
</div>

<div class="row g-4 mb-5">
    <?php if (empty($jobs)): ?>
    <div class="col-12">
        <div class="no-jobs">
            <i class="bi bi-inbox" style="font-size: 3rem; color: #e2dfd8;"></i>
            <h4 class="mt-3" style="color: #1a1a2e;">No jobs found</h4>
            <p class="text-muted">Try adjusting your search or filter criteria</p>
        </div>
    </div>
    <?php else: ?>
    <?php foreach ($jobs as $job): ?>
    <div class="col-md-6 col-lg-4">
        <div class="job-card p-4 h-100 d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="job-badge"><?= htmlspecialchars($job['niche_name'] ?? 'General') ?></span>
                <small class="text-muted">📋 <?= $job['proposals_count'] ?? 0 ?> proposals</small>
            </div>
            <h5 class="fw-bold mb-2" style="color: #1a1a2e;"><?= htmlspecialchars($job['title']) ?></h5>
            <p class="text-muted small flex-grow-1"><?= htmlspecialchars(substr($job['description'], 0, 100)) ?>...</p>
            <div class="mt-auto border-top pt-3 mt-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block">Est. Budget</small>
                        <strong style="color: #1a1a2e;">$<?= number_format($job['budget'], 2) ?></strong>
                    </div>
                    <a href="view.php?id=<?= $job['id'] ?>" class="btn-fh-outline btn-sm">View Details</a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>