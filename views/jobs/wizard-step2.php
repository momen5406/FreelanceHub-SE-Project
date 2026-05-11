<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Client') {
    header('Location: ../auth/login.php');
    exit();
}

$selectedNicheId = $_POST['niche_id'] ?? null;
$selectedNicheName = $_POST['niche_name'] ?? null;

if (!$selectedNicheId) {
    header('Location: create.php');
    exit();
}

$_SESSION['job_wizard'] = [
    'title' => $_POST['title'],
    'description' => $_POST['description'],
    'budget' => $_POST['budget'],
    'niche_id' => $selectedNicheId,
    'niche_name' => $selectedNicheName,
    'client_id' => $_SESSION['user_id']
];

require_once __DIR__ . '/../partials/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header"
                    style="background: #e8a045; color: #1a1a2e; font-weight: bold; font-size: 1.2rem;">
                    Step 2: Niche Specific Details - <?= htmlspecialchars($selectedNicheName) ?>
                </div>
                <div class="card-body">
                    <form action="wizard-step3.php" method="POST">
                        <?php if ($selectedNicheId == 1): ?>
                        <div class="mb-3"><label class="form-label">ML Framework *</label><select name="ml_framework"
                                class="form-control" required>
                                <option value="">Select</option>
                                <option value="TensorFlow">TensorFlow</option>
                                <option value="PyTorch">PyTorch</option>
                                <option value="Scikit-learn">Scikit-learn</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Data Stack *</label><select name="data_stack[]"
                                class="form-control" multiple required>
                                <option value="Python">Python</option>
                                <option value="Pandas">Pandas</option>
                                <option value="NumPy">NumPy</option>
                                <option value="SQL">SQL</option>
                            </select><small class="text-muted">Hold Ctrl to select multiple</small></div>
                        <div class="mb-3"><label class="form-label">Algorithm Type *</label><select
                                name="algorithm_type" class="form-control" required>
                                <option value="">Select</option>
                                <option value="Classification">Classification</option>
                                <option value="Regression">Regression</option>
                                <option value="Clustering">Clustering</option>
                                <option value="NLP">NLP</option>
                            </select></div>

                        <?php elseif ($selectedNicheId == 2): ?>
                        <div class="mb-3"><label class="form-label">Document Type *</label><select name="document_type"
                                class="form-control" required>
                                <option value="">Select</option>
                                <option value="Contract">Contract</option>
                                <option value="NDA">NDA</option>
                                <option value="Terms of Service">Terms of Service</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Jurisdiction *</label><input type="text"
                                name="jurisdiction" class="form-control" placeholder="e.g., Egypt, USA, UK" required>
                        </div>

                        <?php elseif ($selectedNicheId == 3): ?>
                        <div class="mb-3"><label class="form-label">Frontend Technologies *</label><select
                                name="frontend[]" class="form-control" multiple required>
                                <option value="React">React</option>
                                <option value="Vue">Vue</option>
                                <option value="Angular">Angular</option>
                                <option value="HTML/CSS">HTML/CSS</option>
                            </select><small class="text-muted">Hold Ctrl to select multiple</small></div>
                        <div class="mb-3"><label class="form-label">Backend Technologies *</label><select
                                name="backend[]" class="form-control" multiple required>
                                <option value="Node.js">Node.js</option>
                                <option value="PHP">PHP</option>
                                <option value="Python">Python</option>
                                <option value="Java">Java</option>
                            </select><small class="text-muted">Hold Ctrl to select multiple</small></div>
                        <div class="mb-3"><label class="form-label">Database</label><select name="database[]"
                                class="form-control" multiple>
                                <option value="MySQL">MySQL</option>
                                <option value="PostgreSQL">PostgreSQL</option>
                                <option value="MongoDB">MongoDB</option>
                            </select></div>

                        <?php elseif ($selectedNicheId == 4): ?>
                        <div class="mb-3"><label class="form-label">Platform *</label><select name="platform"
                                class="form-control" required>
                                <option value="">Select</option>
                                <option value="iOS">iOS</option>
                                <option value="Android">Android</option>
                                <option value="Both">Both</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Framework *</label><select name="framework"
                                class="form-control" required>
                                <option value="">Select</option>
                                <option value="React Native">React Native</option>
                                <option value="Flutter">Flutter</option>
                                <option value="Swift">Swift</option>
                                <option value="Kotlin">Kotlin</option>
                            </select></div>

                        <?php elseif ($selectedNicheId == 5): ?>
                        <div class="mb-3"><label class="form-label">Design Software *</label><select name="software[]"
                                class="form-control" multiple required>
                                <option value="Photoshop">Photoshop</option>
                                <option value="Illustrator">Illustrator</option>
                                <option value="Figma">Figma</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Delivery Format *</label><select name="file_format"
                                class="form-control" required>
                                <option value="">Select</option>
                                <option value="PSD">PSD</option>
                                <option value="AI">AI</option>
                                <option value="PDF">PDF</option>
                                <option value="PNG">PNG</option>
                            </select></div>

                        <?php elseif ($selectedNicheId == 6): ?>
                        <div class="mb-3"><label class="form-label">Source Language *</label><select
                                name="source_language" class="form-control" required>
                                <option value="">Select</option>
                                <option value="English">English</option>
                                <option value="Arabic">Arabic</option>
                                <option value="French">French</option>
                                <option value="Spanish">Spanish</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Target Language *</label><select
                                name="target_language" class="form-control" required>
                                <option value="">Select</option>
                                <option value="Arabic">Arabic</option>
                                <option value="English">English</option>
                                <option value="French">French</option>
                                <option value="Spanish">Spanish</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Word Count *</label><input type="number"
                                name="word_count" class="form-control" required placeholder="e.g., 5000"></div>

                        <?php elseif ($selectedNicheId == 7): ?>
                        <div class="mb-3"><label class="form-label">Marketing Type *</label><select
                                name="marketing_type" class="form-control" required>
                                <option value="">Select</option>
                                <option value="SEO">SEO</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Email">Email</option>
                                <option value="PPC">PPC</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Platform *</label><select name="platform[]"
                                class="form-control" multiple required>
                                <option value="Google">Google</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                            </select></div>

                        <?php elseif ($selectedNicheId == 8): ?>
                        <div class="mb-3"><label class="form-label">Data Stack *</label><select name="data_stack[]"
                                class="form-control" multiple required>
                                <option value="Python">Python</option>
                                <option value="R">R</option>
                                <option value="SQL">SQL</option>
                                <option value="Tableau">Tableau</option>
                            </select></div>
                        <div class="mb-3"><label class="form-label">Analysis Type *</label><select name="analysis_type"
                                class="form-control" required>
                                <option value="">Select</option>
                                <option value="Descriptive">Descriptive</option>
                                <option value="Predictive">Predictive</option>
                                <option value="Prescriptive">Prescriptive</option>
                            </select></div>

                        <?php else: ?>
                        <div class="mb-3"><label class="form-label">Additional Requirements</label><textarea
                                name="additional_requirements" class="form-control" rows="4"></textarea></div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="create.php" class="btn btn-secondary">← Back</a>
                            <button type="submit" class="btn"
                                style="background: #e8a045; color: #1a1a2e; font-weight: bold;">Continue →</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>