<?php
function getMons() {
    $mon_name = json_decode(file_get_contents('../json/pokedex.json'), true);	
    require('config.php');
    $result = $conn->query("SELECT * FROM pokemon WHERE disappear_time > utc_timestamp() and pokemon_id>493");
    if ($result->num_rows > 0) {
        while($row = $result->fetch_object()) {
			$cp = $row->cp;
            $iv = round((($row->individual_attack+$row->individual_defense+$row->individual_stamina)/45)*100,2) . '%';
			if ($cp==NULL){$cp='-';$iv='-';} else {$cp = $row->cp . ' CP';}
			?>
            <tr>
                <td> 
                    <img height='42' width='42' 	
                    src='<?=$assetRepo?>pokemon_icon_<?=str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT)?>_00.png' />
                    <?=$mon_name[$row->pokemon_id]['name']?>
                </td>
                <td><?=$iv?></td>
                <td><?=$cp?></td>
                <td><?=$row->disappear_time?></td>
                <td><?=$row->last_modified?></td>
                <td> 
                    <a href='https://maps.google.com/?q=<?=$row->latitude . ','. $row->longitude?>'>MAP</a>
                </td>
            </tr>
     <?php }
  } else {
      echo "ERROR: No Results Found!";
  }
}

function getRaids() {
	$raid_mon_name = json_decode(file_get_contents('../json/pokedex.json'), true);
  require('config.php');
  $result = $conn->query("SELECT * FROM raid,gymdetails WHERE raid.gym_id=gymdetails.gym_id AND (raid.pokemon_id != '(NULL)') AND raid.end > utc_timestamp() ORDER BY raid.end ASC;");
  if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $Time_Start = explode(" ", $row["start"]);
          $Time_End = explode(" ", $row["end"]);
		  $raid_scan_time = explode(" ", $row["last_scanned"]);
          echo "<tr>";
          echo "<td> <img height='42' width='42' src='" . $assetRepo . "pokemon_icon_"; if ($row["pokemon_id"] < 100 && $row["pokemon_id"] > 9) { echo '0' . $row["pokemon_id"]; } elseif ($row["pokemon_id"] < 10) { echo '00' . $row["pokemon_id"]; } else { echo $row["pokemon_id"]; }; echo "_00.png'> ". $raid_mon_name[$row["pokemon_id"]]['name'] . "</td>";
          echo "<td> " . $row["name"] . "</td>";
          echo "<td> " . $row["cp"] . "</td>";
          echo "<td> " . $row["level"] . " ★</td>";
          echo "<td> " . substr($Time_Start[1],0,strrpos($Time_Start[1],':')) .  " - " . substr($Time_End[1],0,strrpos($Time_Start[1],':')) . "</td>";
          echo "<td> " . $raid_scan_time[1] . "</td>";
          echo "</tr>";
      }
  } else {
      echo "ERROR: No Results Found!";
  }
}

function index() {
}

?>
