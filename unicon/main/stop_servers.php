<?php
/*
###############################################################################
# Name: stop_servers.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 17-7-2009
# V1.1 17-2-2010
# Comment: Now uses constants from config.inc.php
#          Added Firefox portable support
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
if($argc == 2){                        // Was a parameter passed
 $server_operation = $argv[1] ;        // yes: 
}
else{
 $server_operation = $server_standard; // no: Use user default
}

// === Stop MySQL Server ======================================================
// Running as a servive takes priority. A user must uninstal the service to
// run servers in standard program mode. Even afeter a power failure a service
// will restart. Requires no special power recovery hence a good reference point. 

if(get_mysql_tracker()=='service'){  // Is server running as a service
  print " Unable to stop MySQL server! Currently running as a service.\n";
}
else{
if ((int)$server_operation & 2 ){         // Server enabled by config or parameter

    if(mysql_running() && file_exists(USF_MYSQL_PID)){ // Is it running 
      print" MySQL Server stopped.\n";    // inform user
      stop_mysql();                       // yes: Stop MySQL
    }
    else{                                 // no:
     print" MySQL Server not running.\n"; // inform user
    }
  }
  else{
    print " MySQL Server not enabled in config.inc.php\n";
  }
}
// ====================================================== Stop MySQL Server ===
print"\n";

// === Stop Apache Server =====================================================
// Running as a servive takes priority. A user must uninstal the service to
// run server in standard program mode. Even afeter a power failure a service
// will restart. Requires no special power recovery hence a good reference point. 

if(get_apache_tracker()=='service'){   // Is server running as a service
  print " Unable to stop Apache server! Currently running as a service.\n";
}
else{
  if ((int)$server_operation & 1 ){       // Server enabled by config or parameter

    if(apache_running() && file_exists(USF_APACHE_PID)){ // Is it running 
      print" Apache Server stopped.\n";    // inform user
      stop_apache();                       // yes: Stop Apache
        //-- Decide if portable Firefox is being used
        if(file_exists(USF_HOSTS_PAC)){    // Does PAC file exists 
          stop_firefox();                  // stop Firefox Portable
        }
    }
    else{                                  // no:
     print" Apache Server not running.\n"; // inform user
    }
  }
  else{
    print " Apache Server not enabled in config.inc.php\n";
  }
}
// ================================================= END Stop Apache Server ===
print"\n";

// === Stop Cron ==============================================================

if ((int)$server_operation & 16 ){    // Cron enabled by config or parameter
 if(get_cron_tracker() == "stop"){    // Already stopped or not running
  print " Cron not running\n";
 }
 else{
  set_cron_tracker("stop");           // Is running signal to stop                             
  print" Cron stopped.\n";            // Inform user
 }
}

else{
 print " Cron not enabled in config.inc.php\n";
}
// ============================================================== Stop Cron ===
print"\n";
?>