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
require_once 'Kernel.php';

class Process extends Kernel
{
	public function deleteVHost()
	{
		printf(gettext('Do you really want to delete \'%1$s\' virtual host?') . "\r\n" . gettext('Type \'yes\' to confirm..') . "\r\n", $_SERVER['argv'][1]);

		$confirm = trim(fgets(STDIN));
		$confirm = trim($confirm, '\'');

		if ($confirm == 'yes')
		{
			unlink($this->ampricotdirvhost . '/' . $_SERVER['argv'][1] );
			exec('"../hosts.exe" rem ' . $vhostdir, $execoutput);
			printf("\r\n" . gettext('%1$s') . "\r\n" . gettext('Virtual Host deleted successfully!') . "\r\n" . gettext('Press Enter to exit...'), $execoutput[0]);
		    trim(fgets(STDIN));
			exit();
		}
		else
		{
			print(gettext('Virtual Host not deleted!') . "\r\n" . gettext('Press Enter to exit...'));
		    trim(fgets(STDIN));
			exit();
		}
	}

	public function addVHost()
	{
		printf(gettext('Enter your Virtual Host.') . "\r\n" . gettext('Example: \'test\' Would create a new virtual host with the url: \'http://test/\' which points to: \'%1$stest/\'') . "\r\n", $this->ampricotdirdata . '/' . 'www');

		$vhostdir = trim(fgets(STDIN));
		$vhostdir = trim($vhostdir, '/\'');

		if (empty($vhostdir))
		{
			print("\r\n" . gettext('Invalid Virtual Host! Virtual Host not created!') . "\r\n" . gettext('Press Enter to exit...'));
			trim(fgets(STDIN));
			exit();
		}

		if (is_file($this->ampricotdirvhost . '/' . $vhostdir . '.conf'))
		{
			printf("\r\n" . gettext('Virtual Host already exists!') . "\r\n" . gettext('Press Enter to exit...'), $vhostdir);
			trim(fgets(STDIN));
			exit();
		}

		if (!is_dir($this->ampricotdirdata . '/www/' . $vhostdir))
		{
			exec('mkdir "' . $this->ampricotdirdata . '/www/' . $vhostdir . '"');
			@file_put_contents($this->ampricotdirdata . '/www/' . $vhostdir . '/index.html', str_replace('localhost', $vhostdir, @file_get_contents($this->ampricotdirdata . '/www/' . 'localhost/index.html')));
		}

		if (!is_dir($this->ampricotdirtmp . '/log/' . $vhostdir))
		{
			exec('mkdir "' . $this->ampricotdirtmp . '/log/apache/' . $vhostdir . '"');
			@file_put_contents($this->ampricotdirtmp . '/log/apache/' . $vhostdir . '/error.log', '');
			@file_put_contents($this->ampricotdirtmp . '/log/apache/' . $vhostdir . '/access.log', '');
		}

		$vhostfilecontents = '<VirtualHost *:*>
    ServerName ' . $vhostdir . '
    DocumentRoot "../../../../front/data/www/' . $vhostdir . '"
    ErrorLog "../../../../front/tmp/log/apache/' . $vhostdir . '/error.log"
    CustomLog "../../../../front/tmp/log/apache/' . $vhostdir . '/access.log" common
</VirtualHost>';

		@file_put_contents($this->ampricotdirvhost . '/' . $vhostdir . '.conf', $vhostfilecontents) or die('Unable to update virtual hosts conf file!');

		exec('"../hosts.exe" rem ' . $vhostdir);
		exec('"../hosts.exe" add ' . $vhostdir . ' 127.0.0.1', $execoutput);

		printf("\r\n" . gettext('%1$s') . "\r\n" . gettext('Virtual Host created successfully!') . "\r\n" . gettext('Press Enter to exit...'), $execoutput[0]);

		trim(fgets(STDIN));
		exit();
	}

