<?php

// The key that must be provided to use the file downloader.
$passKey = "mysecurekey";

// The location on the local server that the file will be saved to.
// This must include the trailing '/';
$saveLocation = "content/";

// Parameters set by the URL string.z
$providedKey = $_GET['key'];
$fileUrl = $_POST['url'];
$fileName = $_POST['filename'];
$showPage = true;
$showForm = true;

if ($providedKey === $passKey && ($fileUrl === null || $fileName === null)) {
    $showForm = true;
    $showPage = true;
} elseif ($providedKey === $passKey && $fileUrl !== null && $fileName !== null) {
    $showPage = false;
    $showForm = false;
    file_put_contents($saveLocation . $fileName, fopen($fileUrl, 'r'));
} else {
    $showForm = false;
    $showPage = true;
}
?>

<?php if ($showPage) : ?>
<html>
    <head>
        <title>File Downloader</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous" />
    </head>
    <body>
        <div class="container" style="margin-top: 50px;">
            <div class="row">
                <div class="col-lg-12">
                    <h2>File Downloader</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?php if ($showForm) : ?>
                        <div id="downloadForm">
                            <label>File URL:</label>
                            <input class="form-control" type="text" name="url" id="url" />
                            <br />
                            <label>Filename to Save:</label>
                            <input class="form-control" type="text" name="filename" id="filename" />
                            <br />
                            <button class="btn btn-primary" type="button" onclick="startDownload();">Download</button>
                        </div>

                        <div id="loadingCircle">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div id="successResult">
                            <br />
                            <p class="text-success">File has been downloaded!</p>
                        </div>
                    <?php else : ?>
                        <p class="text-danger">Error. Incorrect key provided.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
        <script>
            $('#loadingCircle').hide();
            $('#successResult').hide();
            $('#downloadForm').show();

            function startDownload() {
                $('#loadingCircle').show();
                $('#downloadForm').hide();
                $('#successResult').hide();
                
                $.post(
                    "download.php?key=<?php echo $providedKey; ?>", {
                        url: $('#url').val(),
                        filename: $('#filename').val()
                    },
                    function(data, status) {
                        $('#loadingCircle').hide();
                        $('#downloadForm').show();
                        $('#successResult').show();
                    }
                );
            }
        </script>
    </body>
</html>
<?php endif; ?>