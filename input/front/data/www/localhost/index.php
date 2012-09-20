<?php
/*==================================================================================*\
|| ################################################################################ ||
|| # Product Name: Ampricot                                                       # ||
|| # License Type: MIT License                                                    # ||
|| # ---------------------------------------------------------------------------- # ||
|| # 																			  # ||
|| #              Copyright ©2012 FruiTechLabs. All Rights Reserved.              # ||
|| #     This product may be redistributed in whole or significant part under     # ||
|| # "The MIT License (MIT)" - http://www.opensource.org/licenses/mit-license.php # ||
|| # 																			  # ||
|| # ----------------------- "Ampricot" IS FREE SOFTWARE ------------------------ # ||
|| #            http://www.ampricot.com | http://www.fruitechlabs.com             # ||
|| ################################################################################ ||
\*==================================================================================*/


// ################################ Global Variables #################################
$ampricotconf = parse_ini_file(str_replace('\\', '/', strstr(__DIR__, 'front', true)) . 'core/inc/ampricot.conf');

// ################################# Default Request #################################
if (empty($_REQUEST['do']) OR !in_array($_REQUEST['do'], array('home', 'phpinfo', 'phpcredits')))
{
	$_REQUEST['do'] = 'home';
}

// ################################ PHP Info Request #################################
if ($_REQUEST['do'] == 'phpinfo')
{
	ob_start();
	phpinfo();
	$phpinfo = ob_get_contents();
	ob_end_clean();
	$phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms','$1', $phpinfo);
	$phpinfo = preg_replace('/<a[^>]+\><img[^>]+\><\/a>/i','$1', $phpinfo);
	$phpinfo = preg_replace('/<a href=\"[^>]+\"\>([^>]+)<\/a>/i', '<a href="index.php?do=phpcredits">$1</a>', $phpinfo);
	$phpinfo = str_replace('class="center"', 'class="phpinfo"', $phpinfo);
	$htmloutput = '<div align="center">' . $phpinfo . '</div>';
}

// ############################### PHP Credits Request ###############################
if ($_REQUEST['do'] == 'phpcredits')
{
	ob_start();
	phpcredits();
	$phpcredits = ob_get_contents();
	ob_end_clean();
	$phpcredits = preg_replace('%^.*<body>(.*)</body>.*$%ms','$1', preg_replace("/<a href=\"[^>]+\"\>PHP Credits<\/a>/i", "$1", $phpcredits));
	$phpcredits = str_replace('class="center"', 'class="phpinfo"', $phpcredits);
	$htmloutput = '<div align="center">' . $phpcredits . '</div>';
}

// ################################## Home Request ###################################
if ($_REQUEST['do'] == 'home')
{
	$files = array();
	$rootdirs = array('vhost' => str_replace('\\', '/', strstr(__DIR__, 'front', true)) . 'front/conf/apache/vhost', 'alias' => str_replace('\\', '/', strstr(__DIR__, 'front', true)) . 'front/conf/apache/alias');

	foreach ($rootdirs as $dir => $path)
	{
		if (is_dir($path))
		{
			if ($handle = opendir($path))
			{
				while (($file = readdir($handle)) !== false)
				{
					if (!is_dir($file))
					{
						if ($dir == 'vhost')
						{
							$files[$dir][] = '<li class="imgvhost"><a href="http://' . str_replace('.conf', '', $file) . '/">' . str_replace('.conf', '', $file) . '</a></li>';
						}
						else
						{
							$files[$dir][] = '<li class="imgalias"><a href="http://localhost/' . str_replace('.conf', '', $file) . '/">' . str_replace('.conf', '', $file) . '</a></li>';
						}
					}
				}

				closedir($handle);
			}
		}
	}

	$apachemoduleslist = array();
	$apachemodules = apache_get_modules();
	foreach ($apachemodules as $module)
	{
		$apachemoduleslist[$module] = '<li class="imgplugin">' . strtolower($module) . "</li>\n";
	}

	$phpextensionslist = array();
	$phpextensions = get_loaded_extensions();
	foreach ($phpextensions as $extension)
	{
		$phpextensionslist[$extension] = '<li class="imgplugin">' . strtolower($extension) . "</li>\n";
	}

	$loadavg = array();
	$is_windows = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');

	if (!$is_windows AND function_exists('exec') AND $stats = @exec('uptime 2>&1') AND trim($stats) != '' AND preg_match('#: ([\d.,]+),?\s+([\d.,]+),?\s+([\d.,]+)$#', $stats, $regs))
	{
		$loadavg[0] = vb_number_format($regs[1], 2);
		$loadavg[1] = vb_number_format($regs[2], 2);
		$loadavg[2] = vb_number_format($regs[3], 2);
	}
	else if (!$is_windows AND @file_exists('/proc/loadavg') AND $stats = @file_get_contents('/proc/loadavg') AND trim($stats) != '')
	{
		$loadavg = explode(' ', $stats);
		$loadavg[0] = vb_number_format($loadavg[0], 2);
		$loadavg[1] = vb_number_format($loadavg[1], 2);
		$loadavg[2] = vb_number_format($loadavg[2], 2);
	}

	$htmloutput = '
						<div class="wrapper">
							<h3>' . gettext('Apache Virtual Hosts:') . '</h3>
							<ul>
								' . implode('', $files['vhost']) . '
							</ul>
							<br />
						</div>

						<div class="wrapper">
							<h3>' . gettext('Apache Alias:') . '</h3>
							<ul>
								' . implode('', $files['alias']) . '
							</ul>
							<br />
						</div>

						<div class="wrapper">
							<h3>' . gettext('Apache Loaded Modules:') . '</h3>
							<ul>
								' . implode('', $apachemoduleslist) . '
							</ul>
							<br />
						</div>

						<div class="wrapper">
							<h3>' . gettext('PHP Loaded Extensions:') . '</h3>
							<ul>
								' . implode('', $phpextensionslist) . '
							</ul>
							<br />
						</div>';

}


