<?php
/*
###############################################################################
# Name: start_servers_as_program.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 17-7-2009
# V1.1 6-10-2009 - Changed cron start and added reset 
# V1.1 15-2-2010
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
if($argc == 2){                       // Was a parameter passed
 $server_operation = $argv[1] ;       // yes: 
}
else{
 $server_operation = $server_standard; // no: Use user default
}

// === Reset Cron =============================================================
 if(!apache_running()){       // Apache not running however Cron may have been
   set_cron_tracker("stop");  // left set to run after a power fail hence reset          
 }
// ========================================================= End Reset Cron ===

// === RUN MySQL SERVER =======================================================
if ((int)$server_operation & 2 ){     // Server enabled by config or parameter  
  // === MySQL Service priority 
  // Running as a servive takes priority. A user must uninstal the service to
  // run server in standard program mode. Even afeter a power failure a service
  // will restart. Requires no special power recovery hence a good reference point. 

  if(get_mysql_tracker()=='service'){    // Is server running as a service
    print "\n Unable to start MySQL server!\n MySQL Server currently running as a service.\n";
  }
  else{  // Not running as a service
   $free_to_run = true; // Assume free to run Apache

    if(file_exists(USF_MYSQL_PID)){      // This MySQL has been started
      if(mysql_running()){                // Running cannot start another instance
        echo  "\n This MySQL server is already running!\n"; // Inform user
        echo  " You can stop the server using Stop_Server.bat\n"; //
        $free_to_run = false; 
      }
      else{                     // Left over from either a power fail or user not 
        unlink(USF_MYSQL_PID); // stopping servers before powering pc off hence detlete          
      }
     }

     if($free_to_run){  // Some other program could be using the ports hence port check                               

    // Port test
    $mysql_port = get_mysql_port();
    if (port_in_use($mysql_port )){ 
      echo " MySQL port  = ".$mysql_port." - Failed port already in use!\n";
      echo  " Failed! To run MySQL server port must be free!\n";
    }
    else{
      echo " MySQL port  = ".$mysql_port." - OK to run MySQL server\n";

      //Run server
      start_mysql();                          // start MySQL server and
      echo " Starting MySQL server ...\n\n";  // inform user

     //Run MySQL console
     if ((int)$server_operation & 8 ){      // Enabled by config or parameter 
       $base = preg_replace('/\//','\\', US_MYSQL_BIN); // Replace / with \
       $cmd1 = "start  ";
       $cmd2 = "\"UNIFORM SERVER MySQL Command prompt\" ";
       $cmd3 = "cmd.exe /k \"COLOR B0 && cd $base\"";
       $cmd  = $cmd1.$cmd2.$cmd3;
       pclose(popen($cmd,'r'));             // Start a new process ignore output  
     } 
    }
  }//Not running as a service
 }//
}// End run mysql

else{
  print " Note:\n";
  print " MySQL Server not enabled in config.inc.php\n";
  print " Or disabled by a user parameter\n";
}
// ====================================================== END MySQL Server ====
print"\n";

// === RUN APACHE SERVER ======================================================
if ((int)$server_operation & 1 ){     // Server enabled by config or parameter 

  // === Apache Service priority 
  // Running as a servive takes priority. A user must uninstal the service to
  // run server in standard program mode. Even afeter a power failure a service
  // will restart. Requires no special power recovery hence a good reference point. 

  if(get_apache_tracker()=='service'){    // Is server running as a service
    print " Unable to start Apache server!\n Apache Server currently running as a service.\n";
  }

  else{ // Not running as a service
    $free_to_run = true; // Assume free to run Apache

    if(file_exists(USF_APACHE_PID)){      // This Apache has been started
      if(apache_running()){                // Running cannot start another instance
        echo  "\n This Apache server is already running!\n"; // Inform user
        echo  " You can stop the server using Stop_Server.bat\n"; //
        $free_to_run = false;             // Skip next section 
      }
      else{                        // Left over from either a power fail or user not 
        unlink(USF_APACHE_PID);   // stopping servers before powering pc off hence detlete
        set_cron_tracker("stop");  // This may have been left set to run hence change to stop          
      }
     }

     if($free_to_run){  // Some other program could be using the ports hence port check                               

        // === Port tests ==========================================
        $failed = false; // Assume ports pass test        
        //=== Apache:
        $apache_port = get_apache_port();
        if (port_in_use($apache_port)){         // Is port in use
          echo " Apache port = ".$apache_port."   - Failed port already in use!\n";
          $failed=true;                         // Set failed flag
        }
        else{
          echo " Apache port = ".$apache_port."   - OK to run Apache server\n";
        }
        //=== Apache SSL
        if(ssl_enabled()){                       // SSL enabled
          $ssl_port = get_apache_ssl_port();
          if (port_in_use($apache_ssl_port)){    // Is port in use
            echo " Apache port = ".$ssl_port."   - Failed port already in use!\n";
            $failed=true;                        // Set failed flag
          }
          else{
            echo " SSL port    = ".$ssl_port."  - OK to run Secure Apache server\n";
          }
        }
        else{                                     // SSL not enabled
           echo " SSL not enabled skipping port test.\n";
        }
        //======================================== END Port test

         if($failed || !apache_syntax_check()){
           if($failed){
             echo  " Failed! To run Apache server both ports must be free!\n";
           }
           if(!apache_syntax_check()){
             echo  " Failed! Please correct Apache config file errors!\n";
           }
         }
         else{ // Free to run server
           echo " Starting Apache server ...\n\n"; // Inform user 
           start_apache();                         // Run Apache server

           //=== Start index page =======================================================
           if ((int)$server_operation & 4 ){  // Enabled by config or parameter  
            //-- Decide which browser to use
            if(file_exists(USF_HOSTS_PAC)){   // Does PAC file exists use portable Firefox 
             $port = get_apache_port();            // Get port may have moved
             $page = "http://localhost:$port/";    // page to display
             start_firefox($page);                 // Start Firefox portable
            }
            else{ // Use PC default browser
             $command = 'start '.USF_REDIRECT1;   // index page and automaticaly start
             exec($command,$dummy,$return);       // browser at initial server start-up 
            }
           }
           //=================================================== END Start index page ===
 
         }// end else 
      }//  end Free to run server                                               
  }// END Not running as a service
}//End run apache

else{
  print " Note:\n";
  print " Apache Server not enabled in config.inc.php\n";
  print " Or disabled by a user parameter\n";
}
// ================================================== END RUN APACHE SERVER ===
print"\n";

// === RUN CRON ===============================================================
if ((int)$server_operation & 16 ){       // Cron enabled by config or parameter  

 if(apache_running()){                   // Only run cron if Apache running
                                         // otherwise ignore and exit quietly

   if(get_cron_tracker() == "run"){      // Cron already running
     print " Cron already running\n";    // Already running, give up
   }
   else{                                 // not running
     set_cron_tracker("run");            // set tracker to run
     print " Cron started \n";           // inform user

    // Test switch between the two: visibe
    //$cmd = 'start ..\..\usr\local\php\php.exe run_cron.php'; // Test

    // Test switch between the two: hidden
    $cmd = 'start uniserv.exe "..\..\usr\local\php\php.exe run_cron.php"';

    pclose(popen($cmd,'r'));             // Start detatched process 

   }
 }//End Apache running
}// End run cron

else{
  print " Cron not enabled in config.inc.php\n";
  print " Or disabled by a user parameter\n";
}
// ========================================================== END RUN CRON ====
print"\n";
?>