REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM ##################
REM ## Compile PCRE ##
REM ##################


REM # Copy & Extract Files
"C:\Program Files\7-Zip\7z.exe" x "D:\source\pcre\pcre-%1.7z" -o"C:\source\pcre\"


REM # Compile PCRE
mkdir "C:\source\pcre\pcre-%1\build\"
cd "C:\source\pcre\pcre-%1\build\"
"C:\Program Files\CMake 2.8\bin\cmake.exe" "C:/source/pcre/pcre-%1/" -G "Visual Studio 9 2008"  -DBUILD_SHARED_LIBS:BOOL=ON -DCMAKE_INSTALL_PREFIX:PATH="C:/source/pcre/pcre-%1/binary/" -DPCRE_BUILD_PCRE16:BOOL=OFF -DPCRE_BUILD_PCRE8:BOOL=ON -DPCRE_BUILD_PCRECPP:BOOL=ON -DPCRE_BUILD_PCREGREP:BOOL=ON -DPCRE_BUILD_TESTS:BOOL=OFF -DPCRE_EBCDIC:BOOL=OFF -DPCRE_NO_RECURSE:BOOL=OFF -DPCRE_REBUILD_CHARTABLES:BOOL=OFF -DPCRE_SHOW_REPORT:BOOL=ON -DPCRE_SUPPORT_BSR_ANYCRLF:BOOL=OFF -DPCRE_SUPPORT_JIT:BOOL=OFF -DPCRE_SUPPORT_PCREGREP_JIT:BOOL=ON -DPCRE_SUPPORT_UNICODE_PROPERTIES:BOOL=ON -DPCRE_SUPPORT_UTF:BOOL=ON
Devenv "C:\source\pcre\pcre-%1\build\PCRE.sln" /build "RelWithDebInfo|Win32" /project "pcre"

REM # Copy Binary PCRE
xcopy "C:\source\pcre\pcre-%1\ChangeLog" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"
xcopy "C:\source\pcre\pcre-%1\LICENCE" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"
xcopy "C:\source\pcre\pcre-%1\NEWS" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"
xcopy "C:\source\pcre\pcre-%1\build\RelWithDebInfo\pcre.dll" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"
xcopy "C:\source\pcre\pcre-%1\build\pcre.h" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"
xcopy "C:\source\pcre\pcre-%1\build\RelWithDebInfo\pcre.lib" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"
xcopy "C:\source\pcre\pcre-%1\build\RelWithDebInfo\pcre.pdb" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"
xcopy "C:\source\pcre\pcre-%1\README" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"


REM # Package Binary PCRE
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\pcre\pcre-%1-bin-win32-vc9.7z" "C:\binary\pcre\pcre-%1-bin-win32-vc9\"


REM # Clean workspace
cd "C:\"
rmdir /S /Q "C:\source\pcre\","C:\binary\pcre\"
