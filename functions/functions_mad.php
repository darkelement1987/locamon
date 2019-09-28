<?php
function getMons()
{
    global $conn;
    global $assetRepo;
    global $monsters;
    $mons = [];
    $mon_name = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    if (empty($monsters)){
        $sql = "SELECT form, gender, catch_prob_1, catch_prob_2, catch_prob_3, cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE disappear_time > utc_timestamp();";
    } else {
        $sql = "SELECT form, gender, catch_prob_1, catch_prob_2, catch_prob_3, cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE pokemon_id in (" . $monsters . ") AND disappear_time > utc_timestamp();";
        }
    $result = $conn->query($sql);

    // Check if mon available
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {

            // Pull Mon ID
            $row->id = '#' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT);

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
                $row->individual_attack = '-';
                $row->individual_defense = '-';
                $row->individual_stamina = '-';
            }

            // Detect Gender
            switch ($row->gender) {
                case '0':
                    $row->gender = 'Not Set';
                    break;
                case '1':
                    $row->gender = 'Male';
                    break;
                case '2':
                    $row->gender = 'Female';
                    break;
                case '3':
                    $row->gender = 'Genderless';
                    break;
                default:
                    $row->gender = '-';
                    break;
            }

            // Detect Level
            if (empty($row->cp_multiplier)){
                $row->level='-';
                } else {
                    if ($row->cp_multiplier < 0.73) {
                        $row->level = 58.35178527 * $row->cp_multiplier * $row->cp_multiplier - 2.838007664 * $row->cp_multiplier + 0.8539209906;
                        } elseif ($row->cp_multiplier > 0.73) {
                            $row->level = 171.0112688 * $row->cp_multiplier - 95.20425243;
                            }
                            $row->level = (round($row->level)*2)/2;
                            }

            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
            $row->name = $mon_name[$row->pokemon_id]['name'];

            // Detect Form
            if (empty($row->form)){
                $row->form='-';
                } else {
                    $row->form = $mon_name[$row->pokemon_id]['forms'][$row->form]['formName'];
                    }

            $mons[] = $row;
        }
        return $mons;
    }
}

function getDitto()
{
    global $conn;
    global $assetRepo;
    $mons = [];
    $mon_name = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    $sql = "SELECT form, gender, catch_prob_1, catch_prob_2, catch_prob_3, cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE pokemon_id in (132) AND disappear_time > utc_timestamp();";
    $result = $conn->query($sql);

    // Check if mon available
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {

            // Pull Mon ID
            $row->id = '#' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT);

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
                $row->individual_attack = '-';
                $row->individual_defense = '-';
                $row->individual_stamina = '-';
            }

            // Detect Gender
            switch ($row->gender) {
                case '0':
                    $row->gender = '-';
                    break;
                case '1':
                    $row->gender = 'Male';
                    break;
                case '2':
                    $row->gender = 'Female';
                    break;
                case '3':
                    $row->gender = '-';
                    break;
                default:
                    $row->gender = '-';
                    break;
            }

            // Detect Level
            if (empty($row->cp_multiplier)){
                $row->level='-';
                } else {
                    if ($row->cp_multiplier < 0.73) {
                        $row->level = 58.35178527 * $row->cp_multiplier * $row->cp_multiplier - 2.838007664 * $row->cp_multiplier + 0.8539209906;
                        } elseif ($row->cp_multiplier > 0.73) {
                            $row->level = 171.0112688 * $row->cp_multiplier - 95.20425243;
                            }
                            $row->level = (round($row->level)*2)/2;
                            }

            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
            $row->name = $mon_name[$row->pokemon_id]['name'];

            // Detect Form
            if (empty($row->form)){
                $row->form='-';
                } else {
                    $row->form = $mon_name[$row->pokemon_id]['forms'][$row->form]['formName'];
                    }

            $mons[] = $row;
        }
        return $mons;
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
                $row->move_1 = '-';
                $row->move_2 = '';
                $row->cp = '-';
                $row->id = '#???';
            // Else it's a raid :-)
            } else {
                $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
                $row->bossname = $mon_name[$row->pokemon_id]['name'];
                if(empty($row->move_1)){$row->move_1='Unknown &';} else {$row->move_1 = $raid_move_1[$row->move_1]['name'] . ' & ';}
                if(empty($row->move_2)){$row->move_2='Unknown';} else {$row->move_2 = $raid_move_2[$row->move_2]['name'];}
                $row->id = '#' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT);
            }
            $raids[] = $row;
        }
        return $raids;
    }
}

