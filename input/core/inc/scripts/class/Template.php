<?php
/*==================================================================================*\
|| ################################################################################ ||
|| # Product Name: Ampricot                                                       # ||
|| # License Type: Free License                                                   # ||
|| # ---------------------------------------------------------------------------- # ||
|| # 																			  # ||
|| #           Copyright ©2005-2012 FruiTechLabs. All Rights Reserved.            # ||
|| #     This product may be redistributed in whole or significant part under     # ||
|| # "The MIT License (MIT)" - http://www.opensource.org/licenses/mit-license.php # ||
|| # 																			  # ||
|| # ----------------------- "Ampricot" IS FREE SOFTWARE ------------------------ # ||
|| #            http://www.ampricot.com | http://www.fruitechlabs.com             # ||
|| ################################################################################ ||
\*==================================================================================*/


namespace Ampricot;


$ampricottpl = '
[Config]
;AMPRICOTCONFIGSTART
ImageList=imagelist.bmp
ServiceCheckInterval=1
ServiceGlyphRunning=99
ServiceGlyphPaused=99
ServiceGlyphStopped=99
TrayIconAllRunning=2
TrayIconSomeRunning=1
TrayIconNoneRunning=0
ID={ampricot}
AboutHeader=Ampricot
AboutVersion=' . $this->ampricotversioncore . '
;AMPRICOTCONFIGEND

[AboutText]
Copyright (c) 2012 FruiTech Labs.
Product: Ampricot - http://www.ampricot.com
Company: FruiTech Labs - http://www.fruitechlabs.com
Developer: Abdelrahman Omran - http://www.omranic.com

[Services]
Name: AmpricotApache
Name: AmpricotMySQL

[Messages]
AllRunningHint=Ampricot - ' . gettext('All sevices running!') . '
SomeRunningHint=Ampricot - ' . gettext('Some sevices running!') . '
NoneRunningHint=Ampricot - ' . gettext('No sevices running!') . '

[DoubleClickAction]
;DOUBLECLICKACTIONSTART
Action: run; FileName: "explorer.exe"; Parameters: "http://localhost/"
;DOUBLECLICKACTIONEND

[StartupAction]
;AMPRICOTSTARTUPACTIONSTART
Action: run; FileName: "ampricotupdater.exe"
Action: run; FileName: "hstart.exe"; Parameters: "/noconsole /silent /wait ' . $this->ampricotinstalldirroot . '/core/inc/harmonymode.bat"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' Refresh.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
Action: service; Service: AmpricotApache; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotMySQL; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
;AMPRICOTSTARTUPACTIONEND

[Menu.Right.Settings]
;AMPRICOTMENURIGHTSETTINGSSTART
AutoLineReduction=no
BarVisible=no
SeparatorsAlignment=center
SeparatorsFade=yes
SeparatorsFadeColor=clBtnShadow
SeparatorsFlatLines=yes
SeparatorsFont=Arial,8,clBlack,bold
SeparatorsGradientEnd=clGray
SeparatorsGradientStart=clSilver
SeparatorsGradientStyle=vertical
SeparatorsSeparatorStyle=caption
;AMPRICOTMENURIGHTSETTINGSEND

[Menu.Right]
;AMPRICOTMENURIGHTSTART
Type: item; Caption: "' . gettext('Public Homepage') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://localhost/"; Glyph: 99
Type: item; Caption: "' . gettext('Public Directory') . '"; Action: shellexecute; FileName: "'. $this->ampricotinstalldirroot . '/front/data/www"; Glyph: 99
Type: separator
Type: submenu; Caption: "' . gettext('Apache Alias') . '"; SubMenu: MenuApacheAlias; Glyph: 99
Type: submenu; Caption: "' . gettext('Virtual Host') . '"; SubMenu: MenuApacheVHost; Glyph: 99
Type: separator
Type: submenu; Caption: "' . gettext('Apache') . '"; SubMenu: MenuApache; Glyph: 99
Type: submenu; Caption: "' . gettext('MySQL') . '"; SubMenu: MenuMySQL; Glyph: 99
Type: submenu; Caption: "' . gettext('PHP') . '"; SubMenu: MenuPHP; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Start All Services') . '"; Action: multi; Actions: ActionServiceStartAll; Glyph: 99
Type: item; Caption: "' . gettext('Stop All Services') . '"; Action: multi; Actions: ActionServiceStopAll; Glyph: 99
Type: item; Caption: "' . gettext('Restart All Services') . '"; Action: multi; Actions: ActionServiceRestartAll; Glyph: 99
Type: separator
Type: submenu; Caption: "' . gettext('&Language') . '"; SubMenu: MenuLanguage; Glyph: 99
Type: separator
Type: submenu; Caption: "' . gettext('Ad&vanced') . '"; SubMenu: MenuAdvanced; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('&Support') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://www.ampricot.com/support/"; Glyph: 99
Type: item; Caption: "' . gettext('&About') . '"; Action: about; Glyph: 99
Type: item; Caption: "' . gettext('E&xit') . '"; Action: multi; Actions: ActionExit; Glyph: 99
;AMPRICOTMENURIGHTEND

