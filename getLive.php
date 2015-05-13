<?php
	$url = "./live.json";

	$live = file_get_contents($url, null, null);
	echo $live;
?>
