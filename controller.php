<?php
require_once 'model.php';
require_once 'view.php';

class LogController {
    private $model;
    private $view;
    
    public function __construct() {
        $this->model = new LogModel();
        $this->view = new LogView();
    }
    
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
            if (isset($_FILES['log_file']) && $_FILES['log_file']['error'] === UPLOAD_ERR_OK) {
                $logContent = file_get_contents($_FILES['log_file']['tmp_name']);
                
                $processedData = $this->model->processLog($logContent);
                
                // Generate CSV file and save it
                $csvFilename = 'processed_license_info.csv';
                $csvContent = $this->generateCSVContent($processedData);
                file_put_contents($csvFilename, $csvContent);
                
                $this->view->showDownloadLink($csvFilename);
            }
        } else {
            $this->view->showForm();
        }
    }
    
    private function generateCSVContent($data) {
        $csvContent = "Date,Time,Software,Status,Feature,User Machine,Licenses\n";
        foreach ($data as $entry) {
            $csvContent .= implode(',', $entry) . "\n";
        }
        return $csvContent;
    }
}

$controller = new LogController();
$controller->handleRequest();
?>
