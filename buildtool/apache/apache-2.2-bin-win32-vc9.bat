REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM ####################
REM ## Compile Apache ##
REM ####################
REM ## Input Arguments:
REM ## - %1: Apache version
REM ## - %2: Apache APR version
REM ## - %3: Apache APR-ICONV version
REM ## - %4: Apache APR-UTIL version
REM ## - %5: Openssl version
REM ## - %6: Zlib version
REM ## Ex. 2.4.3 1.4.6 1.2.1 1.4.1 1.0.1c 1.2.7
REM ####################


REM # Extract Sources
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apache\httpd-%1.7z" -o"C:\source\apache\"
rmdir /s /q "C:\source\apache\httpd-%1\srclib\apr\","C:\source\apache\httpd-%1\srclib\apr-util\"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apr\apr-%2.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\apr-%2" "apr"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apr-iconv\apr-iconv-%3.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\apr-iconv-%3" "apr-iconv"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\apr-util\apr-util-%4.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\apr-util-%4" "apr-util"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\openssl\openssl-%5.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\openssl-%5" "openssl"
"C:\Program Files\7-Zip\7z.exe" x "D:\source\zlib\zlib-%6.7z" -o"C:\source\apache\httpd-%1\srclib\"
rename "C:\source\apache\httpd-%1\srclib\zlib-%6" "zlib"


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


REM # Copy VS 2008 Project Files
xcopy /S "D:\source\apache\vs\2.2\*" "C:\source\apache\httpd-%1\"
xcopy "C:\source\apache\httpd-%1\modules\ssl\mod_ssl.h" "C:\source\apache\httpd-%1\include\"


REM # Compile Apache
Devenv "C:\source\apache\httpd-%1\Apache.sln" /build "Release|Win32" /project "InstallBin"


REM # Package Apache
rename "C:\Apache22" "apache-%1-bin-win32-vc9"
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\apache\httpd-%1-bin-win32-vc9.7z" "C:\httpd-%1-bin-win32-vc9\"


REM # Clean workspace
cd "C:\"
rmdir /S /Q "C:\source\apache\","C:\binary\apache\","C:\apache-%1-bin-win32-vc9\"
