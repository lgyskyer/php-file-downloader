<?php

// The key that must be provided to use the file downloader.
$passKey = "mysecurekey";

// The location on the local server that the file will be saved to.
// This must include the trailing '/';
$saveLocation = "content/";

?>

<html>
<head>
    <title>File Downloader</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-lg-12">
                <h2>File Downloader</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php
                    $providedKey = $_GET['key'];
                    $fileUrl = $_GET['url'];
                    $fileName = $_GET['filename'];
                    
                    if ($providedKey === $passKey) {
                        if ($fileUrl === null || $fileName === null) {
                            echo '
                                <form method="get" action="download.php">
                                    <input type="hidden" name="key" value="' . $passKey . '" />
                                    
                                    <label>File URL:</label>
                                    <input class="form-control" type="text" name="url" />
                                    <br />
                                    <label>Filename to Save:</label>
                                    <input class="form-control" type="text" name="filename" />
                                    <br />
                                    <button class="btn btn-primary" type="submit">Download</button>
                                </form>';
                        } else {
                            file_put_contents($saveLocation . $fileName, fopen($fileUrl, 'r'));
                            
                            echo '<p class="text-success">Downloaded file from "' . $fileUrl . '", saved as "' . $fileName . '".</p>';
                            echo '<a class="btn btn-primary" href="download.php?key=' . $passKey . '">Download Another File</a>';
                        }
                    } else {
                        echo '<p class="text-danger">Error. Incorrect key provided.</p>';
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>