@echo off
cls
COLOR B0
mode con:cols=50 lines=10
TITLE UNIFORM SERVER - Update shebang 

rem ###################################################
rem # Name: Run_shebang_update.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 24-6-2009
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\..\usr\local\php\php.exe -n  shebang_update.php

rem ### restore original working directory
pause
popd

