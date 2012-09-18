REM ######################
REM ## Compile Ampricot ##
REM ######################


"C:\Program Files\7-Zip\7z.exe" x "D:\binary\apache\httpd-%1-bin-win32-vc9.7z" -o"D:\workspace\fruitechlabs\ampricot\input\core\bin\apache\"
"C:\Program Files\7-Zip\7z.exe" x "D:\binary\mysql\mysql-%2-bin-win32-vc9.7z" -o"D:\workspace\fruitechlabs\ampricot\input\core\bin\mysql\"
"C:\Program Files\7-Zip\7z.exe" x "D:\binary\php\php-%3-bin-win32-vc9.7z" -o"D:\workspace\fruitechlabs\ampricot\input\core\bin\php\"
"C:\Program Files\7-Zip\7z.exe" x "D:\script\php\phpmyadmin\phpMyAdmin-%4-all-languages.7z" -o"D:\workspace\fruitechlabs\ampricot\input\core\app\"


rename "D:\workspace\fruitechlabs\ampricot\input\core\bin\apache\httpd-%1-bin-win32-vc9" "apache-%1"
rename "D:\workspace\fruitechlabs\ampricot\input\core\bin\mysql\mysql-%2-bin-win32-vc9" "mysql-%2"
rename "D:\workspace\fruitechlabs\ampricot\input\core\bin\php\php-%3-bin-win32-vc9" "php-%3"
rename "D:\workspace\fruitechlabs\ampricot\input\core\app\phpMyAdmin-%4-all-languages" "phpmyadmin-%4"

xcopy "D:\script\php\adminer\adminer-%5.php" "D:\workspace\fruitechlabs\ampricot\input\core\app\adminer-%5\"
rename "D:\workspace\fruitechlabs\ampricot\input\core\app\adminer-%5\adminer-%5.php" "index.php"


xcopy /s "D:\workspace\fruitechlabs\ampricot\input\front\conf\apache\apache-2.4\*" "D:\workspace\fruitechlabs\ampricot\input\front\conf\apache\apache-%1\"
xcopy /s "D:\workspace\fruitechlabs\ampricot\input\front\conf\mysql\mysql-5.5\*" "D:\workspace\fruitechlabs\ampricot\input\front\conf\mysql\mysql-%2\"
xcopy /s "D:\workspace\fruitechlabs\ampricot\input\front\conf\php\php-5.4\*" "D:\workspace\fruitechlabs\ampricot\input\front\conf\php\php-%3\"
xcopy "D:\toolbox\fruitechlabs\ampricot\assets\vcr9.exe" "D:\workspace\fruitechlabs\ampricot\input\core\inc\"


CALL "C:\Program Files\NSIS\Unicode\makensis.exe" "D:\workspace\fruitechlabs\ampricot\script\setup.nsi"


rmdir /S /Q "D:\workspace\fruitechlabs\ampricot\input\core\bin\","D:\workspace\fruitechlabs\ampricot\input\core\app\"
rmdir /S /Q "D:\workspace\fruitechlabs\ampricot\input\front\conf\apache\apache-%1\","D:\workspace\fruitechlabs\ampricot\input\front\conf\mysql\mysql-%2\","D:\workspace\fruitechlabs\ampricot\input\front\conf\php\php-%3\"
del "D:\workspace\fruitechlabs\ampricot\input\core\inc\vcr9.exe"