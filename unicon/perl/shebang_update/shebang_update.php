<?php
/*
###############################################################################
# Name: shebang_update.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 29-6-2009
# V1.1 2-2-2010
# Comment: Now uses constants from config.inc.php
# V1.2 14-6-2010
# Comment: Corrected hungry replace
###############################################################################
*/
#error_reporting(0);  // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../../main/includes/config.inc.php";
include_once "../../main/includes/functions.php";

run_location_tracker();   // Have servers moved update configuration accordingly

//=== Update shebang in all files in folder cgi-bin and sub-folders ===========

  print "\n ============== PERL SHEBANG UPDATE =============\n\n";
if (perl_installed()){

  $start_dir   = US_CGI_BIN;                         // Main Perl folder
  $file_type   = '/(\.pl|\.cgi)/' ;                  // List of file types
  $search_str  = "/^#!.*/";                          // Old shebang
  $replace_str = "#!".US_BASE_F."/usr/bin/perl.exe"; // New shebang

 recursive_search_replace($start_dir,$file_type,$search_str,$replace_str);
 print " Updated shebang in files: *.pl and *.cgi\n\n";
 print " In folder UniServer\cgi-bin and all sub-folders.\n\n";
}
else{
 print " No action taken!\n\n";
 print " Perl not installed\n\n";
}
exit;
?>