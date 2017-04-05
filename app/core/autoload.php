<?php

/**
 * Load application core
 *
 * List of file included:
 *
 * config.php { Global variables }
 * _functions.php { Controllers functions }
 * EKETwig.php { Twig Loader }
 * EKEDB.php { Database connection class }
 * EKEModel.php { Abstract Model }
 * EKELog.php { Log Model }
 * EKEMail.php { Log Model }
 * Dump_Router.php { Router class }
 *
 */
Class CoreLoader {

	/**
	 * List of file to include
	 * @var array[string]
	 */
	public static $files = [
		'config/config.php',
		'controller/_functions.php',
		'controller/EKETwig.php',
		'database/EKEDB.php',
		'model/EKELog.php',
		'model/EKEMail.php',
		'model/EKEModel.php',
		'router/Dump_Router.php'
	];

}

/**
 * Require all core scripts
 */
$core_files = CoreLoader::$files;

foreach ($core_files as $path) {

    require_once __DIR__ . '/' . $path;

}
