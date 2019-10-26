<?php
function getMons($u = null)
{
    global $conn;
    global $assetRepo;
    global $mapkey;
    global $clock;

    $mons = [];
    $pokedex = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'));
    $key = json_decode(file_get_contents(DIRECTORY . '/json/forms.json'));
    $moves = json_decode(file_get_contents(DIRECTORY . '/json/moves.json'));
    $sql = <<<SQL
    SELECT
        pokemon.encounter_id as id,
        pokemon.latitude as lat, 
        pokemon.longitude as lon,
        pokemon.height,
        pokemon.weight,
        UNIX_TIMESTAMP(CONVERT_TZ(pokemon.disappear_time, '+00:00', @@global.time_zone)) as expires,
        UNIX_TIMESTAMP(CONVERT_TZ(pokemon.last_modified, '+00:00', @@global.time_zone)) as updated, 
        pokemon.individual_attack as atk_iv, 
        pokemon.individual_defense as def_iv, 
        pokemon.individual_stamina as sta_iv, 
        pokemon.pokemon_id,
        pokemon.move_1,
        pokemon.move_2,
        pokemon.gender,
        pokemon.cp,
        pokemon.cp_multiplier,
        pokemon.form,
        pokemon.costume,
        pokemon.catch_prob_1,
        pokemon.catch_prob_2,
        pokemon.catch_prob_3,
        pokemon.weather_boosted_condition
    FROM pokemon 
    WHERE pokemon.disappear_time > utc_timestamp()
SQL;
    if (!empty($u)) {
        $sql = $sql . ' AND pokemon.last_modified > DATE_SUB(UTC_TIMESTAMP(), INTERVAL ' . $u . ' SECOND)';
    }
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            if ($row->atk_iv !== null && $row->def_iv !== null &&  $row->sta_iv !== null) {
                $row->iv = round((($row->atk_iv + $row->def_iv + $row->sta_iv) / 45) * 100, 2);
            } else {
                $row->iv = '';
                $row->cp = '';
            }
            if (empty($row->cp_multiplier)) {
                $row->level = '';
            } else {
                if ($row->cp_multiplier < 0.73) {
                    $level = 58.35178527 * $row->cp_multiplier * $row->cp_multiplier - 2.838007664 * $row->cp_multiplier + 0.8539209906;
                } elseif ($row->cp_multiplier > 0.73) {
                    $level = 171.0112688 * $row->cp_multiplier - 95.20425243;
                }
                $row->level = (round($level) * 2) / 2;
            }
            if (!empty($row->move_1) && !empty($row->move_2)) {
                $row->move_1 = $moves->{$row->move_1};
                $row->move_2 = $moves->{$row->move_2};
            }
            $row->disappear_time = date($clock, $row->expires);
            $row->last_modified = date($clock, $row->updated);
            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, '0', STR_PAD_LEFT) . '_' . str_pad($row->form, 2, '0', STR_PAD_LEFT) . '.png';
            $row->name = $pokedex->{$row->pokemon_id}->name;
            $row->types = $pokedex->{$row->pokemon_id}->types;
            if (!empty($row->form) || !$row->form === '0') {
                $row->form = $key->forms->{$row->form};
            }
            $row->static_map = '';
            if ($mapkey !== '') {
                $row->static_map = 'https://open.mapquestapi.com/staticmap/v5/map?size=300,200&zoom=15&locations=' . $row->lat . ',' . $row->lon . '|https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $row->pokemon_id . '.png&key=' . $mapkey;
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
    $moves = json_decode(file_get_contents(DIRECTORY . '/json/moves.json'));
    $forms = json_decode(file_get_contents(DIRECTORY . '/json/forms.json'));
    $sql = <<<SQL
        SELECT 
            c.gym_id as id,
            c.latitude as lat, 
            c.longitude as lon,
            b.name as gym_name,
            b.url as image,
            UNIX_TIMESTAMP(CONVERT_TZ(a.end, '+00:00', @@global.time_zone)) as end, 
            UNIX_TIMESTAMP(CONVERT_TZ(a.start, '+00:00', @@global.time_zone)) as start,
            UNIX_TIMESTAMP(CONVERT_TZ(a.spawn, '+00:00', @@global.time_zone)) as spawn, 
            a.pokemon_id, 
            a.move_1, 
            a.move_2, 
            UNIX_TIMESTAMP(CONVERT_TZ(a.last_scanned, '+00:00', @@global.time_zone))          as last_scanned, 
            a.form, 
            c.team_id, 
            c.slots_available, 
            a.level, 
            c.is_ex_raid_eligible as ex,
            a.cp, 
            b.description
        FROM raid a INNER JOIN gymdetails b INNER JOIN gym c 
        ON a.gym_id = b.gym_id AND a.gym_id = c.gym_id
        WHERE a.end > utc_timestamp()
SQL;
    if (!empty($u)) {
        $sql = $sql . 'AND a.last_scanned > DATE_SUB(UTC_TIMESTAMP(), INTERVAL ' . $u . ' SECOND)';
    }
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {

            if (!empty($row->move_1) && !empty($row->move_2)) {
                $row->move_1 = $moves->{$row->move_1};
                $row->move_2 = $moves->{$row->move_2};
            }
            $row->time_start = date($clock, $row->start);
            $row->time_spawn = date($clock, $row->spawn);
            $row->time_end = date($clock, $row->end);
            $row->raid_scan_time = date($clock, $row->last_scanned);
            $row->stars = str_repeat('★', $row->level);

            if ($row->gym_name === null) {
                $row->gym_name = 'Link';
            }
            if (!empty($row->pokemon_id)) {
                $id = $row->pokemon_id;
                $row->types = $pokedex->$id->types;
                $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, '0', STR_PAD_LEFT) . '_' . str_pad($row->form, 2, '0', STR_PAD_LEFT) . '.png';
                $row->name = $pokedex->{$row->pokemon_id}->name;
            } else {
                $id = '0';
                $row->sprite = 'images/egg_' . $row->level . '.png';
                $row->name = 'Egg';
            }
            if (!empty($row->form)) {
                $row->form = $forms->forms->{$row->form};
            }
            $row->static_map = '';
            if ($mapkey !== '') {
                $row->static_map = 'https://open.mapquestapi.com/staticmap/v5/map?size=400,200&zoom=16&locations=' . $row->lat . ',' . $row->lon . '|https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/' . $id . '.png&key=' . $mapkey;
            }
            $raids[] = $row;
        }
    }
    return $raids;
}
