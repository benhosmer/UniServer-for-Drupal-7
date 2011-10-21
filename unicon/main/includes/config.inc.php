<?php
/*
###############################################################################
# Name: The Uniform Server Control Configuration 2.0
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric) 
# Web: http://www.uniformserver.com
# V1.1 28-2-2010
# Comment: Updated to use constants and not variables 
###############################################################################
*/

/*
###############################################################################
# User Variables:
# $server_standard - Controls server when run as a standard program 
# $server_service  - Controls server when run as a service 
#
# $server_standard - Options are binary coded as follows:
#                    Bit function
# Apache         1   1 - Run Apache server 0 - no change ignore
# Mysql          2   1 - Run MySQL  server 0 - no change ignore
# Index page     4   1 - Start Index page  0 - no change ignore
# MySQL Console  8   1 - Display console   0 - no change ignore
# Cron          16   1 - Run cron          0 - no change ignore
#
# $server_service  - Options are binary coded as follows:
#                    Bit function
# Apache         1   1 - Run Apache as a service 0 - no change ignore
# Mysql          2   1 - Run MySQL  as a service 0 - no change ignore
# Index page     4   1 - Start Index page        0 - no change ignore
#
# Defaults: 
# $server_standard = 7;
# $server_service  = 7;
###############################################################################
*/

  $server_standard = 7;
  $server_service  = 7;

/* ----- END User Variables service ------------------------------------------- */


/* Path variables - NO BACKSLASH */
// All paths are absolute referenced to folder UniServr (can be renamed) 

$path_array  = explode("\\unicon",dirname(__FILE__));  // Split at folder unicon
$base        = "$path_array[0]";                       // find drive letter and any sub-folders 
$base_f      = preg_replace('/\\\/','/', $base);       // Replace \ with /

$Disk         = substr($base, 0, 2);                   // Disk eg C:
$Drive_letter = substr($base, 0, 1);                   // Disk eg C

//=== MySQL ===
define("US_DB_HOST",            "localhost");          // Set dbhost to 127.0.0.1 for W7 IPV6 issues
                                                       // Original was localhost (can change back)

//=== FOLDERS ===
define("US_BASE",               "$base");             // Uniform server base folder back slash
define("US_BASE_F",             "$base_f");           // Uniform server base folder forward slash

define("US_USR",                "$base_f/usr");       // Apache MySQl PHP folder
define("US_WWW",                "$base_f/www");       // Web root folder
define("US_SSL",                "$base_f/ssl");       // SSL web root folder
define("US_HOME",               "$base_f/home");
define("US_MSMTP",              "$base_f/msmtp");
define("US_PLUGINS",            "$base_f/plugins");
define("US_HTPASSWD",           "$base_f/htpasswd");
define("US_PHP",                 US_USR."/local/php"); 

define("US_APACHE",              US_USR."/local/apache2");       // Apache folder
define("US_APACHE_BIN",          US_USR."/local/apache2/bin");   // Apache binary folder; 
define("US_CGI_BIN",             US_BASE_F."/cgi-bin");          // Top folder

define("US_UNICON",              US_BASE_F."/unicon") ;          // Server control folder
define("US_UNICON_MAIN",         US_BASE_F."/unicon/main");      // Main Server control
define("US_UNICON_TRAY_MENU",    US_BASE_F."/unicon/tray_menu"); // UniTray menu folder
define("US_APANEL_WWW",          US_HOME."/admin/www");          // Apanel WWW folder
define("US_MYSQL_BIN",           US_USR."/local/mysql/bin");     // MySQL  Binary folder
define("US_DB_BASE",             US_BASE_F."/db_backup") ;       // Data base backup folder

//=== FILES ====
//-- Apache
define("USF_APACHE_SYNTAX",      "$base\unicon\main\apache_syntax_check.bat");     // Apachesyntax
define("USF_APACHE_CERT_GEN",    "$base\unicon\key_cert_gen\Run.bat");             // Generate certificate and enable ssl
define("USF_CERT",               US_USR."/local/apache2/conf/ssl.crt/server.crt"); // Server certificate
define("USF_CERT_CA",            US_USR."/local/apache2/conf/ssl.crt/ca.crt");     // CA Server
define("USF_APACHE_ERROR_LOG",   US_APACHE."/logs/error.log");                     // Apache error log
define("USF_APACHE_ACCESS_LOG",  US_APACHE."/logs/access.log");                    // Apache access log
define("USF_APACHE_PID",         US_USR."/local/apache2/logs/httpd.pid");          // Apache PID
define("USF_APACHE_CNF",         US_USR."/local/apache2/conf/httpd.conf");         // Apache configuration
define("USF_APACHE_SSL_CNF",     US_USR."/local/apache2/conf/ssl.conf");           // Apache configuration

//-- MySQL
define("USF_MYSQL_RESTORE_PASS",  "$base\unicon\restore_mysql_password\Run_restore.bat"); // Restore MySQL password
define("USF_MYSQL_INI",           US_USR."/local/mysql/my.ini");           // MySQL configuration
define("USF_SMALL_MY_INI",        US_USR."/local/mysql/small_my.ini");     // MySQL configuration
define("USF_MEDIUM_INI",          US_USR."/local/mysql/medium_my.ini");    // MySQL configuration

define("USF_MYSQL_ERROR_LOG",   US_USR."/local/mysql/data/mysql.err");   //MySQLerror log
define("USF_MYSQL_PID",         US_USR."/local/mysql/data/mysql.pid");   // MtSQL PID

