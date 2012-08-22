# Installer attributes
Name "Apricore"

# General Symbol Definitions
!define APRICOREID "apricore"
!define APRICORENAME "Apricore"
!define APRICOREVERSIONCOREMAJOR "1"
!define APRICOREVERSIONCOREMINOR "0"
!define APRICOREVERSIONCORE "1.0.0.0"
!define APRICOREVERSIONAPACHE "2.4.2"
!define APRICOREVERSIONMYSQL "5.5.27"
!define APRICOREVERSIONPHP "5.4.6"
!define APRICOREVERSIONPHPMYADMIN "3.5.2.2"
!define APRICOREVERSIONADMINER "3.5.1"
!define APRICORECOMPANY "FruiTech Labs"
!define APRICOREURLTEXT "apricore.fruitechlabs.com"
!define APRICOREURL "http://apricore.fruitechlabs.com"
!define APRICOREURLABOUT "http://apricore.fruitechlabs.com/about"
!define APRICOREURLUPDATE "http://apricore.fruitechlabs.com/update"
!define APRICOREURLHELP "http://apricore.fruitechlabs.com/support"
!define APRICORELAUNCHER "apricore.exe"
!define APRICOREINSTALLER "apricore-${APRICOREVERSIONCORE}.exe"
!define APRICOREUNINSTALLER "uninstall.exe"
!define APRICOREREGKEY "SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\${APRICORENAME}"
!define MUI_LANGDLL_REGISTRY_ROOT "HKLM"
!define MUI_LANGDLL_REGISTRY_KEY "${APRICOREREGKEY}"
!define MUI_LANGDLL_REGISTRY_VALUENAME "InstallerLanguage"

# Installer attributes
BrandingText " "
OutFile "..\..\..\..\binary\apricore\${APRICOREINSTALLER}"
CRCCheck on
XPStyle on
ShowInstDetails hide
ShowUninstDetails hide
InstallDirRegKey HKLM "${APRICOREREGKEY}" "InstallLocation"

# File info
VIProductVersion "${APRICOREVERSIONCORE}"
VIAddVersionKey ProductName "Apricore"
VIAddVersionKey ProductVersion "${APRICOREVERSIONCORE}"
VIAddVersionKey CompanyName "${APRICORECOMPANY}"
VIAddVersionKey FileVersion "${APRICOREVERSIONCORE}"
VIAddVersionKey FileDescription "Apache ${APRICOREVERSIONAPACHE}, MySQL ${APRICOREVERSIONMYSQL}, PHP ${APRICOREVERSIONPHP}"
VIAddVersionKey LegalCopyright "Copyright (c) 2012 ${APRICORECOMPANY}."

# Best Compression
SetCompress Auto
SetCompressor /SOLID lzma
SetCompressorDictSize 32
SetDatablockOptimize On

# Custom Variables
Var StartMenuGroup
var SCQuickLaunch
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
!define MUI_STARTMENUPAGE_REGISTRY_ROOT "HKLM"
!define MUI_STARTMENUPAGE_REGISTRY_KEY "${APRICOREREGKEY}"
!define MUI_STARTMENUPAGE_REGISTRY_VALUENAME "StartMenuGroup"
!define MUI_STARTMENUPAGE_DEFAULTFOLDER "${APRICORENAME}"
!define MUI_FINISHPAGE_NOAUTOCLOSE
!define MUI_FINISHPAGE_RUN "$INSTDIR\core\inc\${APRICORELAUNCHER}"
!define MUI_FINISHPAGE_RUN_TEXT "$(^StartLink)"
!define MUI_FINISHPAGE_LINK "${APRICOREURLTEXT}"
!define MUI_FINISHPAGE_LINK_LOCATION "${APRICOREURL}"
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
!insertmacro MUI_PAGE_STARTMENU "Application" "$StartMenuGroup"
!insertmacro MUI_PAGE_INSTFILES
!define MUI_PAGE_CUSTOMFUNCTION_SHOW "SCQuickLaunch_Show"
!define MUI_PAGE_CUSTOMFUNCTION_LEAVE "SCQuickLaunch_Leave"
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
    ExecWait '"$INSTDIR\core\inc\vc9.exe" /q'
    Delete /REBOOTOK "$INSTDIR\core\inc\vcredist-9.exe"
