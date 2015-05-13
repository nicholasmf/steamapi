<?php
	$file = file_get_contents ("./items.json", 0, null, null);
	$json_output = json_decode($file);
	//print_r ($json_output);
	foreach ($json_output->itemdata as $name => $item)
	{
		//echo $heroes->localized_name . "\n";
		$iname = $item->img;
		$url = "http://cdn.dota2.com/apps/dota2/images/items/" . $iname;
		$img = "./items_icons/" . $item->id . ".png";
		do { if (($getImage = file_get_contents($url)) === FALSE) echo "error getting image\n"; } while ($getImage === FALSE);
		file_put_contents($img, $getImage);
		echo $item->id . ".png saved successfully\n";
	}
?>