[ActionReload]
;ACTIONRELOADSTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' Refresh.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONRELOADEND

[ActionExit]
;ACTIONEXITSTART
Action: run; FileName: "hstart.exe"; Parameters: "/noconsole /silent /wait ' . $this->ampricotinstalldirroot . '/core/inc/killupdater.bat"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "hstart.exe"; Parameters: "/noconsole /silent /wait ' . $this->ampricotinstalldirroot . '/core/inc/cleanonexit.bat"; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotMySQL; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: exit
;ACTIONEXITEND

[ActionServiceStartAll]
;ACTIONSERVICESTARTALLSTART
Action: service; Service: AmpricotApache; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotMySQL; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
;ACTIONSERVICESTARTALLEND

[ActionServiceStopAll]
;ACTIONSERVICESTOPALLSTART
Action: service; Service: AmpricotApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotMySQL; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
;ACTIONSERVICESTOPALLEND

[ActionServiceRestartAll]
;ACTIONSERVICERESTARTALLSTART
Action: service; Service: AmpricotApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotMySQL; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotApache; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotMySQL; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
;ACTIONSERVICERESTARTALLEND

[ActionSwitchHarmonyMode]
;ACTIONSWITCHHARMONYMODESTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' SwitchHarmonyMode.php on"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' Refresh.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotApache; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONSWITCHHARMONYMODEEND

[ActionSwitchServerStatus]
;ACTIONSWITCHSERVERSTATUSSTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' SwitchServerStatus.php on"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' Refresh.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotApache; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONSWITCHSERVERSTATUSEND

[ActionCleanOnExit]
;ACTIONCLEANONEXITSTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' SwitchCleanOnExit.php on"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' Refresh.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONCLEANONEXITEND

[MenuLanguage]
;MENULANGUAGESTART
;MENULANGUAGEEND

;ACTIONLANGUAGE

[MenuAdvanced]
;MENUADVANCEDSTART
Type: submenu; Caption: "' . gettext('Browse Directories') . '"; SubMenu: MenuAdvancedBrowse; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Activate &Harmony Mode') . '"; Action: multi; Actions: ActionSwitchHarmonyMode; Glyph: 99
Type: item; Caption: "' . gettext('Server On The &Web') . '"; Action: multi; Actions: ActionSwitchServerStatus; Glyph: 99
Type: item; Caption: "' . gettext('&Clean On Exit') . '"; Action: multi; Actions: ActionCleanOnExit; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('&Reload Settings') . '"; Action: multi; Actions: ActionReload; Glyph: 99
;MENUADVANCEDEND

[MenuAdvancedBrowse]
;MENUADVANCEDBROWSESTART
Type: item; Caption: "' . gettext('cgi-bin') . '"; Action: shellexecute; FileName: "' . $this->ampricotinstalldirroot . '/front/data/cgi-bin"; Glyph: 99
Type: item; Caption: "' . gettext('MySQL Data') . '"; Action: shellexecute; FileName: "' . $this->ampricotinstalldirroot . '/front/data/mysql/mysql-' . $this->ampricotversionmysql . '"; Glyph: 99
;MENUADVANCEDBROWSEEND

[MenuApache]
;MENUAPACHESTART
Type: submenu; Caption: "' . gettext('Version') . '"; SubMenu: MenuApacheVersion; Glyph: 99
Type: submenu; Caption: "' . gettext('Service') . '"; SubMenu: MenuApacheService; Glyph: 99
Type: submenu; Caption: "' . gettext('Modules') . '"; SubMenu: MenuApacheModule; Glyph: 99
Type: submenu; Caption: "' . gettext('Tools') . '"; SubMenu: MenuApacheTools; Glyph: 99
;MENUAPACHEEND