SectionEnd

Section "Apache HTTP Server ${APRICOREVERSIONAPACHE}" SEC0001
    SectionIn RO

    Var /GLOBAL installdirectory
    ${str_replace} "\" "/" "$INSTDIR" "$installdirectory"

    SetOverwrite on
    SetOutPath $INSTDIR\core\bin\apache\apache-${APRICOREVERSIONAPACHE}
    File /r /x cgi-bin /x conf /x htdocs /x include /x lib /x logs /x manual /x *.pdb /x *.txt ..\input\core\bin\apache\apache-${APRICOREVERSIONAPACHE}\*
    SetOutPath $INSTDIR\core\bin\apache\apache-${APRICOREVERSIONAPACHE}\conf
    File ..\input\core\inc\parse\httpd.conf
    SetOutPath $INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}
    File /r ..\input\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\*
    SetOutPath $INSTDIR\front\conf\apache\vhost
    File /r ..\input\front\conf\apache\vhost\*
    SetOutPath $INSTDIR\front\data\www
    File /r ..\input\front\data\www\*
    SetOutPath $INSTDIR\front\data\cgi-bin
    File /r ..\input\front\data\cgi-bin\*
    CreateDirectory $INSTDIR\front\tmp\log\apache\localhost

    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\core\bin\apache\apache-${APRICOREVERSIONAPACHE}\conf\httpd.conf"

    ${file_replace} "@APRICOREVERSIONPHP@" "${APRICOREVERSIONPHP}" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
    ${file_replace} "@APRICORESERVERPORTHTTPAPACHE@" "$mui.ApacheOptsPage.ServerPortHTTP.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
    ${file_replace} "@APRICORESERVERADMINAPACHE@" "$mui.ApacheOptsPage.AdminEmail.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
    ${file_replace} "@APRICORESERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"

    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-autoindex.conf"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-autoindex.conf"

    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-dav.conf"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-dav.conf"

    ${file_replace} "@APRICORESERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-info.conf"

    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-manual.conf"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-manual.conf"

    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-multilang-errordoc.conf"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-multilang-errordoc.conf"

    ${file_replace} "@APRICORESERVERPORTHTTPSAPACHE@" "$mui.ApacheOptsPage.ServerPortHTTPS.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-ssl.conf"
    ${file_replace} "@APRICORESERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-ssl.conf"
    ${file_replace} "@APRICORESERVERADMINAPACHE@" "$mui.ApacheOptsPage.AdminEmail.VAL" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-ssl.conf"
    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\extra\httpd-ssl.conf"

    ${file_replace} "@APRICORESERVERNAMEAPACHE@" "$mui.ApacheOptsPage.ServerName.VAL" "all" "all" "$INSTDIR\front\conf\apache\vhost\localhost.conf"

    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\data\www\localhost\index.php"

    ExecWait '"$INSTDIR\core\bin\apache\apache-${APRICOREVERSIONAPACHE}\bin\httpd.exe" -k install -n ApricoreApache'
    ExecWait 'sc config ApricoreApache start= demand'
SectionEnd

