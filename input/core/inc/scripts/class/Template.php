<?php
/*==================================================================================*\
|| ################################################################################ ||
|| # Product Name: Apricore                                        Version: 1.0.0 # ||
|| # License Type: Free License                                                   # ||
|| # ---------------------------------------------------------------------------- # ||
|| # 																			  # ||
|| #           Copyright ©2005-2012 FruiTechLabs. All Rights Reserved.           # ||
|| #     This product may be redistributed in whole or significant part under     # ||
|| # "The MIT License (MIT)" - http://www.opensource.org/licenses/mit-license.php # ||
|| # 																			  # ||
|| # ----------------------- "Apricore" IS FREE SOFTWARE ------------------------ # ||
|| #        http://apricore.fruitechlabs.com | http://www.fruitechlabs.com        # ||
|| ################################################################################ ||
\*==================================================================================*/


$apricoretpl = '
[Config]
;APRICORECONFIGSTART
ImageList=imagelist.bmp
ServiceCheckInterval=1
ServiceGlyphRunning=99
ServiceGlyphPaused=99
ServiceGlyphStopped=99
TrayIconAllRunning=2
TrayIconSomeRunning=1
TrayIconNoneRunning=0
ID={apricore}
AboutHeader=Apricore
AboutVersion=' . $this->apricoreversioncore . '
;APRICORECONFIGEND

[AboutText]
Copyright (c) 2012 FruiTechLabs.
http://apricore.fruitechlabs.com

[Services]
Name: ApricoreApache
Name: ApricoreMySQL

[Messages]
AllRunningHint=Apricore - ' . gettext('All sevices running!') . '
SomeRunningHint=Apricore - ' . gettext('Some sevices running!') . '
NoneRunningHint=Apricore - ' . gettext('No sevices running!') . '

[DoubleClickAction]
;DOUBLECLICKACTIONSTART
Action: run; FileName: "explorer.exe"; Parameters: "http://localhost/"
;DOUBLECLICKACTIONEND

[StartupAction]
;APRICORESTARTUPACTIONSTART
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . $this->apricorephpini . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig
Action: service; Service: ApricoreApache; ServiceAction: startresume; Flags: ignoreerrors
Action: service; Service: ApricoreMySQL; ServiceAction: startresume; Flags: ignoreerrors
;APRICORESTARTUPACTIONEND

[Menu.Right.Settings]
;APRICOREMENURIGHTSETTINGSSTART
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
;APRICOREMENURIGHTSETTINGSEND

[Menu.Right]
;APRICOREMENURIGHTSTART
Type: item; Caption: "' . gettext('Public Homepage') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://localhost/"; Glyph: 99
Type: item; Caption: "' . gettext('Public Directory') . '"; Action: shellexecute; FileName: "'. $this->apricoreinstalldirroot . '/front/data/www"; Glyph: 99
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
Type: item; Caption: "' . gettext('&Support') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://apricore.fruitechlabs.com/support"; Glyph: 99
Type: item; Caption: "' . gettext('&About') . '"; Action: about; Glyph: 99
Type: item; Caption: "' . gettext('E&xit') . '"; Action: multi; Actions: ActionExit; Glyph: 99
;APRICOREMENURIGHTEND

[ActionReload]
;ACTIONRELOADSTART
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . $this->apricorephpini . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONRELOADEND

[ActionExit]
;ACTIONEXITSTART
Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors
Action: service; Service: ApricoreMySQL; ServiceAction: stop; Flags: ignoreerrors
Action: exit
;ACTIONEXITEND

[ActionServiceStartAll]
;ACTIONSERVICESTARTALLSTART
Action: service; Service: ApricoreApache; ServiceAction: startresume; Flags: ignoreerrors
Action: service; Service: ApricoreMySQL; ServiceAction: startresume; Flags: ignoreerrors
;ACTIONSERVICESTARTALLEND

[ActionServiceStopAll]
;ACTIONSERVICESTOPALLSTART
Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors
Action: service; Service: ApricoreMySQL; ServiceAction: stop; Flags: ignoreerrors
;ACTIONSERVICESTOPALLEND

[ActionServiceRestartAll]
;ACTIONSERVICERESTARTALLSTART
Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: service; Service: ApricoreMySQL; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: service; Service: ApricoreApache; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
Action: service; Service: ApricoreMySQL; ServiceAction: startresume; Flags: ignoreerrors waituntilterminated
;ACTIONSERVICERESTARTALLEND

[ActionSwitchServerStatus]
;ACTIONSWITCHSERVERSTATUSSTART
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . $this->apricorephpini . ' SwitchServerStatus.php online"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . $this->apricorephpini . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: service; Service: ApricoreApache; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONSWITCHSERVERSTATUSEND

[MenuLanguage]
;MENULANGUAGESTART
;MENULANGUAGEEND

;ACTIONLANGUAGE

[MenuAdvanced]
;MENUADVANCEDSTART
Type: submenu; Caption: "' . gettext('Browse Directories') . '"; SubMenu: MenuAdvancedBrowse; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Switch Server Online') . '"; Action: multi; Actions: ActionSwitchServerStatus; Glyph: 99
Type: item; Caption: "' . gettext('&Reload Settings') . '"; Action: multi; Actions: ActionReload; Glyph: 99
;MENUADVANCEDEND

