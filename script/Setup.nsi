# Installer attributes
Name "Ampricot"

# General Symbol Definitions
!define AMPRICOTID "ampricot"
!define AMPRICOTNAME "Ampricot"
!define AMPRICOTVERSIONCOREMAJOR "1"
!define AMPRICOTVERSIONCOREMINOR "1"
!define AMPRICOTVERSIONCORE "1.1.0"
!define AMPRICOTVERSIONAPACHE "2.4.3"
!define AMPRICOTVERSIONMYSQL "5.5.28"
!define AMPRICOTVERSIONPHP "5.4.8"
!define AMPRICOTVERSIONPHPMYADMIN "3.5.3"
!define AMPRICOTVERSIONADMINER "3.6.1"
!define AMPRICOTCOMPANY "FruiTech Labs"
!define AMPRICOTURLTEXT "www.ampricot.com"
!define AMPRICOTURL "http://www.ampricot.com"
!define AMPRICOTURLABOUT "http://www.ampricot.com/about/"
!define AMPRICOTURLUPDATE "http://www.ampricot.com/news/"
!define AMPRICOTURLHELP "http://www.ampricot.com/support/"
!define AMPRICOTLAUNCHER "ampricot.exe"
!define AMPRICOTINSTALLER "ampricot-stack-${AMPRICOTVERSIONCORE}.exe"
!define AMPRICOTUNINSTALLER "uninstall.exe"
!define AMPRICOTREGKEY "SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\${AMPRICOTNAME}"
!define MUI_LANGDLL_REGISTRY_ROOT "HKLM"
!define MUI_LANGDLL_REGISTRY_KEY "${AMPRICOTREGKEY}"
!define MUI_LANGDLL_REGISTRY_VALUENAME "InstallerLanguage"

# Installer attributes
RequestExecutionLevel highest
BrandingText " "
OutFile "..\..\..\..\binary\ampricot\${AMPRICOTINSTALLER}"
CRCCheck on
XPStyle on
ShowInstDetails hide
ShowUninstDetails hide
InstallDirRegKey HKLM "${AMPRICOTREGKEY}" "InstallLocation"

# File info
VIProductVersion "${AMPRICOTVERSIONCORE}"
VIAddVersionKey ProductName "Ampricot"
VIAddVersionKey ProductVersion "${AMPRICOTVERSIONCORE}"
VIAddVersionKey CompanyName "${AMPRICOTCOMPANY}"
VIAddVersionKey FileVersion "${AMPRICOTVERSIONCORE}"
VIAddVersionKey FileDescription "Apache ${AMPRICOTVERSIONAPACHE}, MySQL ${AMPRICOTVERSIONMYSQL}, PHP ${AMPRICOTVERSIONPHP}"
VIAddVersionKey LegalCopyright "Copyright (c) 2012 ${AMPRICOTCOMPANY}."

# Best Compression
SetCompress Auto
SetCompressor /SOLID lzma
SetCompressorDictSize 32
SetDatablockOptimize On

# Custom Variables
Var WINDRIVE

# Apache Vars
Var mui.ApacheOptsPage
Var mui.ApacheOptsPage.ServerName.LBL
Var mui.ApacheOptsPage.ServerName.TXT
Var mui.ApacheOptsPage.ServerName.VAL
Var mui.ApacheOptsPage.AdminEmail.LBL
Var mui.ApacheOptsPage.AdminEmail.TXT
Var mui.ApacheOptsPage.AdminEmail.VAL
Var mui.ApacheOptsPage.ServerPortHTTP.LBL
Var mui.ApacheOptsPage.ServerPortHTTP.TXT
Var mui.ApacheOptsPage.ServerPortHTTP.VAL
Var mui.ApacheOptsPage.ServerPortHTTPS.LBL
Var mui.ApacheOptsPage.ServerPortHTTPS.TXT
Var mui.ApacheOptsPage.ServerPortHTTPS.VAL

# MySQL Vars
Var mui.MySQLOptsPage
Var mui.MySQLOptsPage.RootPass.LBL
Var mui.MySQLOptsPage.RootPass.TXT
Var mui.MySQLOptsPage.RootPass.VAL
Var mui.MySQLOptsPage.ConfirmPass.LBL
Var mui.MySQLOptsPage.ConfirmPass.TXT
Var mui.MySQLOptsPage.ConfirmPass.VAL
Var mui.MySQLOptsPage.ServerPort.LBL
Var mui.MySQLOptsPage.ServerPort.TXT
Var mui.MySQLOptsPage.ServerPort.VAL

# Refreshing Windows Defines
!define SHCNE_ASSOCCHANGED 0x8000000
!define SHCNF_IDLIST 0