[MenuApacheVersion]
;MENUAPACHEVERSIONSTART
;MENUAPACHEVERSIONEND

;ACTIONAPACHEVERSION

[MenuApacheService]
;MENUAPACHESERVICESTART
Type: item; Caption: "' . gettext('Start Service') . '"; Action: service; Service: AmpricotApache; ServiceAction: startresume; Glyph: 99
Type: item; Caption: "' . gettext('Stop Service') . '"; Action: service; Service: AmpricotApache; ServiceAction: stop; Glyph: 99
Type: item; Caption: "' . gettext('Restart Service') . '"; Action: service; Service: AmpricotApache; ServiceAction: restart; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Install Service') . '"; Action: multi; Actions: ActionApacheServiceInstall; Glyph: 99
Type: item; Caption: "' . gettext('Uninstall Service') . '"; Action: multi; Actions: ActionApacheServiceUninstall; Glyph: 99
;MENUAPACHESERVICEEND

[MenuApacheAlias]
Type: item; Caption: "' . gettext('Add Alias') . '"; Action: multi; Actions: ActionApacheAliasAdd; Glyph : 16
Type: separator
;MENUAPACHEALIASSTART
;MENUAPACHEALIASEND

;MENUAPACHEALIASCONTROLSTART

;ACTIONAPACHEALIASCONTROL

[MenuApacheVHost]
Type: item; Caption: "' . gettext('Add Virtual Host') . '"; Action: multi; Actions: ActionApacheVHostAdd; Glyph : 16
Type: separator
;MENUAPACHEVHOSTSTART
;MENUAPACHEVHOSTEND

;MENUAPACHEVHOSTCONTROLSTART

;ACTIONAPACHEVHOSTCONTROLS

[MenuApacheModule]
;MENUAPACHEMODULESTART
;MENUAPACHEMODULEEND

;ACTIONAPACHEMODULE

[MenuApacheTools]
;MENUAPACHETOOLSSTART
Type: item; Caption: "' . gettext('Test Port 80') . '"; Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php.exe"; Parameters: "-c ' . $this->ampricotphpini . ' TestPort.php 0"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated; Glyph: 99
Type: item; Caption: "' . gettext('httpd.conf') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->ampricotapacheconf . '"; Glyph: 99
Type: item; Caption: "' . gettext('access.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->ampricotdirtmp . '/log/apache/access.log"; Glyph: 99
Type: item; Caption: "' . gettext('error.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->ampricotdirtmp . '/log/apache/error.log"; Glyph: 99
;MENUAPACHETOOLSEND

[ActionApacheServiceInstall]
;ACTIONAPACHESERVICEINSTALLSTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php.exe"; Parameters: "-c ' . $this->ampricotphpini . ' TestPort.php 1"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirapache . '/apache-' . $this->ampricotversionapache . '/bin/httpd.exe"; Parameters: "' . $this->ampricotserviceapacheinstall . '"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "sc"; Parameters: "config AmpricotApache start= demand"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHESERVICEINSTALLEND

[ActionApacheServiceUninstall]
;ACTIONAPACHESERVICEUNINSTALLSTART
Action: service; Service: AmpricotApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirapache . '/apache-' . $this->ampricotversionapache . '/bin/httpd.exe"; Parameters: "' . $this->ampricotserviceapacheuninstall . '"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHESERVICEUNINSTALLEND

[ActionApacheAliasAdd]
;ACTIONAPACHEALIASADDSTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php.exe"; Parameters: "-c ' . $this->ampricotphpini . ' AliasAdd.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' Refresh.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotApache; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONAPACHEALIASADDEND

[ActionApacheVHostAdd]
;ACTIONAPACHEVHOSTADDSTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php.exe"; Parameters: "-c ' . $this->ampricotphpini . ' VHostAdd.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php-win.exe"; Parameters: "-c ' . $this->ampricotphpini . ' Refresh.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotApache; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONAPACHEVHOSTADDEND

[MenuMySQL]
;MEWNUMYSQLSTART
Type: submenu; Caption: "' . gettext('Version') . '"; SubMenu: MenuMySQLVersion; Glyph: 99
Type: submenu; Caption: "' . gettext('Service') . '"; SubMenu: MenuMySQLService; Glyph: 99
Type: submenu; Caption: "' . gettext('Tools') . '"; SubMenu: MenuMySQLTools; Glyph: 99
;MEWNUMYSQLEND

