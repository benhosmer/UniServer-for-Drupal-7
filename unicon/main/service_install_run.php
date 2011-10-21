<?php
/*
###############################################################################
# Name: service_install_run.php
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

// == RUN MYSQL SERVICE =======================================================
if ((int)$server_operation & 2 ){       // Server enabled by config or parameter

  // === Check server running 
  if(mysql_running() && file_exists(USF_MYSQL_PID)){
    if(get_mysql_tracker()=='program'){ // Server running as a standard program
      print " Unable to install MySQL service!\n MySQL Server is running as a standard program.\n";
    }
    if(get_mysql_tracker()=='service'){ // Server running as a service
      print " MySQL Server already installed as a service.\n";
    }
  }
  else{ // server not running, we have a chance to run as a service if ports are free!

    //=== Port test =====
    $mysql_port = get_mysql_port();
    if (port_in_use($mysql_port)){ 
      echo " MySQL port  = ".$mysql_port." - Failed port already in use!\n";
      echo  " Failed! To install MySQL service port must be free!\n";
    }
    else{
      echo " MySQL port  = ".$mysql_port." - OK to run MySQL server\n";

     // === Start mysql service ========
     echo " Installing MySQL service ".USC_MYSQL_SERVICE_NAME." ...\n\n";  // Inform user 
     install_mysql_service();  // Install
     start_mysql_service();    // Start
     // === END Start mysql service ====

     // === Run MySQL console =======
     if ((int)$server_operation & 8 ){       // Enabled by config or parameter
       $base = preg_replace('/\//','\\', US_MYSQL_BIN); // Replace / with \
       $cmd1 = "start  ";
       $cmd2 = "\"UNIFORM SERVER MySQL Service Command prompt\" ";
       $cmd3 = "cmd.exe /k \"COLOR B0 && cd $base\"";
       $cmd  = $cmd1.$cmd2.$cmd3;
       pclose(popen($cmd,'r'));         // Start a new process ignore output  
     } 
     // === END Run MySQL console =======
    }// end else

  }//end // server not running
}// end $us_run_mysql_service

else{  //User has not enabled this in config.inc.php
 print " MySQL service not enabled in config.inc.php\n";
 print " Or disabled by a user parameter\n";
}
// ================================================== END RUN MYSQL SERVICE ===
print "\n";

// == RUN APACHE SERVICE =====================================================
if ((int)$server_operation & 1 ){       // Server enabled by config or parameter

  // === Check server running 
  if(apache_running() && file_exists(USF_APACHE_PID)){
    if(get_apache_tracker()=='program'){ // Server running as a standard program
      print " Unable to install Apache service!\n Apache Server is running as a standard program.\n";
    }
    if(get_apache_tracker()=='service'){ // Server running as a service
      print " Apache Server already installed as a service.\n";
    }
  }
  else{ // server not running, we have a chance to run as a service if ports are free!

     // === Port tests ==========================================
     $failed = false; // Assume ports pass test        
     //=== Apache:
     $apache_port = get_apache_port();
     if (port_in_use($apache_port)){
       echo " Apache port = ".$apache_port."   - Failed port already in use!\n";
       $failed=true; // Set failed flag
     }
     else{
       echo " Apache port = ".$apache_port."   - OK to run Apache service\n";
     }
     //=== Apache SSL
     if(ssl_enabled()){ // SSL enabled need to test this port
       $ssl_port = get_apache_ssl_port();
       if (port_in_use($apache_ssl_port)){ 
         echo " Apache port = ".$ssl_port."   - Failed port already in use!\n";
         $failed=true; // Set failed flag
       }
       else{
         echo " SSL port    = ".$ssl_port."  - OK to run Secure Apache serice\n";
       }
     }
     else{                                                  // SSL not enabled
        echo " SSL not enabled skipping port test.\n";
     }
     //======================================== END Port test


      if($failed || !apache_syntax_check()){
        if($failed){
          echo  " Failed! To install Apache service both ports must be free!\n";
        }
        if(!apache_syntax_check()){
           echo  " Failed! Please correct Apache config file errors!\n";
        }
      }

      else{ // Free to run server

        //=== Start service ===========================
        echo " Installing ".USC_APACHE_SERVICE_NAME." Service ...\n\n";  // Inform user 
        install_apache_service();  // Install service set tracker to service 
        start_apache_service();    // Start service
 
        //=== End Start service ===========================

        //=== Start index page =======================================================
        if ((int)$server_operation & 4 ){    // Enabled by config or parameter 
          $command = 'start '.USF_REDIRECT1; // index page and automaticaly start
          exec($command,$dummy,$return);     // browser at initial server start-up 
        }
        //=================================================== END Start index page ===
      }// end Free to run server
  }// end else // server not running
}// end $us_run_apache_service

else{  //User has not enabled this in config.inc.php
 print " Apache service not enabled in config.inc.php\n";
 print " Or disabled by a user parameter\n";
}
// ================================================ END RUN APACHE SERVICE ===
print "\n";
?>