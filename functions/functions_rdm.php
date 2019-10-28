<?php
function getMons($u = null)
{
    global $conn;
    global $assetRepo;
    global $mapkey;
    global $clock;

    $mons = [];
    $pokedex = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    $key = json_decode(file_get_contents(DIRECTORY . '/json/forms.json'), true);
    $moves = json_decode(file_get_contents(DIRECTORY . '/json/moves.json'), true);
    $sql = <<<SQL
        SELECT id, 
               lat, 
               lon, 
               size as height,
               weight,
               expire_timestamp as expires, 
               updated, 
               atk_iv, 
               def_iv, 
               sta_iv,
               pokemon_id, 
               move_1, 
               move_2, 
               gender, 
               cp, 
               form, 
               level, 
               costume, 
               iv
            FROM pokemon 
            WHERE expire_timestamp > unix_timestamp(now())
SQL;
    if (!empty($u)) {
        $sql = $sql . ' AND updated > DATE_SUB(UNIX_TIMESTAMP(), INTERVAL ' . $u . ' SECOND)';
    }
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {

            $row->disappear_time = date($clock, $row->expires);
            $row->last_modified = date($clock, $row->updated);
            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, '0', STR_PAD_LEFT) . '_' . str_pad($row->form, 2, '0', STR_PAD_LEFT) . '.png';
            $row->name = $pokedex[$row->pokemon_id]['name'];
            $row->types = $pokedex[$row->pokemon_id]['types'];
            if (empty($row->iv)) {
                $row->iv = '';
            }
            if (!empty($row->move_1) && !empty($row->move_2)) {
                $row->move_1 = $moves[$row->move_1];
                $row->move_2 = $moves[$row->move_2];
            }
            $row->static_map = '';

            if ($mapkey !== '') {
                $row->static_map = 'https://open.mapquestapi.com/staticmap/v5/map?size=200,200&zoom=15&locations=' . $row->lat . ',' . $row->lon . '|https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $row->pokemon_id . '.png&key=' . $mapkey;
            }
            if (!empty($row->form) || $row->form !== "0") {
                $row->form = $key['forms'][$row->form];
            }
            $mons[] = $row;
        }
    }
    return $mons;
}


function getRaids($u = null)
{
    global $conn;
    global $assetRepo;
    global $mapkey;
    global $clock;

    $raids = [];
    $pokedex = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'));
    $key = json_decode(file_get_contents(DIRECTORY . '/json/forms.json'));
    $moves = json_decode(file_get_contents(DIRECTORY . '/json/moves.json'));
    $sql = <<<SQL
        SELECT
            id,
            lat,
            lon,
            name                  AS gym_name,
            url                   AS image,
            raid_end_timestamp    AS end,
            raid_battle_timestamp AS start,
            raid_spawn_timestamp  AS spawn,
            raid_pokemon_id       AS pokemon_id,
            raid_pokemon_move_1   AS move_1,
            raid_pokemon_move_2   AS move_2,
            last_modified_timestamp AS last_scanned,
            raid_pokemon_form     AS form,
            team_id,
            availble_slots        AS slots_available,
            raid_level            AS level,
            ex_raid_eligible      AS ex,
            raid_pokemon_cp       AS cp
        FROM   gym         
        WHERE 
            raid_end_timestamp > UNIX_TIMESTAMP()
            AND raid_pokemon_id IS NOT NULL 
SQL;
    if (!empty($u)) {
        $sql = $sql . ' AND last_modified_timestamp > DATE_SUB(UNIX_TIMESTAMP(NOW()), INTERVAL ' . $u . ' SECOND)';
    }
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            $row->time_start = date($clock, $row->start);
            $row->time_spawn = date($clock, $row->spawn);
            $row->time_end = date($clock, $row->end);
            $row->raid_scan_time = date($clock, $row->last_scanned);
            $row->stars = str_repeat('★', $row->level);
            if ($row->gym_name === null) {
                $row->gym_name = 'Link';
            }
            if ($row->pokemon_id !== '0') {
                $row->name = $pokedex->{$row->pokemon_id}->name;
                $row->types = $pokedex->{$row->pokemon_id}->types;
                $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, '0', STR_PAD_LEFT) . '_00.png';
            } else {
                $row->name = 'Egg';
                $row->sprite = 'images/egg_' . $row->level . '.png';
            }
            if (!empty($row->move_1) && !empty($row->move_2)) {
                $row->move_1 = $moves->{$row->move_1};
                $row->move_2 = $moves->{$row->move_2};
            }
            $row->static_map = '';
            if ($mapkey !== '') {
                $row->static_map = 'https://open.mapquestapi.com/staticmap/v5/map?size=400,200&zoom=15&locations=' . $row->lat . ',' . $row->lon . '|https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $row->pokemon_id . '.png&key=' . $mapkey;
            }
            if (!empty($row->form)) {
                $row->form = $key->forms->{$row->form};
            }
            $raids[] = $row;
        }
    }
    return $raids;
}
