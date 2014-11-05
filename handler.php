<?php
/*
 * Icinga HUD
 *
 * handler.php
 *
 * contains functions used by webpage templates for downloading raw data,
 *   processing it, and formatting it four output
 *
 * Copyright Seth Galitzer <sgsax@ksu.edu>
 *
 */

function getJSON($myquery) {
/* getJSON()
 *
 * parameters: $myquery - filter to be passed to CGI
 *
 * Connect to server using URL, path, and credentials provided in config.php,
 *   download result and return as keyed array.
 */

    require "config.php";

    $url = "$icingaurl/$icingacgi?$myquery";
    $data = curl_init($url);
    curl_setopt_array($data, 
            array(
                CURLOPT_USERPWD => "$icingauser:$icingapass",
                CURLOPT_PORT => "$icingaport",
                CURLOPT_HTTPAUTH => 1,
                CURLAUTH_BASIC => 1,
                CURLOPT_RETURNTRANSFER => 1
                ));
    $raw = curl_exec($data);
    curl_close($data);
    return json_decode($raw, TRUE);
};

function getHostInfo($input) {
/* getHostInfo()
 *
 * parameters: $input - keyed array containing raw data returned from CGI; this
 *   should be a list of hosts
 *
 * Take raw data array and add it to new array with only necessary values
 *   and return that array.
 */

    $ret = array();
    foreach($input["status"]["host_status"] as $host) {
        array_push($ret,
                array(
                    "host_name" => $host["host_name"],
                    "status" => $host["status"],
                    "status_information" => $host["status_information"]
                    )
                );
    };
    return $ret;
};

function printHostInfo($info) {
/* printHostInfo()
 *
 * parameters: $info - keyed array with processed data needed for this operation
 *
 * Take processed data array containing host status and output as nice html
 *   with appropriate formatting.
 */
    $hosts = getHostInfo($info);
    $i=1;

    print "<div class=\"row\">\n";
    foreach($hosts as $host){
        print "    <div class=\"col-lg-2\">\n";
        print "        <div class=\"alert alert-danger\">\n";
        print "            <a href=\"host.php?host=$host[host_name]\" class=\"alert-link\"><strong>$host[host_name]</strong> - ($host[status])</a><br>\n";
        print "            $host[status_information]\n";
        print "        </div><!-- alert -->\n";
        print "    </div><!-- col -->\n";
        if(($i%6) == 0) {
            print "</div><!-- row -->\n";
            print "<div class=\"clearfix visible-lg-block\"></div>\n";
            print "<div class=\"row\">\n";
            $i=1;
        } else {
            $i+=1;
        }
    };
    print "</div><!-- row -->\n";
};

function getServiceInfo($input) {
/* getServiceInfo()
 *
 * parameters:  $input - keyed array containing raw data returned from CGI; this
 *   should be a list of hosts and services
 *
 * Take raw data array and add it to new array with only necessary values
 *   and return that array.
 */
    $ret = array();
    foreach($input["status"]["service_status"] as $host) {
        array_push($ret,
                array(
                    "host_name" => $host["host_name"],
                    "service" => $host["service_display_name"],
                    "last_check" => $host["last_check"],
                    "status" => $host["status"],
                    "acknowledged" => $host["has_been_acknowledged"],
                    "status_information" => $host["status_information"]
                    )
                );
    };
    return $ret;
};

