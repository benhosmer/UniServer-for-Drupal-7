@echo off
cls
COLOR B0
mode con:cols=65 lines=20
TITLE UNIFORM SERVER - Start UniTray

rem ###################################################
rem # Name: Start_UniTray.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 14-6-2009
rem # Starts The UniTray Icon for the Uniform Server
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php-win.exe -n  start_unitray.php

:pause

rem ### restore original working directory
popd
EXIT