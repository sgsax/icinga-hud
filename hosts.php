<?php
/*
 * Icinga HUD
 *
 * hostgroup.php
 *
 * web template for displaying all hosts with problems (status not "OK") that
 *   are not acknowledged
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

    require_once "config.php";
    require_once "handler.php";

    $json_data = getJSON($queryhosts);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Icinga HUD - All Host Problems</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <div class="row">
            <div class="span12">
                <p class="lead">Host Problems Overview</p>
                    <h2>Hosts</h2>
                    <?php printHostInfo($json_data); ?>
            </div><!-- /.span12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

</body>
</html>