# Included files
!include Sections.nsh
!include MUI2.nsh
!include Locate.nsh
!include EnvVarUpdate.nsh
!include WinMessages.nsh
!include FileFunc.nsh
!include WinVer.nsh
!include Core.nsh
!include Apache.nsh
!include MySQL.nsh

# Reserve Files
# ---------------
# If you are using solid compression, files that are required before
# the actual installation should be stored first in the data block,
# because this will make your installer start faster.
ReserveFile "${NSISDIR}\Plugins\System.dll"
!insertmacro MUI_RESERVEFILE_LANGDLL # Language selection dialog

# MUI Symbol Definitions
!define MUI_ABORTWARNING
!define MUI_ICON "..\input\core\inc\icon.ico"
!define MUI_HEADERIMAGE
!define MUI_HEADERIMAGE_RIGHT
!define MUI_HEADERIMAGE_BITMAP "..\input\core\inc\parse\logo.bmp"
!define MUI_HEADERIMAGE_UNBITMAP "..\input\core\inc\parse\logo.bmp"
!define MUI_WELCOMEFINISHPAGE_BITMAP "..\input\core\inc\parse\wizard.bmp"
!define MUI_COMPONENTSPAGE_SMALLDESC
!define MUI_COMPONENTSPAGE_CHECKBITMAP "${NSISDIR}\Contrib\Graphics\Checks\modern.bmp"
!define MUI_FINISHPAGE_NOAUTOCLOSE
!define MUI_FINISHPAGE_RUN "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"
!define MUI_FINISHPAGE_RUN_TEXT "$(^StartLink)"
!define MUI_FINISHPAGE_HARMONYMODE
!define MUI_FINISHPAGE_HARMONYMODE_TEXT $(^ActivateHarmonyMode)
!define MUI_FINISHPAGE_HARMONYMODELABEL_TEXT $(^ActivateHarmonyModeLabel)
!define MUI_FINISHPAGE_LINK "${AMPRICOTURLTEXT}"
!define MUI_FINISHPAGE_LINK_LOCATION "${AMPRICOTURL}"
!define MUI_UNABORTWARNING
# !define MUI_UNICON "..\input\core\inc\icon.ico"
!define MUI_UNWELCOMEFINISHPAGE_BITMAP "..\input\core\inc\parse\wizard.bmp"
!define MUI_UNFINISHPAGE_NOAUTOCLOSE

# Installer pages
!insertmacro MUI_PAGE_WELCOME
!insertmacro MUI_PAGE_LICENSE "$(^LicenseFile)"
!insertmacro MUI_PAGE_COMPONENTS
!insertmacro MUI_PAGE_APACHEOPTS
!insertmacro MUI_PAGE_MYSQLOPTS
!insertmacro MUI_PAGE_DIRECTORY
!insertmacro MUI_PAGE_INSTFILES
!insertmacro MUI_PAGE_FINISH
!insertmacro MUI_UNPAGE_CONFIRM
!insertmacro MUI_UNPAGE_INSTFILES

# Installer Types
!ifndef NOINSTTYPES
    InstType "Default"
    InstType "Full"
!endif

# Installer languages
!insertmacro MUI_LANGUAGE "English"

# Installer sections
Section "-pre" SEC0000
    SetOutPath $INSTDIR\core\inc
    File /r /x parse ..\input\core\inc\*
    DetailPrint "$(^VCREDIST)"
    ExecWait '"$INSTDIR\core\inc\vcr9.exe" /q'
    Delete /REBOOTOK "$INSTDIR\core\inc\vcr9.exe"
SectionEnd

