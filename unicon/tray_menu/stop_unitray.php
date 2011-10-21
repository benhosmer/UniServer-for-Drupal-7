<?php
/*
###############################################################################
# Name: start_unitray.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 14-6-2009
###############################################################################
*/
//error_reporting(0); // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../main/includes/config.inc.php";
include_once "../main/includes/functions.php";

run_location_tracker();   // Have servers moved if moved
                          // update configuration accordingly

stop_unitray();

exit;
?>