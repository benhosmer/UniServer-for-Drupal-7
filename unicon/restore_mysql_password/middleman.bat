@echo off
cls
TITLE UNIFORM SERVER - Restore MySQL Password

rem ##################################################################
rem # Name: middleman.bat
rem # Location:  plugins\restore_mysql_password
rem # Uses file: plugins\restore_mysql_password\restore.sql
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: MPG (ric)
rem # V 1.0 14-6-2009
rem # This script is very draconian, assumes MySQL server not running
rem # restores password and privilages back to defaults.
rem # Username: root
rem # Password: root 
rem ##################################################################
@echo off
:COLOR B0
:mode con:cols=80 lines=40

rem ## working directory current folder 
pushd %~dp0

IF "%1" == ""     goto :NOTSET 
IF NOT "%1" == "" goto :ISSET 

rem ### This script has been manually run and assumes server not running
:NOTSET 

rem ## get MySQL executable name
FOR /F "tokens=*" %%i in ('dir /B ..\..\usr\local\mysql\bin\mysqld-op*') do SET mysql_exe=%%i

rem ## kill mysql regardless
..\program\pskill.exe %mysql_exe% C
..\program\unidelay.exe 

rem ## set password file to original
(set /p dummy=root)> ..\..\home\admin\www\mysql_password <nul

goto :RESTORE

:ISSET
rem ## restore.php supplies name and will have set password file
set mysql_exe=%1

:RESTORE

rem ## Change working dir to MySQL
CD ..\..\usr\local\mysql\bin

rem ## start mysql server
start %mysql_exe% --skip-grant-tables --user=root

rem ## wait for MySQL server to start
:NOTSTARTED
..\..\..\..\unicon\program\unidelay.exe 
..\..\..\..\unicon\program\pskill.exe %mysql_exe% >nul
if errorlevel 2 goto :NOTSTARTED

rem ## run restore script
mysql < ..\..\..\..\unicon\restore_mysql_password\restore.sql

rem ## Delay and then kill server
..\..\..\..\unicon\program\unidelay.exe
..\..\..\..\unicon\program\pskill.exe %mysql_exe% C

rem == Wait for MySQL to stop 
:RUNNING2
..\..\..\..\unicon\program\unidelay.exe 
..\..\..\..\unicon\program\pskill.exe %mysql_exe%
if errorlevel 2 goto :NOTRUNNING2

:END
rem restore original working directory
popd
EXIT




