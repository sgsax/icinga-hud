<?php
/*
 * Icinga HUD
 *
 * host.php
 *
 * web template for displaying services and their status for a specific host
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

    require_once "config.php";
    require_once "handler.php";

    $host="";

    if (!empty($_GET)) {
        $host=htmlspecialchars($_GET["host"]);
    }

    if($host != "") {
        $json_data = getJSON($queryhost . $host);
    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>CIS Systems Status - <?php echo $host; ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <div class="row">
            <div class="span12">
                <p class="lead">Host Details</p>
                    <h2>Hosts</h2>
                    <?php printHostDetails($json_data); ?>
            </div><!-- /.span12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

</body>
</html>