SectionGroup "Core Components" SECGRP0000
    Section "Apache HTTP Server ${AMPRICOTVERSIONAPACHE}" SEC0001
        SectionIn RO

        Var /GLOBAL installdirectory
        ${str_replace} "\" "/" "$INSTDIR" "$installdirectory"

        SetOverwrite on
        SetOutPath $INSTDIR\core\bin\apache\apache-${AMPRICOTVERSIONAPACHE}
        File /r /x cgi-bin /x conf /x htdocs /x include /x lib /x logs /x manual /x *.pdb /x *.txt ..\input\core\bin\apache\apache-${AMPRICOTVERSIONAPACHE}\*
        SetOutPath $INSTDIR\core\bin\apache\apache-${AMPRICOTVERSIONAPACHE}\conf
        File ..\input\core\inc\parse\httpd.conf
        SetOutPath $INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}
        File /r ..\input\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\*
        SetOutPath $INSTDIR\front\conf\apache\vhost
        File /r ..\input\front\conf\apache\vhost\*
        SetOutPath $INSTDIR\front\data\www
        File /r ..\input\front\data\www\*
        SetOutPath $INSTDIR\front\data\cgi-bin
        File /r ..\input\front\data\cgi-bin\*
        CreateDirectory $INSTDIR\front\tmp\log\apache\localhost

        DetailPrint "Compile Apache Configuration Files..."

        ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\core\bin\apache\apache-${AMPRICOTVERSIONAPACHE}\conf\httpd.conf"

        ${file_replace} "@AMPRICOTVERSIONPHP@" "${AMPRICOTVERSIONPHP}" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"
        ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"
        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"
        ${file_replace} "@AMPRICOTSERVERPORTHTTPAPACHE@" "$mui.ApacheOptsPage.ServerPortHTTP.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"
        ${file_replace} "@AMPRICOTSERVERADMINAPACHE@" "$mui.ApacheOptsPage.AdminEmail.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"
        ${file_replace} "@AMPRICOTSERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"

        ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-autoindex.conf"
        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-autoindex.conf"

        ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-dav.conf"
        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-dav.conf"

        ${file_replace} "@AMPRICOTSERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-info.conf"

        ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-manual.conf"
        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-manual.conf"

        ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-multilang-errordoc.conf"
        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-multilang-errordoc.conf"

        ${file_replace} "@AMPRICOTSERVERPORTHTTPSAPACHE@" "$mui.ApacheOptsPage.ServerPortHTTPS.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-ssl.conf"
        ${file_replace} "@AMPRICOTSERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-ssl.conf"
        ${file_replace} "@AMPRICOTSERVERADMINAPACHE@" "$mui.ApacheOptsPage.AdminEmail.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-ssl.conf"
        ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\extra\httpd-ssl.conf"

        ${file_replace} "@AMPRICOTSERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\vhost\localhost.conf"

        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\data\www\localhost\index.php"

        DetailPrint "Install Apache Service..."
        GetTempFileName $0
        Rename $0 $0.bat
        FileOpen $1 $0.bat w
        FileSeek $1 0 END
        FileWrite $1 '"$INSTDIR\core\bin\apache\apache-${AMPRICOTVERSIONAPACHE}\bin\httpd.exe" -k install -n AmpricotApache$\nsc.exe config AmpricotApache start= demand'
        FileClose $1
        ExecWait '"$INSTDIR\core\inc\hstart.exe" /noconsole /silent /wait "$0.bat"'
        Delete /REBOOTOK $0.bat
    SectionEnd

    Section "MySQL ${AMPRICOTVERSIONMYSQL}" SEC0002
        SectionIn RO

        SetOverwrite on
        SetOutPath $INSTDIR\core\bin\mysql\mysql-${AMPRICOTVERSIONMYSQL}
        File /r /x data /x docs /x include /x debug /x lib /x mysql-test /x scripts /x sql-bench /x *.lib /x *.pdb /x *.ini /x COPYING /x README ..\input\core\bin\mysql\mysql-${AMPRICOTVERSIONMYSQL}\*
        SetOutPath $INSTDIR\front\conf\mysql\mysql-${AMPRICOTVERSIONMYSQL}
        File /r ..\input\front\conf\mysql\mysql-${AMPRICOTVERSIONMYSQL}\*
        SetOutPath $INSTDIR\front\data\mysql\mysql-${AMPRICOTVERSIONMYSQL}
        File /r ..\input\core\bin\mysql\mysql-${AMPRICOTVERSIONMYSQL}\data\*
        CreateDirectory $INSTDIR\front\tmp\log\mysql

        DetailPrint "Compile MySQL Configuration Files..."

        ${file_replace} "@AMPRICOTSERVERPORTMYSQL@" "$mui.MySQLOptsPage.ServerPort.VAL" "all" "all" "$INSTDIR\front\conf\mysql\mysql-${AMPRICOTVERSIONMYSQL}\mysql.ini"
        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\mysql\mysql-${AMPRICOTVERSIONMYSQL}\mysql.ini"
        ${file_replace} "@AMPRICOTVERSIONMYSQL@" "${AMPRICOTVERSIONMYSQL}" "all" "all" "$INSTDIR\front\conf\mysql\mysql-${AMPRICOTVERSIONMYSQL}\mysql.ini"

        DetailPrint "Install MySQL Service..."

        GetTempFileName $0
        Rename $0 $0.bat
        FileOpen $1 $0.bat w
        FileSeek $1 0 END
        FileWrite $1 '"$INSTDIR\core\bin\mysql\mysql-${AMPRICOTVERSIONMYSQL}\bin\mysqld.exe" --install-manual AmpricotMySQL --defaults-file=$INSTDIR\front\conf\mysql\mysql-${AMPRICOTVERSIONMYSQL}\mysql.ini'
        FileClose $1
        ExecWait '"$INSTDIR\core\inc\hstart.exe" /noconsole /silent /wait "$0.bat"'
        Delete /REBOOTOK $0.bat

        DetailPrint "Updating MySQL 'root' password.."

        GetTempFileName $0
        Rename $0 $0.sql
        FileOpen $1 $0.sql w
        FileSeek $1 0 END
        FileWrite $1 'USE `mysql`;$\nUPDATE `user` SET Password=PASSWORD($\'$mui.MySQLOptsPage.RootPass.VAL$\') WHERE User=$\'root$\';'
        FileClose $1

        GetTempFileName $2
        Rename $2 $2.bat
        FileOpen $3 $2.bat w
        FileSeek $3 0 END
        FileWrite $3 'for /f "tokens=5 delims= " %%p in ($\'netstat -a -n -o ^| findstr 0.0:6033$\') do taskkill.exe /f /t /pid %%p$\n"$INSTDIR\core\bin\mysql\mysql-${AMPRICOTVERSIONMYSQL}\bin\mysqld.exe" --no-defaults --default-storage-engine=MyISAM --skip-innodb --port=6033 --datadir="$INSTDIR\front\data\mysql\mysql-${AMPRICOTVERSIONMYSQL}" --skip-grant-tables --bootstrap --standalone <"$0.sql"'
        FileClose $3
        ExecWait '"$INSTDIR\core\inc\hstart.exe" /noconsole /silent /wait "$2.bat"'

        Delete /REBOOTOK $0.sql
        Delete /REBOOTOK $2.bat
    SectionEnd

    Section "PHP ${AMPRICOTVERSIONPHP}" SEC0003
        SectionIn RO

        SetOverwrite on
        SetOutPath $INSTDIR\core\bin\php\php-${AMPRICOTVERSIONPHP}
        File /r /x extras /x dev /x *.ini /x *.reg /x *.lib /x *.txt /x php.ini-development /x php.ini-production ..\input\core\bin\php\php-${AMPRICOTVERSIONPHP}\*
        SetOutPath $INSTDIR\front\conf\php\php-${AMPRICOTVERSIONPHP}
        File /r ..\input\front\conf\php\php-${AMPRICOTVERSIONPHP}\*
        CreateDirectory $INSTDIR\front\tmp\log\php

        DetailPrint "Compile PHP Configuration Files..."

        ${file_replace} "@AMPRICOTVERSIONPHP@" "${AMPRICOTVERSIONPHP}" "all" "all" "$INSTDIR\front\conf\php\php-${AMPRICOTVERSIONPHP}\php.ini"
        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\php\php-${AMPRICOTVERSIONPHP}\php.ini"

        ${file_replace} "#LoadModule php5_module" "LoadModule php5_module" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"
        ${file_replace} "#AddType application/x-httpd-php .php" "AddType application/x-httpd-php .php" "all" "all" "$INSTDIR\front\conf\apache\apache-${AMPRICOTVERSIONAPACHE}\httpd.conf"

        WriteRegExpandStr HKLM "SYSTEM\CurrentControlSet\Control\Session Manager\Environment" "PHPRC" "$INSTDIR\front\conf\php\php-${AMPRICOTVERSIONPHP}"
        SendMessage ${HWND_BROADCAST} ${WM_WININICHANGE} 0 "STR:Environment" /TIMEOUT=5000

        CreateDirectory $INSTDIR\front\tmp\dmp
        CreateDirectory $INSTDIR\front\tmp\sess
    SectionEnd
