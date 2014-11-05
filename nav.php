<?php 
/*
 * Icinga HUD
 *
 * nav.php
 *
 * web template for building the navigation bar used by all pages 
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

require "config.php";

function echoActiveClassIfRequestMatches($requestUri) {
/* echoActiveClassIfRequestMatches()
 * borrowed from post by member Chris Moutray here:
 *   http://stackoverflow.com/a/11814284
 *
 * parameters: $myquery - filter to be passed to CGI
 *
 * Connect to server using URL, path, and credentials provided in config.php,
 *   download result and return as keyed array.
 */
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri) {
        echo 'class="active"';
    }
}

function buildHostgroupMenu($query) {
/* buildHostgroupMenu()
 *
 * parameters: $query - filter to be passed to CGI
 *
 * Get the list of hostgroups and assemble the dropdown used in the navigation
 *   bar. Print output as nice html.
 */

    $json_data = getJSON($query);
    $hostgrps = getHostgroupInfo($json_data);

    foreach($hostgrps as $hostgrp) {
        print "<li><a href=\"hostgroup.php?hostgroup=$hostgrp[hostgroup_name]\">$hostgrp[hostgroup_name]</a></li>\n";
    }
}
?>

<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo $branding; ?></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="/">Overview</a></li>
                <li <?=echoActiveClassIfRequestMatches("hosts")?>><a href="hosts.php">Hosts</a></li>
                <li <?=echoActiveClassIfRequestMatches("services")?>><a href="services.php">Services</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Hostgroups <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="hostgroups.php">All</a></li>
                        <li class="divider"></li>
                        <?php buildHostgroupMenu($queryhostgroups) ?>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