$output = '
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Ampricot &raquo; Cross-platform web server solution stack package for professional web development.</title>
	<link rel="stylesheet" href="data:text/css;base64,77u/KnttYXJnaW46MDtwYWRkaW5nOjB9Ym9keXtiYWNrZ3JvdW5kOiNmN2Y3Zjc7Y29sb3I6IzMzMztmb250LWZhbWlseTpWZXJkYW5hLFRhaG9tYSwiQml0U3RyZWFtIHZlcmEgU2FucyIsQXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7Zm9udC1zaXplOjEycHh9aDF7Zm9udC1zaXplOjIwcHh9aDJ7Zm9udC1zaXplOjE2cHh9aDN7Zm9udC1zaXplOjE0cHg7Ym9yZGVyLWJvdHRvbTozcHggc29saWQgZ3JheTt3aWR0aDoyMDBweH1he2NvbG9yOiMzYjU5OTg7dGV4dC1kZWNvcmF0aW9uOm5vbmV9YTpob3Zlcnt0ZXh0LWRlY29yYXRpb246dW5kZXJsaW5lfS5maXhlZHtjbGVhcjpib3RofS5oZWFkZXJ7Ym9yZGVyLWJvdHRvbToxcHggc29saWQgI2I3YjdiN30uaGVhZGVyIC5pbm5lcntiYWNrZ3JvdW5kOiNmN2Y3Zjc7Ym9yZGVyLWJvdHRvbToxcHggc29saWQgI0ZGRjtoZWlnaHQ6NTVweCFpbXBvcnRhbnR9LmhlYWRlciAuY29udGVudHtwb3NpdGlvbjpyZWxhdGl2ZTttYXJnaW46MCBhdXRvO3RleHQtYWxpZ246bGVmdDt3aWR0aDo5NjJweH0uaGVhZGVyIC5jYXB0aW9ue2JvcmRlci1sZWZ0OjVweCBzb2xpZDtmbG9hdDpsZWZ0O21hcmdpbi10b3A6OHB4O3BhZGRpbmc6MXB4IDAgMnB4IDEwcHg7Ym9yZGVyLWNvbG9yOiM5OTk7ZGlzcGxheTpibG9jaztiYWNrZ3JvdW5kOnVybCgnZGF0YTppbWFnZS9wbmc7YmFzZTY0LGlWQk9SdzBLR2dvQUFBQU5TVWhFVWdBQUFEUUFBQUFuQ0FZQUFBQkFPbXZlQUFBQUNYQklXWE1BQUM0akFBQXVJd0Y0cFQ5MkFBQUtUMmxEUTFCUWFHOTBiM05vYjNBZ1NVTkRJSEJ5YjJacGJHVUFBSGphblZOblZGUHBGajMzM3ZSQ1M0aUFsRXR2VWhVSUlGSkNpNEFVa1NZcUlRa1FTb2dob2RrVlVjRVJSVVVFRzhpZ2lBT09qb0NNRlZFc0RJb0syQWZrSWFLT2c2T0lpc3I3NFh1amE5YTg5K2JOL3JYWFB1ZXM4NTJ6endmQUNBeVdTRE5STllBTXFVSWVFZUNEeDhURzRlUXVRSUVLSkhBQUVBaXpaQ0Z6L1NNQkFQaCtQRHdySXNBSHZnQUJlTk1MQ0FEQVRadkFNQnlIL3cvcVFwbGNBWUNFQWNCMGtUaExDSUFVQUVCNmprS21BRUJHQVlDZG1DWlRBS0FFQUdETFkyTGpBRkF0QUdBbmYrYlRBSUNkK0psN0FRQmJsQ0VWQWFDUkFDQVRaWWhFQUdnN0FLelBWb3BGQUZnd0FCUm1TOFE1QU5ndEFEQkpWMlpJQUxDM0FNRE9FQXV5QUFnTUFEQlJpSVVwQUFSN0FHRElJeU40QUlTWkFCUkc4bGM4OFN1dUVPY3FBQUI0bWJJOHVTUTVSWUZiQ0MxeEIxZFhMaDRvemtrWEt4UTJZUUpobWtBdXdubVpHVEtCTkEvZzg4d0FBS0NSRlJIZ2cvUDllTTRPcnM3T05vNjJEbDh0NnI4Ry95SmlZdVArNWMrcmNFQUFBT0YwZnRIK0xDK3pHb0E3Qm9CdC9xSWw3Z1JvWGd1Z2RmZUxacklQUUxVQW9PbmFWL053K0g0OFBFV2hrTG5aMmVYazVOaEt4RUpiWWNwWGZmNW53bC9BVi8xcytYNDgvUGYxNEw3aUpJRXlYWUZIQlBqZ3dzejBUS1VjejVJSmhHTGM1bzlIL0xjTC8vd2QweUxFU1dLNVdDb1U0MUVTY1k1RW1venpNcVVpaVVLU0tjVWwwdjlrNHQ4cyt3TSszelVBc0dvK0FYdVJMYWhkWXdQMlN5Y1FXSFRBNHZjQUFQSzdiOEhVS0FnRGdHaUQ0YzkzLys4Ly9VZWdKUUNBWmttU2NRQUFYa1FrTGxUS3N6L0hDQUFBUktDQktyQkJHL1RCR0N6QUJoekJCZHpCQy94Z05vUkNKTVRDUWhCQ0NtU0FISEpnS2F5Q1FpaUd6YkFkS21BdjFFQWROTUJSYUlhVGNBNHV3bFc0RGoxd0QvcGhDSjdCS0x5QkNRUkJ5QWdUWVNIYWlBRmlpbGdqamdnWG1ZWDRJY0ZJQkJLTEpDREppQlJSSWt1Uk5VZ3hVb3BVSUZWSUhmSTljZ0k1aDF4R3VwRTd5QUF5Z3Z5R3ZFY3hsSUd5VVQzVURMVkR1YWczR29SR29ndlFaSFF4bW84V29KdlFjclFhUFl3Mm9lZlFxMmdQMm84K1E4Y3d3T2dZQnpQRWJEQXV4c05Dc1Rnc0NaTmp5N0VpckF5cnhocXdWcXdEdTRuMVk4K3hkd1FTZ1VYQUNUWUVkMElnWVI1QlNGaE1XRTdZU0tnZ0hDUTBFZG9KTndrRGhGSENKeUtUcUV1MEpyb1IrY1FZWWpJeGgxaElMQ1BXRW84VEx4QjdpRVBFTnlRU2lVTXlKN21RQWtteHBGVFNFdEpHMG01U0kra3NxWnMwU0Jvams4bmFaR3V5QnptVUxDQXJ5SVhrbmVURDVEUGtHK1FoOGxzS25XSkFjYVQ0VStJb1VzcHFTaG5sRU9VMDVRWmxtREpCVmFPYVV0Mm9vVlFSTlk5YVFxMmh0bEt2VVllb0V6UjFtam5OZ3haSlM2V3RvcFhUR21nWGFQZHByK2gwdWhIZGxSNU9sOUJYMHN2cFIraVg2QVAwZHd3TmhoV0R4NGhuS0JtYkdBY1laeGwzR0srWVRLWVowNHNaeDFRd056SHJtT2VaRDVsdlZWZ3F0aXA4RlpIS0NwVktsU2FWR3lvdlZLbXFwcXJlcWd0VjgxWExWSStwWGxOOXJrWlZNMVBqcVFuVWxxdFZxcDFRNjFNYlUyZXBPNmlIcW1lb2IxUS9wSDVaL1lrR1djTk13MDlEcEZHZ3NWL2p2TVlnQzJNWnMzZ3NJV3NOcTRaMWdUWEVKckhOMlh4MktydVkvUjI3aXoycXFhRTVRek5LTTFlelV2T1VaajhINDVoeCtKeDBUZ25uS0tlWDgzNkszaFR2S2VJcEc2WTBUTGt4WlZ4cnFwYVhsbGlyU0t0UnEwZnJ2VGF1N2FlZHByMUZ1MW43Z1E1Qngwb25YQ2RIWjQvT0JaM25VOWxUM2FjS3B4Wk5QVHIxcmk2cWE2VWJvYnRFZDc5dXArNllucjVlZ0o1TWI2ZmVlYjNuK2h4OUwvMVUvVzM2cC9WSERGZ0dzd3drQnRzTXpoZzh4VFZ4Ynp3ZEw4ZmI4VkZEWGNOQVE2VmhsV0dYNFlTUnVkRThvOVZHalVZUGpHbkdYT01rNDIzR2JjYWpKZ1ltSVNaTFRlcE43cHBTVGJtbUthWTdURHRNeDgzTXphTE4xcGsxbXoweDF6TG5tK2ViMTV2ZnQyQmFlRm9zdHFpMnVHVkpzdVJhcGxudXRyeHVoVm81V2FWWVZWcGRzMGF0bmEwbDFydXR1NmNScDdsT2swNnJudFpudzdEeHRzbTJxYmNac09YWUJ0dXV0bTIyZldGblloZG50OFd1dys2VHZaTjl1bjJOL1QwSERZZlpEcXNkV2gxK2M3UnlGRHBXT3Q2YXpwenVQMzNGOUpicEwyZFl6eERQMkRQanRoUExLY1JwblZPYjAwZG5GMmU1YzRQemlJdUpTNExMTHBjK0xwc2J4dDNJdmVSS2RQVnhYZUY2MHZXZG03T2J3dTJvMjYvdU51NXA3b2Zjbjh3MG55bWVXVE56ME1QSVErQlI1ZEUvQzUrVk1HdmZySDVQUTArQlo3WG5JeTlqTDVGWHJkZXd0NlYzcXZkaDd4Yys5ajV5bitNKzR6dzMzakxlV1YvTU44QzN5TGZMVDhOdm5sK0YzME4vSS85ay8zci8wUUNuZ0NVQlp3T0pnVUdCV3dMNytIcDhJYitPUHpyYlpmYXkyZTFCaktDNVFSVkJqNEt0Z3VYQnJTRm95T3lRclNIMzU1ak9rYzVwRG9WUWZ1alcwQWRoNW1HTHczNE1KNFdIaFZlR1A0NXdpRmdhMFRHWE5YZlIzRU56MzBUNlJKWkUzcHRuTVU4NXJ5MUtOU28rcWk1cVBObzN1alM2UDhZdVpsbk0xVmlkV0Vsc1N4dzVMaXF1Tm01c3Z0Lzg3Zk9INHAzaUMrTjdGNWd2eUYxd2VhSE93dlNGcHhhcExoSXNPcFpBVEloT09KVHdRUkFxcUJhTUpmSVRkeVdPQ25uQ0hjSm5JaS9STnRHSTJFTmNLaDVPOGtncVRYcVM3Skc4Tlhra3hUT2xMT1c1aENlcGtMeE1EVXpkbXpxZUZwcDJJRzB5UFRxOU1ZT1NrWkJ4UXFvaFRaTzJaK3BuNW1aMnk2eGxoYkwreFc2THR5OGVsUWZKYTdPUXJBVlpMUXEyUXFib1ZGb28xeW9Ic21kbFYyYS96WW5LT1phcm5pdk43Y3l6eXR1UU41enZuLy90RXNJUzRaSzJwWVpMVnkwZFdPYTlyR281c2p4eGVkc0s0eFVGSzRaV0Jxdzh1SXEyS20zVlQ2dnRWNWV1ZnIwbWVrMXJnVjdCeW9MQnRRRnI2d3RWQ3VXRmZldmMxKzFkVDFndldkKzFZZnFHblJzK0ZZbUtyaFRiRjVjVmY5Z28zSGpsRzRkdnlyK1ozSlMwcWF2RXVXVFBadEptNmViZUxaNWJEcGFxbCthWERtNE4yZHEwRGQ5V3RPMzE5a1hiTDVmTktOdTdnN1pEdWFPL1BMaThaYWZKenMwN1AxU2tWUFJVK2xRMjd0TGR0V0hYK0c3UjdodDd2UFkwN05YYlc3ejMvVDdKdnR0VkFWVk4xV2JWWmZ0Sis3UDNQNjZKcXVuNGx2dHRYYTFPYlhIdHh3UFNBLzBISXc2MjE3blUxUjNTUFZSU2o5WXI2MGNPeHgrKy9wM3ZkeTBOTmcxVmpaekc0aU53UkhuazZmY0ozL2NlRFRyYWRveDdyT0VIMHg5MkhXY2RMMnBDbXZLYVJwdFRtdnRiWWx1NlQ4dyswZGJxM25yOFI5c2ZENXcwUEZsNVN2TlV5V25hNllMVGsyZnl6NHlkbFoxOWZpNzUzR0Rib3JaNzUyUE8zMm9QYisrNkVIVGgwa1gvaStjN3ZEdk9YUEs0ZFBLeTIrVVRWN2hYbXE4NlgyM3FkT284L3BQVFQ4ZTduTHVhcnJsY2E3bnVlcjIxZTJiMzZSdWVOODdkOUwxNThSYi8xdFdlT1QzZHZmTjZiL2ZGOS9YZkZ0MStjaWY5enN1NzJYY243cTI4VDd4ZjlFRHRRZGxEM1lmVlAxdiszTmp2M0g5cXdIZWc4OUhjUi9jR2hZUFAvcEgxanc5REJZK1pqOHVHRFlicm5qZytPVG5pUDNMOTZmeW5RODlrenlhZUYvNmkvc3V1RnhZdmZ2alY2OWZPMFpqUm9aZnlsNU8vYlh5bC9lckE2eG12MjhiQ3hoNit5WGd6TVY3MFZ2dnR3WGZjZHgzdm85OFBUK1I4SUg4by8yajVzZlZUMEtmN2t4bVRrLzhFQTVqei9HTXpMZHNBQUFBZ1kwaFNUUUFBZWlVQUFJQ0RBQUQ1L3dBQWdPa0FBSFV3QUFEcVlBQUFPcGdBQUJkdmtsL0ZSZ0FBRWloSlJFRlVlTnJFbVd1TUpOZDEzMy9uM3FycTZ1YzhkblptZDJlNEwrMlN1OHUzYUlZVUtZVkpMRm14NmNpR0FnV0dHU0YyckRoQUVnVjVBWWJ6QWdQblF5Z3JIMnpFRWhOTHNBMVlzQkZIaUp3Z2hKV0lra0piRDFNVVJXb3Bhcm5jOTNCbmRuZW11MmU2dTZycmNlODkrZEFVeVlSaW9oQ09jNEJDb2I0VTd2K2Urei8zZi81SFZCV0EwRDhQZ0x6NkxUWkFzME0yMlNJdmh6eHorYnZjbUxqVUdYN21DNmVmK0R0NW1mZHVQWFQzVXcvZitSYy85ZURCbS84WW1LcDZ4QUJCVUR4aUtqYWV2OG9ML1Q3VC9SMGlGVm9OeTZXdFMzc0hrNTBmdnpIY2ZHQmp1SG1ualdOL2RQbm8wNGVXamo2Yk50cmY3cWFkNTdNcUQrTjhoNVhlQ2xlR2wybkhUVnB4d3RMOE1vMjRpV3JnKzBYRUR4QkpsR0JFL3N4VFo3NzhDeGVINng4c3E0eG0ydWJsNFpWSFhoeHMvT1Nwdld0UExyU1NaOEI4RHZUMFcvMG5NaEdqNmZqQjArc3ZQSGI2eW5jZTlNRVRKdzBhMm1IbjZwbjd6L1RYMmR0ZDJsNmIyLys1bXhadStuUnM0NmRGSlBCL0VmOEhRSVpHbk16LzRaa3YvNzFQZnVrelAzK2hmM1gveXZ3cUt3dUhTZE1tK3hZUDBVbjN0bC9vRHo5d1M4MEg5blo3UDRkdC9SNHFqNk9jLzU5L0pVeW1rMXVmZXVtcFQxL29YN2xsZGZrb1NkeEVURVFyN1ZDSG1pUk9DZXFYenZiWFAzSnRzdjJYT2xIOFh4T2JmQ0l5MGRmL0JBQUpXUE91YjV6NzZtUC83RDgrOXA0OE9KWjZlNGlpUUxmZG90UHFFWm5BMmF0UDgwMWY4U00zbmVSZHNUbGtlOHYvcUhWZzlTZkVwTCtJazg4U1djeWxiY2JaZFRhVDhmdXY3Rnk5WmYvU1RhUlJoeVJwRUprRWF5d1k4RUVSSXpUaWxMeVlySHpuK3RrUHY3VDUzUis3N2NESlg3cDUzNGxmQSsvZUppQUJhLzdzYnYvMGJ6Mys1VThjMWhTT0xhNFJhaUdKbWhncWJKUmk0Z1g2UmM3QnVXWHVYTjFQL3N4LzRjdFBYK1hBemU4OGZzK1BQdmlwVm05NTFkam9WNzBQOXVnNFBTd0h5Z2VYRmp2TXR4ZEo0dzdXeEtDQ2lLSHlGVUpOSkJGT1FSWDJkRmJZSEY3YTg5OWVlT0pqSVJRMzNYWHdoLzZwb3NYYkFLUW5ZZmNUejEzKzJ1RXNqYm5sSGJkUlpBWGVXbXJiSkkvbTZTYUxwRWE0ZTArYjk3MWpsVVhaNEN0Zi8wODgrZnMzbUY4NXc1d1p6TzlaTy9iWTdrNTBKQkwvRlRmTmRUN3BjTWV4Ky9DVkliWXBJU2plS3g0bDlUV1ZxeEVSbkhkVXJzVDVpcm5XRWx2Ymw1UGYrOHB2L01QRTJzbER0NzcvbDBURXYxcTczaFQyMFVjZm5VR1lEcitYbTNueDI3K091L2FBYXNDbWJlWkZhR0ZwMlpoak91SitmNTA3M0dYdWJXenhycVdVdVo1bmRPRzduUDVtbjZTemp3Lys3SDBjT3BxQnJhTG04dkw5YXlkdnFkYnVPdld2aDlYaWwrSkc3NlMxNmZIRU5tbEdUZHBKaDRBSElEWXhOb3F3MW1JbFFzVGd2Q1A0d0ZaL2cvTTNYbnozMFFQSEx5eDM5Mzg3dkVXVms5Zks5dURDTFAxKzk1OUxlZjVmZ0ljb29kS0t1cmlHMjczS3RIOFIyeitISFc5RGxKSXUzMExTblVOU2kyK2RvTzdlaldsMGFNWkRtRjVIYlF0cDdvVzRQY0l1L3Ezbnp1MTg1cVgrdGZsV0svbG9aZmdIQVR0dkVjcTZZbHlPbUxvQ0VhRVpwVmlFd2svWm1lNXlZM2lONTg5K2xmVnJMM0J5N2VZTGYvV2hqeng4ZU9YWW1lOEg2clVNVVl3Ui9BOUpjZVZYQ05NMkdOQWNxMU1TTFVtck1ZM1JSY3gwRnhvTG1OWUt4Z2hHYyt6eVhkakZFeVRORG5FU1FUQm9hRk5uTmFHMG1CQTNoT0tPcGJtNUp6ck45cWFyZUtwdzdubHI1VllIKzYwMUdMRllZNGx0UkFCYVNaTnUycUdYZGxtWjM4L0s0Z0dzTVZ5OGNYNWhzYnN3ZjJMMXRpY0FMMko0NC9NNmg5U0JHMzlJdzJqdjdIS2Nnc3RScmRCc0M5ZS9RTDI3VFhBZ2pRaFQ5SkdRWW8vOEdHYnVNT3BMZE5vSGlWRUVEUTVmRDZpR0x4TzNWa25ubG81RjNjNkhqKzViZVhTYzVleE9wMy9RTXZFM2M1ZjkzTWpPL2MxZ0dvZlVHaEdBNENoVkNHcXhVWU1FNWVDK2s2U2RGbEczeGJuK1N3OC9kL0dwV3ozaDJSQTg2UGN0Q201Ri9PNmZJemhVQzhSNFZDSW9NNWhzb3NVT21CU1NHSW9STXMxcDNQdFQyTGw1L1BnU3FqRnFZcEFXS2hHK3lpaDNyMU51bjZOdWJLTGhMbHJKL0UrRCtZMWpQSGY1RUMrai9sQVdkci82aFg2MHRHOUEvYmMzN2ZGb3pGNWlpU2dWcUVkRWZrUVJNcVRSeXZmM2pqLzU3cHYvZkZSTXR3WkpMSnNMVVp2NTFqeHE3WnNCQ1hvVVA3b0xBb0tpS2toUVFqMkNlZ0pxQ0NaQ3BrTVliQlBmOFJPWVBYdW9keTdpYW84R1E3QmRWSGRSZGZqcGhITGNweHBzRU5sZGJOd2xhUzJ0R1pmZEU5eTBGRC84c093OCt5Tm1jdkhlTmJjeHQxckRPK28ydWM0VGtwUVFXU0kvd1NUN0NITkg2ZXc1SksyREs0TWlPdnpMNWZEQ2k5dkZEVjNJRTFiWGJvSDIzS3pPdnhHUSt1a3hDWG1pNGhFVVVaM3BNVjhRQWdSZklkTSszRmduT2Z3dWtxTzNVWS9XY2NVSVh6dThnNkE1UVNHNEVxMEwzR1NFenpLRUhOZGFwNTQvMmt6RS9tT3o4M25IdFcvY3ArTXhvWVRTR2JSU0dpR2owNmlJSEpqZUNzd3ZJYjJEbVAwL2pndTdUZG4rdzcvVzNETzVYMDMyOFgzN2ozeHFzcjVMNlNwaVg3OFpFRDYvRHh4Q0FHWmdLSGFnSE9OZFFjaTI0UG9GVFBjUXllMFBFZkxyaEdtR0w2YlVSWWJMUzRJUCtBQWFIRVFwV3VSSVZSQnFCd3FSdklodVBIV1BIZDZBZWdYdkZ0SFNnVzFpelJUYnFtRGZNV1R4RkhSV29MMEU0ejhDbVdMcVB0WDV6MURaTDk3aUdrdWYxTlZIanZuS2Z5cGgrZHozdlZoRjYzZWlpZ2FQcUlFNlE2cytvZXpqc3o1aGNBMnBZeHJ2ZkJnUnhZMkgrR0tIZXJKRG5aZTRDdFE3UWwyaEtOZ1M5VFc0Z3FneFIzY3RKUW5QNHB4RmtsUFlUb1J0R01JY2FBZ1kzU0phUFVHMDV6alNtRU4xQjRrRndqSWF4VWh6RmJFcm1PcFpaRkpHZzRzdi9VTDMxSDBmakpvZi9rVlkvQ3l2M21WdnlGQTlSL0NJVjlUblNER0Fhb0t2TXZ6d09neUhOTzcrSUhiUEduNzRDcjRzOE5NTWwyZlVXWTJ2UVYxTnFDclVSRmhUSXRVRVUxWE0zWGFNdExlRkgzZVE1QUFXZ3pZYUlCMk1hU01TSWUwT3NuQ0lJRTIwekFtNmdCdE5VYjBibDFuVmZDS2RsZmNTTmU3QVplc3M1UzhUNytPNFJCdS9yTEw4Z2pyL2tvVDZEUnhTQmhJY2FBbCtqTmE3YURsQko3dHcvU3J4MGluaTQvZWkrUUJmNVBoc2dwL211TnhSNzA3d3BVZkZnSzhSVTZGVVNMWk44OUFKMm9kVHlJZFFMNkxlSUZFWGtpV0k5bUNURG5TTzRPTkY4djVaOERjd3pWVzhXYVNLS2t6d1lJVnEreHNVV3plMHUzWkVtc2YrT3JaNEh0eEZkTHB4aE9iY3g5QURQNFhXVS9PNlpvZ3VvUVdxQlhpUFZsTjh2b1BmdW9wNEliNzFJZFJWaE1rQVgwNXhaWTdQYy95NG9CNVgxRm1CbTA3eFpZMHZwb1RKRUZ5Z2ZmUVFZak5DbVlLelNMQ285NGhhYkJ6aFpKNnNOR3hmZVk1cWZKV2FKamUydGhodVg5U3F6cWw4QWNaSmV2QWVkcHAzY3UzU09tU1hrTlp4c0t1empTcSs5YU9pTjM1YWc4Rzh6aUZ6R2VlUWFncXVKbmdsWkVOMGVKMzQySVBZbFNOUVpJUnlRcWhydkkrb2M2WE9ITjViWEdqZ1NvTXZIR0U2Sll3bUpOMWwwZ056YUJsQmFDTEJveGh3QVZHRGFvUFJ1T0xpTTE5aTQvVFhrQ2hsNDVWTnRsNjV5TGkvS2J2WHpwRU5yMUZrdTlUZTBkaC9zMHdiUjVoYzN3QXFNQzN3TWVTYk1lWHBEd1ZmZDE4REZDUzlRTEJRRm1neFFyM0hqN2F4N1dYaVkvZEN0WXZXR2I2cThkTXBvWWE2Z3JweWhCQ2pJU0pVQVozV1VGUklHVWozNzBmaUNUb2R6NVFJQWxXQmVBSFRZdUxtMlIyT3VQTGl0M0ZWd2NXekZ6bno5QjlUWnBrT05pOXJPUnFoZFUweDJpVWZ2RUxrZDdGemE0d21Gb29oaEZjN2lXREJYM28zT2p6MUdvZThwQytJNldib3FLMnVJSlJUdEtwSWp0eVBwQWs2SGFKT0NTNFFpaWsrcjFGblVJMEkzb01LR2hTdllKMkNpWWxXbHNFN0pFQ29BbFM3VU5ZUU9VSlZNQnhsYkY1OG1VRi95UEY3N3VUNWI3M01tVzk5RzF3bDdXNUxJMktTWmdjYnhhb2t5RFFYYXh1VVZZeXZBaVpkQUhjTmdvS2Z0b1grbmE5bFNFMzZBdEhpbDVBR0dpMUJyVVR6YTlpVlkxQk5DVldCcnl1Q2JSTWtKVGdsQkFFYVlCdUUyczI0Z1NGSURPa0NVYnNGOVFRbFFhUUpVUXRKOW9BbWxGdFhtUXdHWEw2MGlhYnpKSjBsUm9NSmcvNlFWODZkcFpvVUZLTWQ4bUVmN3h4VjVhU2U1bENOOE5WVTY4bEFSUXhLQW1GbXpDRERCOTRvZlhMWE9QQnBFNjMvc0JaWkU5dkc3amtGSnNKUGg0U3l4SGxEOElGQWpEZE52Q3RSTmFoWEVJT29SUUtvS3FIUlE1SUlYSUVFUVcwSGxSWmk1eEFuaEdIR3RPaHo0YVV6SER4eE8xVVpHTzBNWnFmRmVRYlhOaVg0R29rYVNOSWdpaE5jS0JIYndQcXhoTnhEM1VCY0FXSWhSSWdPN25pOXltbEE0N25QdWU2cFR4SVp4TGFRMXI3Wmdra0ltaEFxQ0ZsSmNBYlZCRFZOSkdwaW93UWJ0eEdUUW9qUUVCTm9vQktEaVdiY3dTTTJScElPZUVNMUdMSTd6SmhNSFVrelpyUzl5ZnFWSytTbEp6aEhrZTFTWkJOVWxXSjNJTlArTllySmlHdzhvaGhzSUtNWHdkY1FJdkFLRHFTZUxMMldvZUE5aElCR1MvL1NkKzlhTWY3c0k1SjROSGlDNlJKTVRYQWx2dko0TDJnQXRSR2tMYkExK0IwZ1FpTjl0WVZJY1hVTGtoNXFLakFHWElYNjBjenJtRTVBYTd3SzJjNEF0NjlCVU5qSmF2cURESU5pZDNaSjFzL1RXdWdSSitrc1czR0hWbm1aSk4rQjRHZWJwaTBFajNvVFh1ZFE4RE9wb241SXV2Ynpmcy85SC9YSnltV1BtUzFRRTRKdDRySDRBTUZFSUJIcTNVek1JcGc0UlZyelJMMjkwSjdIRnhaTUF6RUZpSUtHV1RzU3gwVFpnR1M4aVVQWjZXL1RiaWIwRm5yNEVDanFRRkFoenpQR2d5MUcyd04yQmp1TXhtUEcyemZvVGM5aGpjRjdCYldnczVOQmFBeWpON2s5TTNTNXh1MS9VMGZIenlISmJ6TTV2OGQ1d1drRGI3dXpqS0F6NGVRTGxBQkpDNU4wa0NqR0pBbWh1ZmZwZ3BWcmM3THpBWmhBYUlIcElIRUNOc0hHbnU3MkJnc0w4OXpZSGxDVVV4cHhSR1FBWS9EZVk3eWhEQVlxajZXRUdsWjB6TXJLTGpSUFFRaFFseURsYkxQTTR1bTNjSDBDYWxxRVpQNFB4RFFmQ1NGK3pFM1AzMW5YVzNnRmJ4TUNIbTBDeG9KVFRFUEJwbWpVZkxxdzNTK0l4UCsyY0V0THFqdnZGem5kUU1ZemllK25vSWI0MEZGNlY4OXkwOXdhVDU0ZHNMNit4ZExTRW5GMGlTZ1NvdGlTTkZzNHNjUUlSc0ZPTTQ0djc5TGMyeU1zbkVCVndVMUFIQmhEM1RqNCtmK04wYWlnSHRSOFhydTNuUEh4Nms5cWN2VWhQOW84NHF0c2Y2Z3lDU2JIbVVwREhjNjRFUDEzc2ZiWjJFYmZLTXBvWTM1NWdhamRHZVhGdm0rMjQrWURXbWFJdEZFWmc0K0kxL2JSWGI3TTZyZk9zcmE4eE1iR0RpZE9udVRTK2pVMEgyRGpKbzF1RDYremdoWHluSnVYSTI0NjNFV1hGcUY5QVBFWmlJRlFFYVIzU2VOOVgvOEJ2RzBGNURMSi9LL1E5bzlUWkkyUXJMUjlYZHBpTXRZc3UrYmJNaG5KM0lIY2lCSkZndDNOa1NoR2t2Wk9WaDcrL1hicjJBT2lmd1EwRVJWVUp0QzhpZDVkeHpsOC9tdmNOU201bE9WazR3SGQxRkRRcGpJcGFmQVlOWkFYM0x3LzVmYmJEeUM5RXIzcHZwa2RVR3pNVnVkelFucm5mOEQwemtjL21HUDh2V3o1RWcwbFlrYUl3VHRITXIrZjF0SVNyYU1QemxwM1kybnNiRkp2bmFjS1hZSkd2MXNsRHoyU3BCZnYwS3dQVVJlaUhoVGJ4SHNXV1g3UE8xbjluUzlTaUhEamtxRU9DYVlSWTdGVVdXQnZYSEhyNFRsdXZ1c29qWVVXTE4wRXJRVTBleG5SYXNiaHNIQk9rNU8vamc5RXZLMVExTldrODN0b0g3dVBLTzJLVmxOQkpLaXZpWHZMTkZvZHROd0YyN3lTU2Z0alVlYzluemIrUHpkQ1hRSTErSFdRQTNSUEh1Ykk4UTdSbFJ1OGxIUm91WnAyTENTcXpLZVc5eng0bk9WREMyaW5oM1FXb05HRi9BTDRIUFVsb2N6UTNudi9sZGplV2RYNjdRSUMxVURjbmlOSzI0UXFWMEgwZFg5aVJ0S28xU09vd1luOTNXbjYzdnVhOWZwSEpYc0dwWUx1QWhRN1dDT3N2Tzl1T3Q5NW1hTUg3NkRPUm1qZUora3RzSERrSUkwNWkwWU9rZ0tzaCt3MGlFV29DTVdRWU83K05hTDl2MGt4UUg3UStkQmJnZ29CRGY2dEVMODJsQks4TDgyUlIybDk2RURUYi81bFUxeEdVVFFLNEd2aTFUVjZEWTlKSjBobkg4UkhJRlFnQWRVSmFQRnF5ekVtaEptQmc1dFNoOE8vVGJUNlR3emV2OWtrK1g4Y29tNVFScmYvWGRONVpDSDFqLzhGeWh1SW1VTlZ3RzhqUGFCWVIwZnJhTlNjS1l1NEFUWUdQRWdERFlxZ1VPd3lEYmQvdG02KysrODNaTFE3VTZmeTZrVHJUeTBVVWJkUkpBOThwT3orekZQUzZFRlVJVFpDZkIvUkFjUVZHcFhBRkNSNzlWM1B3SGtRTjBIekhTYit0dC9NRysvN0cwaXl6Zi9pYjBmOHFVWUFkUmVMeG5zZkNkTDVlR1A4bWI5aTNLWmdGWlVVcEExWWtHUjJ2Mmc5R3lCb0NTaGwwWHBsVXV4N1BMU09mVndrS1dIeTltYXNmNkpIRHdYOEs5UDB3WjkxdXZERnlINzlRMVpQUHhEVm15M0RFT0tsR1IxME5zMGpsRlJGNDBwUkgvamNOS3o5bHFmNWJDeWU3OWxXLzk4QnZYNzhxcW1MMS81ZExRLy9lNnRIVHhBdlBCQVhYN3NuZHBmZklkVzBxOTZHMmk2dWwrWGlFMXFFSjJ2Zi9pNlJJbThCNUh2eFB3WUF5V3dQUWRremhtb0FBQUFBU1VWT1JLNUNZSUk9JykgdG9wIGxlZnQgbm8tcmVwZWF0O3BhZGRpbmctbGVmdDo1N3B4O2N1cnNvcjpwb2ludGVyfS5mb290ZXJ7Ym9yZGVyLXRvcDoxcHggc29saWQgI2I3YjdiNztmb250LXNpemU6MTBweH0uZm9vdGVyIC5pbm5lcntiYWNrZ3JvdW5kOiNmN2Y3Zjc7Ym9yZGVyLXRvcDoxcHggc29saWQgI0ZGRjtoZWlnaHQ6MzBweDttYXJnaW46MH0uZm9vdGVyIC5jb250ZW50e21hcmdpbjo4cHggYXV0byAwO3dpZHRoOjk0MHB4fS50aXRsZXtmb250LXN0eWxlOm5vcm1hbDtmb250LXdlaWdodDo0MDA7bGV0dGVyLXNwYWNpbmc6LTFweDt0ZXh0LXRyYW5zZm9ybTp1cHBlcmNhc2V9LnRpdGxlIGF7Y29sb3I6IzY2Njt0ZXh0LWRlY29yYXRpb246bm9uZX0udGl0bGUgYTpob3Zlcntjb2xvcjojM2I1OTk4fS50YWdsaW5le2NvbG9yOiM5OTk7Zm9udC1zaXplOjEwcHh9Lm5hdmlnYXRpb257cG9zaXRpb246YWJzb2x1dGU7cmlnaHQ6MDt0b3A6MDtmbG9hdDpyaWdodDttYXJnaW4tdG9wOjEwcHh9Lm5hdmlnYXRpb24gbGl7ZmxvYXQ6bGVmdDtmb250LXNpemU6MTBweDtsaXN0LXN0eWxlOm5vbmU7bWFyZ2luLXRvcDoxcHghaW1wb3J0YW50fS5uYXZpZ2F0aW9uIGxpIGF7Ym9yZGVyLWNvbG9yOiNkNmQ2ZDY7Ym9yZGVyLXN0eWxlOnNvbGlkO2JvcmRlci13aWR0aDowIDFweCAwIDA7ZGlzcGxheTpibG9jaztwYWRkaW5nOjNweCA1cHggNHB4fS5jb250YWluZXJ7YmFja2dyb3VuZDojZTllOWU5IHVybCgnZGF0YTppbWFnZS9naWY7YmFzZTY0LFIwbEdPRGxoQ0FBSUFKRUFBT25wNmVUazVPN204QUFBQUNINUJBRUFBQUlBTEFBQUFBQUlBQWdBQUFJTmpBTUpoMnE2RG54T1ZzcW1MUUE3JykgcmVwZWF0O3BhZGRpbmc6MTVweCAwfS5jY29udGVudHtiYWNrZ3JvdW5kOiNmZmYgdXJsKCdkYXRhOmltYWdlL2dpZjtiYXNlNjQsUjBsR09EbGhEd0VCQUtJQUFBQUFBUC8vLy9mMzk5M2QzZi8vL3dBQUFBQUFBQUFBQUNINUJBRUFBQVFBTEFBQUFBQVBBUUVBQUFNUU9MTGMvakRLU2F1OU9Pdk51LzlhQWdBNycpIHJlcGVhdC15IHRvcCByaWdodDtib3JkZXI6c29saWQgMXB4ICNiN2I3Yjc7bWFyZ2luOjAgYXV0bzt0ZXh0LWFsaWduOmxlZnQ7d2lkdGg6OTYwcHh9Lm1haW57ZmxvYXQ6bGVmdDtvdmVyZmxvdzpoaWRkZW47cGFkZGluZzoxMHB4IDE1cHggMDt3aWR0aDo2NTlweH0ub3V0cHV0e21hcmdpbi1ib3R0b206MjBweH0ub3V0cHV0IC5jb250ZW50e2xpbmUtaGVpZ2h0OjE0NSU7b3ZlcmZsb3c6aGlkZGVuO3BhZGRpbmctYm90dG9tOjVweH0uc2lkZWJhcntiYWNrZ3JvdW5kOiNmN2Y3Zjc7Ym9yZGVyLWxlZnQ6MXB4IHNvbGlkICNEREQ7ZmxvYXQ6cmlnaHQ7aGVpZ2h0OjEwMCU7b3ZlcmZsb3c6aGlkZGVuO3BhZGRpbmctdG9wOjVweDt3aWR0aDoyNzBweH0uc2lkZWJhcnN1YntwYWRkaW5nOjVweDtwYWRkaW5nLWxlZnQ6MTBweDtmb250LXdlaWdodDpib2xkfWRpdi53cmFwcGVye21hcmdpbi1ib3R0b206MWVtfS53cmFwcGVyIHVse3dpZHRoOjU2ZW07bGlzdC1zdHlsZTpub25lO3BhZGRpbmctdG9wOi41ZW19LndyYXBwZXIgdWwgbGl7ZmxvYXQ6bGVmdDt3aWR0aDoxMmVtO3BhZGRpbmctbGVmdDoyZW19LmltZ3BsdWdpbntiYWNrZ3JvdW5kOnVybCgnZGF0YTppbWFnZS9wbmc7YmFzZTY0LGlWQk9SdzBLR2dvQUFBQU5TVWhFVWdBQUFCQUFBQUFRQ0FZQUFBQWY4LzloQUFBQUJHZEJUVUVBQUsvSU53V0s2UUFBQUJsMFJWaDBVMjltZEhkaGNtVUFRV1J2WW1VZ1NXMWhaMlZTWldGa2VYSEpaVHdBQUFIaFNVUkJWRGpMcFpJOVNKVnhGTVovcjJZRmZsdy9rY1FzaUp0NWIxaWplMHREdGJRM0d0RlFZd1ZORmJRMXVqUkZhMU1VSktRNFZoWXFkN0s0Z29wSzNVSWx5KzU3bm5NYVhqSGpxb3RuT2ZEbm5PZC9udDg1U1VSd2tEaTAyK09EcWJzbGR4VWxEMG12SHcwOXViU1hRRjF0ODUxMm5HSi9Vei81bG54aTB0QitFOVFJM0QvLytFZlZxaHRwcEd4VU56Q3ptZjBFa29qZzRmUzljQmVTb3l6SFFOdVp4TnlZWHA1Wk01TWsxWmtaVDY4OGI2dGhJQmVuRy9ONE9CNUI0SW5jaVlCQ1Z5R25FQkhPKy9MSDNTRktRdUY0T0VzLzUxbmRYTVhDOEFqcWtucmNnMU81UEdhMmg0Q0pVcVZFUzBPTzdzWWV2djJxb0ZCbUovNGdGNGJvYU9yZzZyUExZV2FZaVZmRG8wbXk4dzV1ajEyUFFsZUIwdmNwNUk2SHNIQVVvcVVoUjI5ekgrNUI0SXhOVHZEbXhsankzeDJZQ1lVd1pWbGJ6WEpoOVVLZVFZNnQybTBMdDk0T2g1bG9QZHFLM0VranpaaTRNTS9ZOURiM01Udi9tWVdWeGFxa3c5SU9BVE5SN0I1QUJIUHJaUXJ0ZzlzYjhYREthMStRT3dzcmk0emVIRDlTQXpFMXd4QlRYejl4dHZNYzVaVTVsaXJMU0tJejE4bkpuaE9aamIyMllLa2hkNG9kZzVpY3Bjb3lMNjY5VEFBdWpseUl2bVBIU1dYWTF0aTFBbVo4bUozRWxQMWlwczEvWU0zSDMwMGcrVys1MW5jOTVZUEVYOGZFYmRBMlJlVllBQUFBQUVsRlRrU3VRbUNDJykgMnB4IDUwJSBuby1yZXBlYXR9LmltZ2FsaWFze2JhY2tncm91bmQ6dXJsKCdkYXRhOmltYWdlL3BuZztiYXNlNjQsaVZCT1J3MEtHZ29BQUFBTlNVaEVVZ0FBQUJBQUFBQVFDQVlBQUFBZjgvOWhBQUFBQkdkQlRVRUFBSy9JTndXSzZRQUFBQmwwUlZoMFUyOW1kSGRoY21VQVFXUnZZbVVnU1cxaFoyVlNaV0ZrZVhISlpUd0FBQUdyU1VSQlZEakx4Wk83aWhSQkZJYS82dTBaVzdHSEJVVjBVUVFUWnpkM1FkaE1ReE9md01SWEVBTkJNTlFYME16QXpGQXdFekh3QVJiTkZEZHdFZDMxTWozWDdhNnVPcjlCdHpOallqS0JKNm5pY1A3djNLcWNKRmF4aEJWdFpVQUs4T0hsbGQyc3Q3WGwzREpQVk9OUCt6RVVWNEhxTDVVRFlIcjV4dnVRQWpnbC9RczdUenZPT1ZBanhqbEMrZVBTd2U2RGZiVmVnTFZ1VDRyMTRlVHI2enZBOHhTQW9CTHp4NnB2ajRsK0RaSWV6dVZrRzlmWTJIN1lSUUlNWklCd3ljbXpIMS9zM0Y4QWFwZklQTkYza1FrNytrdzlQV0J5K0laT2RnNVVnM21rQUFUeS90MHVzb3Z6R2VDVVdUakN6MEIrU2owZWtmZHZrWjNhYkJ2K1U0R2FDdEoxaUVtNkFOUUo2ZkV6ckcvZW5nY0t3L3dYUXZFS3hTRUtReFJHS0U3SXp0K0RTaXdCSk1VU203MXJndU1ZaFFLckJ5Z09JUlN0ZjRUaUZGUkJ2YlJHS2lRTFdQMjl5UlNIS0JUdGZkQm1IczBCVXBndnRnRjR5UkZSK05VS2kwWFpjWWpDZUNHMnNta3pMQUhrYlJCbVAwL1VrMjZPNVluVUFjdEJwMUdzQUkrUzVuUkpKSmFsNUsxYUFNcnEwZDZUbTl1STZ6anlmNzVkQWU2dHgvU3NXZUQvL28yL0FiNklIMy9oMjVwT0FBQUFBRWxGVGtTdVFtQ0MnKSAycHggNTAlIG5vLXJlcGVhdH0uaW1ndmhvc3R7YmFja2dyb3VuZDp1cmwoJ2RhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQkFBQUFBUUNBWUFBQUFmOC85aEFBQUFCR2RCVFVFQUFLL0lOd1dLNlFBQUFCbDBSVmgwVTI5bWRIZGhjbVVBUVdSdlltVWdTVzFoWjJWU1pXRmtlWEhKWlR3QUFBSXRTVVJCVkRqTGZWTTdiQk5CRUgyN2Q3YWxPS2ZZanNNM2dGTGpSQ0FnaUFvRkJBSWhRVU5KaDBTTHFHZ3BFUVcyYTZMUThWR2dBQXFVQnFXazRiQWJERWdvTkNBTEpOdEpsS0R6ZlphWjJiTkZVSkk5emM3ZDdjNTdiM1ptbFRFR3V3M2Y5eDlIVVhRakRFT1hQTWlMOWZ0OTlzL1VUZ0ROWm5PTUF1WUxoY0wxWEc0RUFRVWhTU0M3S2FaWUxHQnA2UzNjN1lJYmpjWWxEaTZYeXdmeitUeFd2djhBc3llSlFXSVNBaktJQ1N3SUFyaXRWaXVJNHpoTEpwc0dNdGwzdTkzL0phUFQ2UkpRZ2dzWEw4cy9sNE1uSncrajExc1Zkc09QWVpWR2pEK0lFNlhpR042OGZvV2psZVBDem11aWdGRTUrTzY4VDlzVWxLTFpUdUxaMXRmVzhPRFdLV0g4Nkw4SHE5MS81WnBWd0ZLWmxUY1dTK1BRV2tPUjZkVDRuUUZNWWhrck15ZmwzYVJub0ZrQmZST0FodU00VzB5bm5nY2ZIalArOWxhdzBLdEpXcUlnVE11anRJTGp1a04yOFp3Q2VWczV5N2p3NVJFMjFpTlJJUUE4OFlGd0NzdzR0V2RFOHJkRDRlZHFsQ3F3akhmRzd5RXBXVUFtRndDZDVzbjI3ZXYySGVsb1J3QnNMOWhLRFJWa01pN3UzendtNVFuRENKdWJnVEJrc3hsS3cwajNhV1hYWW81TXl5Z0tLSytIeTh2dnpnNGFoWHpKODd3cHJrNjczUTVJWFk1VDQ3aks5QXlPSERvZ2l2YnRuWkJtMjNJWDZ2WDZiUUs1T252NnpEblBLK0RsaTZkL3FPWlA2SHhtNmYvMHYxM0tSbXVmaHdDMVdtMkNTdlpyYnU0OFJqMlBOc1J3SFUyZzFZMXF0VHE2MDIwZFhpYVMzaUg3c0xqNC9NU2cvMVBHVDd0ZDk3K0c4YUE0RkpPdDF3QUFBQUJKUlU1RXJrSmdnZz09JykgMnB4IDUwJSBuby1yZXBlYXR9LndyYXBwZXIgYnJ7Y2xlYXI6bGVmdH0uc2hhZGV7ZmxvYXQ6cmlnaHQ7Y29sb3I6Izk5OX1pbnB1dFt0eXBlPSJ0ZXh0Il17dGV4dC1hbGlnbjpsZWZ0O2JvcmRlcjowO2ZvbnQtZmFtaWx5Ok1vbmFjbywnQ291cmllciBOZXcnLCdEZWphVnUgU2FucyBNb25vJywnQml0c3RyZWFtIFZlcmEgU2FucyBNb25vJyxtb25vc3BhY2U7Zm9udC1zaXplOjExcHg7Y29sb3I6IzY2NjtiYWNrZ3JvdW5kOiNmN2Y3Zjd9LmJib3JkZXIxcHh7Ym9yZGVyLWJvdHRvbToxcHggc29saWQgI2NjY30uYmJvcmRlcjJweHtib3JkZXItYm90dG9tOjJweCBzb2xpZCAjY2NjfS5jZW50ZXJwYWR7dGV4dC1hbGlnbjpjZW50ZXI7cGFkZGluZzoxMHB4fS5mbG9hdHJpZ2h0e2Zsb2F0OnJpZ2h0fS5waHBpbmZvIHRhYmxle2JvcmRlci1jb2xsYXBzZTpjb2xsYXBzZX0ucGhwaW5mbyB0YWJsZXttYXJnaW4tbGVmdDphdXRvO21hcmdpbi1yaWdodDphdXRvO3RleHQtYWxpZ246bGVmdH0ucGhwaW5mbyB0aHt0ZXh0LWFsaWduOmNlbnRlciFpbXBvcnRhbnR9LnBocGluZm8gdGQsdGh7Ym9yZGVyOjFweCBzb2xpZCAjOTk5O2ZvbnQtc2l6ZTo3NSU7dmVydGljYWwtYWxpZ246YmFzZWxpbmU7cGFkZGluZzo1cHh9LnBocGluZm8gaW1ne2Zsb2F0OnJpZ2h0O2JvcmRlcjowfS5waHBpbmZvIGgxe2ZvbnQtc2l6ZToxNTAlO3BhZGRpbmc6NXB4fS5waHBpbmZvIGgye2ZvbnQtc2l6ZToxMjUlO3BhZGRpbmc6NXB4fS5waHBpbmZvIGhye3dpZHRoOjYwMHB4O2JhY2tncm91bmQtY29sb3I6I2NjYztib3JkZXI6MDtoZWlnaHQ6MXB4O2NvbG9yOiMwMDB9LnBocGluZm8gdGh7YmFja2dyb3VuZC1jb2xvcjojQ0NDfS5waHBpbmZvIC5we3RleHQtYWxpZ246Y2VudGVyO2ZvbnQtc2l6ZToyMDAlfS5waHBpbmZvIC5le2ZvbnQtd2VpZ2h0OmJvbGQ7Y29sb3I6IzAwMDt3aWR0aDozMyV9LnBocGluZm8gLmh7Zm9udC13ZWlnaHQ6Ym9sZDtjb2xvcjojMDAwfS5waHBpbmZvIC52e2NvbG9yOiMwMDB9LnBocGluZm8gLnZye3RleHQtYWxpZ246cmlnaHQ7Y29sb3I6IzAwMH0ucGhwaW5mbyB0cjpudGgtY2hpbGQob2RkKXtiYWNrZ3JvdW5kLWNvbG9yOiNmMGYwZjB9LnBocGluZm8gdHI6bnRoLWNoaWxkKGV2ZW4pe2JhY2tncm91bmQtY29sb3I6I2ZmZn0=" type="text/css" media="screen" />
