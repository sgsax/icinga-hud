<?php
/*
 * Icinga HUD
 *
 * hostgroups.php
 *
 * web template for displaying all hostgroups with a summary of their hosts
 *   and service status
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

    require_once "config.php";
    require_once "handler.php";

    $json_data = getJSON($queryhostgroups);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CIS Systems Status - All Hostgroups</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <div class="row">
            <div class="span12">
                <p class="lead">Hostgroups Overview</p>
                <h2>Hostgroups (<?php print countHostgroups($json_data); ?> groups)</h2>
                <?php printHostgroupInfo($json_data); ?>
            </div><!-- /.span12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

</body>
</html>
