REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM ###################
REM ## Compile MySQL ##
REM ###################
REM ## Input Arguments:
REM ## - %1: MySQL version
REM ## Ex. 5.5.28
REM ####################


REM # Copy Sources
"C:\Program Files\7-Zip\7z.exe" x "D:\source\mysql\mysql-%1.7z" -o"C:\source\mysql\"


REM # Compile MySQL
mkdir "C:\source\mysql\mysql-%1\build2\"
cd "C:\source\mysql\mysql-%1\build2\"
cmake "C:/source/mysql/mysql-%1/" -G "Visual Studio 9 2008" -DCMAKE_INSTALL_PREFIX:PATH="C:/binary/mysql/mysql-%1-bin-win32-vc9/" -DENABLED_PROFILING:BOOL=ON -DENABLE_DEBUG_SYNC:BOOL=ON -DENABLE_GCOV:BOOL=OFF -DINSTALL_LAYOUT:STRING=STANDALONE -DMYSQL_DATADIR:PATH="C:/source/mysql/mysql-%1/MySQL Server 5.5/data" -DMYSQL_MAINTAINER_MODE:BOOL=OFF -DWITH_ARCHIVE_STORAGE_ENGINE:BOOL=ON -DWITH_BLACKHOLE_STORAGE_ENGINE:BOOL=ON -DWITH_DEBUG:BOOL=OFF -DWITH_EMBEDDED_SERVER:BOOL=OFF -DWITH_EXTRA_CHARSETS:STRING=all -DWITH_FEDERATED_STORAGE_ENGINE:BOOL=ON -DWITH_INNOBASE_STORAGE_ENGINE:BOOL=ON -DWITH_PARTITION_STORAGE_ENGINE:BOOL=ON -DWITH_PERFSCHEMA_STORAGE_ENGINE:BOOL=ON -DWITH_SSL:STRING=bundled -DWITH_UNIT_TESTS:BOOL=OFF -DWITH_ZLIB:STRING=bundled
Devenv "C:\source\mysql\mysql-%1\build2\MySQL.sln" /build "RelWithDebInfo|Win32" /project "INSTALL"


REM # Package MySQL
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\mysql\mysql-%1-bin-win32-vc9.7z" "C:\binary\mysql\mysql-%1-bin-win32-vc9\"


REM # Clean workspace
cd "C:\"
rmdir /S /Q "C:\source\mysql\","C:\binary\mysql\"
