<?php 
/*
 * Icinga HUD
 *
 * hostgroup.php
 *
 * web template for displaying hosts from a specific hostgroup with a summary
 *   of their service status
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

    require_once "config.php";
    require_once "handler.php";

    $hostgroup="";

    if (!empty($_GET)) {
        $hostgroup=htmlspecialchars($_GET["hostgroup"]);
    }

    if($hostgroup != "") {
        $json_data = getJSON($queryhostgroup . $hostgroup);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Icinga HUD - Status for <?php echo $hostgroup; ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css" />
</head>
<body>
    <?php include "nav.php"; ?>

    <div class="container">
        <div class="row">
            <div class="span12">
                <p class="lead">Hostgroup Overview for <?php print $hostgroup; ?></p>
                <h2><?php print $hostgroup; ?> (<?php print countHostsInGroup($json_data); ?> hosts)</h2>
                <?php
                    if($hostgroup != "") {
                        printHostgroupDetails($json_data);
                    } else {
                        print "No hostgroup selected<br>\n";
                    }
                ?>
            </div><!-- /.span12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

</body>
</html>
