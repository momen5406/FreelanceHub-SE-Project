<?php
require_once __DIR__ . '/../models/JobWizard.php';
require_once __DIR__ . '/../models/NicheConfig.php';

class JobWizardController
{
    private $jobWizard;
    private $nicheConfig;

    public function __construct()
    {
        $this->jobWizard = new JobWizard();
        $this->nicheConfig = new NicheConfig();
    }

    public function index()
    {
        $niches = $this->nicheConfig->getAllNiches();
        include __DIR__ . '/../../views/job-wizard/index.php';
    }

    public function getFields($niche)
    {
        $fields = $this->nicheConfig->getFieldsByNiche($niche);
        header('Content-Type: application/json');
        echo json_encode($fields);
    }

    public function saveStep1()
    {
        session_start();
        $_SESSION['wizard']['niche'] = $_POST['niche'];
        $_SESSION['wizard']['title'] = $_POST['title'];
        $_SESSION['wizard']['description'] = $_POST['description'];
        $_SESSION['wizard']['budget'] = $_POST['budget'];

        header('Location: /job-wizard/step2');
    }

    public function step2()
    {
        $niche = $_SESSION['wizard']['niche'] ?? null;
        if (!$niche) {
            header('Location: /job-wizard');
            exit();
        }

        $fields = $this->nicheConfig->getFieldsByNiche($niche);
        include __DIR__ . '/../../views/job-wizard/step2.php';
    }

    public function saveStep2()
    {
        session_start();
        $_SESSION['wizard']['dynamic_fields'] = $_POST;
        header('Location: /job-wizard/step3');
    }

    public function step3()
    {
        $jobData = $_SESSION['wizard'] ?? null;
        if (!$jobData) {
            header('Location: /job-wizard');
            exit();
        }

        include __DIR__ . '/../../views/job-wizard/step3.php';
    }

    public function submitJob()
    {
        session_start();
        $clientId = $_SESSION['user']['id'] ?? 1;

        $result = $this->jobWizard->createJob($clientId, $_SESSION['wizard']);

        if ($result['success']) {
            unset($_SESSION['wizard']);
            header('Location: /jobs/success?id=' . $result['job_id']);
        } else {
            echo "Error: " . $result['error'];
        }
    }
}