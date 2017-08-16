<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Slim Whoops Examples</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<style type="text/css">
.part {
    margin-top: 20px;
    margin-bottom: 30px;
}
</style>
<body>
<div class="container">
    <h3>Examples</h3>
    <hr>

    <div class="part">
        <h5>Base usage</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="30%">File</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <a href="./simple-mode.php">simple-mode.php</a>
                    </td>
                    <td>middleware usage</td>
                </tr>
                <tr>
                    <td>
                        <a href="./global-mode.php">global-mode.php</a>
                    </td>
                    <td>Individual usage</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="part">
        <h5>Other sample</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="30%">File</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <a href="./twig-exception.php">twig-exception.php</a>
                    </td>
                    <td>Raise exception when twig template not exists</td>
                </tr>
                <tr>
                    <td>
                        <a href="./ajax-json-handler.php">ajax-json-handler.php</a>
                    </td>
                    <td>Auto response json message when request is ajax</td>
                </tr>
                <tr>
                    <td>
                        <a href="./custom-whoops-handler.php">custom-whoops-handler.php</a>
                    </td>
                    <td>Custom whoops handler</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
