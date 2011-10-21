@echo off
cls
COLOR B0
mode con:cols=80 lines=39
TITLE UNIFORM SERVER - Certificate and Key generator 

rem ###################################################
rem # Name: Run.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 14-6-2009
rem # V 1.1 14-6-2010 - Added cli.ini for ssl
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -c ..\..\usr\local\php\php-cli.ini  ssl_gen.php

rem ### restore original working directory
pause
popd
EXIT

