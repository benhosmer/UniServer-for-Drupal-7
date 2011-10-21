<?php
/*
###############################################################################
# Name: run_cron.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# Comment: Run Cron scripts.
# Some applications require a script to be periodically run (referred to as a
# cron job). Specify these scripts and time in cron.ini How often cron.ini is
# checked is defined by $cron_time which is currently set to 1 minute.
# This should be suitable for most applications.
# 
# V1.0  4-9-2009 - Original
# V1.1 27-9-2009 - Split out configuration into cron.ini
#                  Added a foreach-loop (Scan Ini File) this now performs all
#                  timings independent of scripts to be run.
#                  Added logging - output file cron.log
# V1.2  5-2-2010 - Added \r\n to keep notepad happy
###############################################################################
*/
#error_reporting(0);  // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../main/includes/config.inc.php";
include_once "../main/includes/functions.php";

//== Variables
$logging   = true;           // true = Enable logging false = disable logging
$cron_time = 60;             // Set cron time (tick) in seconds. Default 1 Min
$loop      = $cron_time;     // Set equal allows immediate first time run

if($logging){
  cron_log("### Log Started ========================="); 
}

//== Main loop
while(TRUE){                       // Infinite loop.
 if(get_cron_tracker() != "run"){  // Check every second for user cron stop
   break;                          // reqest. Breaks out of while loop and 
 }                                 // kills script.
 else{
   if($loop == $cron_time){        // True for first entry hence immediately
                                   // runs scriptss. There after run at
                                   // cron time.

//#############################################################################
//#                         Scan Ini File                                     #
//#############################################################################

$ini_array = parse_ini_file(USF_CRON_INI, true); // Read cron.ini into array
foreach($ini_array as $key => $value){           // Scan array's main keys

 $tim = strtotime("now");                        //current time
 $tim_s  = strtotime($ini_array[$key]['start']); // Get start time
 $period = $ini_array[$key]['period'];           // Get period
 $path   = $ini_array[$key]['path'];             // Get path to run script
 $ref    = $ini_array[$key]['ref'];              // Get repeat time marker

if((float)$tim > (float)$tim_s){      // New start or started
  if( $ref != ""){                    // It was started
    if( (float)$tim < (float)$ref ){  // Is it a triggered update 
      continue;                       // No: Start next foreach
    }
  }
  //== Eiter a new start or triggered update hence run script

 if($period == "hourly"){              // ... Get user defined period
  $offset =(60*60);                    // Set number of corresponding
 }                                     // seconds ...
 if($period == "daily"){
  $offset =(24*60*60);
 }
 if($period == "weekly"){
  $offset =(7*24*60*60);
 }
 if($period == "monthly"){
  $offset =(4*7*24*60*60);
 }
 if(preg_match('/^\d+/',$period)){     // If is digits 
   $offset = (int)$period;             // Set to int
 }

 $ref = $tim +  $offset;               // Create new repeat time marker
 my_ini_set(USF_CRON_INI,$key,'ref',$ref);    // Save to ini file for later use 

 // There are two file types that can be run browser or local CLI example:
 // http://localhost/drupal/cron.php
 // ..\..\plugins\dtdns_updater\dtdns_updater.php
 // A user specifies only the path/filename

 if(preg_match('/^http/',$path)){      // Is it a browser address 
   $dummy = @file($path);              // Yes: Run on Server, $dummy not used 
    if($logging){
      cron_log($path);                 // Save to log and add time 
    }
 }
 else{                                 // No: Hence run local PHP script
   $cmd = 'start uniserv.exe '. "\"..\..\usr\local\php\php.exe $path\"";
   pclose(popen($cmd,'r'));            // Start detached process 
    if($logging){
      cron_log($path);                 // Save to log and add time 
    }
 }
}//if
}// End foreach

//#############################################################################
//#                           END Scan Ini File                               #
//#############################################################################

     //print_r($dummy);      // Test line displays cron page output 
     $loop =0;               // Reset loop counter
   }
   $loop = $loop+1;          // Increment loop counter
  }
  sleep(1);                  // Base delay one second allows stop to be checked 
}

if($logging){
  cron_log("### Log Ended ===========================\n"); 
}

//=== Log =====================================================================
// Logs Cron actions to a log file
// Input: String to be logged
// USF_CRON_LOG   Path to file including file name

function cron_log($str){

  $str = date('Y-m-d H:i')."  ".$str."\r\n"; /// Note \r\n to keep notepad happy

  $fh = fopen(USF_CRON_LOG, 'a') or die("can't open file");
  fwrite($fh, $str);
  fclose($fh);
 
}
//=== END Log =================================================================

?>