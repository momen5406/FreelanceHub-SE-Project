<?php require_once '../../views/partials/header.php'; ?>

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

  .btn-fh-outline {
    color: #1a1a2e;
    border: 2px solid #e2dfd8;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
  }

  .btn-fh-outline:hover {
    border-color: #1a1a2e;
    background: #1a1a2e;
    color: #fff;
  }
</style>

<!-- Search and Filter Section -->
<div class="row mb-4">
  <div class="col-12">
    <div class="fh-search-bar d-flex gap-3 align-items-center flex-wrap">
      <div class="flex-grow-1">
        <div class="input-group">
          <span class="input-group-text bg-transparent border-end-0 border-2" style="border-color: #e2dfd8;"><i class="bi bi-search text-muted"></i></span>
          <input type="text" class="form-control border-start-0 border-2 shadow-none" style="border-color: #e2dfd8; padding: 0.6rem;" placeholder="Search for projects, skills, or clients...">
        </div>
      </div>
      <button class="btn btn-fh-primary px-4"><i class="bi bi-funnel me-2"></i>Filter</button>
    </div>
  </div>
</div>

<!-- Job Listings Header -->
<div class="d-flex justify-content-between align-items-end mb-4">
  <h3 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">Latest Opportunities</h3>
  <span class="text-muted fw-semibold">Showing top results</span>
</div>

<!-- Job Cards Grid -->
<div class="row g-4 mb-5">

  <!!!-- TODO: For loop 34an yegeb eljobs mn eldatabase  -->
  <div class="col-md-6 col-lg-4">
    <div class="job-card p-4 h-100 d-flex flex-column">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <span class="job-badge">Web Development</span>
      </div>
      <h5 class="fw-bold mb-2" style="color: #1a1a2e;">Next.js E-commerce Platform</h5>
      <p class="text-muted small flex-grow-1">Need a high-performance e-commerce site with Tailwind CSS and a Node.js backend. Must integrate with Supabase.</p>

      <div class="mt-auto border-top pt-3 mt-3 d-flex justify-content-between align-items-center">
        <div>
          <small class="text-muted d-block">Est. Budget</small>
          <strong style="color: #1a1a2e;">$1,500</strong>
        </div>
        <a href="../../views/jobs/view.php" class="btn btn-fh-outline btn-sm">View Details</a>
      </div>
    </div>
  </div>  
</div>

<?php require_once '../../views/partials/footer.php'; ?>