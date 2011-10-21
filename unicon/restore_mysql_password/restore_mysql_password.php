<?php
/*
###############################################################################
# Name: restore_mysql_password.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 25-6-2009
###############################################################################
*/
#error_reporting(0); // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../main/includes/config.inc.php";
include_once "../main/includes/functions.php";

run_location_tracker();   // Have servers moved if moved
                          // update configuration accordingly

print "\n ============ RESTORE MYSQL PASSWORD ============\n\n";


//=== To restore password MySQL must be stopped ===============
$status = get_mysql_tracker(); 
 
if(mysql_running() && $status == "service"){
  print " MySQL service running\n";
  print " Stopping MySQL service\n";
  stop_mysql_service();
}

if(mysql_running() && $status == "program"){
  print " MySQL program running\n";
  print " Killing MySQL program\n";
  kill_mysql(); //Note kill not stop. Stop uses password  
}

//=== Wait for server to stop =====================================

$safety_timer = 0;                  // Set timer
while(mysql_running()){             // Check Apache started
 if($safety_timer == 40){           // Has safety time been reached
   exit(1);                         // Exit with error code                             
 }
 $safety_timer = $safety_timer +1;  // update timer
 usleep(500000);                    // delay 0.5 sec and repeat
}

//=== Update password file ==============
 print " Password file restored to root\n";
 $wfile = fopen(USF_MYSQL_PASSWORD, 'w');
 fwrite($wfile, "root");
 fclose($wfile);

//=== Restore password ====================
  print " Restoring MySQL server password\n";
  $MySQL_exe = get_mysql_exe();       // get program name
  $cmd = "middleman.bat $MySQL_exe";  // command line to be run  

  exec($cmd,$dummy,$return);          // 0=running 1=not-running 

  print " Restored MySQL server password\n";
//=== Restore Server to original state =====

if($status == "service"){
  print " Starting MySQL service\n";
  start_mysql_service();
}


if($status == "program"){
  print " Starting MySQL program\n";
  start_mysql();
}

exit;
?>