<?php

/*
 * Icinga HUD
 *
 * config.php
 *
 * contains configuration data used by application
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

// how to connect to the icinga server
// change these values to fit your needs
$icingaurl="http://myhost.example.com";  // your Icinga Classic Web host URL
$icingaport=80;                          // alternate port, if necessary
$icingacgi="cgi-bin/status.cgi";         // path to CGI
$icingauser="myuser";                    // username to connect to server
$icingapass="mypass";                    // password

$queryhosts="style=hostdetail&hostprops=10&hoststatustypes=12&jsonoutput";
$querysvcs="serviceprops=10&servicestatustypes=28&jsonoutput";
$queryall="allproblems&hostprops=10&hoststatustypes=12&serviceprops=10&servicestatustypes=28&jsonoutput";
$queryhostgroups="hostgroup=all&style=summary&jsonoutput";
$queryhostgroup="style=overview&jsonoutput&hostgroup=";
$queryhost="style=detaili&jsonoutput&host=";
$queryhostgroupdetail="style=detail&jsonoutput&hostgroup=";
$queryservicedetail="jsonoutput&style=detail&servicestatustypes=28&hostgroup=";

?>
