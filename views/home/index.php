<?php require_once '../../views/partials/header.php'; ?>

<style>
    .hero-section {
        background-color: #1a1a2e;
        color: #fff;
        padding: 5rem 0 6rem;
        position: relative;
        overflow: hidden;
        border-bottom: 4px solid #e8a045;
    }

    /* A subtle background pattern for the hero */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: radial-gradient(circle at 20% 50%, rgba(232, 160, 69, 0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-weight: 800;
        font-size: 3.5rem;
        letter-spacing: -1.5px;
        line-height: 1.1;
        margin-bottom: 1.5rem;
    }

    .hero-title span {
        color: #e8a045;
    }

    .btn-hero-primary {
        background: #e8a045;
        color: #1a1a2e;
        border: none;
        border-radius: 8px;
        font-weight: 800;
        padding: 0.8rem 2rem;
        font-size: 1.1rem;
        transition: all 0.2s;
    }

    .btn-hero-primary:hover {
        background: #d4903a;
        transform: translateY(-2px);
        color: #1a1a2e;
    }

    .btn-hero-outline {
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        background: transparent;
        border-radius: 8px;
        font-weight: 700;
        padding: 0.8rem 2rem;
        font-size: 1.1rem;
        transition: all 0.2s;
    }

    .btn-hero-outline:hover {
        border-color: #fff;
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }

    .stats-bar {
        background: #fff;
        border-bottom: 1px solid #e2dfd8;
        padding: 2rem 0;
        box-shadow: 0 10px 30px rgba(26, 26, 46, 0.03);
        position: relative;
        z-index: 2;
        margin-top: -2rem;
        border-radius: 12px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: #1a1a2e;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .category-card {
        background: #fff;
        border: 1.5px solid #e2dfd8;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        transition: all 0.2s ease;
        text-decoration: none;
        display: block;
        height: 100%;
    }

    .category-card:hover {
        border-color: #e8a045;
        box-shadow: 0 10px 25px rgba(232, 160, 69, 0.15);
        transform: translateY(-5px);
    }

    .category-icon {
        width: 60px;
        height: 60px;
        background: rgba(232, 160, 69, 0.15);
        color: #d35400;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1.2rem;
    }

    .category-title {
        color: #1a1a2e;
        font-weight: 800;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }
</style>

<!-- HERO SECTION -->
<div class="hero-section">
    <div class="container hero-content text-center">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h1 class="hero-title">Hire the best talent or <span>find work you love.</span></h1>
                <p class="lead text-white-50 mb-5 px-md-5">FreelanceHub connects visionary clients with elite
                    professionals. Secure escrow payments, seamless collaboration, and world-class results.</p>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="../../views/jobs" class="btn btn-hero-primary">Find Work</a>
                    <a href="../../views/auth/register.php" class="btn btn-hero-outline">Hire Talent</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TRUST STATS -->
<div class="container">
    <div class="stats-bar px-4 text-center">
        <div class="row g-4">
            <div class="col-md-4 border-end-md">
                <div class="stat-number">4.9/5</div>
                <div class="stat-label mt-1">Average Rating</div>
            </div>
            <div class="col-md-4 border-end-md">
                <div class="stat-number">10k+</div>
                <div class="stat-label mt-1">Active Freelancers</div>
            </div>
            <div class="col-md-4">
                <div class="stat-number">$2M+</div>
                <div class="stat-label mt-1">Secured in Escrow</div>
            </div>
        </div>
    </div>
</div>

<!-- CATEGORIES SECTION -->
<div class="container py-5 mt-4 mb-5">
    <div class="text-center mb-5">
        <h2 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">Top Categories</h2>
        <p class="text-muted">Find exactly what you need to make your project a success.</p>
    </div>

    <div class="row g-4">

        <div class="col-md-6 col-lg-3">
            <div class="category-card">
                <div class="category-icon"><i class="bi bi-code-slash"></i></div>
                <h4 class="category-title">Web Development</h4>
                <div class="text-muted small">1,240 Skills</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="category-card">
                <div class="category-icon" style="background: rgba(46, 204, 113, 0.15); color: #27ae60;"><i class="bi bi-phone"></i></div>
                <h4 class="category-title">Mobile Apps</h4>
                <div class="text-muted small">850 Skills</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="category-card">
                <div class="category-icon" style="background: rgba(155, 89, 182, 0.15); color: #8e44ad;"><i class="bi bi-palette"></i></div>
                <h4 class="category-title">UI/UX Design</h4>
                <div class="text-muted small">1,020 Skills</div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="category-card">
                <div class="category-icon" style="background: rgba(52, 152, 219, 0.15); color: #2980b9;"><i class="bi bi-pen"></i></div>
                <h4 class="category-title">Content Writing</h4>
                <div class="text-muted small">645 Skills</div>
            </div>
        </div>

    </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>