[MenuAdvancedBrowse]
;MENUADVANCEDBROWSESTART
Type: item; Caption: "' . gettext('cgi-bin') . '"; Action: shellexecute; FileName: "' . $this->apricoreinstalldirroot . '/front/data/cgi-bin"; Glyph: 99
Type: item; Caption: "' . gettext('MySQL Data') . '"; Action: shellexecute; FileName: "' . $this->apricoreinstalldirroot . '/front/data/mysql/mysql-' . $this->apricoreversionmysql . '"; Glyph: 99
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
Type: item; Caption: "' . gettext('Start Service') . '"; Action: service; Service: ApricoreApache; ServiceAction: startresume; Glyph: 99
Type: item; Caption: "' . gettext('Stop Service') . '"; Action: service; Service: ApricoreApache; ServiceAction: stop; Glyph: 99
Type: item; Caption: "' . gettext('Restart Service') . '"; Action: service; Service: ApricoreApache; ServiceAction: restart; Glyph: 99
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
Type: item; Caption: "' . gettext('Test Port 80') . '"; Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "-c ' . $this->apricorephpini . ' TestPort.php 0"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated; Glyph: 99
Type: item; Caption: "' . gettext('httpd.conf') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoreapacheconf . '"; Glyph: 99
Type: item; Caption: "' . gettext('access.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoredirtmp . '/log/apache/access.log"; Glyph: 99
Type: item; Caption: "' . gettext('error.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoredirtmp . '/log/apache/error.log"; Glyph: 99
;MENUAPACHETOOLSEND

[ActionApacheServiceInstall]
;ACTIONAPACHESERVICEINSTALLSTART
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "-c ' . $this->apricorephpini . ' TestPort.php 1"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirapache . '/apache-' . $this->apricoreversionapache . '/bin/httpd.exe"; Parameters: "' . $this->apricoreserviceapacheinstall . '"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "config ApricoreApache start= demand"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHESERVICEINSTALLEND

[ActionApacheServiceUninstall]
;ACTIONAPACHESERVICEUNINSTALLSTART
Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirapache . '/apache-' . $this->apricoreversionapache . '/bin/httpd.exe"; Parameters: "' . $this->apricoreserviceapacheuninstall . '"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHESERVICEUNINSTALLEND

[ActionApacheAliasAdd]
;ACTIONAPACHEALIASADDSTART
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "-c ' . $this->apricorephpini . ' AliasAdd.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . $this->apricorephpini . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: service; Service: ApricoreApache; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONAPACHEALIASADDEND

[ActionApacheVHostAdd]
;ACTIONAPACHEVHOSTADDSTART
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "-c ' . $this->apricorephpini . ' VHostAdd.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . $this->apricorephpini . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: service; Service: ApricoreApache; ServiceAction: restart
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
Type: item; Caption: "' . gettext('Start Service') . '"; Action: service; Service: ApricoreMySQL; ServiceAction: startresume; Flags: ignoreerrors; Glyph: 99
Type: item; Caption: "' . gettext('Stop Service') . '"; Action: service; Service: ApricoreMySQL; ServiceAction: stop; Glyph: 99
Type: item; Caption: "' . gettext('Restart Service') . '"; Action: service; Service: ApricoreMySQL; ServiceAction: restart; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Install Service') . '"; Action: multi; Actions: ActionMySQLServiceInstall; Glyph: 99
Type: item; Caption: "' . gettext('Uninstall Service') . '"; Action: multi; Actions: ActionMySQLServiceUninstall; Glyph: 99
;MENUMYSQLSERVICEEND

[MenuMySQLTools]
;MENUMYSQLTOOLSSTART
Type: item; Caption: "' . gettext('Console') . '"; Action: run; FileName: "' . $this->apricoreinstalldirmysql . '/mysql-' . $this->apricoreversionmysql . '/bin/mysql.exe"; Parameters: "-u root -p"; Glyph: 99
Type: item; Caption: "' . gettext('Reset Root Password') . '"; Action: multi; Actions: ActionResetMySQLPass; Glyph: 99
Type: item; Caption: "' . gettext('mysql.ini') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoremysqlini . '"; Glyph: 99
Type: item; Caption: "' . gettext('error.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoredirtmp . '/log/mysql/error.log"; Glyph: 99
;MENUMYSQLTOOLSEND

[ActionResetMySQLPass]
;ACTIONRESETMYSQLPASSSTART
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "-c ' . $this->apricorephpini . ' ResetMySQLPass.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: service; Service: ApricoreMySQL; ServiceAction: restart
Action: resetservices
Action: readconfig
;ACTIONRESETMYSQLPASSEND

[ActionMySQLServiceInstall]
;ACTIONMYSQLSERVICEINSTALLSTART
Action: run; FileName: "' . $this->apricoreinstalldirmysql . '/mysql-' . $this->apricoreversionmysql . '/bin/mysqld.exe"; Parameters: "' . $this->apricoreservicemysqlinstall . '"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONMYSQLSERVICEINSTALLEND

[ActionMySQLServiceUninstall]
;ACTIONMYSQLSERVICEUNINSTALLSTART
Action: service; Service: ApricoreMySQL; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirmysql . '/mysql-' . $this->apricoreversionmysql . '/bin/mysqld.exe"; Parameters: "' . $this->apricoreservicemysqluninstall . '"; ShowCmd: hidden; Flags: waituntilterminated
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
Type: item; Caption: "' . gettext('php.ini') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricorephpini . '"; Glyph: 99
Type: item; Caption: "' . gettext('error.log') . '"; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoredirtmp . '/log/php/error.log"; Glyph: 99
;MENUPHPTOOLSEND

;ACTIONPHPEXTENSION

';

return $apricoretpl;