	public function testPort($install = 0, $hostname = '127.0.0.1', $port = 80, $errno = 0, $errstr = '', $timeout = 1)
	{
		$fp = @fsockopen($hostname, $port, $errno, $errstr, $timeout);

		if ($fp)
		{
			$serverfound = 0;
			printf(gettext('Your port \'%1$s\' is actually used by:') . "\r\n--------------------------------------------------\r\n", $port);
			$out = "GET / HTTP/1.1\r\nHost: " . $hostname . "\r\nConnection: Close\r\n\r\n";
			fwrite($fp, $out);

			while (!feof($fp))
			{
				$line = fgets($fp, 128);
				if (preg_match('/Server: /i', $line))
				{
					printf(gettext('%1$s'), $line);
					$serverfound = 1;
				}
			}

			fclose($fp);

			if (!$serverfound)
			{
				print(gettext('Information not available, it might be \'Skype\' or another windows application!'));
			}
			else if ($install)
			{
				print("\r\n" . gettext('Can NOT install \'Apache Service\', please stop the above application(s) and try again!'));
			}
		}
		else
		{
			printf(gettext('Congrats! Your port \'%1$s\' is not actually used.'), $port);
		}

		print("\r\n" . gettext('Press Enter to exit...'));
		trim(fgets(STDIN));
	}

	public function deleteAlias()
	{
		printf(gettext('Do you really want to delete \'%1$s\' alias?') . "\r\n" . gettext('Type \'yes\' to confirm..') . "\r\n", $_SERVER['argv'][1]);

		$confirm = trim(fgets(STDIN));
		$confirm = trim($confirm, '\'');

		if ($confirm == 'yes')
		{
			unlink($this->ampricotdiralias . '/' . $_SERVER['argv'][1]);
			print(gettext('Alias deleted successfully!') . "\r\n" . gettext('Press Enter to exit...'));
		    trim(fgets(STDIN));
			exit();
		}
		else
		{
			print(gettext('Alias not deleted!') . "\r\n" . gettext('Press Enter to exit...'));
		    trim(fgets(STDIN));
			exit();
		}
	}

	public function addAlias()
	{
		print(gettext('Enter your alias.') . "\r\n" . gettext('Example: \'test\' Would create an alias for the url: \'http://localhost/test/\'') . "\r\n");

		$aliasdir = trim(fgets(STDIN));
		$aliasdir = trim($aliasdir, '/\'');

		if (empty($aliasdir))
		{
			print("\r\n" . gettext('Invalid Alias! Alias not created!') . "\r\n" . gettext('Press Enter to exit...'));
			trim(fgets(STDIN));
			exit();
		}

		if (is_file($this->ampricotdiralias . '/' . $aliasdir . '.conf'))
		{
			printf("\r\n" . gettext('Alias already exists!') . "\r\n" . gettext('Press Enter to exit...'), $aliasdir);
			trim(fgets(STDIN));
			exit();
		}

		printf(gettext('Enter the destination of your alias -fully qualified directory path-.') . "\r\n" . gettext('Example: \'c:/test/\' Would make \'http://localhost/%1$s/\' point to: \'c:/test/\'') . "\r\n", $aliasdir);

		$aliasdest = trim(fgets(STDIN));

		if (is_dir($aliasdest))
		{
			$aliasfilecontents = 'Alias /' . $aliasdir . ' "' . $aliasdest . '"
<Directory "' . $aliasdest . '">
    Options Indexes FollowSymLinks MultiViews
    AllowOverride all
    ' . ((str_replace('.', '', substr($this->ampricotversionapache, 0, 3)) == '24') ? 'Require local' : 'Order Deny,Allow
    Deny from all
    Allow from 127.0.0.1') . '
</Directory>';

			@file_put_contents($this->ampricotdiralias . '/' . $aliasdir . '.conf', $aliasfilecontents) or die('Unable to create alias conf file!');

			print("\r\n" . gettext('Alias created successfully!') . "\r\n" . gettext('Press Enter to exit...'));
		}
		else
		{
			print("\r\n" . gettext('This directory doesn\'t exist! Alias not created!') . "\r\n" . gettext('Press Enter to exit...'));
		}

		trim(fgets(STDIN));
		exit();
	}

