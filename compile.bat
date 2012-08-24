REM #############################################
REM ## Ampricot: Prepare Workspace and Compile ##
REM #############################################


"C:\Program Files\7-Zip\7z.exe" x "D:\binary\apache\apache-2.4.2-bin-win32-vc9.tar.gz" -o"D:\workspace\tmp\apache\"
"C:\Program Files\7-Zip\7z.exe" x "D:\binary\mysql\mysql-5.5.27-bin-win32-vc9.tar.gz" -o"D:\workspace\tmp\mysql\"
"C:\Program Files\7-Zip\7z.exe" x "D:\binary\php\php-5.4.6-bin-win32-vc9.tar.gz" -o"D:\workspace\tmp\php\"
"C:\Program Files\7-Zip\7z.exe" x "D:\script\php\phpmyadmin\phpMyAdmin-3.5.2.2-all-languages.tar.gz" -o"D:\workspace\tmp\app\"


"C:\Program Files\7-Zip\7z.exe" x "D:\workspace\tmp\apache\apache-2.4.2-bin-win32-vc9.tar" -o"D:\workspace\fruitechlabs\ampricot\input\core\bin\apache\"
"C:\Program Files\7-Zip\7z.exe" x "D:\workspace\tmp\mysql\mysql-5.5.27-bin-win32-vc9.tar" -o"D:\workspace\fruitechlabs\ampricot\input\core\bin\mysql\"
"C:\Program Files\7-Zip\7z.exe" x "D:\workspace\tmp\php\php-5.4.6-bin-win32-vc9.tar" -o"D:\workspace\fruitechlabs\ampricot\input\core\bin\php\"
"C:\Program Files\7-Zip\7z.exe" x "D:\workspace\tmp\app\phpMyAdmin-3.5.2.2-all-languages.tar" -o"D:\workspace\fruitechlabs\ampricot\input\core\app\"


rename "D:\workspace\fruitechlabs\ampricot\input\core\bin\apache\apache-2.4.2-bin-win32-vc9" "apache-2.4.2"
rename "D:\workspace\fruitechlabs\ampricot\input\core\bin\mysql\mysql-5.5.27-bin-win32-vc9" "mysql-5.5.27"
rename "D:\workspace\fruitechlabs\ampricot\input\core\bin\php\php-5.4.6-bin-win32-vc9" "php-5.4.6"
rename "D:\workspace\fruitechlabs\ampricot\input\core\app\phpMyAdmin-3.5.2.2-all-languages" "phpmyadmin-3.5.2.2"

xcopy "D:\script\php\adminer\adminer-3.5.1.php" "D:\workspace\fruitechlabs\ampricot\input\core\app\adminer-3.5.1\"
rename "D:\workspace\fruitechlabs\ampricot\input\core\app\adminer-3.5.1\adminer-3.5.1.php" "index.php"


xcopy /s "D:\workspace\fruitechlabs\ampricot\input\front\conf\apache\apache-2.4.xx\*" "D:\workspace\fruitechlabs\ampricot\input\front\conf\apache\apache-2.4.2\"
xcopy /s "D:\workspace\fruitechlabs\ampricot\input\front\conf\mysql\mysql-5.5.xx\*" "D:\workspace\fruitechlabs\ampricot\input\front\conf\mysql\mysql-5.5.27\"
xcopy /s "D:\workspace\fruitechlabs\ampricot\input\front\conf\php\php-5.4.xx\*" "D:\workspace\fruitechlabs\ampricot\input\front\conf\php\php-5.4.6\"
xcopy "D:\toolbox\fruitechlabs\ampricot\assets\vc9.exe" "D:\workspace\fruitechlabs\ampricot\input\core\inc\"


CALL "C:\Program Files\NSIS\Unicode\makensis.exe" "D:\workspace\fruitechlabs\ampricot\script\setup.nsi"


rmdir /S /Q "D:\workspace\tmp\apache\","D:\workspace\tmp\mysql\","D:\workspace\tmp\php\","D:\workspace\tmp\app\"
rmdir /S /Q "D:\workspace\fruitechlabs\ampricot\input\core\bin\","D:\workspace\fruitechlabs\ampricot\input\core\app\"
rmdir /S /Q "D:\workspace\fruitechlabs\ampricot\input\front\conf\apache\apache-2.4.2\","D:\workspace\fruitechlabs\ampricot\input\front\conf\mysql\mysql-5.5.27\","D:\workspace\fruitechlabs\ampricot\input\front\conf\php\php-5.4.6\"
del "D:\workspace\fruitechlabs\ampricot\input\core\inc\vc9.exe"