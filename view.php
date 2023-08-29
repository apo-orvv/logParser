<?php
class LogView {
    public function showForm($error = null) {
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
                    <center><input type="file" name="log_file" id="log_file"></center> <br><br>
                    <button type="submit" name="submit">Process Log</button>
                </form>
            </div>
        </body>
        </html>';
    }

    public function showDownloadLink($filename) {
        echo '<p style="text-align: center;">Processed CSV file ready for download: <a href="' . $filename . '">' . $filename . '</a></p>';
    }
}


?>
