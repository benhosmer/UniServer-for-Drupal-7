<?php
/*
###############################################################################
# Name: ssl_gen.php
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric)
# Web: http://www.uniformserver.com
# V1.0 27-8-2009
# V1.1 28-2-2010
# Comment: Now uses constants from config.inc.php
#          Create folders if deleted by a user!
# V1.2 14-6-2010
# Comment: Uses functions from PHP php_openssl.dll 
#          Removes duplicated files hence size reduction 
# V1.3 28-1-2011
# Updated line openssl_x509_export_to_file
###############################################################################
*/
//error_reporting(0); // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../main/includes/config.inc.php";
include_once "../main/includes/functions.php";

run_location_tracker();   // Have servers moved if moved
                          // update configuration accordingly
print "\n";

//=== Check folders exist if not create =======================================
$crt_folder = US_USR."/local/apache2/conf/ssl.crt";
$key_folder = US_USR."/local/apache2/conf/ssl.key";

if(!is_dir($crt_folder )) {
 mkdir($crt_folder ) or die("Failed to create main destination folder");
}
if(!is_dir($key_folder)) {
 mkdir($key_folder) or die("Failed to create destination sub-folder");
}

//=== Check for CA control ====================================================
if (is_file(USF_CERT_CA)){
  print " Checking for CA ...\n\n";
  print " It looks like you are using your own CA to avoid overwriting your\n";
  print " current server certificate and key this script has terminated.\n\n";
  print " To create a new server certificate and key use the CA script.\n\n";
  exit;
}

//=== Check for Server certificate ============================================
if (is_file(USF_CERT)){
  print " Checking for Server certificate ...\n\n";
  print " You have already created a server certificate and key!\n";
  print " Are you sure you want to delete and create new ones?\n\n";

  $user_inut = prompt_user(" Create new server cert and key Yes or No ","Yes");

  if($user_inut != "Yes"){
    print "\n\n";
    exit;
  }
}
//===
print " \n ##############################################################################\n";
print " #                                                                            #\n";
print " # Uniform Server: Certificate and Key generator                              #\n";
print " #                                                                            #\n";
print " #----------------------------------------------------------------------------#\n";
print " # This script creates and installs a new self-signed certificate and key.    #\n";
print " #                                                                            #\n";
print " # 1) CN Common Name. Change this to your full domain name e.g. www.fred.com  #\n";
print " #    If you do not have a domain name use the default by pressing eneter.    #\n";
print " #                                                                            #\n";
print " # 2) To change any of the three defaults edit file:                          #\n";
print " #    unicon/key_cert_gen/ssl_gen.php                                         #\n";
print " #    search for the edit section and replace accordingly.                    #\n";
print " #                                                                            #\n";
print " #----------------------------------------------------------------------------#\n\n";


// Get user input
//********* Edit defaults *****************************************************

$str1 = prompt_user("  CN Common Name. Your full domain name ", "localhost");
$str2 = prompt_user("  OU Organization Unit (eg, section)  ", "Secure demo");
$str3 = prompt_user("  O  Organization Name (eg, company)    ", "UniServer");
print "\n ";

//********* Do not Edit below this line ***************************************

//== Determine path
$ssl_path = getcwd();
$ssl_path = preg_replace('/\\\/','/', $ssl_path);  // Replace \ with /

//== Create a configuration array containing path to openssl.cnf 
$config = array(
'config' => "$ssl_path/openssl.cnf"
);

//=== Create data array for certificate information
$dn = array(
   "countryName"            => "UK",
   "stateOrProvinceName"    => "Cambridge",
   "localityName"           => "Cambs",
   "organizationName"       => $str3,
   "organizationalUnitName" => $str2,
   "commonName"             => $str1,
   "emailAddress"           => "me@example.com"
);

print "Creating a private key and signing request\n\n";

//=== Generate a new private (and public) key pair
$privkey = openssl_pkey_new($config);

//=== Generate a certificate signing request
$csr = openssl_csr_new($dn, $privkey, $config);

//== Create a self-signed certificate valid for 3650 days
$sscert = openssl_csr_sign($csr, null, $privkey, 3650, $config);

//== Create key file. Note no passphrase
openssl_pkey_export_to_file($privkey,"server.key",NULL, $config);

//== Create server certificate 
openssl_x509_export_to_file($sscert,  "server.crt",  TRUE );

//== Create a signing request file 
openssl_csr_export_to_file($csr, "server.csr");

//== Copy files to server
copy("server.crt", US_APACHE."/conf/ssl.crt/server.crt"); 
copy("server.key", US_APACHE."/conf/ssl.key/server.key"); 

//== Delete files
unlink("server.crt");  
unlink("server.key");

//=== Copying new certificate and key to Server
print " #----------------------------------------------------------------------------#\n";
print " # Copying new certificate and key to Server                                  #\n";
print " # Location:                                                                  #\n";
print " #           Certificate:  usr/local/apache2/conf/ssl.crt/server.crt          #\n";
print " #           Key:          usr/local/apache2/conf/ssl.key/server.key          #\n";
print " #                                                                            #\n";
print " # Certificate Signing Request                                                #\n";
print " # Location:                                                                  #\n";
print " #           Certificate:  unicon/key_cert_gen/server.csr                     #\n";
print " #----------------------------------------------------------------------------#\n";
print " #                                                                            #\n";
print " # You must now restart the servers to enable the new configuration.          #\n";
print " #                                                                            #\n";
print " ##############################################################################\n\n";

//== Enable SSL in Apache's config file \usr\local\apache2\conf\httpd.conf
 $s_str = "#LoadModule ssl_module modules/mod_ssl.so";      // search for
 $s_str = preg_quote($s_str,'/');                           // Convert to regex format
 $s_str = '/'.$s_str.'/';                                   // Create regex pattern
 $replace_str = "LoadModule ssl_module modules/mod_ssl.so"; // replace with

 file_search_replace(USF_APACHE_CNF,$s_str,$replace_str);  // Update Apache cnf

exit;
?>