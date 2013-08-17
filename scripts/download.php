<?php

if (!isset($params[0])) {
	$ob = ob_get_clean();
	$params[0] = "download";
	include "auth.php";
	echo $ob;
	foreach (glob("files/*") as $file) {
		echo basename($file);
	}
}

?>