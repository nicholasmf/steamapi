<?php
/*
	$file = file_get_contents ("./heroes.json", 0, null, null);
	$json_output = json_decode($file);
	//print_r ($json_output);
	foreach ($json_output->result->heroes as $heroes)
	{
		//echo $heroes->localized_name . "\n";
		$name = $heroes->name;
		$name = str_replace ("npc_dota_hero_", "", $name);
		$url = "http://cdn.dota2.com/apps/dota2/images/heroes/" . $name . "_sb.png";
		$img = "./heroes_icons/small/" . $heroes->id . ".png";
		do { if (($getImage = file_get_contents($url)) === FALSE) echo "error getting image\n"; } while ($getImage === FALSE);
		file_put_contents($img, $getImage);
		echo $heroes->id . ".png saved successfully\n";
	}
*/

/*
*/
	// curl settings
	$curl = curl_init ();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	// get heroes list file
	$file = file_get_contents ("./heroes.json", 0, null, null);
	$json_output = json_decode($file);

	// download all heroes images
	foreach ($json_output->result->heroes as $heroes)
	{
		$name = $heroes->name;
		$name = str_replace ("npc_dota_hero_", "", $name);
		$url = "http://cdn.dota2.com/apps/dota2/images/heroes/" . $name . "_sb.png";
		curl_setopt($curl, CURLOPT_URL, $url);
		
		$img = "./heroes_icons/small/" . $heroes->id . ".png";
		do { if (($getImage = curl_exec($curl)) === FALSE) echo "error getting image\n"; } while ($getImage === FALSE);
		file_put_contents($img, $getImage);
		echo $heroes->id . ".png saved successfully\n";
	}
	curl_close($curl);

/*
	// multi curl settings
	$curl_arr = array();
	$results = array();
	$mh = curl_multi_init();

	$file = file_get_contents ("./heroes.json", 0, null, null);
	$json_output = json_decode($file);

	// loop through heroes list
	foreach ($json_output->result->heroes as $heroes)
	{
		// get target url
		$name = $heroes->name;
		$name = str_replace ("npc_dota_hero_", "", $name);
		$id = $heroes->id;
		$url = "http://cdn.dota2.com/apps/dota2/images/heroes/" . $name . "_sb.png";

		// curl settings
		$curl_arr[$id] = curl_init();
		curl_setopt($curl_arr[$id], CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_arr[$id], CURLOPT_URL, $url);

		curl_multi_add_handle($mh, $curl_arr[$id]);
	}

	// execute the handles
	$running = null;
	do {
		curl_multi_exec($mh, $running);
	} while($running > 0);

	// get content and remove handles
	foreach ($curl_arr as $id => $c)
	{
		$results[$id] = curl_multi_getcontent($c);
		curl_multi_remove_handle($mh, $c);
	}

	//print_r($results);

	// all done 
	curl_multi_close($mh);

	foreach ($results as $id => $im)
	{
		$img = "./heroes_icons/small/" . $id . ".png";
		file_put_contents($img, $im);
		echo $id . ".png saved successfully\n";
	}
*/
?>
