@echo off
for /f "tokens=2 delims==" %%a in ('find "ampricotcleanonexit = " %~dp0ampricot.conf') do set ampricotcleanonexit=%%a
if %ampricotcleanonexit% == "on" (
    del /f /q /s "@AMPRICOTINSTALLDIRROOT@/front/tmp/*.*"
)