@echo off
rem ###################################################
rem # Name: Run.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 17-6-2009
rem # Could be first run, check and update location.
rem # Run redirection page
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -n  location_check.php

redirect.html

rem ### restore original working directory

popd
EXIT

