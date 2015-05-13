<?php
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
?>
