<?php

Router::$routes = array(
		"blog" => "blog.php",
		"" => "home.php",
		"resume" => "resume.php",
		"auth" => "auth.php",
		"contact" => "contact.php"
	);
	
Router::$css = array( 
		"blog" => "blog.css",
		"" => "home.css"
	);

Router::$js = array(
		"contact" => "contact.js",
		"blog" => "blog.js",
	);

Router::$title = array(
		"blog" => "Blog",
		"resume" => "Resume"
	);
	
define('TITLE','Zach Souser');

class Router {
	static $routes, $css, $js, $title;	
	static function getParams($uri) {
		$params = array();
		list ($uri,$get) = explode("?",$uri);
		foreach (explode("&",$get) as $param) {
		  list ($key, $val) = explode("=",$param);
		  $_GET[$key] = $val;
		}
		$path = explode("/",$uri);
		foreach ($path as $param) {
			if (!empty($param)) 
			$params[] = $param;
		}
		return $params;
	}
	
	static function route($uri) {
		$params = self::getParams($uri);
		$route = array_shift($params);
		if ($route == "..") echo "GTFO";
		if (isset(self::$routes[$route]))
			include "scripts/".self::$routes[$route];
		else include "scripts/404.php";
	}

	static function head($uri) {
		$params = self::getParams($uri);
		$route = array_shift($params);
		echo "<title>".TITLE;
		if (isset(self::$title[$route])) echo " - " . self::$title[$route];
		echo "</title>";
		if (isset(self::$css[$route])) echo "<link rel='stylesheet' href='/css/".self::$css[$route]."'>";
		if (isset(self::$js[$route])) echo "<script src='/js/".self::$js[$route]."'></script>"; 
	}
}

ob_start();

?>