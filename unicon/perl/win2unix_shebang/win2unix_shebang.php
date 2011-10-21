<?php
/*
###############################################################################
# Name: win2uni.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 29-6-2009
# Converts 
# Unpdates shebang for Unix
# V1.1 2-2-2010
# Comment: Now uses constants from config.inc.php
###############################################################################
*/
#error_reporting(0);  // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../../main/includes/config.inc.php";
include_once "../../main/includes/functions.php";

run_location_tracker();   // Have servers moved update configuration accordingly

//=== Update shebang in all files in folder cgi-bin and sub-folders ===========

  print "\n ============= End of line and shebang update =============\n\n";
if (perl_installed()){

  //Copy all fies to new folder and update end of lines
  recursive_copy(US_BASE_F.'/cgi-bin',US_BASE_F.'/cgi-bin-unix'); // Copy folder tree before conversion  

  $start_dir = US_BASE_F.'/cgi-bin-unix';                   // starting folder
  $file_type = '/(\.pl|\.cgi)/';                            // list file types to convert
  $search_str  = '/\r/' ;                                   // string to search for
  $replace_str = "" ;                                       // replace string              
  recursive_search_replace($start_dir,$file_type,$search_str,$replace_str); // replace

  //Update shebang to Unix

  $start_dir   = US_BASE_F."/cgi-bin-unix";  // Unix Perl folder
  $file_type   = '/(\.pl|\.cgi)/' ;          // List of file types
  $search_str  = "/^#!.*/";                  // Old shebang
  $replace_str = "#!"."/usr/bin/perl";       // New shebang

 recursive_search_replace($start_dir,$file_type,$search_str,$replace_str);

 print " New folder created UniServer/cgi-bin-unix\n";
 print " Copied all folders and files from cgi-bin to cgi-bin-unix\n";
 print " Updated end of lines to Unix format\n";
 print " Updated shebang in files: *.pl and *.cgi to Unix\n\n";
}
else{
 print " No action taken!\n\n";
 print " Perl not installed\n\n";
}
exit;
?>