	public function switchServerStatus($status)
	{
		$textonline = '# Controls who can get stuff from this server.
    ' . ((str_replace('.', '', substr($this->ampricotversionapache, 0, 3)) == '24') ? 'Require all granted' : 'Order Allow,Deny
    Allow from all');

		$textoffline = '# Controls who can get stuff from this server.
    ' . ((str_replace('.', '', substr($this->ampricotversionapache, 0, 3)) == '24') ? 'Require local' : 'Order Deny,Allow
    Deny from all
    Allow from 127.0.0.1');

		$this->ampricotapacheconfcontent = str_replace((($status == 'on') ? $textoffline : $textonline), (($status == 'on') ? $textonline : $textoffline), @file_get_contents($this->ampricotapacheconf));
		@file_put_contents($this->ampricotapacheconf, $this->ampricotapacheconfcontent);
	}

	public function msg($operands)
	{
		switch ($operands)
		{
			case 'phpapacheincompatible':
				print(gettext('Sorry, this \'PHP\' version doesn\'t seem to be compatible with your actual \'Apache\' version. Switching \'PHP\' version cancelled!'));
				break;

			case 'apachephpincompatible':
				print(gettext('Sorry, this \'Apache\' version doesn\'t seem to be compatible with your actual \'PHP\' version. Switching \'Apache\' version cancelled!'));
				break;

			default:
				print(gettext('Unknown operands!'));
				break;
		}

		print("\r\n" . gettext('Press Enter to exit...'));
		trim(fgets(STDIN));
	}

	public function iniSet($inifile, $params)
	{
		$inifilecontents = @file_get_contents($this->{$inifile});

		foreach ($params as $param => $value)
		{
			$inifilecontents = preg_replace('|' . $param . ' = .*|', $param . ' = "' . $value . '"', $inifilecontents);
		}

		@file_put_contents($this->{$inifile}, $inifilecontents);
	}

	public function listDir($dir)
	{
		if ($handle = @opendir($dir))
		{
			while (($file = @readdir($handle)) !== false)
			{
				if ($file != "." && $file != ".." && is_dir($dir . '/' . $file))
				{
					$list[] = $file;
				}
			}

			closedir($handle);
		}

		if (isset($list))
		{
			return $list;
		}
		else
		{
			return;
		}
	}

	public function switchPHPVersion($newphpversion)
	{
		$phpapachecompatibleversion = str_replace('.', '_', str_replace('.0', '', substr($this->ampricotversionapache, 0, strrpos($this->ampricotversionapache, '.'))));

		if (is_file($this->ampricotinstalldirphp . '/php-' . $newphpversion . '/php5apache' . $phpapachecompatibleversion . '.dll'))
		{
			$conffilecontents = @file($this->ampricotapacheconf);
			$newconffilecontents = '';

			foreach ($conffilecontents as $line)
			{
				if (strstr($line, 'LoadModule') && strstr($line, 'php'))
				{
					$newconffilecontents .= 'LoadModule php5_module ../../php/php-' . $newphpversion . '/php5apache' . $phpapachecompatibleversion . '.dll' . "\r\n";
				}
				else if (strstr($line, 'PHPIniDir'))
				{
					$newconffilecontents .= 'PHPIniDir ../../../../front/conf/php/php-' . $newphpversion . '/' . "\r\n";
				}
				else
				{
					$newconffilecontents .= $line;
				}
			}

			@file_put_contents($this->ampricotapacheconf, $newconffilecontents);

			$this->iniSet('ampricotfileconf', array('ampricotversionphp' => $newphpversion));
		}
	}

	public function switchApacheVersion($newapacheversion)
	{
		$apachephpcompatibleversion = str_replace('.', '_', str_replace('.0', '', substr($newapacheversion, 0, strrpos($newapacheversion, '.'))));

		if (is_file($this->ampricotinstalldirphp . '/php-' . $this->ampricotversionphp . '/php5apache' . $apachephpcompatibleversion . '.dll'))
		{
			$this->iniSet('ampricotfileconf', array('ampricotversionapache' => $newapacheversion));
		}
	}

	public function switchApacheModule($apachemoduleargv)
	{
		$conffilecontents = @file_get_contents($this->ampricotapacheconf) or die ("$this->ampricotapacheconf file not found!");

		if ($apachemoduleargv[2] == 'on')
		{
			$findtext    = '#LoadModule ' . $apachemoduleargv[1];
			$replacetext = 'LoadModule ' . $apachemoduleargv[1];
		}
		else
		{
			$findtext    = 'LoadModule ' . $apachemoduleargv[1];
			$replacetext = '#LoadModule ' . $apachemoduleargv[1];
		}

		$conffilecontents = str_replace($findtext, $replacetext, $conffilecontents);
		@file_put_contents($this->ampricotapacheconf, $conffilecontents);
	}

