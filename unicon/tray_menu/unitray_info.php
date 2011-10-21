<?php
/*
###############################################################################
# Name: unitray_info.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Unitray Support file
# Displays server information. Ports read from configuration files
# Added support for USB plugin
# V1.0 17-2-2010
###############################################################################
*/
//error_reporting(0); // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../main/includes/config.inc.php";
include_once "../main/includes/functions.php";

run_location_tracker();  // Have servers moved if moved update configuration
print"\n";

//=== Check for parameters passed
if($argc == 2){            // Was a parameter passed
 $information = $argv[1] ; // yes: 
}
else{
 exit;                     // no: Give up
}

$mysql_port  = get_mysql_port();
$apache_port = get_apache_port();
$ssl_port    = get_apache_ssl_port();

//### Decide which browser to use either PC defaulr browser or poetable Firefox ###
if(file_exists(USF_HOSTS_PAC)){      // Does PAC file exists use  poetable Firefox
  //=== Display Admin Panel in browser ==========================================
  if($information == 1){
   start_firefox("http://localhost:$apache_port/apanel/"); // display index page 
   exit;
  }
  //=== Display phpMyAdmin in browser ===
  if($information == 2){
   start_firefox("http://localhost:$apache_port/apanel/phpmyadmin/"); // display index page 
   exit;
  }
  //=== Display WWW Root in browser ===
  if($information == 3){
   start_firefox("http://localhost:$apache_port/"); // display index page 
   exit;
  }
  //=== Display SSL Root in browser ===
  if($information == 4){
   start_firefox("https://localhost:$ssl_port/"); // display index page 
   exit;
  }
  //=== Display Apache Server Information in browser ===
  if($information == 5){
   start_firefox("http://localhost:$apache_port/server-info"); // display index page 
   exit;
  }
  //=== Display Apache Server Status in browser ===
  if($information == 6){
   start_firefox("start http://localhost:$apache_port/server-status"); // display index page 
   exit;
  }
  //=== Display PHP Info in browser ===
  if($information == 7){
   start_firefox("http://localhost:$apache_port/apanel/phpinfo.php"); // display index page 
   exit;
  }
}//end if

else{                                 // PAC does not exist use default brower
  //=== Display Admin Panel in browser ===
  if($information == 1){
   $command = "start http://localhost:$apache_port/apanel/"; // display index page 
   exec($command,$dummy,$return);                           // run command 
   exit;
  }
  //=== Display phpMyAdmin in browser ===
  if($information == 2){
   $command = "start http://localhost:$apache_port/apanel/phpmyadmin/"; // display index page 
   exec($command,$dummy,$return);                           // run command 
   exit;
  }
  //=== Display WWW Root in browser ===
  if($information == 3){
   $command = "start http://localhost:$apache_port/"; // display index page 
   exec($command,$dummy,$return);                           // run command 
   exit;
  }
  //=== Display SSL Root in browser ===
  if($information == 4){
   $command = "start https://localhost:$ssl_port/"; // display index page 
   exec($command,$dummy,$return);                           // run command 
   exit;
  }
  //=== Display Apache Server Information in browser ===
  if($information == 5){
   $command = "start http://localhost:$apache_port/server-info"; // display index page 
   exec($command,$dummy,$return);                           // run command 
   exit;
  }
  //=== Display Apache Server Status in browser ===
  if($information == 6){
   $command = "start http://localhost:$apache_port/server-status"; // display index page 
   exec($command,$dummy,$return);                           // run command 
   exit;
  }
  //=== Display PHP Info in browser ===
  if($information == 7){
   $command = "start http://localhost:$apache_port/apanel/phpinfo.php"; // display index page 
   exec($command,$dummy,$return);                           // run command 
   exit;
  }
}//end else
exit;
?>