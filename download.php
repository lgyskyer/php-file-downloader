<?php

// The key that must be provided to use the file downloader.
$passKey = "mysecurekey";

// The location on the local server that the file will be saved to.
// This must include the trailing '/';
$saveLocation = "content/";

// Parameters set by the URL string.
$providedKey = $_GET['key'];

$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str);

$fileUrl = $json_obj->url;
$fileName = $json_obj->filename;
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
    <body ng-app="downloadModule" ng-controller="downloadController" ng-cloak>
        <div class="container" style="margin-top: 50px;">
            <div class="row">
                <div class="col-lg-12">
                    <h2>File Downloader</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <?php if ($showForm) : ?>
                        <form ng-show="!isLoading" ng-submit="downloadFile();">
                            <label>File URL:</label>
                            <input class="form-control" type="text" ng-model="downloadUrl" required />
                            <br />
                            <label>Filename to Save:</label>
                            <input class="form-control" type="text" ng-model="downloadFileName" required />
                            <br />
                            <button class="btn btn-primary" type="submit">Download</button>
                        </form>

                        <div ng-show="isLoading">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <br />

                        <div ng-show="isSuccess">
                            <p class="text-success">File has been downloaded!</p>
                        </div>

                        <div ng-show="isError">
                            <p class="text-danger">An error occurred downloading the file.</p>
                        </div>
                    <?php else : ?>
                        <p class="text-danger">Error. Incorrect key provided.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha256-CjSoeELFOcH0/uxWu6mC/Vlrc1AARqbm/jiiImDGV3s=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.8/angular.min.js" integrity="sha256-23hi0Ag650tclABdGCdMNSjxvikytyQ44vYGo9HyOrU=" crossorigin="anonymous"></script>
        <script>
            var downloadModule = angular.module('downloadModule', []);
            downloadModule.controller('downloadController', function ($scope, $http) {
                $scope.pageKey = '<?php echo $providedKey; ?>';
                $scope.isLoading = false;
                $scope.isSuccess = false;
                $scope.isError = false;

                $scope.downloadFile = function () {
                    $scope.isError = false;
                    $http({
                        method: 'post',
                        data: {
                            url: $scope.downloadUrl,
                            filename: $scope.downloadFileName
                        },
                        url: 'download.php?key=' + $scope.pageKey,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (success) {
                        $scope.isSuccess = true;
                        $scope.downloadUrl = '';
                        $scope.downloadFileName = '';
                    }, function (error) {
                        $scope.isSuccess = false;
                        $scope.isError = true;
                    });
                };
            });
        </script>
    </body>
</html>
<?php endif; ?>