@echo off
cls
COLOR B0
rem mode con:cols=96 lines=41
TITLE UNIFORM SERVER - Pre-Check 

rem ###################################################
rem # Name: Run_pre_check.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 10-9-200
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -n  pre_check.php

rem ### restore original working directory
pause
popd
exit


