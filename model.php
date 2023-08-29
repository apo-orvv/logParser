<?php
class LogModel {
    public function processLog($logContent) {
        $entryPattern = '/(\d{2}:\d{2}:\d{2}) \((.*?)\) (.*?)\: "(.*?)" (.*?) (\(\d+ licenses\))/';
        preg_match_all($entryPattern, $logContent, $matches, PREG_SET_ORDER);

        $data = [];
        foreach ($matches as $entry) {
            $timestampStr = $entry[1];
            $software = $entry[2];
            $status = $entry[3];
            $feature = $entry[4];
            $userMachine = $entry[5];
            $licenses = $entry[6];
            
            $timestamp = DateTime::createFromFormat('H:i:s', $timestampStr);
            $licensesCount = (int) preg_replace('/\D/', '', $licenses);
            
            $data[] = [
                "Date" => $timestamp->format('Y-m-d'),
                "Time" => $timestamp->format('H:i:s'),
                "Software" => $software,
                "Status" => $status,
                "Feature" => $feature,
                "User Machine" => $userMachine,
                "Licenses" => $licensesCount
            ];
        }
        
        return $data;
    }
}
?>
