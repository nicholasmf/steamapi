<?php
/*
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
*/

	// curl settings
	$curl = curl_init ();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// get output	

	$file = file_get_contents ("./items.json", 0, null, null);
	$json_output = json_decode($file);
	//print_r ($json_output);
	foreach ($json_output->itemdata as $name => $item)
	{
		$iname = $item->img;
		$url = "http://cdn.dota2.com/apps/dota2/images/items/" . $iname;
		curl_setopt($curl, CURLOPT_URL, $url);
		
		$img = "./items_icons/" . $item->id . ".png";
		do { if (($getImage = curl_exec($curl)) === FALSE) echo "error getting image\n"; } while ($getImage === FALSE);
		file_put_contents($img, $getImage);
		echo $item->id . ".png saved successfully\n";

	}
	curl_close($curl);
/*
*/

/*
	// multi curl settings
	$curl_arr = array();
	$results = array();
	$mh = curl_multi_init();

	$file = file_get_contents ("./items.json", 0, null, null);
	$json_output = json_decode($file);
	//print_r ($json_output);

	// loop through items list
	foreach ($json_output->itemdata as $name => $item)
	{
		// get target url
		$iname = $item->img;
		$id = $item->id;
		$url = "http://cdn.dota2.com/apps/dota2/images/items/" . $iname;

		// curl settings
		$curl_arr[$id] = curl_init ();
		curl_setopt($curl_arr[$id], CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_arr[$id], CURLOPT_URL, $url);

		curl_multi_add_handle($mh, $curl_arr[$id]);
	}

	// execute the handles
	$running = null;
	do {
		$status = curl_multi_exec($mh, $running);
		if ($status > 0)
		{
			echo "ERROR!\n" . curl_multi_strerror($status);
		}
	} while($status === CURLM_CALL_MULTI_PERFORM || $running);

	// get content and remove handles
	foreach ($curl_arr as $id => $c)
	{
		$results[$id] = curl_multi_getcontent($c);
		curl_multi_remove_handle($mh, $c);
	}

	// all done 
	curl_multi_close($mh);

	foreach ($results as $id => $im)
	{
		if ($im != "")
		{
			$img = "./items_icons/" . $id . ".png";
			file_put_contents($img, $im);
			echo $id . ".png saved successfully\n";
		}
	}
*/
?>

