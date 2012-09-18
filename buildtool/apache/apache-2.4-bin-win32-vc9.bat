REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM ####################
REM ## Compile Apache ##
REM ####################


REM # Extract Sources
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apache\httpd-%1.7z" -o"C:\source\apache\"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apr\apr-1.4.6.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\apr-1.4.6" "apr"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apr-iconv\apr-iconv-1.2.1.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\apr-iconv-1.2.1" "apr-iconv"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apr-util\apr-util-1.4.1.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\apr-util-1.4.1" "apr-util"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\openssl\openssl-1.0.1c.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\openssl-1.0.1c" "openssl"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\pcre\pcre-8.31.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\pcre-8.31" "pcre"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\zlib\zlib-1.2.7.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\zlib-1.2.7" "zlib"


REM # Convert Line Ends
cd "C:\source\apache\httpd-%1\"
perl "C:\source\apache\httpd-%1\srclib\apr\build\lineends.pl"
perl "C:\source\apache\httpd-%1\srclib\apr\build\cvtdsp.pl" -2005


REM # Compile Zlib
cd "C:\source\apache\httpd-%1\srclib\zlib\"
nmake -f "C:\source\apache\httpd-%1\srclib\zlib\win32\Makefile.msc" LOC="-DASMV -DASMINF" OBJA="inffas32.obj match686.obj"
mt -manifest zlib1.dll.manifest -outputresource:zlib1.dll;2


REM # Compile Openssl
cd "C:\source\apache\httpd-%1\srclib\openssl\"
perl "C:\source\apache\httpd-%1\srclib\openssl\Configure" VC-WIN32 enable-camellia disable-idea
CALL "C:\source\apache\httpd-%1\srclib\openssl\ms\do_nasm.bat"
nmake /f "C:\source\apache\httpd-%1\srclib\openssl\ms\ntdll.mak"


REM # Compile PCRE
mkdir "C:\source\apache\httpd-%1\srclib\pcre\build\"
cd "C:\source\apache\httpd-%1\srclib\pcre\build\"
"C:\Program Files\CMake 2.8\bin\cmake.exe" "C:/source/apache/httpd-%1/srclib/pcre/" -G "Visual Studio 9 2008"  -DBUILD_SHARED_LIBS:BOOL=ON -DCMAKE_INSTALL_PREFIX:PATH="C:/source/apache/httpd-%1/srclib/pcre/binary/" -DPCRE_BUILD_PCRE16:BOOL=OFF -DPCRE_BUILD_PCRE8:BOOL=ON -DPCRE_BUILD_PCRECPP:BOOL=ON -DPCRE_BUILD_PCREGREP:BOOL=ON -DPCRE_BUILD_TESTS:BOOL=OFF -DPCRE_EBCDIC:BOOL=OFF -DPCRE_NO_RECURSE:BOOL=OFF -DPCRE_REBUILD_CHARTABLES:BOOL=OFF -DPCRE_SHOW_REPORT:BOOL=ON -DPCRE_SUPPORT_BSR_ANYCRLF:BOOL=OFF -DPCRE_SUPPORT_JIT:BOOL=OFF -DPCRE_SUPPORT_PCREGREP_JIT:BOOL=ON -DPCRE_SUPPORT_UNICODE_PROPERTIES:BOOL=ON -DPCRE_SUPPORT_UTF:BOOL=ON
Devenv "C:\source\apache\httpd-%1\srclib\pcre\build\PCRE.sln" /build "RelWithDebInfo|Win32" /project "pcre"
xcopy "C:\source\apache\httpd-%1\srclib\pcre\build\RelWithDebInfo\*" "C:\source\apache\httpd-%1\srclib\pcre\"
xcopy "C:\source\apache\httpd-%1\srclib\pcre\build\pcre.h" "C:\source\apache\httpd-%1\srclib\pcre\"


REM # Copy VS 2008 Project Files
xcopy /S "D:\source\apache\vs\2.4\*" "C:\source\apache\httpd-%1\"
xcopy "C:\source\apache\httpd-%1\modules\ssl\mod_ssl.h" "C:\source\apache\httpd-%1\include\"


REM # Compile Apache
Devenv "C:\source\apache\httpd-%1\Apache.sln" /build "Release|Win32" /project "InstallBin"


REM # Package Apache
rename "C:\Apache24" "httpd-%1-bin-win32-vc9"
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\apache\httpd-%1-bin-win32-vc9.7z" "C:\httpd-%1-bin-win32-vc9\"


REM # Clean workspace
cd "C:\"
rmdir /S /Q "C:\source\apache\","C:\httpd-%1-bin-win32-vc9\"
