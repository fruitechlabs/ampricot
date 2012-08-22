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


namespace Apricore;
require_once 'Kernel.php';
require_once 'Process.php';

class Reload extends Kernel
{
	public function __construct()
	{
		parent::__construct();
		$this->apricoretpl = require_once 'Template.php';
	}

	public function serverstatus()
	{
		if ($this->apricoreconf['apricorestatus'] == 'online')
		{
			$this->apricoretpl = str_replace(array(gettext('Switch Server Online'), 'SwitchServerStatus.php online', 'Glyph: 99'), array(gettext('Switch Server Offline'), 'SwitchServerStatus.php offline', 'Glyph: 99'), $this->apricoretpl);
		}
	}

	public function language()
	{
		if ($handle = @opendir($this->apricoredirlang))
		{
			$languages = array();

			while (($folder = @readdir($handle)) !== false)
			{
				if ($folder != '.' && $folder != '..' && is_dir($this->apricoredirlang . '/' . $folder))
				{
					if ($folder == $this->apricorelang)
					{
						$languages["$folder"] = 1;
					}
					else
					{
						$languages["$folder"] = 0;
					}
				}
			}

			closedir($handle);
		}

		ksort($languages);
		$menulanguage = ";MENULANGUAGESTART\r\n";
		$actionlanguage = '';

		foreach ($languages as $langname => $langstatus)
		{
			$menulanguage .= 'Type: item; Caption: "' . gettext($langname) . '"; Action: multi; Actions: ActionLang_' . str_replace(' ', '', $langname) . (($langstatus) ? '; Glyph: 3' : '') . "\r\n";
			$actionlanguage .= '[ActionLang_' . ucwords(str_replace(' ', '', $langname)) . "]\r\n;ACTIONLANG_" . strtoupper(str_replace(' ', '', $langname)) . "_START\r\n" .
'Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' SwitchLang.php ' . $langname . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONLANG_' . strtoupper(str_replace(' ', '', $langname)) . "_END\r\n";
		}

		$this->apricoretpl = str_replace(';MENULANGUAGESTART', $menulanguage, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONLANGUAGE', $actionlanguage, $this->apricoretpl);
	}

	public function phpextension()
	{
		$phpextini = array();
		$phpinilines = @file($this->apricorephpini) or die(gettext('Sorry, PHP configuration file \'php.ini\' doesn\'t exist!'));

		// Loaded PHP extensions (php.ini)
		foreach($phpinilines as $line)
		{
			$phpextmatch = array();

			if(preg_match('/^(;)?extension\s*=\s*"?([a-z0-9_]+)\.dll"?/i', $line, $phpextmatch))
			{
				$phpextname = $phpextmatch[2];

				if($phpextmatch[1] == ';')
				{
					$phpextini[$phpextname] = 0;
				}
				else
				{
					$phpextini[$phpextname] = 1;
				}
			}
		}

		// Actual existing PHP extensions (File System)
		if ($handle = @opendir($this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/ext/'))
		{
			$phpextfiles = array();

			while (($file = @readdir($handle)) !== false)
			{
				if ($file != "." && $file != ".." && strstr($file, '.dll'))
				{
					$phpextfiles[str_replace('.dll', '', $file)] = $phpextini[str_replace('.dll', '', $file)];
				}
			}

			closedir($handle);
		}

		ksort($phpextfiles);
		$menuphpextension = ";MENUPHPEXTENSIONSTART\r\n";
		$actionphpextension = '';

		foreach ($phpextfiles as $phpextname => $phpextstatus)
		{
			$menuphpextension .= 'Type: item; Caption: "' . $phpextname . '"; Action: multi; Actions: ActionPHPExtention_' . ucwords($phpextname) . (($phpextstatus) ? '; Glyph: 3' : '') . "\r\n";
			$actionphpextension .= '[ActionPHPExtention_' . ucwords($phpextname) . "]\r\n;ACTIONPHPEXTENSION_" . strtoupper($phpextname) . "_START\r\n" .
'Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' SwitchPHPExtension.php ' . $phpextname . (($phpextstatus) ? ' off' : ' on') . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreApache"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONPHPEXTENSION_' . strtoupper($phpextname) . "_END\r\n";
		}

		$this->apricoretpl = str_replace(';MENUPHPEXTENSIONSTART', $menuphpextension, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONPHPEXTENSION', $actionphpextension, $this->apricoretpl);
	}

	public function phpsetting()
	{
		$phpiniactual = array();
		$phpinisettings = @parse_ini_file($this->apricorephpini);
		$phpinisettingsraw = @parse_ini_file($this->apricorephpini, null, true);

		foreach($phpinisettingsraw as $phpsettingkey => $phpsettingvalue)
		{
			if (in_array($phpsettingvalue, array('On', 'Off')) && $phpsettingkey != 'engine')
			{
				$phpiniactual[$phpsettingkey] = intval($phpinisettings[$phpsettingkey]);
			}
		}

		ksort($phpiniactual);
		$menuphpsetting = ";MENUPHPSETTINGSTART\r\n";
		$actionphpsetting = '';

		foreach ($phpiniactual as $phpsettingkey => $phpsettingvalue)
		{
			$menuphpsetting .= 'Type: item; Caption: "' . $phpsettingkey . '"; Action: multi; Actions: ActionPHPSetting_' . ucwords($phpsettingkey) . (($phpsettingvalue) ? '; Glyph: 3' : '') . "\r\n";
			$actionphpsetting .= '[ActionPHPSetting_' . ucwords($phpsettingkey) . "]\r\n;ACTIONPHPSETTING_" . strtoupper($phpsettingkey) . "_START\r\n" .
'Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' SwitchPHPSetting.php ' . $phpsettingkey . (($phpsettingvalue) ? ' off' : ' on') . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreApache"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONPHPSETTING_' . strtoupper($phpsettingkey) . "_END\r\n";
		}

		$this->apricoretpl = str_replace(';MENUPHPSETTINGSTART', $menuphpsetting, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONPHPSETTING', $actionphpsetting, $this->apricoretpl);
	}

	public function apachemodule()
	{
		$apacheconflines = @file($this->apricoreapacheconf) or die(gettext('Sorry, Apache configuration file \'httpd.conf\' doesn\'t exist!'));

		foreach($apacheconflines as $line)
		{
			$apachemodulenames = explode(' ', $line);

			if (preg_match('|^#LoadModule|', $line))
			{
				$apachemodules[$apachemodulenames[1]] = 0;
			}
			elseif (preg_match('|^LoadModule|',$line))
			{
				$apachemodules[$apachemodulenames[1]] = 1;
			}
		}

		ksort($apachemodules);
		$menuapachemodule = ";MENUAPACHEMODULESTART\r\n";
		$actionapachemodule = '';

		foreach ($apachemodules as $apachemodulename => $apachemodulestatus)
		{
			$menuapachemodule .= 'Type: item; Caption: "' . $apachemodulename . '"; Action: multi; Actions: ActionApacheModule_' . ucwords($apachemodulename) . (($apachemodulestatus) ? '; Glyph: 3' : '') . "\r\n";
			$actionapachemodule .= '[ActionApacheModule_' . ucwords($apachemodulename) . "]\r\n;ACTIONAPACHEMODULE_" . strtoupper($apachemodulename) . "_START\r\n" .
'Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' SwitchApacheModule.php ' . $apachemodulename . (($apachemodulestatus) ? ' off' : ' on') . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreApache"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHEMODULE_' . strtoupper($apachemodulename) . "_END\r\n";
		}

		$this->apricoretpl = str_replace(';MENUAPACHEMODULESTART', $menuapachemodule, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONAPACHEMODULE', $actionapachemodule, $this->apricoretpl);
	}

	public function apachealias()
	{
		if ($handle = @opendir($this->apricorediralias))
		{
			$apachealiasexisting = array();

			while (($file = @readdir($handle)) !== false)
			{
				if ($file != "." && $file != ".." && strstr($file, '.conf'))
				{
					$apachealiasexisting[] = $file;
				}
			}

			closedir($handle);
		}

		ksort($apachealiasexisting);
		$menuapachealias = ";MENUAPACHEALIASSTART\r\n";
		$actionapachealias = '';
		$menuapachealiascontrol = '';
		$actionapachealiascontrol = '';

		foreach ($apachealiasexisting as $apachealiasname)
		{
			$cleanapachealiasname = str_replace('.conf', '', $apachealiasname);
			$menuapachealias .= 'Type: submenu; Caption: "http://localhost/' . $cleanapachealiasname . '/"; SubMenu: MenuApacheAliasControl_' . ucwords($cleanapachealiasname) . "; Glyph: 99\r\n";
			$menuapachealiascontrol .= '[MenuApacheAliasControl_' . ucwords($cleanapachealiasname) . "]\r\n;MENUAPACHEALIASCONTROL_" . strtoupper($cleanapachealiasname) . "_START\r\n" .
'Type: item; Caption: "' . gettext('Access Online') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://localhost/' . $cleanapachealiasname . '"; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Edit Alias') . '"; Action: multi; Actions: ActionApacheAliasEdit_' . ucwords($cleanapachealiasname) . '; Glyph: 99
Type: item; Caption: "' . gettext('Delete Alias') . '"; Action: multi; Actions: ActionApacheAliasDelete_' . ucwords($cleanapachealiasname) . '; Glyph: 99
;MENUAPACHEALIASCONTROL_' . strtoupper($cleanapachealiasname) . "_END\r\n";
			$actionapachealiascontrol .= '[ActionApacheAliasEdit_' . ucwords($cleanapachealiasname) . "]\r\n;ACTIONAPACHEALIASEDIT_" . strtoupper($cleanapachealiasname) . "_START\r\n" .
'Action: run; FileName: "notepad.exe"; parameters:"' . $this->apricoreinstalldirroot . '/front/conf/apache/alias/' . $apachealiasname . '"; Flags: waituntilterminated
Action: service; Service: ApricoreApache; ServiceAction: restart; Flags: ignoreerrors waituntilterminated
;ACTIONAPACHEALIASEDIT_' . strtoupper($cleanapachealiasname) . 'END
;ACTIONAPACHEALIASDELETE_' . strtoupper($cleanapachealiasname) . "_START\r\n[ActionApacheAliasDelete_" . ucwords($cleanapachealiasname) . ']
Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' AliasDelete.php ' . $apachealiasname . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreApache"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHEALIASDELETE_' . strtoupper($cleanapachealiasname) . "_END\r\n";
		}

		$this->apricoretpl = str_replace(';MENUAPACHEALIASSTART', $menuapachealias, $this->apricoretpl);
		$this->apricoretpl = str_replace(';MENUAPACHEALIASCONTROLSTART', $menuapachealiascontrol, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONAPACHEALIASCONTROL', $actionapachealiascontrol, $this->apricoretpl);
	}

	public function apachevhost()
	{
		if ($handle = @opendir($this->apricoredirvhost))
		{
			$apachevhostexisting = array();

			while (($file = @readdir($handle)) !== false)
			{
				if ($file != "." && $file != ".." && strstr($file, '.conf'))
				{
					$apachevhostexisting[] = $file;
				}
			}

			closedir($handle);
		}

		ksort($apachevhostexisting);
		$menuapachevhost = ";MENUAPACHEVHOSTSTART\r\n";
		$actionapachevhost = '';
		$menuapachevhostcontrol = '';
		$actionapachevhostcontrols = '';

		foreach ($apachevhostexisting as $apachevhostname)
		{
			$cleanapachevhostname = str_replace('.conf', '', $apachevhostname);
			$menuapachevhost .= 'Type: submenu; Caption: "http://' . $cleanapachevhostname . '/"; SubMenu: MenuApacheVHostControl_' . ucwords($cleanapachevhostname) . "; Glyph: 99\r\n";
			$menuapachevhostcontrol .= '[MenuApacheVHostControl_' . ucwords($cleanapachevhostname) . "]\r\n;MENUAPACHEVHOSTCONTROL_" . strtoupper($cleanapachevhostname) . "_START\r\n" .
'Type: item; Caption: "' . gettext('Access Online') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://' . $cleanapachevhostname . '/"; Glyph: 99
Type: item; Caption: "' . gettext('Browse Directory') . '"; Action: shellexecute; FileName: "'. $this->apricoreinstalldirroot . '/front/data/www/' . $cleanapachevhostname . '"; Glyph: 99
Type: separator
Type: item; Caption: "' . gettext('Edit Virtual Host') . '"; Action: multi; Actions: ActionApacheVHostEdit_' . ucwords($cleanapachevhostname) . '; Glyph: 99
Type: item; Caption: "' . gettext('Delete Virtual Host') . '"; Action: multi; Actions: ActionApacheVHostDelete_' . ucwords($cleanapachevhostname) . '; Glyph: 99
Type: separator
Type: item; Caption: ' . gettext('access.log') . '; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoredirtmp . '/log/apache/' . $cleanapachevhostname . '/access.log"; Glyph: 99
Type: item; Caption: ' . gettext('error.log') . '; Action: run; FileName: "notepad.exe"; parameters: "' . $this->apricoredirtmp . '/log/apache/' . $cleanapachevhostname . '/error.log"; Glyph: 99
;MENUAPACHEVHOSTCONTROL_' . strtoupper($cleanapachevhostname) . "_END\r\n";
			$actionapachevhostcontrols .= '[ActionApacheVHostEdit_' . ucwords($cleanapachevhostname) . "]\r\n;ACTIONAPACHEVHOSTEDIT_" . strtoupper($cleanapachevhostname) . "_START\r\n" .
'Action: run; FileName: "notepad.exe"; parameters:"' . $this->apricoreinstalldirroot . '/front/conf/apache/vhost/' . $apachevhostname . '"; Flags: waituntilterminated
Action: service; Service: ApricoreApache; ServiceAction: restart; Flags: ignoreerrors waituntilterminated
;ACTIONAPACHEVHOSTEDIT_' . strtoupper($cleanapachevhostname) . 'END
;ACTIONAPACHEVHOSTDELETE_' . strtoupper($cleanapachevhostname) . "_START\r\n[ActionApacheVHostDelete_" . ucwords($cleanapachevhostname) . ']
Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' VHostDelete.php ' . $apachevhostname . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreApache"; ShowCmd: hidden; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHEVHOSTDELETE_' . strtoupper($cleanapachevhostname) . "_END\r\n";
		}

		$this->apricoretpl = str_replace(';MENUAPACHEVHOSTSTART', $menuapachevhost, $this->apricoretpl);
		$this->apricoretpl = str_replace(';MENUAPACHEVHOSTCONTROLSTART', $menuapachevhostcontrol, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONAPACHEVHOSTCONTROLS', $actionapachevhostcontrols, $this->apricoretpl);
	}

	public function apacheversion()
	{
		$process = new Process();
		$apacheversions = $process->listDir($this->apricoreinstalldirapache);
		$menuapacheversion = ";MENUAPACHEVERSIONSTART\r\n";
		$actionapacheversion = '';
		ksort($apacheversions);

		foreach ($apacheversions as $apacheversion)
		{
			$cleanapacheversion = str_replace('apache-', '', $apacheversion);
			$cleanapacheversion2 = str_replace('.', '_', $cleanapacheversion);
			$apachephpcompatibleversion = str_replace('.', '_', str_replace('.0', '', substr($cleanapacheversion, 0, strrpos($cleanapacheversion, '.'))));
			$apachephpcompatible = 1;

			if (!is_file($this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php5apache' . $apachephpcompatibleversion . '.dll'))
			{
				$apachephpcompatible = 0;
				$actionapacheversion .= '[ActionApacheVersion_' . $cleanapacheversion2 . "]\r\n;ACTIONAPACHEVERSION_" . $cleanapacheversion2 . "_START\r\n" .
'Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "MSG.php apachephpincompatible"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
;ACTIONAPACHEVERSION_' . $cleanapacheversion2 . "_END\r\n";
			}
			else
			{
				$actionapacheversion .= '[ActionApacheVersion_' . $cleanapacheversion2 . "]\r\n;ACTIONAPACHEVERSION_" . $cleanapacheversion2 . "_START\r\n" .
'Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: closeservices; Flags: ignoreerrors
Action: run; FileName: "' . $this->apricoreinstalldirapache . '/apache-' . $this->apricoreversionapache . '/bin/httpd.exe"; Parameters: "' . $this->apricoreserviceapacheuninstall . '"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' switchApacheVersion.php ' . $cleanapacheversion . '"; WorkingDir: "' . $this->apricoreinstalldirroot.'/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' SwitchPHPVersion.php ' . $this->apricoreversionphp . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirapache . '/apache-' . $cleanapacheversion . '/bin/httpd.exe"; Parameters: "' . $this->apricoreserviceapacheinstall . '"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "config ApricoreApache start= demand"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreApache"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONAPACHEVERSION_' . $cleanapacheversion2 . "_END\r\n";
			}

			$menuapacheversion .= 'Type: item; Caption: "' . $cleanapacheversion . '"; Action: multi; Actions: ActionApacheVersion_' . $cleanapacheversion2 . (($apachephpcompatible) ? (($cleanapacheversion == $this->apricoreversionapache) ? '; Glyph: 3' : '') : '; Glyph: 4') . "\r\n";
		}

		$menuapacheversion .= 'Type: separator
		Type: item; Caption: "' . gettext('More versions...') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://apricore.fruitechlabs.com/download#apache"' . "; Glyph: 99\r\n";

		$this->apricoretpl = str_replace(';MENUAPACHEVERSIONSTART', $menuapacheversion, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONAPACHEVERSION', $actionapacheversion, $this->apricoretpl);
	}

	public function phpversion()
	{
		$process = new Process();
		$phpversions = $process->listDir($this->apricoreinstalldirphp);
		$menuphpversion = ";MENUPHPVERSIONSTART\r\n";
		$actionphpversion = '';
		ksort($phpversions);

		foreach ($phpversions as $phpversion)
		{
			$cleanphpversion = str_replace('php-', '', $phpversion);
			$cleanphpversion2 = str_replace('.', '_', $cleanphpversion);
			$phpapachecompatibleversion = str_replace('.', '_', str_replace('.0', '', substr($this->apricoreversionapache, 0, strrpos($this->apricoreversionapache, '.'))));
			$phpapachecompatible = 1;

			if (!is_file($this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php5apache' . $phpapachecompatibleversion . '.dll'))
			{
				$phpapachecompatible = 0;
				$actionphpversion .= '[ActionPHPVersion_' . $cleanphpversion2 . "]\r\n;ACTIONPHPVERSION_" . $cleanphpversion2 . "_START\r\n" .
'Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php.exe"; Parameters: "MSG.php phpapacheincompatible"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
;ACTIONPHPVERSION_' . $cleanphpversion2 . "_END\r\n";
			}
			else
			{
				$actionphpversion .= '[ActionPHPVersion_' . $cleanphpversion2 . "]\r\n;ACTIONPHPVERSION_" . $cleanphpversion2 . "_START\r\n" .
'Action: service; Service: ApricoreApache; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' SwitchPHPVersion.php ' . $cleanphpversion . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreApache"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONPHPVERSION_' . $cleanphpversion2 . "_END\r\n";
			}

			$menuphpversion .= 'Type: item; Caption: "' . $cleanphpversion . '"; Action: multi; Actions: ActionPHPVersion_' . $cleanphpversion2 . (($phpapachecompatible) ? (($cleanphpversion == $this->apricoreversionphp) ? '; Glyph: 3' : '') : '; Glyph: 4') . "\r\n";
		}

		$menuphpversion .= 'Type: separator
Type: item; Caption: "' . gettext('More versions...') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://apricore.fruitechlabs.com/download#php"' . "; Glyph: 99\r\n";

		$this->apricoretpl = str_replace(';MENUPHPVERSIONSTART', $menuphpversion, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONPHPVERSION', $actionphpversion, $this->apricoretpl);
	}

	public function mysqlversion()
	{
		$process = new Process();
		$mysqlversions = $process->listDir($this->apricoreinstalldirmysql);
		$menumysqlversion = ";MENUMYSQLVERSIONSTART\r\n";
		$actionmysqlversion = '';
		ksort($mysqlversions);

		foreach ($mysqlversions as $mysqlversion)
		{
			$cleanmysqlversion = str_replace('mysql-', '', $mysqlversion);
			$cleanmysqlversion2 = str_replace('.', '_', $cleanmysqlversion);
			$menumysqlversion .= 'Type: item; Caption: "' . $cleanmysqlversion . '"; Action: multi; Actions: ActionMySQLVersion_' . $cleanmysqlversion2 . (($cleanmysqlversion == $this->apricoreversionmysql) ? '; Glyph: 3' : '') . "\r\n";
			$actionmysqlversion .= '[ActionMySQLVersion_' . $cleanmysqlversion2 . "]\r\n;ACTIONMYSQLVERSION_" . $cleanmysqlversion2 . "_START\r\n" .
'Action: service; Service: ApricoreMySQL; ServiceAction: stop; Flags: ignoreerrors waituntilterminated
Action: closeservices; Flags: ignoreerrors
Action: run; FileName: "' . $this->apricoreinstalldirmysql . '/mysql-' . $this->apricoreversionmysql . '/bin/mysqld.exe"; Parameters: "' . $this->apricoreservicemysqluninstall . '"; ShowCmd: hidden; Flags: ignoreerrors waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' SwitchMySQLVersion.php ' . $cleanmysqlversion . '"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirmysql . '/mysql-' . $cleanmysqlversion . '/bin/mysqld.exe"; Parameters: "' . $this->apricoreservicemysqlinstall . '"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "sc"; Parameters: "start ApricoreMySQL"; ShowCmd: hidden; Flags: waituntilterminated
Action: run; FileName: "' . $this->apricoreinstalldirphp . '/php-' . $this->apricoreversionphp . '/php-win.exe"; Parameters: "-c ' . str_replace(' ', '%20', $this->apricorephpini) . ' Refresh.php"; WorkingDir: "' . $this->apricoreinstalldirroot . '/core/inc/scripts"; Flags: waituntilterminated
Action: resetservices
Action: readconfig
;ACTIONMYSQLVERSION_' . $cleanmysqlversion2 . "_END\r\n";
		}

		$menumysqlversion .= 'Type: separator
Type: item; Caption: "' . gettext('More versions...') . '"; Action: run; FileName: "explorer.exe"; Parameters: "http://apricore.fruitechlabs.com/download#mysql"' . "; Glyph: 99\r\n";

		$this->apricoretpl = str_replace(';MENUMYSQLVERSIONSTART', $menumysqlversion, $this->apricoretpl);
		$this->apricoretpl = str_replace(';ACTIONMYSQLVERSION', $actionmysqlversion, $this->apricoretpl);
	}

	public function save()
	{
		@file_put_contents($this->apricorefileini, $this->apricoretpl);
	}

	public function commit()
	{
		$this->serverstatus();
		$this->language();
		$this->apacheversion();
		$this->apachevhost();
		$this->apachealias();
		$this->apachemodule();
		$this->phpversion();
		$this->phpsetting();
		$this->phpextension();
		$this->mysqlversion();
		$this->save();
	}
}
