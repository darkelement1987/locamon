<?php
function getMons()
{
    global $conn;
    global $assetRepo;
	global $clock;
    $mons = [];
    $mon_name = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    $sql = "SELECT individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE disappear_time > utc_timestamp();";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            if ($row->individual_attack !== null && $row->individual_defense !== null &&  $row->individual_stamina !== null) {
                $row->iv = round((($row->individual_attack + $row->individual_defense + $row->individual_stamina) / 45) * 100, 2);
            } else {
                $row->iv = '';
            }
            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
            $row->name = $mon_name[$row->pokemon_id]['name'];
            $mons[] = $row;
        }
    } else {
        var_dump($conn);
        $mons =  '<tr><td colspan="6" class="text-center"> No Pokemon At This Time</td></tr>';
    }
    return $mons;
}

function getRaids()
{
    global $conn;
    global $assetRepo;
	global $clock;
    $raids = [];
    $sql = "SELECT UNIX_TIMESTAMP(CONVERT_TZ(a.start, '+00:00', @@global.time_zone)) as start, UNIX_TIMESTAMP(CONVERT_TZ(a.end, '+00:00', @@global.time_zone)) as end, UNIX_TIMESTAMP(CONVERT_TZ(a.spawn, '+00:00', @@global.time_zone)) as spawn, a.pokemon_id, a.move_1, a.move_2, UNIX_TIMESTAMP(CONVERT_TZ(a.last_scanned, '+00:00', @@global.time_zone)) as last_scanned, b.name, a.level, a.cp, c.latitude, c.longitude FROM raid a INNER JOIN gymdetails b INNER JOIN gym c ON a.gym_id = b.gym_id AND a.gym_id = c.gym_id  AND a.end > UTC_TIMESTAMP() ORDER BY a.end ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            $row->time_start = date($clock, $row->start);
            $row->time_end = date($clock, $row->end);
            $row->raid_scan_time = date($clock, $row->last_scanned);
            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 0, 3, STR_PAD_LEFT) . '_00.png';
            $raids[] = $row;
        }
        return $raids;
    } else {
        return '<tr><td colspan="6" class="text-center"> No Raids At This Time</td></tr>';
    }
}