Section "MySQL ${APRICOREVERSIONMYSQL}" SEC0002
    SectionIn RO

    SetOverwrite on
    SetOutPath $INSTDIR\core\bin\mysql\mysql-${APRICOREVERSIONMYSQL}
    File /r /x data /x docs /x include /x debug /x lib /x mysql-test /x scripts /x sql-bench /x *.lib /x *.pdb /x *.ini /x COPYING /x README ..\input\core\bin\mysql\mysql-${APRICOREVERSIONMYSQL}\*
    SetOutPath $INSTDIR\front\conf\mysql\mysql-${APRICOREVERSIONMYSQL}
    File /r ..\input\front\conf\mysql\mysql-${APRICOREVERSIONMYSQL}\*
    SetOutPath $INSTDIR\front\data\mysql\mysql-${APRICOREVERSIONMYSQL}
    File /r ..\input\core\bin\mysql\mysql-${APRICOREVERSIONMYSQL}\data\*
    CreateDirectory $INSTDIR\front\tmp\log\mysql

    ${file_replace} "@APRICORESERVERPORTMYSQL@" "$mui.MySQLOptsPage.ServerPort.VAL" "all" "all" "$INSTDIR\front\conf\mysql\mysql-${APRICOREVERSIONMYSQL}\mysql.ini"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\mysql\mysql-${APRICOREVERSIONMYSQL}\mysql.ini"
    ${file_replace} "@APRICOREVERSIONMYSQL@" "${APRICOREVERSIONMYSQL}" "all" "all" "$INSTDIR\front\conf\mysql\mysql-${APRICOREVERSIONMYSQL}\mysql.ini"

    ExecWait '"$INSTDIR\core\bin\mysql\mysql-${APRICOREVERSIONMYSQL}\bin\mysqld.exe" --install-manual ApricoreMySQL --defaults-file=$INSTDIR\front\conf\mysql\mysql-${APRICOREVERSIONMYSQL}\mysql.ini'

    DetailPrint "Updating MySQL 'root' password.."
    ${file_replace} "@APRICOREMYSQLROOTPASS@" "$mui.MySQLOptsPage.RootPass.VAL" "all" "all" "$INSTDIR\core\inc\mysqlresetrootpass.sql"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\core\inc\mysqlresetrootpass.bat"
    ${file_replace} "@APRICOREVERSIONMYSQL@" "${APRICOREVERSIONMYSQL}" "all" "all" "$INSTDIR\core\inc\mysqlresetrootpass.bat"

    ExecWait '"$INSTDIR\core\inc\mysqlresetrootpass.bat"'
    Delete /REBOOTOK $INSTDIR\core\inc\mysqlresetrootpass.bat
    Delete /REBOOTOK $INSTDIR\core\inc\mysqlresetrootpass.sql
SectionEnd

Section "PHP ${APRICOREVERSIONPHP}" SEC0003
    SectionIn RO

    SetOverwrite on
    SetOutPath $INSTDIR\core\bin\php\php-${APRICOREVERSIONPHP}
    File /r /x extras /x dev /x *.ini /x *.reg /x *.lib /x *.txt /x php.ini-development /x php.ini-production ..\input\core\bin\php\php-${APRICOREVERSIONPHP}\*
    SetOutPath $INSTDIR\front\conf\php\php-${APRICOREVERSIONPHP}
    File /r ..\input\front\conf\php\php-${APRICOREVERSIONPHP}\*
    CreateDirectory $INSTDIR\front\tmp\log\php

    ${file_replace} "@APRICOREVERSIONPHP@" "${APRICOREVERSIONPHP}" "all" "all" "$INSTDIR\front\conf\php\php-${APRICOREVERSIONPHP}\php.ini"
    ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\php\php-${APRICOREVERSIONPHP}\php.ini"

    ${file_replace} "#LoadModule php5_module" "LoadModule php5_module" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
    ${file_replace} "#PHPIniDir" "PHPIniDir" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
    ${file_replace} "#AddType application/x-httpd-php .php" "AddType application/x-httpd-php .php" "all" "all" "$INSTDIR\front\conf\apache\apache-${APRICOREVERSIONAPACHE}\httpd.conf"
SectionEnd