function printServiceInfo($info) {
/* printServiceInfo()
 *
 * parameters: $info - keyed array with processed data needed for this operation
 *
 * Take processed data array containing host and service status and output as
 *   nice html with appropriate formatting.
 */
    $services = getServiceInfo($info);
    $i=1;

    print "<div class=\"row\">\n";
    foreach($services as $service) {
        if(strpos($service["status_information"], "CHECK_NRPE") !== 0) {
            print "    <div class=\"col-lg-2\">\n";
            if(strcmp($service["status"],"WARNING") == 0) {
                print "        <div class=\"alert alert-warning\">\n";
            } elseif (strcmp($service["status"],"CRITICAL") == 0) {
                print "        <div class=\"alert alert-danger\">\n";
            } else {
                print "        <div class=\"alert alert-info\">\n";
            };
            print "            <a href=\"host.php?host=$service[host_name]\" class=\"alert-link\"><strong>$service[host_name]</strong></a><br>\n";
            print "            $service[service] - $service[status]<br>\n";
            print "            $service[status_information]\n";
            print "        </div><!-- alert -->\n";
            print "    </div><!-- col -->\n";
            if(($i%6) == 0) {
                print "</div><!-- row -->\n";
                print "<div class=\"clearfix visible-lg-block\"></div>\n";
                print "<div class=\"row\">\n";
                $i=1;
            } else {
                $i+=1;
            };
        };
    };
    print "</div><!-- row -->\n";
};

function getHostgroupInfo($input) {
/* getHostgroupInfo()
 *
 * parameters:  $input - keyed array containing raw data returned from CGI; this
 *   should be a list of hostgroups with host and service status
 *
 * Take raw data array and add it to new array with only necessary values
 *   and return that array.
 */
    $ret = array();
    foreach($input["status"]["hostgroup_summary"] as $hostgrp) {
        array_push($ret,
                array(
                    "hostgroup_name" => $hostgrp["hostgroup_name"],
                    "hosts_up" => $hostgrp["hosts_up"],
                    "hosts_down" => $hostgrp["hosts_down"],
                    "services_ok" => $hostgrp["services_ok"],
                    "services_warning" => $hostgrp["services_warning_unacknowledged"],
                    "services_critical" => $hostgrp["services_critical_unacknowledged"]
                    )
                );
    };
    return $ret;
};

function printHostgroupInfo($info) {
/* printHostgroupInfo()
 *
 * parameters: $info - keyed array with processed data needed for this operation
 *
 * Take processed data array containing hostgroup and service status and output
 *   as nice html with appropriate formatting.
 */
    $hostgrps = getHostgroupInfo($info);
    $i=1;

    print "<div class=\"row\">\n";
    foreach($hostgrps as $hostgrp) {
        $cnt = $hostgrp["hosts_up"] + $hostgrp["hosts_down"];
        print "    <div class=\"col-lg-2\">\n";
        if(($hostgrp["hosts_down"] > 0) ||
           ($hostgrp["services_critical"] > 0)) {
            print "        <div class=\"alert alert-danger\">\n";
        } elseif ($hostgrp["services_warning"] > 0) {
            print "        <div class=\"alert alert-warning\">\n";
        } else {
            print "        <div class=\"alert alert-success\">\n";
        };
        print "            <a href=\"hostgroup.php?hostgroup=$hostgrp[hostgroup_name]\" class=\"alert-link\"><strong>$hostgrp[hostgroup_name]</strong> ($cnt hosts)</a><br>\n";
        print "            <div class=\"row\">\n";
        print "                <div class=\"col-lg-6\">\n";
        if($hostgrp["hosts_down"] > 0) {
            print "                    <div class=\"alert alert-danger\">\n";
        } else {
            print "                    <div class=\"alert alert-success\">\n";
        }
        print "                        <strong>Hosts</strong><br>\n";
        print "                        UP: $hostgrp[hosts_up]<br>\n";
        print "                        DOWN: $hostgrp[hosts_down]<br>\n";
        print "                    </div><!-- alert -->\n";
        print "                </div><!-- col -->\n";
        print "                <div class=\"col-lg-6\">\n";
        if($hostgrp["services_critical"] > 0) {
            print "                    <div class=\"alert alert-danger\">\n";
        } elseif ($hostgrp["services_warning"] > 0) {
            print "                    <div class=\"alert alert-warning\">\n";
        } else {
            print "                    <div class=\"alert alert-success\">\n";
        }
        print "                        <strong>Services</strong><br>\n";
        print "                        OK: $hostgrp[services_ok]<br>\n";
        print "                        WARN: $hostgrp[services_warning]<br>\n";
        print "                        CRIT: $hostgrp[services_critical]<br>\n";
        print "                    </div><!-- alert -->\n";
        print "                </div><!-- col -->\n";
        print "            </div><!-- row -->\n";
        print "        </div><!-- alert -->\n";
        print "    </div><!-- col -->\n";
        if(($i%6) == 0) {
            print "</div><!-- row -->\n";
            print "<div class=\"clearfix visible-lg-block\"></div>\n";
            print "<div class=\"row\">\n";
            $i=1;
        } else {
            $i+=1;
        };
    };
    print "</div><!-- row -->\n";
};

