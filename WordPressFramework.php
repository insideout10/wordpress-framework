<?php

// the autoload code is deliberately taken from the log4php project.
if (function_exists('__autoload')) {
	trigger_error("WordPressFramework: It looks like your code is using an __autoload() function. WordPressFramework uses spl_autoload_register() which will bypass your __autoload() function and may break autoloading.", E_USER_WARNING);
}

spl_autoload_register(array('WordPressFramework', 'autoload'));

class WordPressFramework {
	
	// the list of classes part of this framework, for autoloading.
	private static $_classes = array(
			'Category' => '/domain/Category.php',
			'CategoryService' => '/services/CategoryService.php'
	);
	
	/**
	 * Class autoloader. This method is provided to be invoked within an
	 * __autoload() magic method.
	 * @param string $className The name of the class to load.
	 */
	public static function autoload($className) {
		if(isset(self::$_classes[$className])) {
			include dirname(__FILE__) . self::$_classes[$className];
		}
	}
	
	public static function loadWordPress($wpload = 'wp-load.php') {
		ob_start(); // avoid wp-load printing empty characters.
		require_once($wpload);
		ob_end_clean();
	}
	
}

?>