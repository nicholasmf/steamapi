<?php
	$jsonurl = "http://api.steampowered.com/IEconDOTA2_570/GetHeroes/v1?key=C65EF0F757498EEA88019EB8C970C4A1&language=en";	
	$curl = curl_init ();
	curl_setopt($curl, CURLOPT_URL, $jsonurl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// get output	
	do
	{
		$json1 = curl_exec($curl);
	} while ($json1 == false);
	curl_close($curl);

	file_put_contents ("./heroes.json", $json1);
?>