SectionGroupEnd

SectionGroup "PHP Apps" SECGRP0001
    Section "phpMyAdmin ${AMPRICOTVERSIONPHPMYADMIN}" SEC0004
        SectionIn RO

        SetOverwrite on
        SetOutPath $INSTDIR\core\app\phpmyadmin-${AMPRICOTVERSIONPHPMYADMIN}
        File /r ..\input\core\app\phpmyadmin-${AMPRICOTVERSIONPHPMYADMIN}\*
        SetOutPath $INSTDIR\front\conf\apache\alias
        File ..\input\front\conf\apache\alias\phpmyadmin.conf

        DetailPrint "Compile phpMyAdmin -Apache Alias- Configuration Files..."

        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\alias\phpmyadmin.conf"
        ${file_replace} "@AMPRICOTVERSIONPHPMYADMIN@" "${AMPRICOTVERSIONPHPMYADMIN}" "all" "all" "$INSTDIR\front\conf\apache\alias\phpmyadmin.conf"
    SectionEnd

    Section "Adminer ${AMPRICOTVERSIONADMINER}" SEC0005
        SectionIn RO

        SetOverwrite on
        SetOutPath $INSTDIR\core\app\adminer-${AMPRICOTVERSIONADMINER}
        File /r ..\input\core\app\adminer-${AMPRICOTVERSIONADMINER}\*
        SetOutPath $INSTDIR\front\conf\apache\alias
        File ..\input\front\conf\apache\alias\adminer.conf

        DetailPrint "Compile Adminer -Apache Alias- Configuration Files..."

        ${file_replace} "@AMPRICOTINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\alias\adminer.conf"
        ${file_replace} "@AMPRICOTVERSIONADMINER@" "${AMPRICOTVERSIONADMINER}" "all" "all" "$INSTDIR\front\conf\apache\alias\adminer.conf"
    SectionEnd