function getShiny()
{
    global $conn;
    global $assetRepo;
    global $monsters;
    $mons = [];
    $mon_name = json_decode(file_get_contents(DIRECTORY . '/json/pokedex.json'), true);
    $sql = "SELECT form, gender, catch_prob_1, catch_prob_2, catch_prob_3, cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE pokemon_id in (" . getShinyList() . ") AND disappear_time > utc_timestamp();";
    $result = $conn->query($sql);

    // Check if mon available
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {

            // Pull Mon ID
            $row->id = '#' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT);

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
                $row->individual_attack = '-';
                $row->individual_defense = '-';
                $row->individual_stamina = '-';
            }

            // Detect Gender
            switch ($row->gender) {
                case '0':
                    $row->gender = 'Not Set';
                    break;
                case '1':
                    $row->gender = 'Male';
                    break;
                case '2':
                    $row->gender = 'Female';
                    break;
                case '3':
                    $row->gender = 'Genderless';
                    break;
                default:
                    $row->gender = '-';
                    break;
            }

            // Detect Level
            if (empty($row->cp_multiplier)){
                $row->level='-';
                } else {
                    if ($row->cp_multiplier < 0.73) {
                        $row->level = 58.35178527 * $row->cp_multiplier * $row->cp_multiplier - 2.838007664 * $row->cp_multiplier + 0.8539209906;
                        } elseif ($row->cp_multiplier > 0.73) {
                            $row->level = 171.0112688 * $row->cp_multiplier - 95.20425243;
                            }
                            $row->level = (round($row->level)*2)/2;
                            }

            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png';
            $row->name = $mon_name[$row->pokemon_id]['name'];

            // Detect Form
            if (empty($row->form)){
                $row->form='-';
                } else {
                    $row->form = $mon_name[$row->pokemon_id]['forms'][$row->form]['formName'];
                    }

            $mons[] = $row;
        }
        return $mons;
    }
}

function getShinyList()
{
    $shiny_ids = "001,002,003,004,005,006,007,008,009,010,011,012,016,017,018,019,020,023,024,025,026,027,028,029,030,031,032,033,034,035,036,037,038,039,040,041,042,043,044,045,050,051,052,053,054,055,056,057,058,059,060,061,062,063,064,065,066,067,068,074,075,076,077,078,081,082,83,086,087,088,089,090,091,092,093,094,095,096,097,098,099,103,104,105,109,110,115,116,117,122,123,124,125,126,127,128,129,130,131,133,134,135,136,138,139,140,141,142,144,145,146,147,148,149,150,152,153,154,155,156,157,158,159,160,161,162,169,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,190,191,192,193,196,197,198,200,202,204,205,207,208,209,210,212,213,215,220,221,225,228,229,230,238,239,240,244,244,245,246,247,248,249,250,252,253,254,255,256,257,258,259,260,261,262,263,264,270,271,272,276,277,278,279,280,281,282,287,288,289,296,297,298,302,303,304,305,306,307,308,309,310,311,312,315,318,319,320,321,325,326,327,328,329,330,333,334,335,336,337,338,339,340,345,346,347,348,349,350,351,353,354,355,356,359,360,361,362,366,367,368,370,371,372,373,374,375,376,380,381,382,383,384,387,388,389,403,404,405,406,407,424,425,426,427,428,429,430,436,437,438,439,461,462,466,467,468,469,470,471,472,473,475,477,478,487,488,504,505,506,507,508,599,600,601,808,809";
    return $shiny_ids;
}