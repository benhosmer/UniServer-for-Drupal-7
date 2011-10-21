@echo off
cls
COLOR B0
mode con:cols=65 lines=20
TITLE UNIFORM SERVER - Stop UniTray

rem ###################################################
rem # Name: Stop_Unitray.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 14-6-2009
rem # Stops The UniTray Icon for the Uniform Server
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

@echo off

..\..\usr\local\php\php-win.exe -n  stop_unitray.php

:pause

rem ### restore original working directory
popd
EXIT