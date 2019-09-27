<?php
function getMons()
{
    global $conn;
    global $assetRepo;
    global $monsters;
    $mons = [];
    $mon_name = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    if (empty($monsters)){
        $sql = "SELECT catch_prob_1, catch_prob_2, catch_prob_3, cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE disappear_time > utc_timestamp();";
    } else {
        $sql = "SELECT cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE pokemon_id in (" . $monsters . ") AND disappear_time > utc_timestamp();";
        }
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            // Check if mon has stats
            if ($row->individual_attack !== null && $row->individual_defense !== null &&  $row->individual_stamina !== null) {
                $row->iv = round((($row->individual_attack + $row->individual_defense + $row->individual_stamina) / 45) * 100, 2) . '%';
                $row->catch_prob_1 = '<img height=\'42\' width=\'42\' src=\'images/poke.png\'>' . round(($row->catch_prob_1) * 100,1) . '% / ';
                $row->catch_prob_2 = '<img height=\'42\' width=\'42\' src=\'images/great.png\'>' . round(($row->catch_prob_2) * 100,1) . '% / ';
                $row->catch_prob_3 = '<img height=\'42\' width=\'42\' src=\'images/ultra.png\'>' . round(($row->catch_prob_3) * 100,1) . '%';
            // If no stats show -
            } else {
                $row->iv = '-';
                $row->cp = '-';
                $row->catch_prob_1 = '-';
                $row->catch_prob_2 = '';
                $row->catch_prob_3 = '';
            }
            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
            $row->name = $mon_name[$row->pokemon_id]['name'];
            $mons[] = $row;
        }
        return $mons;
    } else {
        return '<tr><td colspan="6" class="text-center"> No Pokemon At This Time</td></tr>';
    }
}

function getDitto()
{
    global $conn;
    global $assetRepo;
    $mons = [];
    $mon_name = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    $sql = "SELECT cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE pokemon_id in (132) AND disappear_time > utc_timestamp();";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            // Check if mon has stats
            if ($row->individual_attack !== null && $row->individual_defense !== null &&  $row->individual_stamina !== null) {
                $row->iv = round((($row->individual_attack + $row->individual_defense + $row->individual_stamina) / 45) * 100, 2) . '%';
                $row->catch_prob_1 = '<img height=\'42\' width=\'42\' src=\'images/poke.png\'>' . round(($row->catch_prob_1) * 100,1) . '% / ';
                $row->catch_prob_2 = '<img height=\'42\' width=\'42\' src=\'images/great.png\'>' . round(($row->catch_prob_2) * 100,1) . '% / ';
                $row->catch_prob_3 = '<img height=\'42\' width=\'42\' src=\'images/ultra.png\'>' . round(($row->catch_prob_3) * 100,1) . '%';
            // If no stats show -
            } else {
                $row->iv = '-';
                $row->cp = '-';
                $row->catch_prob_1 = '-';
                $row->catch_prob_2 = '';
                $row->catch_prob_3 = '';
            }
            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
            $row->name = $mon_name[$row->pokemon_id]['name'];
            $mons[] = $row;
        }
        return $mons;
    } else {
        return '<tr><td colspan="6" class="text-center"> No Ditto At This Time</td></tr>';
    }
}

function getRaids()
{
    global $conn;
    global $assetRepo;
    global $clock;
    $raids = [];
    $mon_name = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    $raid_move_1 = json_decode(file_get_contents(DIRECTORY . '/json/moves.json'), true);
    $raid_move_2 = json_decode(file_get_contents(DIRECTORY . '/json/moves.json'), true);
    $sql = "SELECT UNIX_TIMESTAMP(CONVERT_TZ(a.start, '+00:00', @@global.time_zone)) as start, UNIX_TIMESTAMP(CONVERT_TZ(a.end, '+00:00', @@global.time_zone)) as end, UNIX_TIMESTAMP(CONVERT_TZ(a.spawn, '+00:00', @@global.time_zone)) as spawn, a.pokemon_id, a.move_1, a.move_2, UNIX_TIMESTAMP(CONVERT_TZ(a.last_scanned, '+00:00', @@global.time_zone)) as last_scanned, b.name, a.level, a.cp, c.latitude, c.longitude FROM raid a INNER JOIN gymdetails b INNER JOIN gym c ON a.gym_id = b.gym_id AND a.gym_id = c.gym_id  AND a.end > UTC_TIMESTAMP() ORDER BY a.end ASC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            $row->time_start = date($clock, $row->start);
            $row->time_end = date($clock, $row->end);
            $row->raid_scan_time = date($clock, $row->last_scanned);
            // If no mon id is scanned then its considered an egg
            if (empty($row->pokemon_id)){
                $row->sprite = 'images/egg_' . $row->level . '.png';
                $row->bossname = 'Egg not hatched';
                $row->move_1 = '';
                $row->move_2 = '';    
            // Else it's a raid :-)
            } else {
                $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
                $row->bossname = $mon_name[$row->pokemon_id]['name'];
                $row->move_1 = $raid_move_1[$row->move_1]['name'] . ' & ';
                $row->move_2 = $raid_move_2[$row->move_2]['name'];            
            }
            $raids[] = $row;
        }
        return $raids;
    } else {
        return '<tr><td colspan="6" class="text-center"> No Raids At This Time</td></tr>';
    }
}
