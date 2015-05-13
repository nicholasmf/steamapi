<html>

<head>
	<link rel = "stylesheet" href = "./style.css" type = "text/css">
	<script>
		function showNW (table)
		{
			var max = 0;
			var pos = 0;
			var n = 0;
			var nwArray = [];
			for (i = 2; i < table.rows.length; i++)
			{
				var cells = table.rows.item(i).cells;
				var cellLength = cells.length;
				for (j = 2; j < cellLength-1; j+=2)
				{
					var value = cells.item(j).innerHTML;
					nwArray[n++] = value;
					//alert(value);
				}
			}

			for (i = 0; i < nwArray.length; i++)
			{
				max = 0;
				for (j = i; j < nwArray.length; j++)
				{
					if (max < parseInt(nwArray[j]))
					{
						max = nwArray[j];
						pos = j;
						//alert(i + " " + max);
					}
				}
				nwArray[pos] = nwArray[i];
				nwArray[i] = max;
				//alert(max);
			}



			alert (nwArray[0]);
		}

		window.onload = function () { /*alert();*/ };
	</script>
</head>

<body>
	<table style = 'hidden' id = 'nwTable' border = '1'>
	</table>

<?php
	$aplay = [];
	$n = 1;
	$error = 0;

/*
	$jsonurl = "http://api.steampowered.com/IDOTA2Match_570/GetLiveLeagueGames/v1/?key=C65EF0F757498EEA88019EB8C970C4A1";

	// CURL settings	
	$curl = curl_init ();
	curl_setopt($curl, CURLOPT_URL, $jsonurl);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	// get output	
	$json1 = curl_exec($curl);
	curl_close($curl);
*/

	$jsonurl = "./live.json";
	$json1 = file_get_contents($jsonurl,0, null,null);
	$json_output = json_decode($json1);

	$count = 0;
	foreach ($json_output->result->games as $game)
	{
		if ($game->league_tier >= 2)
		{
		$count++;
		$radnw = 0;
		$direnw = 0;
		$n = 1;
		echo "<table class = 'games' id = '" . $game->match_id . "' onClick='showNW(this)'>";
		echo "<colgroup>" . 
			"<col style = 'width: 5%'>" .
			"<col style = 'width: 35%'>" .
			"<col style = 'width: 5%'>" .
			"<col style = 'width: 10%'>" . 
			"<col style = 'width: 5%'>" . 
			"<col style = 'width: 35%'>" .
			"<col style = 'width: 5%'>" .
			"</colgroup>";
			if ($game->radiant_team->team_name != "" && $game->dire_team->team_name != '')
			{
				// Get array with players id and name
				foreach ($game->players as $player)
				{
					if ($player->team < 2)
					{
						$aplay[$n]['id'] = $player->account_id;
						$aplay[$n++]['name'] = $player->name;
					}
				}

				// Team name and score
				echo "<tr>" . "<th colspan = '7'>" . date("i:s", (int)$game->scoreboard->duration) . "</th></tr><tr>" .
					"<th align = 'left'>" . $game->radiant_team->team_name . "</th>" .
					"<th align = 'center'>" . $game->scoreboard->radiant->score . "</th>" .
					"<th align = 'center'>VS</th>" .
					"<th align = 'center'>" . $game->scoreboard->dire->score . "</th>" .
					"<th align = 'right'>" . $game->dire_team->team_name . "</th>" .
					"</tr><tr></tr>";
				echo "League tier: " . $game->league_tier . "<br>";

				// Towers state
				//$rtowers = $game->scoreboard->radiant->tower_state;
				//$dtowers = $game->scoreboard->dire->tower_state;
				//echo "Towers :<br>";
				//echo decbin($rtowers);
				//echo "<br>".decbin($dtowers)."<br>";

				/*
				*/
				foreach ($game->scoreboard->radiant->players as $players)
				{
					foreach ($aplay as $key => $ap)
					{
						if ($ap['id'] == $players->account_id)
						{
							$aplay[$key]['nw'] = $players->net_worth;
							$aplay[$key]['slot'] = ($players->player_slot);
							$aplay[$key]['rt'] = $players->respawn_timer;
							$aplay[$key]['id'] = $players->hero_id;
							$radnw += $players->net_worth;
							$aplay[$key]['k'] = $players->kills;
							$aplay[$key]['d'] = $players->death;
							$aplay[$key]['a'] = $players->assists;
							$aplay[$key]['lh'] = $players->last_hits;
							$aplay[$key]['de'] = $players->denies;
						}
					}
					//echo "key: " . in_array('$players->account_id', $aplay[1]);
					//$aplay[$players->account_id][nw] = $players->new_worth;
					//$aplay[$players->account_id][slot] = $players->player_slot;
				}
				foreach ($game->scoreboard->dire->players as $players)
				{
					foreach ($aplay as $key => $ap)
					{
						if ($ap['id'] == $players->account_id)
						{
							$aplay[$key]['nw'] = $players->net_worth;
							$aplay[$key]['slot'] = ($players->player_slot + (int)5);
							$aplay[$key]['rt'] = $players->respawn_timer;
							$aplay[$key]['id'] = $players->hero_id;
							$direnw += $players->net_worth;
							$aplay[$key]['k'] = $players->kills;
							$aplay[$key]['d'] = $players->death;
							$aplay[$key]['a'] = $players->assists;
							$aplay[$key]['lh'] = $players->last_hits;
							$aplay[$key]['de'] = $players->denies;
						}
					}
					//$aplay[$players->account_id][nw] = $players->new_worth;
					//$aplay[$players->account_id][slot] = $players->player_slot;
				}
//				parseScoreboardPlayers($game->scoreboard->radiant);
//				parseScoreboardPlayers($game->scoreboard->dire);
				for ($i = 1; $i <= ($n/2); $i++)
				{
					$j = 1;
					$k = 1;
					while (($aplay[$j]['slot'] != $i) && ($j != $n)) 
					{ 
//						echo "slot: " . $aplay[$j][slot] . " e i: " . $i . "<br>";
						$j++; 
					}
					while (($aplay[$k]['slot'] != ($i+(int)5)) && ($k != $n)) $k++;
					echo "<tr><td>" . 
						"<img src = './heroes_icons/small/" . $aplay[$j]['id'] . ".png'></img>" .
						"</td>" . "<td align='left'>" .
						"(" . (int)$aplay[$j]['rt'] . ")" . 
						$aplay[$j]['name'] . "<br>" . 
						$aplay[$j]['k'] . "/" . $aplay[$j]['d'] . "/" . $aplay[$j]['a'] . " " .
						$aplay[$j]['lh'] . "/" . $aplay[$j]['de'] . "</td>" . 
						"<td>" . $aplay[$j]['nw'] . "</td>" . 
						"<td>" . "</td>" .
						"<td>" . $aplay[$k]['nw'] . "</td>" .
						"<td align='right'>" . $aplay[$k]['name'] .
						"(" . (int)$aplay[$k]['rt'] . ")" . "<br>" . 
						$aplay[$k]['k'] . "/" . $aplay[$k]['d'] . "/" . $aplay[$k]['a'] . " " .
						$aplay[$k]['lh'] . "/" . $aplay[$k]['de'] . "</td>" . "<td>" .
						"<img src = './heroes_icons/small/" . $aplay[$k]['id'] . ".png'></img>" .
						"</td>" . "</tr>";
				}
				echo "<tr><td colspan = '3' align = 'right'>$radnw</td>" .
				"<td align = 'center'>" . ($radnw - $direnw) . "</td>" .
				"<td colspan = '3' align = 'left'>$direnw</td></tr>";
				//print_r($aplay);
			}
		}
		echo "</table>";
	}
	//$match_id = $json_output->result->matches[1]->match_id;
	//$match_start = "&start_at_match_id=".$match_id;

	//echo "finished search for account: ".$accid."<br>";
	//echo "Files downloaded: ".$count_ok." / Failed downloads: ".$count_fail."<br><br>";
	//echo $count;
	//print_r($aplay);
	echo $count == 0 ? "No games found" : "";
?>

</body>

</html>