SectionGroup "PHP Apps" SECGRP0000
    Section "phpMyAdmin ${APRICOREVERSIONPHPMYADMIN}" SEC0004
        SectionIn RO

        SetOverwrite on
        SetOutPath $INSTDIR\core\app\phpmyadmin-${APRICOREVERSIONPHPMYADMIN}
        File /r ..\input\core\app\phpmyadmin-${APRICOREVERSIONPHPMYADMIN}\*
        SetOutPath $INSTDIR\front\conf\apache\alias
        File ..\input\front\conf\apache\alias\phpmyadmin.conf

        ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\alias\phpmyadmin.conf"
        ${file_replace} "@APRICOREVERSIONPHPMYADMIN@" "${APRICOREVERSIONPHPMYADMIN}" "all" "all" "$INSTDIR\front\conf\apache\alias\phpmyadmin.conf"
    SectionEnd
    Section "Adminer ${APRICOREVERSIONADMINER}" SEC0005
        SectionIn RO

        SetOverwrite on
        SetOutPath $INSTDIR\core\app\adminer-${APRICOREVERSIONADMINER}
        File /r ..\input\core\app\adminer-${APRICOREVERSIONADMINER}\*
        SetOutPath $INSTDIR\front\conf\apache\alias
        File ..\input\front\conf\apache\alias\adminer.conf

        ${file_replace} "@APRICOREINSTALLDIRCORE@" "$installdirectory" "all" "all" "$INSTDIR\front\conf\apache\alias\adminer.conf"
        ${file_replace} "@APRICOREVERSIONADMINER@" "${APRICOREVERSIONADMINER}" "all" "all" "$INSTDIR\front\conf\apache\alias\adminer.conf"
    SectionEnd
SectionGroupEnd

Section "-post" SEC00099
    SetOutPath $INSTDIR\core\inc
    WriteUninstaller $INSTDIR\core\inc\${APRICOREUNINSTALLER}

    !insertmacro MUI_STARTMENU_WRITE_BEGIN "Application"
    SetOutPath $SMPROGRAMS\$StartMenuGroup
    CreateShortcut "$SMPROGRAMS\$StartMenuGroup\$(^StartLink).lnk" "$INSTDIR\core\inc\${APRICORELAUNCHER}"
    CreateShortcut "$SMPROGRAMS\$StartMenuGroup\$(^UninstallLink).lnk" "$INSTDIR\core\inc\${APRICOREUNINSTALLER}"
    !insertmacro MUI_STARTMENU_WRITE_END

    WriteRegStr HKLM "${APRICOREREGKEY}" "InstallLocation" "$INSTDIR"
    WriteRegStr HKLM "${APRICOREREGKEY}" "DisplayIcon" "$INSTDIR\core\inc\icon.ico"
    WriteRegStr HKLM "${APRICOREREGKEY}" "DisplayName" "${APRICORENAME}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "DisplayVersion" "${APRICOREVERSIONCORE}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "Publisher" "${APRICORECOMPANY}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "ProductID" "${APRICOREID}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "URLInfoAbout" "${APRICOREURLABOUT}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "URLUpdateInfo" "${APRICOREURLUPDATE}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "HelpLink" "${APRICOREURLHELP}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "UninstallString" "$INSTDIR\core\inc\${APRICOREUNINSTALLER}"
    WriteRegStr HKLM "${APRICOREREGKEY}" "Comments" "Apache ${APRICOREVERSIONAPACHE}, MySQL ${APRICOREVERSIONMYSQL}, PHP ${APRICOREVERSIONPHP}"
    WriteRegDWORD HKLM "${APRICOREREGKEY}" "NoModify" 1
    WriteRegDWORD HKLM "${APRICOREREGKEY}" "NoRepair" 1
    WriteRegDWORD HKLM "${APRICOREREGKEY}" "VersionMajor" ${APRICOREVERSIONCOREMAJOR}
    WriteRegDWORD HKLM "${APRICOREREGKEY}" "VersionMinor " ${APRICOREVERSIONCOREMINOR}

    ${GetSize} "$INSTDIR" "/S=0K" $0 $1 $2
    IntFmt $0 "0x%08X" $0
    WriteRegDWORD HKLM "${APRICOREREGKEY}" "EstimatedSize" $0
    WriteRegStr HKLM "${APRICOREREGKEY}" "HelpTelephone" ""
    WriteRegStr HKLM "${APRICOREREGKEY}" "Contact" ""

    ${GetTime} "" "LS" $0 $1 $2 $3 $4 $5 $6
    WriteRegStr HKLM "${APRICOREREGKEY}" "InstallDate" "$2$1$0"
    WriteRegStr HKLM "${APRICOREREGKEY}" "InstallSource" ""
    WriteRegStr HKLM "${APRICOREREGKEY}" "LocalPackage" ""
    WriteRegStr HKLM "${APRICOREREGKEY}" "Readme" ""

    ${file_replace} "@APRICOREVERSIONCORE@" "${APRICOREVERSIONCORE}" "all" "all" "$INSTDIR\core\inc\apricore.conf"
    ${file_replace} "@APRICOREVERSIONAPACHE@" "${APRICOREVERSIONAPACHE}" "all" "all" "$INSTDIR\core\inc\apricore.conf"
    ${file_replace} "@APRICOREVERSIONMYSQL@" "${APRICOREVERSIONMYSQL}" "all" "all" "$INSTDIR\core\inc\apricore.conf"
    ${file_replace} "@APRICOREVERSIONPHP@" "${APRICOREVERSIONPHP}" "all" "all" "$INSTDIR\core\inc\apricore.conf"
    ${file_replace} "@APRICOREINSTALLDIRROOT@" "$installdirectory" "all" "all" "$INSTDIR\core\inc\apricore.conf"
    ${file_replace} "@APRICOREVERSIONPHP@" "${APRICOREVERSIONPHP}" "all" "all" "$INSTDIR\core\inc\apricore.ini"
    ${file_replace} "@APRICOREINSTALLDIRROOT@" "$installdirectory" "all" "all" "$INSTDIR\core\inc\apricore.ini"
    ${file_replace} "@APRICOREVERSIONCORE@" "${APRICOREVERSIONCORE}" "all" "all" "$INSTDIR\core\inc\apricore.ini"

    Var /GLOBAL urleninstalldirectory
    ${str_replace} " " "%20" "$installdirectory" "$urleninstalldirectory"
    ${file_replace} "@APRICOREINSTALLDIRROOTENC@" "$urleninstalldirectory" "all" "all" "$INSTDIR\core\inc\apricore.ini"

    SetOutPath $SMSTARTUP
    CreateShortcut "$SMSTARTUP\${APRICORENAME}.lnk" "$INSTDIR\core\inc\${APRICORELAUNCHER}"

    SetOutPath $INSTDIR
    CreateShortcut "$INSTDIR\www.lnk" "$INSTDIR\front\data\www"

    IfSilent +1 +3
    SetOutPath $QUICKLAUNCH
    CreateShortcut "$QUICKLAUNCH\${APRICORENAME}.lnk" "$INSTDIR\core\inc\${APRICORELAUNCHER}"

    CreateDirectory $INSTDIR\front\tmp\dmp
