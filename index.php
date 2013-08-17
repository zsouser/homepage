<?php

include "scripts/init.php";

?><!doctype html>
<html>
<head>
<link rel="stylesheet" href="/css/layout.css">
<meta name="viewport" content="width=800px, maximum-scale=1">
<meta property="og:title" content="Zach Souser" />
<meta property="og:url" content="http://www.zsouser.com/" />
<meta property="og:image" content="http://www.zsouser.com/img/pre.jpg" />
<meta property="fb:admins" content="1081981216"/>
<script src='http://code.jquery.com/jquery-1.10.2.min.js'></script>
<?
Router::head($_SERVER['REQUEST_URI']);
?>
<script>

function auth(realm,fn) {
	$.ajax("/auth/"+realm).complete(function(data) {
		if (data.status == 200) return fn();
	});
}

</script>
</head>
<body>
<div id='bg-img'></div>
<div id='all'>
<header><h1>Hi, I'm Zach</h1>
<nav>Welcome to <a href='/'>my website</a>! Check out my <a href='/resume'>résumé</a>, <a href='/blog'>code blog</a>, or <a href='/contact/'>contact me</a>.</nav>
</header>
<div id="content">
<? 
Router::route($_SERVER['REQUEST_URI']);
?>
<div style='clear:both;'>&nbsp;</div>
</div>
</div>

</body>
</html>
<?php
ob_end_flush();
?>
