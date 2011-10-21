<?php
/*
###############################################################################
# Name: change_apache_port.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 21-5-2011
###############################################################################
*/
#error_reporting(0); // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../main/includes/config.inc.php";
include_once "../main/includes/functions.php";

run_location_tracker();   // Have servers moved if moved
                          // update configuration accordingly
print "\n";

//=== Both servers must be stopped and not installed as a service =============

if(get_apache_tracker() != "free" || get_mysql_tracker() != "free"){
  print " This script was terminated!\n\n";
  print " The servers are either running or installed as a service.\n";
  print " Please stop and uninstall the servers\n\n";
  print " Then run this script again.\n\n"; 
  exit;
}

print " #################################################\n";
print " #                                               #\n";
print " #     Uniform Server: Change Apache Port        #\n";
print " #                                               #\n";
print " #################################################\n\n";

$Apache_port_old = get_apache_port();                # Server port

#== Get user input

$Apache_port     = prompt_user(" Current Apache port = $Apache_port_old   New Port = ", "");
print "\n";

if($Apache_port == ""){
  exit;
}

//=============== UPDATE FIREFOX ==========================
if(file_exists(USF_HOSTS_PAC)){ // Does PAC file exists use  portable Firefox

  $r_str = ":".$Apache_port;
  $s_str = ":".$Apache_port_old;
  $s_str = preg_quote($s_str,'/');        // Convert to regex format
  $s_str = '/'.$s_str.'/';                // Create regex pattern
  file_search_replace(USF_HOSTS_PAC,$s_str,$r_str);
}

//=== Update Apache config with new ports ===
  $r_str = "Listen ".$Apache_port;
  $s_str = "Listen ".$Apache_port_old;
  $s_str = preg_quote($s_str,'/');        // Convert to regex format
  $s_str = '/'.$s_str.'/';                // Create regex pattern
  file_search_replace(USF_APACHE_CNF,$s_str,$r_str);

  $r_str = ":".$Apache_port;
  $s_str = ":".$Apache_port_old;
  $s_str = preg_quote($s_str,'/');        // Convert to regex format
  $s_str = '/'.$s_str.'/';                // Create regex pattern
  file_search_replace(USF_APACHE_CNF,$s_str,$r_str);

//=== Update Apanel redirect.html to new port ===
  $r_str = ":".$Apache_port;
  $s_str = ":".$Apache_port_old;
  $s_str = preg_quote($s_str,'/');        // Convert to regex format
  $s_str = '/'.$s_str.'/';                // Create regex pattern
  file_search_replace(USF_REDIRECT1,$s_str,$r_str);

//=== Update Apanel redirect2.html to new port ===
  $r_str = ":".$Apache_port;
  $s_str = ":".$Apache_port_old;
  $s_str = preg_quote($s_str,'/');        // Convert to regex format
  $s_str = '/'.$s_str.'/';                // Create regex pattern
  file_search_replace(USF_REDIRECT2,$s_str,$r_str);

print " Server port has been changed\n\n";

?>