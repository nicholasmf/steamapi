<?php
	$url = "http://api.steampowered.com/IDOTA2Match_570/GetLiveLeagueGames/v1/?key=C65EF0F757498EEA88019EB8C970C4A1";

	// CURL settings	
	$curl = curl_init ();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// get output	
	$json1 = curl_exec($curl);
	curl_close($curl);

	echo date("d/m/Y H:i:s");
	if (file_put_contents ("./live.json", $json1) )
	{
		echo "-download completed";
	}
	else
	{
		echo "-download failed";
	}
?>
