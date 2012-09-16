"@AMPRICOTINSTALLDIRCORE@\core\bin\apache\apache-@AMPRICOTVERSIONAPACHE@\bin\httpd.exe" -k install -n AmpricotApache
sc.exe config AmpricotApache start= demand