SectionGroupEnd

SectionGroup "Shortcuts" SECGRP0002
    Section "System Startup" SEC0006
        SectionIn 1 2
        WriteRegStr HKLM "SOFTWARE\Microsoft\Windows\CurrentVersion\Run" "${AMPRICOTNAME}" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"
    SectionEnd

    Section "Quick Launch" SEC0007
        SectionIn 1 2
        SetShellVarContext all
        SetOutPath $QUICKLAUNCH
        CreateShortcut "$QUICKLAUNCH\$(^Name).lnk" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"
    SectionEnd

    Section "Start Menu" SEC0008
        SectionIn 1 2
        SetShellVarContext all
        SetOutPath $SMPROGRAMS\${AMPRICOTNAME}
        CreateShortcut "$SMPROGRAMS\${AMPRICOTNAME}\$(^StartLink).lnk" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"
        CreateShortcut "$SMPROGRAMS\${AMPRICOTNAME}\$(^UninstallLink).lnk" "$INSTDIR\core\inc\${AMPRICOTUNINSTALLER}"
    SectionEnd

    Section "Desktop" SEC0009
        SectionIn 1 2
        SetShellVarContext all
        SetOutPath $DESKTOP
        CreateShortcut "$DESKTOP\$(^Name).lnk" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"
    SectionEnd
SectionGroupEnd

