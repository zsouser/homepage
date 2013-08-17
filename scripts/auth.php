<?php
ob_clean();
$realms = array(
	"blog" => "Enter a user name and password to modify this blog"
);
$access = array(
	"blog" => array( 
		"zach"=>"password"
		
	),
	"download" => array(
		"zach"=>"password"
	),
	"print" => array(
		"hire"=>"me"
	)
);
if (!isset($_SERVER['PHP_AUTH_USER']) || ($access[$params[0]][$_SERVER['PHP_AUTH_USER']] != $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="'.$realms[$params[0]].'"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}
?>