function getHostgroupDetails($input) {
/* getHostgorupDetails()
 *
 * parameters:  $input - keyed array containing raw data returned from CGI; this
 *   should be a list of hosts and services from a specific hostgroup
 *
 * Take raw data array and add it to new array with only necessary values
 *   and return that array.
 */
    $ret = array();
    foreach($input["status"]["hostgroup_overview"][0]["members"] as $host) {
        array_push($ret,
                array(
                    "host_name" => $host["host_name"],
                    "host_status" => $host["host_status"],
                    "services_ok" => $host["services_status_ok"],
                    "services_warning" => $host["services_status_warning"],
                    "services_critical" => $host["services_status_critical"],
                    "services_unknown" => $host["services_status_unknown"]
                    )
                );
    };
    return $ret;
};

function printHostgroupDetails($info) {
/* printHostgroupDetails()
 *
 * parameters: $info - keyed array with processed data needed for this operation
 *
 * Take processed data array containing hosts and service status from a specific
 *   hostgroup and output as nice html with appropriate formatting.
 */
    $hosts = getHostgroupDetails($info);
    $i=1;

    print "<div class=\"row\">\n";
    foreach($hosts as $host) {
        print "    <div class=\"col-lg-2\">\n";
        if($host["host_status"] == "DOWN") {
            print "        <div class=\"alert alert-danger\">\n";
        } else {
            print "        <div class=\"alert alert-success\">\n";
        };
        print "            <a href=\"host.php?host=$host[host_name]\" class=\"alert-link\"><strong>$host[host_name] - $host[host_status]</strong></a><br>\n";
        print "            <div class=\"row\">\n";
        print "                <div class=\"col-lg-12\">\n";
        if($host["services_critical"] > 0) {
            print "                    <div class=\"alert alert-danger\">\n";
        } elseif ($host["services_warning"] > 0) {
            print "                    <div class=\"alert alert-warning\">\n";
        } elseif ($host["services_unknown"] > 0) {
            print "                    <div class=\"alert alert-info\">\n";
        } else {
            print "                    <div class=\"alert alert-success\">\n";
        }
        print "                        <strong>Services</strong><br>\n";
        print "                        <table class=\"table-condensed\">\n";
        print "                            <tr>\n";
        print "                                <td>OK: $host[services_ok]</td>\n";
        print "                                <td>CRITICAL: $host[services_critical]</td>\n";
        print "                            </tr>\n";
        print "                            <tr>\n";
        print "                                <td>WARNING: $host[services_warning]</td>\n";
        print "                                <td>UNKOWN: $host[services_unknown]</td>\n";
        print "                            </tr>\n";
        print "                        </table>\n";
        print "                    </div><!-- alert -->\n";
        print "                </div><!-- col -->\n";
        print "            </div><!-- row -->\n";
        print "        </div><!-- alert -->\n";
        print "    </div><!-- col -->\n";
        if(($i%6) == 0) {
            print "</div><!-- row -->\n";
            print "<div class=\"clearfix visible-lg-block\"></div>\n";
            print "<div class=\"row\">\n";
            $i=1;
        } else {
            $i+=1;
        };
    };
    print "</div><!-- row -->\n";
};