SectionEnd


# Uninstaller sections
Section /o "-un.Main Uninstall Step" UNSEC0000
    ExecWait 'sc stop ApricoreApache'
    ExecWait 'sc stop ApricoreMySQL'
    ExecWait '"$INSTDIR\core\inc\${APRICORELAUNCHER}" -quit -id={apricore}'
    ExecWait '"$INSTDIR\core\bin\apache\apache-${APRICOREVERSIONAPACHE}\bin\httpd.exe" -k uninstall -n ApricoreApache'
    ExecWait '"$INSTDIR\core\bin\mysql\mysql-${APRICOREVERSIONMYSQL}\bin\mysqld.exe" --remove ApricoreMySQL'
    RmDir /r /REBOOTOK $INSTDIR\core
    RmDir /r /REBOOTOK $INSTDIR\front\tmp
    ${GetTime} "" "LS" $0 $1 $2 $3 $4 $5 $6
    CreateDirectory $INSTDIR\front\.backup\$2-$1-$0-$4-$5-$6
    Rename /REBOOTOK "$INSTDIR\front\conf" "$INSTDIR\front\.backup\$2-$1-$0-$4-$5-$6\conf"
    Rename /REBOOTOK "$INSTDIR\front\data" "$INSTDIR\front\.backup\$2-$1-$0-$4-$5-$6\data"
SectionEnd

