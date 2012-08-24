FOR /F "tokens=5 delims= " %%P IN ('netstat -a -n -o ^| findstr 0.0:80') DO TaskKill.exe /F /T /PID %%P
FOR /F "tokens=5 delims= " %%P IN ('netstat -a -n -o ^| findstr 0.0:443') DO TaskKill.exe /F /T /PID %%P
FOR /F "tokens=5 delims= " %%P IN ('netstat -a -n -o ^| findstr 0.0:3306') DO TaskKill.exe /F /T /PID %%P