	public function switchPHPExtension($phpextensionargv)
	{
		$inifilecontents = @file_get_contents($this->ampricotphpini) or die ("$this->ampricotphpini file not found!");

		if ($phpextensionargv[2] == 'on')
		{
			$findtext    = ';extension=' . $phpextensionargv[1] . '.dll';
			$replacetext = 'extension=' . $phpextensionargv[1] . '.dll';
			$findtext    = ';zend_extension=' . $phpextensionargv[1] . '.dll';
			$replacetext = 'zend_extension=' . $phpextensionargv[1] . '.dll';
		}
		else
		{
			$findtext    = 'extension=' . $phpextensionargv[1] . '.dll';
			$replacetext = ';extension=' . $phpextensionargv[1] . '.dll';
			$findtext    = 'zend_extension=' . $phpextensionargv[1] . '.dll';
			$replacetext = ';zend_extension=' . $phpextensionargv[1] . '.dll';
		}

		$inifilecontents = str_replace($findtext, $replacetext, $inifilecontents);
		@file_put_contents($this->ampricotphpini, $inifilecontents);
	}

	public function switchPHPSetting($phpsettingargv)
	{
		$inifilecontents = @file_get_contents($this->ampricotphpini) or die ("$this->ampricotphpini file not found!");

		if ($phpsettingargv[2] == 'on')
		{
			$findtext    = $phpsettingargv[1] . ' = Off';
			$replacetext = $phpsettingargv[1] . ' = On';
		}
		else
		{
			$findtext    = $phpsettingargv[1] . ' = On';
			$replacetext = $phpsettingargv[1] . ' = Off';
		}

		$inifilecontents = str_replace($findtext, $replacetext, $inifilecontents);
		@file_put_contents($this->ampricotphpini, $inifilecontents);
	}

	public function resetMySQLPass()
	{
		print(gettext('Enter new MySQL root password:') . "\r\n");

		$newmysqlrootpass = trim(fgets(STDIN));

		if (empty($newmysqlrootpass))
		{
			print("\r\n" . gettext('Invalid new MySQL root password! MySQL root password not changed!') . "\r\n" . gettext('Press Enter to exit...'));
			trim(fgets(STDIN));
			exit();
		}

		printf(gettext('Confirm new MySQL root password:') . "\r\n");

		$newmysqlrootpassconfirm = trim(fgets(STDIN));

		if ($newmysqlrootpass != $newmysqlrootpassconfirm)
		{
			print("\r\n" . gettext('The passwords you typed do not match. MySQL root password not changed!') . "\r\n" . gettext('Press Enter to exit...'));
			trim(fgets(STDIN));
			exit();
		}

		$sqlfilecontents = "USE `mysql`;
UPDATE `user` SET Password=PASSWORD('" . $newmysqlrootpass . "') WHERE User='root';
FLUSH PRIVILEGES;";

		@file_put_contents($this->ampricotdirtmp . '/dmp/mysqlresetrootpass.sql', $sqlfilecontents) or die('Unable to create sql file!');

		$batfilecontents = '"' . $this->ampricotinstalldirmysql . '/mysql-' . $this->ampricotversionmysql . '/bin/mysqld.exe" --no-defaults --skip-innodb --port=6033 --default-storage-engine=MyISAM --datadir="' . $this->ampricotdirdata . '/mysql" --bind-address=127.0.0.1 --bootstrap --skip-grant-tables --standalone <"' . $this->ampricotinstalldirroot . '/front/tmp/dmp/mysqlresetrootpass.sql"';

		@file_put_contents($this->ampricotdirtmp . '/dmp/mysqlresetrootpass.bat', $batfilecontents) or die('Unable to create batch file!');

		exec($this->ampricotdirtmp . '/dmp/mysqlresetrootpass.bat');

		unlink($this->ampricotdirtmp . '/dmp/mysqlresetrootpass.sql');
		unlink($this->ampricotdirtmp . '/dmp/mysqlresetrootpass.bat');
	}
}
