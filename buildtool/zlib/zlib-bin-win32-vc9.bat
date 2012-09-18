REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM ##################
REM ## Compile Zlib ##
REM ##################


REM # Copy & Extract Files
"C:\Program Files\7-Zip\7z.exe" x "D:\source\zlib\zlib-%1.7z" -o"C:\source\zlib\"


REM # Compile zlib
cd "C:\source\zlib\zlib-%1\"
nmake -f "C:\source\zlib\zlib-%1\win32\Makefile.msc" LOC="-DASMV -DASMINF" OBJA="inffas32.obj match686.obj"
mt -manifest zlib1.dll.manifest -outputresource:zlib1.dll;2

REM # Copy Binary Zlib
xcopy "C:\source\zlib\zlib-%1\ChangeLog" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\FAQ" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\README" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\zdll.lib" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\zlib.3.pdf" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\zlib.lib" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\zlib.pdb" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\zlib1.dll" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"
xcopy "C:\source\zlib\zlib-%1\zlib1.pdb" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"

REM # Package Binary Zlib
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\zlib\zlib-%1-bin-win32-vc9.7z" "C:\binary\zlib\zlib-%1-bin-win32-vc9\"


REM # Clean workspace
cd "C:\"
rmdir /S /Q "C:\source\zlib\","C:\binary\zlib\"
