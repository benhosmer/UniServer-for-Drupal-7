@echo off
cls
COLOR B0
mode con:cols=50 lines=6
TITLE UNIFORM SERVER - Switch PHP ini 

rem ###################################################
rem # Name: Switch_production.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 30-6-2009
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

..\..\usr\local\php\php.exe -n  php_ini_switch.php pro

rem ### restore original working directory
pause
popd