Section "-post" SEC00099
    SetShellVarContext all

    DetailPrint "Compile Ampricot Uninstaller..."

    SetOutPath $INSTDIR\core\inc
    WriteUninstaller $INSTDIR\core\inc\${AMPRICOTUNINSTALLER}

    WriteRegStr HKLM "${AMPRICOTREGKEY}" "InstallLocation" "$INSTDIR"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "DisplayIcon" "$INSTDIR\core\inc\icon.ico"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "DisplayName" "${AMPRICOTNAME}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "DisplayVersion" "${AMPRICOTVERSIONCORE}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "Publisher" "${AMPRICOTCOMPANY}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "ProductID" "${AMPRICOTID}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "URLInfoAbout" "${AMPRICOTURLABOUT}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "URLUpdateInfo" "${AMPRICOTURLUPDATE}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "HelpLink" "${AMPRICOTURLHELP}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "UninstallString" "$INSTDIR\core\inc\${AMPRICOTUNINSTALLER}"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "Comments" "Apache ${AMPRICOTVERSIONAPACHE}, MySQL ${AMPRICOTVERSIONMYSQL}, PHP ${AMPRICOTVERSIONPHP}"
    WriteRegDWORD HKLM "${AMPRICOTREGKEY}" "NoModify" 1
    WriteRegDWORD HKLM "${AMPRICOTREGKEY}" "NoRepair" 1
    WriteRegDWORD HKLM "${AMPRICOTREGKEY}" "VersionMajor" ${AMPRICOTVERSIONCOREMAJOR}
    WriteRegDWORD HKLM "${AMPRICOTREGKEY}" "VersionMinor " ${AMPRICOTVERSIONCOREMINOR}

    ${GetSize} "$INSTDIR" "/S=0K" $0 $1 $2
    IntFmt $0 "0x%08X" $0
    WriteRegDWORD HKLM "${AMPRICOTREGKEY}" "EstimatedSize" $0
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "HelpTelephone" ""
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "Contact" "http://www.ampricot.com/contact/"

    ${GetTime} "" "LS" $0 $1 $2 $3 $4 $5 $6
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "InstallDate" "$2$1$0"
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "InstallSource" ""
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "LocalPackage" ""
    WriteRegStr HKLM "${AMPRICOTREGKEY}" "Readme" ""

    DetailPrint "Compile Ampricot Configuration Files..."

    ${file_replace} "@AMPRICOTVERSIONCORE@" "${AMPRICOTVERSIONCORE}" "all" "all" "$INSTDIR\core\inc\ampricot.conf"
    ${file_replace} "@AMPRICOTVERSIONAPACHE@" "${AMPRICOTVERSIONAPACHE}" "all" "all" "$INSTDIR\core\inc\ampricot.conf"
    ${file_replace} "@AMPRICOTVERSIONMYSQL@" "${AMPRICOTVERSIONMYSQL}" "all" "all" "$INSTDIR\core\inc\ampricot.conf"
    ${file_replace} "@AMPRICOTVERSIONPHP@" "${AMPRICOTVERSIONPHP}" "all" "all" "$INSTDIR\core\inc\ampricot.conf"
    ${file_replace} "@AMPRICOTINSTALLDIRROOT@" "$installdirectory" "all" "all" "$INSTDIR\core\inc\ampricot.conf"
    ${file_replace} "@AMPRICOTVERSIONPHP@" "${AMPRICOTVERSIONPHP}" "all" "all" "$INSTDIR\core\inc\ampricot.ini"
    ${file_replace} "@AMPRICOTINSTALLDIRROOT@" "$installdirectory" "all" "all" "$INSTDIR\core\inc\ampricot.ini"
    ${file_replace} "@AMPRICOTVERSIONCORE@" "${AMPRICOTVERSIONCORE}" "all" "all" "$INSTDIR\core\inc\ampricot.ini"
    ${file_replace} "@AMPRICOTINSTALLDIRROOT@" "$installdirectory" "all" "all" "$INSTDIR\core\inc\cleanonexit.bat"

    Var /GLOBAL urleninstalldirectory
    ${str_replace} " " "%20" "$installdirectory" "$urleninstalldirectory"
    ${file_replace} "@AMPRICOTINSTALLDIRROOTENC@" "$urleninstalldirectory" "all" "all" "$INSTDIR\core\inc\ampricot.ini"

    SetOutPath $INSTDIR
    CreateShortcut "$INSTDIR\www.lnk" "$INSTDIR\front\data\www"

    IfSilent +1 +2
    ${file_replace} "ampricotharmony = $\"off$\"" "ampricotharmony = $\"on$\"" "all" "all" "$INSTDIR\core\inc\ampricot.conf"

    IfSilent +1 +2
    WriteRegStr HKLM "SOFTWARE\Microsoft\Windows\CurrentVersion\Run" "${AMPRICOTNAME}" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"

    IfSilent +1 +3
    SetOutPath $QUICKLAUNCH
    CreateShortcut "$QUICKLAUNCH\${AMPRICOTNAME}.lnk" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"

    IfSilent +1 +4
    SetOutPath $SMPROGRAMS\${AMPRICOTNAME}
    CreateShortcut "$SMPROGRAMS\${AMPRICOTNAME}\$(^StartLink).lnk" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"
    CreateShortcut "$SMPROGRAMS\${AMPRICOTNAME}\$(^UninstallLink).lnk" "$INSTDIR\core\inc\${AMPRICOTUNINSTALLER}"

    IfSilent +1 +3
    SetOutPath $DESKTOP
    CreateShortcut "$DESKTOP\${AMPRICOTNAME}.lnk" "$INSTDIR\core\inc\${AMPRICOTLAUNCHER}"

    ${EnvVarUpdate} $0 "PATH" "A" "HKLM" "$INSTDIR\core\bin\php\php-${AMPRICOTVERSIONPHP}"

    CreateDirectory $INSTDIR\core\lib
SectionEnd

