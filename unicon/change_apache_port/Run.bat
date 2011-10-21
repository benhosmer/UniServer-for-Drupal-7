@echo off
cls
COLOR B0
mode con:cols=53 lines=20
TITLE UNIFORM SERVER - Change Apache Port 

rem ###################################################
rem # Name: Run.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 21-5-2011
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -n  change_apache_port.php

rem ### restore original working directory
pause
popd

