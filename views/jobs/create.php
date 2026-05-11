<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
  header('Location: ../auth/login.php');
  exit();
}

require_once __DIR__ . '/../partials/header.php';
?>

<style>
.hero-section {
    background-color: #1a1a2e;
    color: #fff;
    padding: 3rem 0 4rem;
    position: relative;
    overflow: hidden;
    border-bottom: 4px solid #e8a045;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle at 20% 50%, rgba(232, 160, 69, 0.08) 0%, transparent 50%);
    z-index: 0;
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-title {
    font-weight: 800;
    font-size: 2.5rem;
    letter-spacing: -1.5px;
}

.hero-title span {
    color: #e8a045;
}

.form-card {
    background: #fff;
    border: 1.5px solid #e2dfd8;
    border-radius: 12px;
    padding: 2rem;
    margin-top: 2rem;
    margin-bottom: 3rem;
    box-shadow: 0 10px 30px rgba(26, 26, 46, 0.05);
}

.form-label {
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 0.5rem;
}

.form-control,
.form-select {
    border: 1.5px solid #e2dfd8;
    border-radius: 8px;
    padding: 0.75rem 1rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #e8a045;
    box-shadow: 0 0 0 0.2rem rgba(232, 160, 69, 0.25);
}

.niche-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
}

.niche-card {
    background: #fff;
    border: 2px solid #e2dfd8;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.niche-card:hover {
    border-color: #e8a045;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(232, 160, 69, 0.1);
}

.niche-card.selected {
    border-color: #e8a045;
    background: rgba(232, 160, 69, 0.05);
}

.niche-icon {
    font-size: 2.5rem;
    margin-bottom: 0.75rem;
}

.niche-name {
    font-weight: 700;
    color: #1a1a2e;
}

.btn-primary-custom {
    background: #e8a045;
    color: #1a1a2e;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 0.75rem 2rem;
}

.btn-primary-custom:hover {
    background: #d4903a;
    transform: translateY(-2px);
}

.btn-outline-custom {
    border: 2px solid #e2dfd8;
    color: #6c757d;
    background: transparent;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.75rem 2rem;
}

.btn-outline-custom:hover {
    border-color: #e8a045;
    color: #e8a045;
}

.step-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2rem;
    margin: 2rem 0;
}

.step-item {
    text-align: center;
    flex: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    background: #e2dfd8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-weight: bold;
    color: #6c757d;
}

.step-circle.active {
    background: #e8a045;
    color: #1a1a2e;
}

.step-label {
    font-size: 0.8rem;
    color: #6c757d;
}

.step-label.active {
    color: #e8a045;
    font-weight: bold;
}

.text-danger {
    color: #e74c3c !important;
}
</style>

<div class="hero-section">
    <div class="container hero-content text-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h1 class="hero-title">Post a <span>Job</span></h1>
                <p class="text-white-50">Fill out the form below to create a new job posting</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="step-indicator">
        <div class="step-item">
            <div class="step-circle active">1</div>
            <div class="step-label active">Basic Info</div>
        </div>
        <div class="step-item">
            <div class="step-circle">2</div>
            <div class="step-label">Niche Details</div>
        </div>
        <div class="step-item">
            <div class="step-circle">3</div>
            <div class="step-label">Review & Submit</div>
        </div>
    </div>

    <div class="form-card">
        <form action="wizard-step2.php" method="POST" id="jobForm">
            <div class="mb-4">
                <label class="form-label">Job Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required
                    placeholder="e.g., Need a professional translation">
            </div>
            <div class="mb-4">
                <label class="form-label">Job Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="5" required
                    placeholder="Describe your project requirements..."></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label">Budget ($) <span class="text-danger">*</span></label>
                <input type="number" name="budget" class="form-control" required placeholder="e.g., 1500">
            </div>
            <div class="mb-4">
                <label class="form-label">Select Niche <span class="text-danger">*</span></label>
                <div class="niche-grid" id="nicheGrid">
                    <div class="niche-card" data-niche-id="1" data-niche-name="AI & Machine Learning">
                        <div class="niche-icon">🤖</div>
                        <div class="niche-name">AI & Machine Learning</div><small class="text-muted">Artificial
                            Intelligence, ML</small>
                    </div>
                    <div class="niche-card" data-niche-id="2" data-niche-name="Legal">
                        <div class="niche-icon">⚖️</div>
                        <div class="niche-name">Legal</div><small class="text-muted">Legal services, contracts</small>
                    </div>
                    <div class="niche-card" data-niche-id="3" data-niche-name="Web Development">
                        <div class="niche-icon">💻</div>
                        <div class="niche-name">Web Development</div><small class="text-muted">Websites, web
                            apps</small>
                    </div>
                    <div class="niche-card" data-niche-id="4" data-niche-name="Mobile Development">
                        <div class="niche-icon">📱</div>
                        <div class="niche-name">Mobile Development</div><small class="text-muted">iOS, Android
                            apps</small>
                    </div>
                    <div class="niche-card" data-niche-id="5" data-niche-name="Design & Creative">
                        <div class="niche-icon">🎨</div>
                        <div class="niche-name">Design & Creative</div><small class="text-muted">UI/UX, Graphic
                            Design</small>
                    </div>
                    <div class="niche-card" data-niche-id="6" data-niche-name="Writing & Translation">
                        <div class="niche-icon">✍️</div>
                        <div class="niche-name">Writing & Translation</div><small class="text-muted">Content,
                            Translation</small>
                    </div>
                    <div class="niche-card" data-niche-id="7" data-niche-name="Marketing & SEO">
                        <div class="niche-icon">📈</div>
                        <div class="niche-name">Marketing & SEO</div><small class="text-muted">Digital Marketing</small>
                    </div>
                    <div class="niche-card" data-niche-id="8" data-niche-name="Data Science">
                        <div class="niche-icon">📊</div>
                        <div class="niche-name">Data Science</div><small class="text-muted">Data Analysis, BI</small>
                    </div>
                    <div class="niche-card" data-niche-id="9" data-niche-name="Cybersecurity">
                        <div class="niche-icon">🔒</div>
                        <div class="niche-name">Cybersecurity</div><small class="text-muted">Security, Testing</small>
                    </div>
                    <div class="niche-card" data-niche-id="10" data-niche-name="Blockchain">
                        <div class="niche-icon">⛓️</div>
                        <div class="niche-name">Blockchain</div><small class="text-muted">Crypto, Web3</small>
                    </div>
                </div>
                <input type="hidden" name="niche_id" id="niche_id" required>
                <input type="hidden" name="niche_name" id="niche_name">
                <div id="nicheError" class="text-danger mt-2" style="display: none;">Please select a niche</div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="../home/index.php" class="btn btn-outline-custom">Cancel</a>
                <button type="submit" class="btn btn-primary-custom" id="submitBtn" disabled>Continue →</button>
            </div>
        </form>
    </div>
</div>

<script>
const nicheCards = document.querySelectorAll('.niche-card');
const nicheInput = document.getElementById('niche_id');
const nicheNameInput = document.getElementById('niche_name');
const submitBtn = document.getElementById('submitBtn');
const nicheError = document.getElementById('nicheError');

nicheCards.forEach(card => {
    card.addEventListener('click', function() {
        nicheCards.forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        nicheInput.value = this.getAttribute('data-niche-id');
        nicheNameInput.value = this.getAttribute('data-niche-name');
        nicheError.style.display = 'none';
        submitBtn.disabled = false;
    });
});

document.getElementById('jobForm').addEventListener('submit', function(e) {
    if (!nicheInput.value) {
        e.preventDefault();
        nicheError.style.display = 'block';
    }
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>