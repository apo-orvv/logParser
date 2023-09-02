<?php
class LogView
{
    public function showForm($error = null)
    {
        echo '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Log Processing</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: url("bg.png") no-repeat center center fixed;
                    background-size: cover;
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    text-align: center;
                    background-color: rgba(255, 255, 255, 0.95);
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
                }
                h1 {
                    text-align: center;
                    color: #333;
                    margin-bottom: 20px;
                }
                form {
                    text-align: center;
                }
                label {
                    font-size: 18px;
                    display: block;
                    margin-bottom: 10px;
                    color: #333;
                }
                .file-input-label {
                    background-color: #3498db;
                    color: #ffffff;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }
                .file-input-label:hover {
                    background-color: #2980b9;
                }
                button[type="submit"] {
                    background-color: #2ecc71;
                    color: #ffffff;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }
                button[type="submit"]:hover {
                    background-color: #27ae60;
                }
                .error {
                    color: #d63031;
                    text-align: center;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>Log Processing</h1> <br><br>';

        if ($error) {
            echo '<p class="error">' . $error . '</p>';
        }

        echo '
                <form method="post" enctype="multipart/form-data">
                    <label for="log_file">Choose a Log File</label> 
                    <label class="file-input-label" for="log_file">Browse</label> <br>
                    <center><input type="file" name="log_file" id="log_file" required></center> <br><br>

                    <!-- User Input Date Range -->
                    <label for="start_date">Start Date:</label>
                    <input type="date" name="start_date" id="start_date" required><br><br>
                    <label for="end_date">End Date:</label>
                    <input type="date" name="end_date" id="end_date" required> <br><br><br>

                    <button type="submit" name="submit">Process Log</button>
                </form>
            </div>
        </body>
        </html>';
    }

    public function displayData($type, $data)
    {
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Feature Activity Visualization</title>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
            <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
            <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
            <style>
            .graph-container1 {
                max-width: 500px;
                margin: 20px auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f5f5f5;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .graph-container2 {
                max-width: 800px;
                margin: 20px auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f5f5f5;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .data-table {
                max-width: 700px;
                margin: 20px auto;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f5f5f5;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            </style>
        </head>
        <body>';

        if ($type === 'download') {
            echo '<p style="text-align: center;">Processed CSV file ready for download: <a href="' . $data . '">' . $data . '</a></p>';
        } elseif ($type === 'graph') {
            
            if ($data['graphType'] === 'doughnut') {
                echo '<div class="graph-container1">';
                echo '
                    <h1>Cumulative Feature Tracker</h1>
                    <canvas id="featureGraph"></canvas>
                
                <script>
                    var ctx = document.getElementById("featureGraph").getContext("2d");
                    
                    var featureData = ' . json_encode($data['featureDurations']) . ';
                    var labels = featureData.map(item => item.Feature);
                    var data = featureData.map(item => parseFloat(item.Duration));
    
                    new Chart(ctx, {
                        type: "doughnut",
                        data: {
                            labels: labels,
                            datasets: [{
                                label: "Feature Activity Duration (hours)",
                                data: data,
                                backgroundColor: [
                                    "rgba(0, 0, 0, 0.2)",
                                    "rgba(255, 159, 64, 0.2)",
                                    "rgba(104, 215, 196, 0.2)",
                                    "rgba(85, 5, 186, 0.2)",
                                    "rgba(4, 105, 255, 0.2)",
                                    "rgba(200, 225, 77, 0.2)",
                                    // colors for more age group
                                ],
                                borderColor: [
                                    "rgba(0, 0, 0, 1)",
                                    "rgba(255, 159, 64, 1)",
                                    "rgba(104, 215, 196, 1)",
                                    "rgba(85, 5, 186, 1)",
                                    "rgba(4, 105, 255, 1)",
                                    "rgba(200, 225, 77, 1)",
                                    // colors for more age group
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top",
                                }
                            }
                        }
                    });
                </script>';
                echo '</div>';
            } elseif ($data['graphType'] === 'bar') {
                echo '<div class="graph-container2">';
                echo '
                    <h1>Feature Flux: Charting Daily Engagement</h1>
                    <canvas id="featureBarGraph"></canvas>
                
                <script>
                    var ctx = document.getElementById("featureBarGraph").getContext("2d");
                    
                    var featureData = ' . json_encode($data['featureDurationsByDay']) . ';
                    var labels = featureData[0].Dates; // Dates are the same for all features
                    var datasets = featureData.map(item => {
                        return {
                            label: item.Feature,
                            data: item.Durations.map(d => parseFloat(d)),
                            backgroundColor: getRandomColor(),
                            fill: false
                        };
                    });
    
                    new Chart(ctx, {
                        type: "bar",
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: "top",
                                }
                            }
                        }
                    });
    
                    function getRandomColor() {
                        var letters = "0123456789ABCDEF";
                        var color = "#";
                        for (var i = 0; i < 6; i++) {
                            color += letters[Math.floor(Math.random() * 16)];
                        }
                        return color;
                    }
                </script>';
                echo '</div>';
            }
            
            echo '<div class="data-table">';

            if ($data['graphType'] === 'doughnut') {
                echo '<h2>Feature Monitor</h2>';
                echo '<table id="dataTable" class="display">';
                echo '<thead><tr><th>Feature</th><th>Duration (hours)</th></tr></thead>';
                echo '<tbody>';
                foreach ($data['featureDurations'] as $item) {
                    echo '<tr><td>' . $item['Feature'] . '</td><td>' . $item['Duration'] . '</td></tr>';
                }
                echo '</tbody>';
                echo '</table>';
                echo '<script>
            $(document).ready(function() {
                $("#dataTable").DataTable();
            });
        </script>';
            } elseif ($data['graphType'] === 'bar') {
                echo '<h2>Engagement Dynamics: Daily Feature Activity</h2>';
                echo '<table id="dataTable2" class="display">';
                echo '<thead><tr><th>Date</th>';

                // Print feature names as column headers
                foreach ($data['featureDurationsByDay'] as $featureData) {
                    echo '<th>' . $featureData['Feature'] . '</th>';
                }

                echo '</tr></thead>';
                echo '<tbody>';

                // Iterate through dates
                $dates = $data['featureDurationsByDay'][0]['Dates']; // Assuming Dates are the same for all features
                foreach ($dates as $date) {
                    echo '<tr>';
                    echo '<td>' . $date . '</td>';

                    // Iterate through features and find matching date
                    foreach ($data['featureDurationsByDay'] as $featureData) {
                        $featureDateIndex = array_search($date, $featureData['Dates']);
                        if ($featureDateIndex !== false) {
                            $duration = $featureData['Durations'][$featureDateIndex];
                            echo '<td>' . $duration . '</td>';
                        } else {
                            // Date not found for this feature, display an empty cell
                            echo '<td></td>';
                        }
                    }

                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '<script>
            $(document).ready(function() {
                $("#dataTable2").DataTable();
            });
        </script>';
            }

            echo '</div>';
            echo '</body></html>';
        }
    }
}
