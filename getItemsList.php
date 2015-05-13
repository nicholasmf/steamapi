<?php
	$jsonurl = "http://www.dota2.com/jsfeed/itemdata";
	$curl = curl_init ();
	curl_setopt($curl, CURLOPT_URL, $jsonurl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// get output	
	$json1 = curl_exec($curl);
	curl_close($curl);

	file_put_contents ("./items.json", $json1);
?>
