REM # Run "Visual Studio 2008 Command Prompt" & set the correct environment
CALL "C:\Program Files\Microsoft Visual Studio 9.0\VC\bin\vcvars32.bat"

REM #####################
REM ## Compile OpenSSL ##
REM #####################


REM # Copy & Extract Files
"C:\Program Files\7-Zip\7z.exe" x "D:\source\openssl\openssl-%1.7z" -o"C:\source\openssl\"


REM # Compile openssl
cd "C:\source\openssl\openssl-%1\"
perl "C:\source\openssl\openssl-%1\Configure" VC-WIN32 enable-camellia disable-idea
CALL "C:\source\openssl\openssl-%1\ms\do_nasm.bat"
nmake /f "C:\source\openssl\openssl-%1\ms\ntdll.mak"

REM # Copy Binary OpenSSL
xcopy "C:\source\openssl\openssl-%1\out32dll\*.exe" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"
xcopy "C:\source\openssl\openssl-%1\out32dll\*.dll" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"
xcopy "C:\source\openssl\openssl-%1\out32dll\*.lib" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"
xcopy "C:\source\openssl\openssl-%1\CHANGES" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"
xcopy "C:\source\openssl\openssl-%1\LICENSE" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"
xcopy "C:\source\openssl\openssl-%1\NEWS" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"
xcopy "C:\source\openssl\openssl-%1\README" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"


REM # Package Binary OpenSSL
"C:\Program Files\7-Zip\7z.exe" a -t7z "D:\binary\openssl\openssl-%1-bin-win32-vc9.7z" "C:\binary\openssl\openssl-%1-bin-win32-vc9\"


REM # Clean workspace
cd "C:\"
rmdir /S /Q "C:\source\openssl\","C:\binary\openssl\"
