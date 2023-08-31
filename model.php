<?php
class LogModel {
    private $pdo;

    public function __construct() {
        // Update these parameters with your actual database credentials
        $dsn = 'mysql:host=localhost;dbname=test;charset=utf8';
        $username = 'root';
        $password = '';

        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function processLog($logContent) {
        $data = [];
        $currentDate = null;

        $lines = explode("\n", $logContent);

        foreach ($lines as $line) {
            if (preg_match('/(\d{2}:\d{2}:\d{2}) \((.*?)\) (.*?)\: "(.*?)" (.*?) (\(\d+ licenses\))/', $line, $matches)) {
                $timestampStr = $matches[1];
                $software = $matches[2];
                $status = $matches[3];
                $feature = $matches[4];
                $userMachine = $matches[5];
                $licensesCount = (int)preg_replace('/[^\d]/', '', $matches[6]);

                $timestamp = DateTime::createFromFormat('H:i:s', $timestampStr);

                if ($currentDate !== null) {
                    $data[] = [
                        "Date" => $currentDate,
                        "Time" => $timestamp->format('H:i:s'),
                        "Software" => $software,
                        "Status" => $status,
                        "Feature" => $feature,
                        "User Machine" => $userMachine,
                        "Licenses" => $licensesCount
                    ];
                }
            } elseif (strpos($line, "TIMESTAMP") !== false) {
                $dateParts = explode(" ", $line);
                $currentDateStr = end($dateParts);
                $currentDate = DateTime::createFromFormat('m/d/Y', $currentDateStr)->format('Y-m-d');
            }

            if ($currentDate !== null) {
                // Insert data into the database
                $this->insertDataToDatabase($currentDate, $timestamp->format('H:i:s'), $software, $status, $feature, $userMachine, $licensesCount);
            }
        }

        return $data;
    }

    private function insertDataToDatabase($date, $time, $software, $status, $feature, $userMachine, $licenses) {
        $sql = "INSERT INTO licenselog (Date, Time, Software, Status, Feature, UserMachine, Licenses) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date, $time, $software, $status, $feature, $userMachine, $licenses]);
    }
}


?>
