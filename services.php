<?php
/*
 * Icinga HUD
 *
 * services.php
 *
 * web template for displaying all hosts with services with problems (status not
 *   "OK") that are not acknowledged
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

    require_once "config.php";
    require_once "handler.php";

    $json_data = getJSON($queryall);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $branding; ?> - All Service Problems</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <div class="row">
            <div class="span12">
                <p class="lead"> Service Problems Overview</p>
                <h2>Services</h2>
                <?php printServiceInfo($json_data); ?>
            </div><!-- /.span12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

</body>
</html>
