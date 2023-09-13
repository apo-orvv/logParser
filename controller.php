<?php
require_once 'model.php';
require_once 'view.php';

class LogController
{
    private $model;
    private $view;

    public function __construct()
    {
        $this->model = new LogModel();
        $this->view = new LogView();
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            if (isset($_FILES['log_file']) && $_FILES['log_file']['error'] === UPLOAD_ERR_OK) {
                $logContent = file_get_contents($_FILES['log_file']['tmp_name']);
                $processedData = $this->model->processLog($logContent);
                // Generate CSV file and save it
                $csvFilename = 'abaqus_info.csv';
                $csvContent = $this->generateCSVContent($processedData);
                file_put_contents($csvFilename, $csvContent);

                $featureDurations = $this->model->calculateFeatureDurations();
                $featureDurationsByDay = $this->model->calculateFeatureDurationsByDay();

                $this->view->displayData([
                    'featureDurationsByDay' => $featureDurationsByDay,
                    'featureDurations' => $featureDurations,
                ], $csvFilename);
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit2'])) {
            $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
            $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;

            $featureDurations = $this->model->calculateFeatureDurationschoice($start_date, $end_date);
            $featureDurationsByDay = $this->model->calculateFeatureDurationsByDaychoice($start_date, $end_date);

            $csvFilename = 'abaqus_info.csv';
            $this->view->displayData([
                'featureDurationsByDay' => $featureDurationsByDay,
                'featureDurations' => $featureDurations,
            ], $csvFilename);
        } else {
            $this->view->showForm();
        }
    }

    private function generateCSVContent($data)
    {
        $csvContent = "Date,Time,Software,Status,Feature,User Machine,Licenses\n";
        foreach ($data as $entry) {
            $csvContent .= implode(',', $entry) . "\n";
        }
        return $csvContent;
    }
}

$controller = new LogController();
$controller->handleRequest();
