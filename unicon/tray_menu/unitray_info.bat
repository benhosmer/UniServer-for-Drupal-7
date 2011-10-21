@echo off
cls
COLOR B0
TITLE UNIFORM SERVER - Server Information test

rem ###################################################
rem # Name: unitray_info.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 3-12-2009
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -n unitray_info.php 1

pause

rem ### restore original working directory
popd
EXIT