# Uninstaller sections
Section /o "-un.pre" UNSEC0000
    SetShellVarContext all

    DetailPrint "Uninstall Apache/MySQL Services..."

    GetTempFileName $0
    Rename $0 $0.bat
    FileOpen $1 $0.bat w
    FileSeek $1 0 END
    FileWrite $1 'sc stop AmpricotApache$\nsc stop AmpricotMySQL$\nCALL "$INSTDIR\core\inc\harmonymode.bat"$\nCALL "$INSTDIR\core\inc\killupdater.bat"$\n"$INSTDIR\core\inc\${AMPRICOTLAUNCHER}" -quit -id={ampricot}$\n"$INSTDIR\core\bin\apache\apache-${AMPRICOTVERSIONAPACHE}\bin\httpd.exe" -k uninstall -n AmpricotApache$\n"$INSTDIR\core\bin\mysql\mysql-${AMPRICOTVERSIONMYSQL}\bin\mysqld.exe" --remove AmpricotMySQL'
    FileClose $1
    ExecWait '"$INSTDIR\core\inc\hstart.exe" /noconsole /silent /wait "$0.bat"'
    Delete /REBOOTOK $0.bat

    DeleteRegValue HKLM "SYSTEM\CurrentControlSet\Control\Session Manager\Environment" "PHPRC"
    SendMessage ${HWND_BROADCAST} ${WM_WININICHANGE} 0 "STR:Environment" /TIMEOUT=5000

    ${un.EnvVarUpdate} $0 "PATH" "R" "HKLM" "$INSTDIR\core\bin\php\php-${AMPRICOTVERSIONPHP}"

    DetailPrint "Clean Registry..."

    DeleteRegKey HKLM "${AMPRICOTREGKEY}"
    Delete /REBOOTOK $DESKTOP\${AMPRICOTNAME}.lnk
    RmDir /r /REBOOTOK $SMPROGRAMS\${AMPRICOTNAME}
    Delete /REBOOTOK $QUICKLAUNCH\${AMPRICOTNAME}.lnk
    Delete /REBOOTOK $INSTDIR\www.lnk

    RmDir /r /REBOOTOK $INSTDIR\core
    RmDir /r /REBOOTOK $INSTDIR\front\tmp


    DetailPrint "Backup Data/Configuration Files..."

    ${GetTime} "" "LS" $0 $1 $2 $3 $4 $5 $6
    CreateDirectory $INSTDIR\front\.backup\$2-$1-$0-$4-$5-$6
    Rename /REBOOTOK "$INSTDIR\front\conf" "$INSTDIR\front\.backup\$2-$1-$0-$4-$5-$6\conf"
    Rename /REBOOTOK "$INSTDIR\front\data" "$INSTDIR\front\.backup\$2-$1-$0-$4-$5-$6\data"
SectionEnd

# Installer functions
Function .onInit
    InitPluginsDir
    !insertmacro MUI_LANGDLL_DISPLAY

    ${Unless} ${AtLeastWinXP}
    ${AndUnless} ${AtLeastServicePack} 3
        MessageBox MB_OK|MB_ICONSTOP "Unsupported operating system.$\nAmpricot ${AMPRICOTVERSIONCORE} requires at least Windows XP SP3 or later to be installed." /SD IDOK
        Abort
    ${EndUnless}

    # Populate Apache Vars
    StrCpy $mui.ApacheOptsPage.ServerName.VAL "localhost"
    StrCpy $mui.ApacheOptsPage.AdminEmail.VAL "webmaster@localhost"
    StrCpy $mui.ApacheOptsPage.ServerPortHTTP.VAL "80"
    StrCpy $mui.ApacheOptsPage.ServerPortHTTPS.VAL "443"

    # Populate MySQL Vars
    IfSilent +1 +3
    StrCpy $mui.MySQLOptsPage.RootPass.VAL "root"
    Goto +3
    StrCpy $mui.MySQLOptsPage.RootPass.VAL ""
    StrCpy $mui.MySQLOptsPage.ConfirmPass.VAL ""
    StrCpy $mui.MySQLOptsPage.ServerPort.VAL "3306"

    Push $WINDIR
    Call GetRoot
    Pop $WINDRIVE
    StrCpy $INSTDIR $WINDRIVE\ampricot

    # Check if already running, If so don't open another but bring to front
    BringToFront
    System::Call "kernel32::CreateMutexA(i 0, i 0, t '$(^Name)') i .r0 ?e"
    Pop $0
    StrCmp $0 "0" launch
    StrLen $0 "$(^Name)"
    IntOp $0 $0 + 1
    loop:
        FindWindow $1 '#32770' '' 0 $1
        IntCmp $1 0 +4
        System::Call "user32::GetWindowText(i r1, t .r2, i r0) i."
        StrCmp $2 "$(^Name)" 0 loop
        System::Call "user32::ShowWindow(i r1,i 9) i."         # If minimized then maximize
        System::Call "user32::SetForegroundWindow(i r1) i."    # Brint to front
        Abort
    launch:

    # Check to see if already installed
    ReadRegStr $R0 HKLM "${AMPRICOTREGKEY}" "InstallLocation"
    IfFileExists $R0 +1 notinstalledordone
    MessageBox MB_YESNO|MB_USERICON "$(^AlreadyInstalled)" /SD IDNO IDYES +1 IDNO quitnow


    ${GetTime} "" "LS" $0 $1 $2 $3 $4 $5 $6
    CreateDirectory $TEMP\$2-$1-$0-$4-$5-$6
    ReadRegStr $R7 HKLM "${AMPRICOTREGKEY}" "UninstallString"
    CopyFiles '$R7' '$TEMP\$2-$1-$0-$4-$5-$6\${AMPRICOTUNINSTALLER}'

    IfSilent +1 +3
    ExecWait '"$TEMP\$2-$1-$0-$4-$5-$6\${AMPRICOTUNINSTALLER}" /S _?=$TEMP\$2-$1-$0-$4-$5-$6'
    Goto +2
    ExecWait '"$TEMP\$2-$1-$0-$4-$5-$6\${AMPRICOTUNINSTALLER}" _?=$TEMP\$2-$1-$0-$4-$5-$6' $7
    StrCmp $7 1 quitnow
    Goto notinstalledordone

    quitnow:
    Quit
    notinstalledordone:
    RmDir /r /REBOOTOK $TEMP\$2-$1-$0-$4-$5-$6
