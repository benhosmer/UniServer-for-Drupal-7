@echo off
cls
COLOR B0
mode con:cols=50 lines=15
TITLE UNIFORM SERVER - Restore MySQL password

rem ###################################################
rem # Name: Run_restore.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 25-6-2009
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -n  restore_mysql_password.php

rem ### restore original working directory

pause
popd
EXIT