Section "-un.post" UNSEC0099
    DeleteRegKey HKLM "${APRICOREREGKEY}"
    Delete /REBOOTOK $QUICKLAUNCH\${APRICORENAME}.lnk
    Delete /REBOOTOK $SMSTARTUP\${APRICORENAME}.lnk
    Delete /REBOOTOK $INSTDIR\www.lnk
    RmDir /r /REBOOTOK $SMPROGRAMS\$StartMenuGroup
    Push $R0
    StrCpy $R0 "$StartMenuGroup" 1
    StrCmp $R0 ">" no_smgroup
no_smgroup:
    Pop $R0
SectionEnd

# Installer functions
Function .onInit
    InitPluginsDir

    !insertmacro MUI_LANGDLL_DISPLAY

    ${Unless} ${AtLeastWinXP}
    ${AndUnless} ${AtLeastServicePack} 3
        MessageBox MB_OK|MB_ICONSTOP "Unsupported operating system.$\nApricore ${APRICOREVERSIONCORE} requires at least Windows XP SP3 or later to be installed." /SD IDOK
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
    StrCpy $INSTDIR $WINDRIVE\apricore

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
    ReadRegStr $R0 HKLM "${APRICOREREGKEY}" "InstallLocation"
    IfFileExists $R0 +1 notinstalledordone
    MessageBox MB_YESNO|MB_USERICON "$(^AlreadyInstalled)" /SD IDNO IDYES +1 IDNO quitnow


    ${GetTime} "" "LS" $0 $1 $2 $3 $4 $5 $6
    CreateDirectory $TEMP\$2-$1-$0-$4-$5-$6
    ReadRegStr $R7 HKLM "${APRICOREREGKEY}" "UninstallString"
    CopyFiles '$R7' '$TEMP\$2-$1-$0-$4-$5-$6\${APRICOREUNINSTALLER}'

    IfSilent +1 +3
    ExecWait '"$TEMP\$2-$1-$0-$4-$5-$6\${APRICOREUNINSTALLER}" /S _?=$TEMP\$2-$1-$0-$4-$5-$6'
    Goto +2
    ExecWait '"$TEMP\$2-$1-$0-$4-$5-$6\${APRICOREUNINSTALLER}" _?=$TEMP\$2-$1-$0-$4-$5-$6' $7
    StrCmp $7 1 quitnow
    Goto notinstalledordone

    quitnow:
    Quit
    notinstalledordone:
FunctionEnd

# Uninstaller functions
Function un.onInit
    ReadRegStr $INSTDIR HKLM "${APRICOREREGKEY}" "InstallLocation"
    !insertmacro MUI_STARTMENU_GETFOLDER "Application" "$StartMenuGroup"
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
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0001} "Apache HTTP Server, a web server software notable for playing a key role in the initial growth of the WWW."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0002} "MySQL is a RDBMS that runs as a server providing multi-user access to a number of databases."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0003} "PHP: Hypertext Preprocessor is a general-purpose scripting language designed for web development."
!insertmacro MUI_DESCRIPTION_TEXT ${SECGRP0000} "Optional PHP applications ready for use."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0004} "A tool written in PHP intended to handle the administration of MySQL over the WWW."
!insertmacro MUI_DESCRIPTION_TEXT ${SEC0005} "A lightweight tool written in PHP intended to handle the administration of multiple RDBMS over the WWW."
!insertmacro MUI_FUNCTION_DESCRIPTION_END

# Installer License Strings
LicenseLangString ^LicenseFile ${LANG_ENGLISH} "..\input\core\inc\parse\license\en.txt"

# Installer Language Strings
LangString "^VCREDIST" "${LANG_ENGLISH}" "Installing Microsoft Visual C++ 2008 SP1 Redistributable Package"
LangString "^StartLink" "${LANG_ENGLISH}" "Start ${APRICORENAME}"
LangString "^UninstallLink" "${LANG_ENGLISH}" "Uninstall ${APRICORENAME}"
LangString "^AlreadyInstalled" "${LANG_ENGLISH}" "${APRICORENAME} is apparently already installed!$\r$\nWould you like to UNINSTALL old version now?"
LangString "^AddQuickLaunch" "${LANG_ENGLISH}" "Add to &Quick Launch"
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
