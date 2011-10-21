<?php
/*
###############################################################################
# Name: pre_check.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 10-9-2010
# Comment: Check port in use. If in use display application details
################################################################################
*/
 #error_reporting(0); // Disable PHP errors and warnings
                       // Comment to Enable for testing

 chdir(dirname(__FILE__)); // Change wd to this files location
 include_once "../main/includes/config.inc.php";
 include_once "../main/includes/functions.php";

 run_location_tracker();   // Have servers moved if moved
                           // update configuration accordingly

 // Legacy suitability check no spaces in path to UniServer
 // Also avoids Vista's insufficient write permissions in C:\Program Files

 $pos = stripos(dirname(__FILE__)," "); // Look for space
 if ($pos !== false) {
  $str .= " \n\n === Space Detected ===\n\n";
  $str .= " A space was detected in folder names leading to folder UniServer\n";
  $str .= " Unfortunately spaces are not allowed in path names\n\n";
  $str .= " Please correct and try again.\n";
  $str .= " Reasons for this restriction require ability to run legacy applications.',\n\n";
  print $str;
  exit;
 }
 //== End of draconian coding now lets see if we can get up and running

 //--- To start servers ports must be free. If these are in use by another
 //    application warn user to take corrective action.

  // Get current server ports - May have been changed from defaults 
  $apache_port     = get_apache_port();
  $apache_ssl_port = get_apache_ssl_port();
  $mysql_port      = get_mysql_port();

  $port_in_use     = false;   // true - display warning message 
  $port_in_use_str = "\n === Port in use test ===\n\n"; // set warning message to display 

  // Ia Apache being used
  if(us_port_in_use($apache_port) && (get_apache_tracker() == "free")){ 
   $port_in_use = true;                            // port in use set flag 
   $details_array =  us_get_port_user_details($apache_port);
   $port_in_use_str .= ' Port number	 = '. $details_array[0]."\n";
   $port_in_use_str .= ' PID		 = '. $details_array[1]."\n";
   $port_in_use_str .= ' Process name	 = '. $details_array[2]."\n";
   $port_in_use_str .= ' Path to process = '. $details_array[3]."\n\n";
  }

  // Ia Apache ssl being used
  if(us_port_in_use($apache_ssl_port) && (get_apache_tracker() == "free")){ 
   $port_in_use = true;                                // port in use set flag 
   $details_array =  us_get_port_user_details($apache_ssl_port);
   $port_in_use_str .= ' Port number	 = '. $details_array[0]."\n";
   $port_in_use_str .= ' PID		 = '. $details_array[1]."\n";
   $port_in_use_str .= ' Process name	 = '. $details_array[2]."\n";
   $port_in_use_str .= ' Path to process = '. $details_array[3]."\n\n";
  }

  // Ia MySQL being used
  if(us_port_in_use($mysql_port ) && (get_mysql_tracker() == "free")){ 
   $port_in_use = true;                            // port in use set flag 
   $details_array =  us_get_port_user_details($mysql_port);
   $port_in_use_str .= ' Port number	 = '. $details_array[0]."\n";
   $port_in_use_str .= ' PID		 = '. $details_array[1]."\n";
   $port_in_use_str .= ' Process name	 = '. $details_array[2]."\n";
   $port_in_use_str .= ' Path to process = '. $details_array[3]."\n";
  }

  if ($port_in_use){                                 // Display warning message 
   $port_in_use_str .= "\n\n To start Uniform Server above ports must be free.\n\n";
   $port_in_use_str .= " Either stop-uninstall offending application.\n";
   $port_in_use_str .= " Or\n";
   $port_in_use_str .= " Move Uniform Server to different ports.\n\n\n";
   print $port_in_use_str;
   exit;
 }
 else{
  print "\n\n Ports are free to use \n\n";
 }

 //==== New Functions ==========================================================

//== Port In Use Test =========================================================
// Checks a port to see if it is currenly in use.
// Input:  Port number Output: true - in use false - free to use 
function us_port_in_use($port){
 $list = shell_exec("netstat -anp tcp");  // Get output list as sring 
 if(strpos($list,'0.0.0.0:'.$port.' ')){  // Check for match
   return true;                           // Found match Port already in use
 }
 else{
  return false;                          // No match Port free to use
 }
}//======================================================== END Port In Use ===

//== Port User Details ========================================================
// Port is being used by another user.
// Input:  Port number
// Output: Array [0] Port nuber [1] PID [2] process name [3] Path to process  

function us_get_port_user_details($port){
 $list = shell_exec("netstat -anop tcp"); // Get output list as sring 

 // Find pid
 $list_array  = explode("\n",$list);       // Split string at \n
 foreach($list_array as $text){            // scan line-by-line
  if(strpos($text,'0.0.0.0:'.$port.' ')){  // Check for match
    $text = trim($text);                   // clean
    $line_array = explode(" ",$text);      // Split string at " "
    $pid = end($line_array);               // Get pid
    break;                                 // exit for loop
  }
 }
 
 // Get process details
 $wmi = new COM('winmgmts://');             // Locally connect to WMI
                                            // Search for process name
 $processes = $wmi->ExecQuery("SELECT Name,
   ProcessId,CommandLine FROM Win32_Process WHERE ProcessId = '$pid' ");

  foreach($processes as $process){                   // Build return array
    $rest_array[] = $port;                           // Port             
    $rest_array[] = $pid;                            // Pid
    $rest_array[] = $process->Name;                  // Process name using port 
    $cmd_array = explode(" ",$process->CommandLine); // Split string at " "
    $rest_array[] =  $cmd_array[0];                  // Process path location
    return $rest_array;                              // Return data
  }

}//================================================== END Port User Details ===

?>
