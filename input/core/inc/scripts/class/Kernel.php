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

class Kernel
{
	public $ampricotfileconf	= '../ampricot.conf';

	public function __construct()
	{
		$this->initConfig();
		$this->initLocale();
	}

	public function initConfig()
	{
		$this->ampricotconf						= @parse_ini_file($this->ampricotfileconf);

		$this->ampricotlang						= $this->ampricotconf['ampricotlang'];
		$this->ampricotlangdef					= $this->ampricotconf['ampricotlangdef'];
		$this->ampricotinstalldirroot			= $this->ampricotconf['ampricotinstalldirroot'];
		$this->ampricotinstalldirapache			= $this->ampricotinstalldirroot . '/core/bin/apache';
		$this->ampricotinstalldirmysql			= $this->ampricotinstalldirroot . '/core/bin/mysql';
		$this->ampricotinstalldirphp			= $this->ampricotinstalldirroot . '/core/bin/php';
		$this->ampricotversioncore				= $this->ampricotconf['ampricotversioncore'];
		$this->ampricotversionapache			= $this->ampricotconf['ampricotversionapache'];
		$this->ampricotversionmysql				= $this->ampricotconf['ampricotversionmysql'];
		$this->ampricotversionphp				= $this->ampricotconf['ampricotversionphp'];
		$this->ampricotserviceapacheinstall		= $this->ampricotconf['ampricotserviceapacheinstall'];
		$this->ampricotserviceapacheuninstall	= $this->ampricotconf['ampricotserviceapacheuninstall'];
		$this->ampricotservicemysqlinstall		= $this->ampricotconf['ampricotservicemysqlinstall'];
		$this->ampricotservicemysqluninstall	= $this->ampricotconf['ampricotservicemysqluninstall'];

		$this->ampricotfileini					= $this->ampricotinstalldirroot . '/core/inc/ampricot.ini';
		$this->ampricotdirlang					= $this->ampricotinstalldirroot . '/core/inc/locale';
		$this->ampricotdirdata					= $this->ampricotinstalldirroot . '/front/data';
		$this->ampricotdiralias					= $this->ampricotinstalldirroot . '/front/conf/apache/alias';
		$this->ampricotdirvhost					= $this->ampricotinstalldirroot . '/front/conf/apache/vhost';
		$this->ampricotapacheconf				= $this->ampricotinstalldirroot . '/front/conf/apache/apache-' . $this->ampricotversionapache . '/httpd.conf';
		$this->ampricotmysqlini					= $this->ampricotinstalldirroot . '/front/conf/mysql/mysql-' . $this->ampricotversionmysql . '/mysql.ini';
		$this->ampricotphpini					= $this->ampricotinstalldirroot . '/front/conf/php/php-' . $this->ampricotversionphp . '/php.ini';
		$this->ampricotdirtmp					= $this->ampricotinstalldirroot . '/front/tmp';
	}

	public function initLocale()
	{
		putenv("LANG=" . $this->ampricotlang);
		setlocale(LC_ALL, $this->ampricotlang);

		bindtextdomain('messages', $this->ampricotdirlang);
		bind_textdomain_codeset('messages', 'utf-8');

		textdomain('messages');
	}
}
