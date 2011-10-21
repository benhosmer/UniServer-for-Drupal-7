@echo off
cls
COLOR B0
mode con:cols=60 lines=12
TITLE UNIFORM SERVER - win2unix + Update shebang 

rem ###################################################
rem # Name: Run.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 29-6-2009
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\..\usr\local\php\php.exe -n  win2unix_shebang.php

rem ### restore original working directory
pause
popd