function countHostsInGroup($raw) {
/* countHostsInGroup()
 *
 * parameters: $raw - a keyed array with data; this should contain a list of
 *   hosts in a specific hostgroup.
 *
 * Count the records in the given array and return that value.
 */
    return count(getHostgroupDetails($raw));
}

function countHostgroups($raw) {
/* countHostgroups()
 *
 * parameters: $raw - a keyed array with data; this should contain a list of
 *   hostgroups
 *
 * Count the records in the given array and return that value.
 */
    return count(getHostgroupInfo($raw));
}

function getHostDetails($input) {
/* getHostDetails()
 *
 * parameters:  $input - keyed array containing raw data returned from CGI; this
 *   should be a list of services and their status for a specific host
 *
 * Take raw data array and add it to new array with only necessary values
 *   and return that array.
 */
    $ret = array();
    foreach($input["status"]["service_status"] as $host) {
        array_push($ret,
                array(
                    "service_name" => $host["service_display_name"],
                    "status" => $host["status"],
                    "last_check" => $host["last_check"],
                    "duration" => $host["duration"],
                    "acknowledged" => $host["has_been_acknowledged"],
                    "info" => $host["status_information"]
                    )
                );
    };
    return $ret;
};

function printHostDetails($info) {
/* printHostDetails()
 *
 * parameters: $info - keyed array with processed data needed for this operation
 *
 * Take processed data array containing services and their status for a specific
 *   host and output as nice html with appropriate formatting.
 */
    $svcs = getHostDetails($info);

    print "<table class=\"table table-striped\">\n";
    print "    <tr><th>Service</th><th>Status</th><th>Ack</th><th>Last Check</th><th>Duration</th><th>Details</th></tr>\n";
    foreach($svcs as $svc) {
        print "    <tr";
        if($svc["status"] == "CRITICAL") {
            print " class=\"danger\"";
        } elseif($svc["status"] == "WARNING") {
            print " class=\"warning\"";
        } elseif($svc["status"] == "UNKNOWN") {
            print " class=\"info\"";
        }
        print ">\n";
        print "        <td>$svc[service_name]</td>\n";
        print "        <td>$svc[status]</td>\n";
        print "        <td>";
        if($svc["acknowledged"]) {
            print "YES";
        } elseif (!($svc["acknowledged"]) && ($svc["status"] != "OK")) {
            print "NO";
        }
        print "</td>\n";
        print "        <td>$svc[last_check]</td>\n";
        print "        <td>$svc[duration]</td>\n";
        print "        <td>$svc[info]</td>\n";
        print "    </tr>\n";
    }
    print "</table>\n";
}

function printServiceDetails($info) {
/* printServiceDetails()
 *
 * parameters: $info - keyed array with processed data needed for this operation
 *
 * Take processed data array containing host and service status from a specific
 *   hostgroup and output as nice html with appropriate formatting.
 */
    $svcs = getServiceInfo($info);

    print "<table class=\"table table-striped\">\n";
    print "    <tr><th>Host</th><th>Service</th><th>Status</th><th>Ack</th><th>Last Check</th><th>Details</th></tr>\n";
    foreach($svcs as $svc) {
        print "    <tr";
        if($svc["status"] == "CRITICAL") {
            print " class=\"danger\"";
        } elseif($svc["status"] == "WARNING") {
            print " class=\"warning\"";
        } elseif($svc["status"] == "UNKNOWN") {
            print " class=\"info\"";
        }
        print ">\n";
        print "        <td>$svc[host_name]</td>\n";
        print "        <td>$svc[service]</td>\n";
        print "        <td>$svc[status]</td>\n";
        print "        <td>";
        if($svc["acknowledged"]) {
            print "YES";
        } elseif (!($svc["acknowledged"]) && ($svc["status"] != "OK")) {
            print "NO";
        }
        print "</td>\n";
        print "        <td>$svc[last_check]</td>\n";
        print "        <td>$svc[status_information]</td>\n";
        print "    </tr>\n";
    }
    print "</table>\n";
}

?>