</head>
<body>
	<!-- header START -->
	<div class="header">
		<div class="inner">
			<div class="content">
				<div class="caption" onclick="location.href=\'http://www.ampricot.com/\';">
					<h1 class="title"><a href="http://www.ampricot.com/" title="Cross-platform web server solution stack package for professional web development.">Ampricot</a></h1>
					<div class="tagline">Cross-platform web server solution stack package for professional web development.</div>
				</div>

				<!-- navigation START -->
				<ul class="navigation">
					<li class="page_item"><a href="http://www.ampricot.com/">' . gettext('Homepage') . '</a></li>
					<li class="page_item"><a href="http://www.ampricot.com/faq/">' . gettext('FAQ') . '</a></li>
					<li class="page_item"><a href="http://www.ampricot.com/screenshots/">' . gettext('Screenshots') . '</a></li>
					<li class="page_item"><a href="http://www.ampricot.com/download/">' . gettext('Downloads') . '</a></li>
					<li class="page_item"><a href="http://www.ampricot.com/news/">' . gettext('News') . '</a></li>
					<li class="page_item"><a href="http://www.ampricot.com/contributors/">' . gettext('Contributors') . '</a></li>
					<li class="page_item"><a href="http://www.fruitechlabs.com/about/">' . gettext('About Us') . '</a></li>
					<li class="page_item"><a href="http://www.ampricot.com/contact/">' . gettext('Contact') . '</a></li>
					<li class="page_item"><a href="http://www.ampricot.com/support/">' . gettext('Support') . '</a></li>
				</ul>
				<!-- navigation END -->
			</div>
		</div>
	</div>
	<!-- header END -->

	<div class="container">
		<div class="ccontent">
			<div class="main">
				<div class="output">
					<div class="content">
						' . $htmloutput . '
					</div>
				</div>
			</div>

			<!-- sidebar START -->
			<div class="sidebar">
				<div class="sidebarsub">
					<div class="bborder1px">' . gettext('Navigation:') . '</div>
					<div><a href="./">' . gettext('Home') . '</a></div>
					<div><a href="index.php?do=phpinfo">' . gettext('PHP Info') . '</a></div>
					<div><a href="index.php?do=phpcredits">' . gettext('PHP Credits') . '</a></div>

					<div>&nbsp;</div>

					<div class="bborder1px"><span class="shade">' . $ampricotconf['ampricotversioncore'] . '</span>Ampricot:</div>
					<div><span class="shade">' . $ampricotconf['ampricotversionapache'] . '</span>Apache:</div>
					<div><span class="shade">' . $ampricotconf['ampricotversionmysql'] . '</span>MySQL:</div>
					<div><span class="shade">' . $ampricotconf['ampricotversionphp'] . '</span>PHP:</div>

					<div>&nbsp;</div>

					<div class="bborder1px">' . gettext('Virtual Host:') . '</div>
					<div><input size="100%" type="text" spellcheck="false" onclick="this.select();" value="http://localhost/" /></div>
					<div><input size="100%" type="text" spellcheck="false" onclick="this.select();" value="@AMPRICOTINSTALLDIRCORE@/front/data/www/localhost" /></div>

					<div class="bborder2px">&nbsp;</div>
					<div class="centerpad">' . gettext('Server Status: ') . (($ampricotconf['ampricotstatus'] == 'on') ? gettext('On The Web') : gettext('Off The Web')) . '</div>
					<div class="centerpad" style="padding-top: 0px !important">' . gettext('Harmony Mode: ') . ucfirst($ampricotconf['ampricotharmony']) . '</div>
					' . ((!empty($loadavg)) ? '<div class="centerpad" style="padding-top: 0px !important">' . gettext('Server Load: ') . $loadavg[0] . '&nbsp;&nbsp;' . $loadavg[1] . '&nbsp;&nbsp;' . $loadavg[2] . '</div>' : '') . '
				</div>
			</div>
			<!-- sidebar END -->

			<div class="fixed"></div>
		</div>
	</div>

	<!-- footer START -->
	<div class="footer">
		<div class="inner">
			<div class="content">
				<p style="text-align: center;">Copyright &copy; 2012 <a href="http://www.fruitechlabs.com" target="_blank">FruiTech Labs</a> | Proudly Made In Egypt.</p>
			</div>
		</div>
	</div>
	<!-- footer END -->
</body>
</html>';

$output = preg_replace(array(
	'#(<pre[^>]*?>)(.*?)</pre>#sie',
	'#>\s+<#s',
	'#(<s(?:cript|tyle)[^>]*?>[^<]*?<!)--#si',
	'#<!--(\s*(\[|\/?VBS|google_ad))#s',
	'#<!--.*?-->#s',
	'#<!js#',
	'#\@vbseo_r_n\@#',
),
array(
	"str_replace('\\\\\"', '\"', '$1'.preg_replace(\"#\r?\n#s\",'@vbseo_r_n@','$2').'$3')",
	'> <',
	'\\1js',
	'<!js\\1',
	'',
	'<!--',
	"\n"
), $output);

echo $output;

?>