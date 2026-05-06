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

<div class="row justify-content-center mt-3 mb-5">
  <div class="col-md-8 col-lg-7">

    <div class="mb-4 text-center">
      <h3 style="color: #1a1a2e; font-weight: 800; letter-spacing: -0.5px;">Post a New Project</h3>
      <p class="text-muted">Fill out the details below to attract top freelance talent.</p>
    </div>

    <div class="card fh-card">
      <div class="card-body p-4 p-md-5">

        <form action="/jobs/create" method="POST">

          <div class="mb-4">
            <label for="title" class="form-label">Project Title</label>
            <input type="text" class="form-control form-control-fh" id="title" name="title" required placeholder="e.g. Build a Responsive Portfolio Website">
          </div>

          <div class="mb-4">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control form-control-fh" id="category" name="category" required placeholder="e.g. Web Design, Mobile Development">
          </div>

          <div class="mb-4">
            <label for="budget" class="form-label">Estimated Budget ($)</label>
            <div class="input-group">
              <span class="input-group-text border-end-0 bg-transparent border-2" style="border-color: #e2dfd8; color: #888;">$</span>
              <input type="number" class="form-control border-start-0 border-2 shadow-none" id="budget" name="budget" required min="10" placeholder="500" style="border-color: #e2dfd8; padding: 0.6rem;">
            </div>
          </div>

          <div class="mb-5">
            <label for="description" class="form-label">Project Description</label>
            <textarea class="form-control form-control-fh" id="description" name="description" rows="5" required placeholder="Describe the deliverables, timeline, and any specific technologies required..."></textarea>
          </div>

          <div class="d-flex justify-content-end gap-3 border-top pt-4">
            <a href="/jobs/my-postings" class="btn text-muted fw-bold px-4" style="text-decoration: none;">Cancel</a>
            <button type="submit" class="btn btn-fh-primary px-5">
              <i class="bi bi-send-check me-2"></i> Publish Job
            </button>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>

<?php require_once '../../views/partials/footer.php'; ?>