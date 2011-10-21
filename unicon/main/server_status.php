<?php
/*
###############################################################################
# Name: server_status.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 11-9-2009
# V1.1 6-10-2009 Added Cron reset
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

// === Reset Cron =============================================================
 if(!apache_running()){       // Apache not running however Cron may have been
   set_cron_tracker("stop");  // left set to run after a power fail hence reset          
 }
// ========================================================= End Reset Cron ===

print " ================== UNIFORM SERVER STATUS ==================\n\n";

if(get_ip_current()){                               // Current IP address 
 print " Your Internet IP Address = $ip_current\n"; // as seen from Internet
}
else{                                               // Either not connected
 print " Your Internet IP Address = Not connected or error! \n"; // to Internet
}                                                   // or errors returned

if(test_access()){                                 // Can server be accessed
 print " Accessible from Internet = YES\n";        // from Internet uses
}                                                  // above IP address
else{
 print " Accessible from Internet = NO\n";         // Not connected or 
}

if(get_cron_tracker() == "run"){                   // Is cron running
 print " Cron run status          = Running\n";    
} 
else{
 print " Cron run status          = Not running\n";  
}                                  

print " PHP INI: php.ini file    = ". get_php_tracker()."\n\n";

print " APACHE SERVER:\n\n";
print "   Apache port            = ". get_apache_port()."\n";
print "   Apache SSL port        = ". get_apache_ssl_port()."\n";
print "   Apache executable name = ". get_apache_exe()."\n";
print "   Apache service name    = ".USC_APACHE_SERVICE_NAME."\n";

if(ssl_enabled()){
print "   Apache SSL server      = Enabled\n";
}
else{
print "   Apache SSL server      = Disabled\n";
}


// Check was PC switched off while server running
if(!apache_running() && get_apache_tracker() == "program"){
  set_apache_tracker('free');
}

if(apache_running() && get_apache_tracker() != "free"){
print "   Apache run status      = Running\n";
 if(get_apache_tracker() == "service"){
  print "   Apache install status  = Installed as a service\n";
 }
 if(get_apache_tracker() == "program"){
  print "   Apache install status  = Installed as a standard program\n";
 }
}
else{
 print "   Apache run status      = Not running\n";
  if(get_apache_tracker() == "free"){
    print "   Apache install status  = Free to install\n";
  }
    if(get_apache_tracker() != "free"){
    print "   Apache install status  = Undefined!\n";
  }
}

//=====================================================================
print "\n MySQL SERVER:\n\n";
print "   MySQL port             = ". get_mysql_port()."\n";
print "   MySQL executable name  = ". get_mysql_exe()."\n";
print "   MySQL service name     = ".USC_MYSQL_SERVICE_NAME."\n";

// Check was PC switched off while server running
if(!mysql_running() && get_mysql_tracker() == "program"){
  set_mysql_tracker('free');
}

if(mysql_running() && get_mysql_tracker() != "free"){
  print "   MySQL run status       = Running\n";

  if(get_mysql_tracker() == "program"){
   print "   MySQL install status   = Installed as a standard program\n";
  }
  if(get_mysql_tracker() == "service"){
    print "   MySQL install status   = Installed as a service\n";
  }
}
else{
print "   MySQL run status       = Not running\n";
 if(get_mysql_tracker() == "free"){
  print "   MySQL install status   = Free to install\n";
 }
 if(get_mysql_tracker() != "free"){
  print "   MySQL install status   = Undefined!\n";
 }
}

//===========================================================================
print "\n PORT STATUS:\n\n";

//=== Apache Ports

if(apache_running() && get_apache_tracker() != "free"){
print "   Apache port     = ". get_apache_port()." In use by this server\n";
}

if(!apache_running() && get_apache_tracker() != "free"){
print "   Apache port     = ". get_apache_port()." Undefined!\n";
}

if(port_in_use(get_apache_port()) && get_apache_tracker() == "free"){
print "   Apache port     = ". get_apache_port()." In use by another program.\n";
}


if(!port_in_use(get_apache_port()) && get_apache_tracker() == "free"){
print "   Apache port     = ". get_apache_port()." Is free to use\n";
}

//=== SSL port
if(apache_running() && get_apache_tracker() != "free" && ssl_enabled()){
print "   Apache SSL port = ". get_apache_ssl_port()." In use by this server\n";
}

if(!apache_running() && get_apache_tracker() != "free" && ssl_enabled()){
print "   Apache SSL port = ". get_apache_ssl_port()." Undefined!\n";
}

if(!apache_running() && get_apache_tracker() != "free" && !ssl_enabled()){
print "   Apache SSL port = ". get_apache_ssl_port()." Undefined!\n";
}

if(apache_running() && get_apache_tracker() != "free" && !ssl_enabled()){
print "   Apache SSL port = ". get_apache_ssl_port()." SSL Not enabled free to use\n";
}

if(port_in_use(get_apache_ssl_port()) && get_apache_tracker() == "free"){
print "   Apache SSL port = ". get_apache_ssl_port()." In use by another program.\n";
}
if(!port_in_use(get_apache_ssl_port()) && get_apache_tracker() == "free"){
print "   Apache SSL port = ". get_apache_ssl_port()." Is free to use\n";
}

//=== MySQL port

if(mysql_running()  && get_mysql_tracker() != "free"){
print "   MySQL port      = ". get_mysql_port()." In use by this server\n";
}

if(!mysql_running()  && get_mysql_tracker() != "free"){
print "   MySQL port      = ". get_mysql_port()." Undefined!\n";
}


if(port_in_use(get_mysql_port()) && get_mysql_tracker() == "free"){
print "   MySQL port      = ". get_mysql_port()." In use by another program.\n";
}
if(!port_in_use(get_mysql_port()) &&  get_mysql_tracker() == "free"){
print "   MySQL port      = ". get_mysql_port()." Is free to use.\n";
}

print "\n\n";
?>