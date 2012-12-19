<?php
define("RESTPHP_PATH", dirname(__FILE__));
define("RESTPHP_MIN_PHP", "5.3.0");

/**
 * @see http://php.net/manual/en/function.phpversion.php
 */
function checkMinReqs() {
	$minPHP = explode(".", RESTPHP_MIN_PHP);
	for($i = 0; $i <= 2; $i++) {
		if(!isset($minPHP[$i])) {
			$minPHP[$i] = 0;
		}
	}
	$minPHP = $minPHP[0] * 10000 + $minPHP[1] * 100 + $minPHP[2];

	if(!defined('PHP_VERSION_ID') || PHP_VERSION_ID < $minPHP) {
		die(sprintf("Minimum PHP version is %s, installed PHP version is %s",
			RESTPHP_MIN_PHP,
			PHP_VERSION
		));
	}
}

spl_autoload_register(function ($class) {
	$folders = array(
			"",
			"/plugins",
	);

	foreach($folders as $folder) {
		$file = RESTPHP_PATH . "{$folder}/{$class}.php";
		if(file_exists($file)) {
			require_once $file;
			return;
		}
	}
});

checkMinReqs();
?>