[MenuMySQLVersion]
;MENUMYSQLVERSIONSTART
;MENUMYSQLVERSIONEND

;ACTIONMYSQLVERSION

[MenuMySQLService]
;MENUMYSQLSERVICESTART
Type: item; Caption: "' . gettext('Start Service') . '"; Action: service; Service: AmpricotMySQL; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated; Glyph: 99
Type: item; Caption: "' . gettext('Stop Service') . '"; Action: service; Service: AmpricotMySQL; ServiceAction: stop; Glyph: 99
Type: item; Caption: "' . gettext('Restart Service') . '"; Action: service; Service: AmpricotMySQL; ServiceAction: restart; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Install Service') . '"; Action: multi; Actions: ActionMySQLServiceInstall; Glyph: 99
Type: item; Caption: "' . gettext('Uninstall Service') . '"; Action: multi; Actions: ActionMySQLServiceUninstall; Glyph: 99
;MENUMYSQLSERVICEEND

[MenuMySQLTools]
;MENUMYSQLTOOLSSTART
Type: item; Caption: "' . gettext('Console') . '"; Action: run; FileName: "' . $this->ampricotinstalldirmysql . '/mysql-' . $this->ampricotversionmysql . '/bin/mysql.exe"; Parameters: "-u root -p"; Glyph: 99
Type: item; Caption: "' . gettext('Reset Root Password') . '"; Action: multi; Actions: ActionResetMySQLPass; Glyph: 99
Type: item; Caption: "' . gettext('mysql.ini') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->ampricotmysqlini . '"; Glyph: 99
Type: item; Caption: "' . gettext('error.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->ampricotdirtmp . '/log/mysql/error.log"; Glyph: 99
;MENUMYSQLTOOLSEND

[ActionResetMySQLPass]
;ACTIONRESETMYSQLPASSSTART
Action: run; FileName: "' . $this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php.exe"; Parameters: "-c ' . $this->ampricotphpini . ' ResetMySQLPass.php"; WorkingDir: "' . $this->ampricotinstalldirroot . '/core/inc/scripts"; Flags: ignoreerrors waituntilterminated
Action: service; Service: AmpricotMySQL; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONRESETMYSQLPASSEND

[ActionMySQLServiceInstall]
;ACTIONMYSQLSERVICEINSTALLSTART
Action: run; FileName: "' . $this->ampricotinstalldirmysql . '/mysql-' . $this->ampricotversionmysql . '/bin/mysqld.exe"; Parameters: "' . $this->ampricotservicemysqlinstall . '"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONMYSQLSERVICEINSTALLEND

[ActionMySQLServiceUninstall]
;ACTIONMYSQLSERVICEUNINSTALLSTART
Action: service; Service: AmpricotMySQL; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->ampricotinstalldirmysql . '/mysql-' . $this->ampricotversionmysql . '/bin/mysqld.exe"; Parameters: "' . $this->ampricotservicemysqluninstall . '"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONMYSQLSERVICEUNINSTALLEND

[MenuPHP]
;MENUPHPSTART
Type: submenu; Caption: "' . gettext('Version') . '"; SubMenu: MenuPHPVersion; Glyph: 99
Type: submenu; Caption: "' . gettext('Settings') . '"; SubMenu: MenuPHPSetting; Glyph: 99
Type: submenu; Caption: "' . gettext('Extensions') . '"; SubMenu: MenuPHPExtension; Glyph: 99
Type: submenu; Caption: "' . gettext('Tools') . '"; SubMenu: MenuPHPTools; Glyph: 99
;MENUPHPEND

[MenuPHPVersion]
;MENUPHPVERSIONSTART
;MENUPHPVERSIONEND

;ACTIONPHPVERSION

[MenuPHPSetting]
;MENUPHPSETTINGSTART
;MENUPHPSETTINGEND

;ACTIONPHPSETTING

[MenuPHPExtension]
;MENUPHPEXTENSIONSTART
;MENUPHPEXTENSIONEND

[MenuPHPTools]
;MENUPHPTOOLSSTART
Type: item; Caption: "' . gettext('php.ini') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->ampricotphpini . '"; Glyph: 99
Type: item; Caption: "' . gettext('error.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->ampricotdirtmp . '/log/php/error.log"; Glyph: 99
;MENUPHPTOOLSEND

;ACTIONPHPEXTENSION

';

return $ampricottpl;
