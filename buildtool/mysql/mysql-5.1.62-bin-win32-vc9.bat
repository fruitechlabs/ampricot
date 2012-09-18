REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM ###################
REM ## Compile MySQL ##
REM ###################

REM # Copy Sources
xcopy "D:\source\mysql\mysql-5.1.62.tar.gz" "C:\source\mysql\"


REM # Extract Sources
"C:\Program Files\7-Zip\7z.exe" x "C:\source\mysql\mysql-5.1.62.tar.gz" -o"C:\source\mysql\"
"C:\Program Files\7-Zip\7z.exe" x "C:\source\mysql\mysql-5.1.62.tar" -o"C:\source\mysql\"


REM # Compile MySQL
del "C:\source\mysql\mysql-5.1.62\win\configure.js"
xcopy "D:\source\mysql\tools\5.1.x\win\configure.js" "C:\source\mysql\mysql-5.1.62\win\"
cd "C:\source\mysql\mysql-5.1.62\"
CALL "C:\source\mysql\mysql-5.1.62\win\configure.js" WITH_ARCHIVE_STORAGE_ENGINE WITH_BLACKHOLE_STORAGE_ENGINE WITH_FEDERATED_STORAGE_ENGINE WITH_INNOBASE_STORAGE_ENGINE WITH_PARTITION_STORAGE_ENGINE
mkdir "C:\source\mysql\mysql-5.1.62\build2\"
cd "C:\source\mysql\mysql-5.1.62\build2\"
cmake "C:/source/mysql/mysql-5.1.62/" -G "Visual Studio 9 2008" -DCMAKE_INSTALL_PREFIX:PATH="C:/binary/mysql/mysql-5.1.62-bin-win32-vc9/" -DENABLED_PROFILING:BOOL=ON -DENABLE_DEBUG_SYNC:BOOL=ON -DENABLE_GCOV:BOOL=OFF -DINSTALL_LAYOUT:STRING=STANDALONE -DMYSQL_DATADIR:PATH="C:/source/mysql/mysql-5.1.62/MySQL Server 5.5/data" -DMYSQL_MAINTAINER_MODE:BOOL=OFF -DWITH_ARCHIVE_STORAGE_ENGINE:BOOL=ON -DWITH_BLACKHOLE_STORAGE_ENGINE:BOOL=ON -DWITH_DEBUG:BOOL=OFF -DWITH_EMBEDDED_SERVER:BOOL=OFF -DWITH_EXTRA_CHARSETS:STRING=all -DWITH_FEDERATED_STORAGE_ENGINE:BOOL=ON -DWITH_INNOBASE_STORAGE_ENGINE:BOOL=ON -DWITH_PARTITION_STORAGE_ENGINE:BOOL=ON -DWITH_PERFSCHEMA_STORAGE_ENGINE:BOOL=ON -DWITH_SSL:STRING=bundled -DWITH_UNIT_TESTS:BOOL=OFF -DWITH_ZLIB:STRING=bundled
Devenv "C:\source\mysql\mysql-5.1.62\build2\MySQL.sln" /build "Release|Win32" /project "INSTALL"


REM # Package MySQL
"c:\Program Files\7-Zip\7z.exe" a -ttar "C:\binary\mysql\mysql-5.1.62-bin-win32-vc9.tar" "C:\binary\mysql\mysql-5.1.62-bin-win32-vc9\"
"c:\Program Files\7-Zip\7z.exe" a -tgzip "C:\binary\mysql\mysql-5.1.62-bin-win32-vc9.tar.gz" "C:\binary\mysql\mysql-5.1.62-bin-win32-vc9.tar"


REM # Store in place
xcopy "C:\binary\mysql\mysql-5.1.62-bin-win32-vc9.tar.gz" "D:\binary\mysql\"


REM # Clean workspace
cd "C:\"
rmdir /S /Q "C:\source\mysql\","C:\binary\mysql\"
