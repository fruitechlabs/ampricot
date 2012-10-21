REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM #################
REM ## Compile PHP ##
REM #################
REM ## Input Arguments:
REM ## - %1: PHP version
REM ## Ex. 5.4.8
REM ####################


REM # Copy Binary Tools Sources
xcopy /s "D:\source\php\tools\bin\*" "C:\source\php\"


REM # Set PHP Vars
cd "C:\source\php\"
CALL "C:\source\php\bin\phpsdk_setvars.bat"
CALL "C:\source\php\bin\phpsdk_buildtree.bat" php5dev


REM # Extract PHP & Dependencies Sources
"C:\Program Files\7-Zip\7z.exe" x "D:\source\php\php-%1.7z" -o"C:\source\php\php5dev\vc9\x86\"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\php\tools\deps-5.4-vc9-x86.7z" -o"C:\source\php\php5dev\vc9\x86\deps\"
xcopy /s "D:\source\php\tools\source\ext\*" "C:\source\php\php5dev\vc9\x86\php-%1\ext\"


REM # Delete apache2filter/apache2handler old files
del "C:\source\php\php5dev\vc9\x86\php-%1\sapi\apache2filter\config.w32","C:\source\php\php5dev\vc9\x86\php-%1\sapi\apache2handler\config.w32","C:\source\php\php5dev\vc9\x86\php-%1\sapi\apache2filter\sapi_apache2.c","C:\source\php\php5dev\vc9\x86\php-%1\sapi\apache2handler\sapi_apache2.c"


REM # Copy apache2filter/apache2handler  files
xcopy "D:\source\php\tools\source\apache24\sapi\apache2filter\5.4\*" "C:\source\php\php5dev\vc9\x86\php-%1\sapi\apache2filter\"
xcopy "D:\source\php\tools\source\apache24\sapi\apache2handler\5.4\*" "C:\source\php\php5dev\vc9\x86\php-%1\sapi\apache2handler\"
xcopy "D:\source\php\tools\source\apache24\include\*" "C:\source\php\php5dev\vc9\x86\deps\include\apache2_4\"
xcopy "D:\source\php\tools\source\apache24\lib\*" "C:\source\php\php5dev\vc9\x86\deps\lib\apache2_4\"


REM # Compile PHP
cd "C:\source\php\php5dev\vc9\x86\php-%1\"
CALL "C:\source\php\php5dev\vc9\x86\php-%1\buildconf"
CALL "C:\source\php\php5dev\vc9\x86\php-%1\configure" "--enable-snapshot-build" "--disable-static-analyze" "--enable-debug-pack" "--enable-apc-debug=no" "--with-oci8=C:\source\php\php5dev\vc9\x86\deps\oci,shared" "--with-oci8-11g=C:\source\php\php5dev\vc9\x86\deps\oci,shared" "--with-pdo-oci=C:\source\php\php5dev\vc9\x86\deps\oci,shared"
nmake snap


REM # Package PHP
"C:\Program Files\7-Zip\7z.exe" x "C:\source\php\php5dev\vc9\x86\php-%1\Release_TS\php-%1-Win32-VC9-x86.zip" -o"C:\binary\php\php-%1-bin-win32-vc9\"
"C:\Program Files\7-Zip\7z.exe" x "C:\source\php\php5dev\vc9\x86\php-%1\Release_TS\php-debug-pack-%1-Win32-VC9-x86.zip" -o"C:\binary\php\php-%1-debug-win32-vc9\"
"C:\Program Files\7-Zip\7z.exe" x "C:\source\php\php5dev\vc9\x86\php-%1\Release_TS\php-devel-pack-%1-Win32-VC9-x86.zip" -o"C:\binary\php\php-%1-devel-win32-vc9\"
"C:\Program Files\7-Zip\7z.exe" x "C:\source\php\php5dev\vc9\x86\php-%1\Release_TS\php-test-pack-%1.zip" -o"C:\binary\php\php-%1-test-win32-vc9\"
xcopy /s "D:\source\php\tools\compiled\5.x\*" "C:\binary\php\php-%1-bin-win32-vc9\"
xcopy /s "D:\source\php\tools\compiled\5.4\*" "C:\binary\php\php-%1-bin-win32-vc9\"
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\php\php-%1-bin-win32-vc9.7z" "C:\binary\php\php-%1-bin-win32-vc9\"
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\php\debug\php-%1-debug-win32-vc9.7z" "C:\binary\php\php-%1-debug-win32-vc9\"
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\php\devel\php-%1-devel-win32-vc9.7z" "C:\binary\php\php-%1-devel-win32-vc9\"
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\php\test\php-%1-test-win32-vc9.7z" "C:\binary\php\php-%1-test-win32-vc9\"


REM # Clean workspace
cd "C:\"
rmdir /s /q "C:\source\php\","C:\binary\php\"
