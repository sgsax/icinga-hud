<?php
/*
 * Icinga HUD
 *
 * index.php
 *
 * web template for displaying all hosts and services with problems (status not
 *   OK); this is the default view when the application is loaded
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
    <title>CIS Systems Status Overview</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
    <script src="bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <div class="row">
            <div class="span12">
                <p class="lead">All Problems Overview</p>
                <h2>Hosts</h2>
                <?php printHostInfo($json_data); ?>
                <h2>Services</h2>
                <?php printServiceInfo($json_data); ?>
            </div><!-- /.span12 -->
        </div><!-- /.row -->


    </div><!-- /.container -->

</body>
</html>
