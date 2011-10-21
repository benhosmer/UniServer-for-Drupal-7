<?php
/*
###############################################################################
# Name: service_stop_uninstall.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 14-6-2009
# V1.1 2-2-2010
# Comment: Now uses constants from config.inc.php
###############################################################################
*/
//error_reporting(0); // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "includes/config.inc.php";
include_once "includes/functions.php";

run_location_tracker();  // Have servers moved if moved update configuration 
print"\n";

//=== If no parameters passed use defaults from config.inc.php
if($argc == 2){                       // Was a parameter passed
 $server_operation = $argv[1];        // yes: 
}
else{
 $server_operation = $server_service; // no: Use user default
}

// == STOP MYSQL SERVICE =======================================================
if ((int)$server_operation & 2 ){       // Server enabled by config or parameter
  $no_mysql_service = true;

  if(mysql_running() && get_mysql_tracker()=='program'){   // not a service
    print " MySQL currently running as a standard program.\n";
  }

  if(mysql_running() && get_mysql_tracker()=='service'){ // Server running as a service
    echo " Stopping MySQL Service ".USC_MYSQL_SERVICE_NAME." ...\n";  // Inform user 
    echo " UnInstalling MySQL Service ".USC_MYSQL_SERVICE_NAME." ...\n";
    stop_mysql_service();      // Stop MySQL service
    uninstall_mysql_service(); // Remove service    
    $no_mysql_service = false;
  } 

  if(!mysql_running() && get_mysql_tracker()=='service'){ // Server running as a service
    echo " MySQL service ".USC_MYSQL_SERVICE_NAME." was not running\n";   // Inform user 
    echo " UnInstalling MySQL Service ".USC_MYSQL_SERVICE_NAME." ...\n";
    uninstall_mysql_service(); // Remove service  
    $no_mysql_service = false;
  }

  if($no_mysql_service){
    print " MySQL is not installed as a service\n";
  }
}// End User selectable in config.inc.php

else{  //User has not enabled this in config.inc.php
 print " MySQL service not enabled in config.inc.php\n";
}
// ================================================== END RUN MYSQL SERVICE ===
print "\n";

// == STOP APACHE SERVICE =====================================================
if ((int)$server_operation & 1 ){       // Server enabled by config or parameter
  $no_apache_service = true;
  if(apache_running() && get_apache_tracker()=='program'){  // not a service
    print " Apache currently running as a standard program.\n"; // inform user
  }

  if(apache_running() && get_apache_tracker()=='service'){  // Server running as a service
    echo " Stopping USC_APACHE_SERVICE_NAME Service ...\n"; // Inform user 
    stop_apache_service();
    echo " UnInstalling Apache Service ".USC_APACHE_SERVICE_NAME." ...\n"; // Inform user 
    uninstall_apache_service();   // And set tracker to free
    $no_apache_service = false;    
  } 

  if(!apache_running() && get_apache_tracker()=='service'){ // Server running as a service
    echo " Apache service ".USC_APACHE_SERVICE_NAME." was not running\n";  // Inform user 
    echo " UnInstalling Apache Service ".USC_APACHE_SERVICE_NAME." ...\n"; 
    uninstall_apache_service();  // And set tracker to free
    $no_apache_service = false;
  } 

  if($no_apache_service){
    print " Apache is not installed as a service\n";
  }
}// End User selectable in config.inc.php

else{  //User has not enabled this in config.inc.php
 print " Apache service not enabled in config.inc.php\n";
}
// ================================================ END RUN APACHE SERVICE ===

print "\n";
?>