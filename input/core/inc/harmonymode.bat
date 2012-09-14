@echo off
for /f "tokens=2 delims==" %%a in ('find "ampricotharmony = " %~dp0ampricot.conf') do set ampricotharmony=%%a
if %ampricotharmony% == "on" (
    for /f "tokens=5 delims= " %%p in ('netstat -a -n -o ^| findstr 0.0:80') do taskkill.exe /f /t /pid %%p
    for /f "tokens=5 delims= " %%p in ('netstat -a -n -o ^| findstr 0.0:443') do taskkill.exe /f /t /pid %%p
    for /f "tokens=5 delims= " %%p in ('netstat -a -n -o ^| findstr 0.0:3306') do taskkill.exe /f /t /pid %%p
)