FunctionEnd

# Uninstaller functions
Function un.onInit
    ReadRegStr $INSTDIR HKLM "${AMPRICOTREGKEY}" "InstallLocation"
    !insertmacro MUI_UNGETLANGUAGE
    !insertmacro SelectSection "${UNSEC0000}"
FunctionEnd

# Installation Succeed
Function .onInstSuccess
    # Refresh the System
    System::Call 'Shell32::SHChangeNotify(i ${SHCNE_ASSOCCHANGED}, i ${SHCNF_IDLIST}, i 0, i 0)'
FunctionEnd

# unInstallation Succeed
Function un.onUninstSuccess
    # Refresh the System
    System::Call 'Shell32::SHChangeNotify(i ${SHCNE_ASSOCCHANGED}, i ${SHCNF_IDLIST}, i 0, i 0)'
FunctionEnd

# Section Descriptions
!insertmacro MUI_FUNCTION_DESCRIPTION_BEGIN
!insertmacro MUI_DESCRIPTION_TEXT ${SECGRP0000} "Core software components."
!insertmacro MUI_DESCRIPTION_TEXT ${SECGRP0001} "Optional PHP applications ready for use."
!insertmacro MUI_DESCRIPTION_TEXT ${SECGRP0002} "Additional software shortcuts useful for quick launch."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0001} "Apache HTTP Server, a web server software notable for playing a key role in the initial growth of the WWW."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0002} "MySQL is a RDBMS that runs as a server providing multi-user access to a number of databases."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0003} "PHP: Hypertext Preprocessor is a general-purpose scripting language designed for web development."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0004} "A tool written in PHP intended to handle the administration of MySQL over the WWW."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0005} "A lightweight tool written in PHP intended to handle the administration of multiple RDBMS over the WWW."
!insertmacro MUI_FUNCTION_DESCRIPTION_END

# Installer License Strings
LicenseLangString ^LicenseFile ${LANG_ENGLISH} "..\input\core\inc\license.txt"

# Installer Language Strings
LangString "^VCREDIST" "${LANG_ENGLISH}" "Install Microsoft Visual C++ 2008 SP1 Redistributable Package"
LangString "^StartLink" "${LANG_ENGLISH}" "Start ${AMPRICOTNAME}"
LangString "^UninstallLink" "${LANG_ENGLISH}" "Uninstall ${AMPRICOTNAME}"
LangString "^AlreadyInstalled" "${LANG_ENGLISH}" "${AMPRICOTNAME} is apparently already installed!$\r$\nWould you like to UNINSTALL old version now?"
LangString "^ActivateHarmonyMode" "${LANG_ENGLISH}" "Activate &Harmony Mode"
LangString "^ActivateHarmonyModeLabel" "${LANG_ENGLISH}" "Activating Harmony Mode will FORCE CLOSE currently running applications using ports required by Ampricot."
LangString "^ApachePageTitle" "${LANG_ENGLISH}" "Apache Server Information"
LangString "^ApachePageDesc" "${LANG_ENGLISH}" "Please enter your apache server's information."
LangString "^ApachePageOptsServerName" "${LANG_ENGLISH}" "&Server Name"
LangString "^ApachePageOptsAdminEmail" "${LANG_ENGLISH}" "Administrator's &Email Address"
LangString "^ApachePageOptsServerPortHTTP" "${LANG_ENGLISH}" "&HTTP &Port"
LangString "^ApachePageOptsServerPortHTTPS" "${LANG_ENGLISH}" "HTTP&S Port"
LangString "^MySQLPageTitle" "${LANG_ENGLISH}" "MySQL Server Information"
LangString "^MySQLPageDesc" "${LANG_ENGLISH}" "Please enter your mysql server's information."
LangString "^MySQLPageOptsRootPass" "${LANG_ENGLISH}" "&Root Account Password"
LangString "^MySQLPageOptsConfirmPass" "${LANG_ENGLISH}" "&Confirm Root Account Password"
LangString "^MySQLPageOptsServerPort" "${LANG_ENGLISH}" "Server &Port"
