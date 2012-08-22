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

class Kernel
{
	public $apricorefileconf	= '../apricore.conf';

	public function __construct()
	{
		$this->initConfig();
		$this->initLocale();
	}

	public function initConfig()
	{
		$this->apricoreconf						= @parse_ini_file($this->apricorefileconf);

		$this->apricorelang						= $this->apricoreconf['apricorelang'];
		$this->apricorelangdef					= $this->apricoreconf['apricorelangdef'];
		$this->apricoreinstalldirroot			= $this->apricoreconf['apricoreinstalldirroot'];
		$this->apricoreinstalldirapache			= $this->apricoreinstalldirroot . '/core/bin/apache';
		$this->apricoreinstalldirmysql			= $this->apricoreinstalldirroot . '/core/bin/mysql';
		$this->apricoreinstalldirphp			= $this->apricoreinstalldirroot . '/core/bin/php';
		$this->apricoreversioncore				= $this->apricoreconf['apricoreversioncore'];
		$this->apricoreversionapache			= $this->apricoreconf['apricoreversionapache'];
		$this->apricoreversionmysql				= $this->apricoreconf['apricoreversionmysql'];
		$this->apricoreversionphp				= $this->apricoreconf['apricoreversionphp'];
		$this->apricoreserviceapacheinstall		= $this->apricoreconf['apricoreserviceapacheinstall'];
		$this->apricoreserviceapacheuninstall	= $this->apricoreconf['apricoreserviceapacheuninstall'];
		$this->apricoreservicemysqlinstall		= $this->apricoreconf['apricoreservicemysqlinstall'];
		$this->apricoreservicemysqluninstall	= $this->apricoreconf['apricoreservicemysqluninstall'];

		$this->apricorefileini					= $this->apricoreinstalldirroot . '/core/inc/apricore.ini';
		$this->apricoredirlang					= $this->apricoreinstalldirroot . '/core/inc/locale';
		$this->apricoredirdata					= $this->apricoreinstalldirroot . '/front/data';
		$this->apricorediralias					= $this->apricoreinstalldirroot . '/front/conf/apache/alias';
		$this->apricoredirvhost					= $this->apricoreinstalldirroot . '/front/conf/apache/vhost';
		$this->apricoreapacheconf				= $this->apricoreinstalldirroot . '/front/conf/apache/apache-' . $this->apricoreversionapache . '/httpd.conf';
		$this->apricoremysqlini					= $this->apricoreinstalldirroot . '/front/conf/mysql/mysql-' . $this->apricoreversionmysql . '/mysql.ini';
		$this->apricorephpini					= $this->apricoreinstalldirroot . '/front/conf/php/php-' . $this->apricoreversionphp . '/php.ini';
		$this->apricoredirtmp					= $this->apricoreinstalldirroot . '/front/tmp';
	}

	public function initLocale()
	{
		putenv("LANG=" . $this->apricorelang);
		setlocale(LC_ALL, $this->apricorelang);

		bindtextdomain('messages', $this->apricoredirlang);
		bind_textdomain_codeset('messages', 'utf-8');

		textdomain('messages');
	}
}
