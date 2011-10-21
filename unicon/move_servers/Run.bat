@echo off
cls
COLOR B0
mode con:cols=96 lines=41
TITLE UNIFORM SERVER - Move Server 

rem ###################################################
rem # Name: Run.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 14-6-2009
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -n  move_servers.php

rem ### restore original working directory
pause
popd