//-- PHP
define("USF_PHP_INI",           US_PHP."/php.ini");                      //PHP configuration
define("USF_PHP_INI_PROD",      US_PHP."/php.ini_production_orion");     //PHP configuration production
define("USF_PHP_INI_DEV",       US_PHP."/php.ini_development_orion");    //PHP configuration development

define("USF_PHP_INI_CLI",         US_PHP."/php-cli.ini");        //PHP configuration CLI
define("USF_PHP_SWITCH_PROD_BAT", "$base\unicon\php_dev_production\Switch_production.bat");  //Switch PHP configuration
define("USF_PHP_SWITCH_DEV_BAT",  "$base\unicon\php_dev_production\Switch_Development.bat"); //Switch PHP configuration

//-- Pear
define("USF_PEAR_CONF",        US_HOME."/admin/www/plugins/pear/pear.conf");                 //Pear configuration

//-- CRON
define("USF_CRON_INI",         US_UNICON_MAIN."/cron.ini");              // Cron configuration file 
define("USF_CRON_LOG",         US_UNICON_MAIN."/cron.log");              // Cron log file 

//-- DtDNS
define("USF_DTDNS_INI",         US_PLUGINS."/dtdns_updater/dtdns.ini");         // DDNS configuration file 
define("USF_DTDNS_LOG",         US_PLUGINS."/dtdns_updater/dtdns.log");         // DtDNS log file 
define("USF_DTDNS_PHP",         US_PLUGINS."/dtdns_updater/dtdns_updater.php"); // DtDNS script 

//-- DB Backup
define("US_DB_DEST",            US_DB_BASE."/db_backup") ;        // Data base backup folder
define("US_DB_TEMP",            US_BASE_F."/tmp/backup_temp") ;   // Temp location for archive creation
define("USF_DB_LOG",            US_DB_BASE."/log.txt") ;          // Data base log file

define("USF_DB_INI",            US_PLUGINS."/db_backup/db_backup.ini"); // DB Backup configuration file 
define("USF_DB_PHP",            US_PLUGINS."/db_backup/db_backup.php"); // DB Backup script enable ogging 

//-- FireFox Portable
define("USF_HOSTS_PAC",        US_BASE_F."/pac/my_hosts.pac"); // Path to hosts pac file

//-- Passwords htaccess
define("USF_APANEL_PASSWORD",  US_HTPASSWD."/home/admin/www/.htpasswd"); // Apanel folderr password file
define("USF_WWW_PASSWORD",     US_HTPASSWD."/www/.htpasswd");            // WWW Root folderr password file
define("USF_SSL_PASSWORD",     US_HTPASSWD."/ssl/.htpasswd");            // SSL Root folderr password file
define("USF_MYSQL_PASSWORD",   US_HOME."/admin/www/mysql_password");     // MySQL password file

define("USF_WWW_HTACCESS",     US_BASE_F."/www/.htaccess");              // WWW Root htaccess file
define("USF_SSL_HTACCESS",     US_BASE_F."/ssl/.htaccess");              // SSL Root htaccess file
define("USF_APANEL_HTACCESS",  US_BASE_F."/home/admin/www/.htaccess");   // Apanel htaccess file

//-- Tracker files
define("USF_APCHE_TRACKER",    US_UNICON_MAIN."/apache_tracker.txt");   // Apache tracker file value: free, service or program
define("USF_MYSQL_TRACKER",    US_UNICON_MAIN."/mysql_tracker.txt");    // MySQL tracker file value: free, service or program
define("USF_LOCATION_TRACKER", US_UNICON_MAIN."/location_tracker.txt"); // Current server ocation top-level folder
define("USF_PHP_TRACKER",      US_UNICON_MAIN."/php_ini_tracker.txt");  // Current php.ini Production or Development
define("USF_CRON_TRACKER",     US_UNICON_MAIN."/cron_tracker.txt");     // Current status of cron run, stop 

//-- MSMTP
define("USF_MSMTP_INI",        US_MSMTP."/msmtprc.ini");               // MSMTP config
define("USF_MSMTP_LOG",        US_MSMTP."/msmtp.log");                 // MSMTP log

//-- Other files
define("USF_REDIRECT1",        US_HOME."/admin/www/redirect.html");  // Open index page in browser
define("USF_REDIRECT2",        US_HOME."/admin/www/redirect2.html"); // Open index page in browser

define("USF_CON_FUNCTIONS",    US_UNICON."/main/includes/functions.php"); // Control functions 
define("USF_PSKILL_EXE",       US_UNICON."/program/pskill.exe");     // Kill process or check process running
define("USF_UNISERV_EXE",      US_UNICON."/program/uniserv.exe");    // Run server hidden
define("USF_PERL_EXE",         US_USR."/bin/perl.exe");              // Check perl installed 
      

/* Variables */ 
$us_version = file_get_contents(US_HOME."/admin/www/includes/.version"); // UniServer version
define("US_VERSION",  "$us_version");     // Uniform Server version
$ip_current = "";                         // Current IP address as seen from Internet

/* Web Variables - NO BACKSLASH */ 

$host            = @$_SERVER["HTTP_HOST"]; // Host
$server          = "http://$host";         // Server - DO NOT CHANGE
$server_path     = US_WWW;                 // $_SERVER["DOCUMENT_ROOT"]
$server_path_ssl = US_SSL;                 // $_SERVER["DOCUMENT_ROOT"]

/* Admin Panel */
$apanel = "$server/apanel";

/* Service names */

define("USC_APACHE_SERVICE_NAME",        "ApacheS1"); // Default ApacheS 1 digit incremented for multi-servers
define("USC_MYSQL_SERVICE_NAME",         "MySQLS1");  // Default MySQLS 1  digit incremented for multi-servers

?>