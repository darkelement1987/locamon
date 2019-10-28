<?php
ini_set("allow_url_fopen", 1);
$locale = 'en'; // ['en', 'de', 'it']
$max_pokemon = 649;

$rdm = ''; //link to hosted rdm map

// dont touch after this
function processor()
{
    global $rdm;
    global $locale;
    $json_structure = <<<JSON
    {
        "keys":{
            "alignments":{},
            "types":{},
            "forms":{}            
        },
        "quests":{
            "rewards":{},
            "quests":{},
            "conditions":{},
            "throws":{}
        },
        "items":{
            "item":{},
            "lures":{}
        },
        "invasions":{
            "grunts":{},
            "characters":{}
        }
    }
JSON;

    $base = json_decode($json_structure);
    $link = $rdm . '/static/data/' . $locale . '.json';
    $json = json_decode(file_get_contents($link));
    foreach ($json->values as $k => $v) {
        $split = explode('_', $k);
        $type = $split[0];
        $last = count($split) - 1;
        if ($split[$last] === 'formatted') {
            $last = $last - 1;
        }
        $id = $split[$last];
        $cat = '';
        switch ($type) {
            case 'poke':
                if ($split[1] === 'type') {
                    $base->keys->types->$id = new stdClass();
                    $base->keys->types->$id->name = $v;
                }

                break;
            case 'move':
                break;
            case 'form':
                $base->keys->forms->$id = $v;
                break;
            case 'quest':
                if ($split[1] === 'reward') {
                    $base->quests->rewards->$id = $v;
                } else if ($split[1] === 'condition') {
                    $base->quests->conditions->$id = $v;
                } else if (count($split) === 2) {
                    $base->quests->quests->$id = $v;
                }
                break;
            case 'alignment':
                $base->keys->alignments->$id = $v;
                break;
            case 'character':
                $base->invasions->characters->$id = $v;
                break;
            case 'throw':
                $base->quests->throws->$id = $v;
                break;
            case 'item':
                $base->items->item->$id = $v;
                break;
            case 'lure':
                $base->items->lures->$id = $v;
                break;
            case 'grunt':
                $base->invasions->grunts->$id = $v;
                break;
            default:
                break;
        }
    }
    return $base;
}

$moves = file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/moves.json');
$moves = json_decode($moves);
$move = new stdClass();
foreach ($moves as $k => $v) {
    $move->$k = $v->name;
}

$pokedex = file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json');
$pokedex = json_decode($pokedex);
$pokemon = new stdClass();
foreach ($pokedex as $k => $v) {
    if ($k > $max_pokemon) { } else {
        $pokemon->$k = new stdClass();
        $pokemon->$k->name = $v->name;
        $pokemon->$k->types = $v->types;
    }
}

$base = processor();


file_put_contents('./../json/pokedex.json', json_encode($pokemon));
file_put_contents('./../json/moves.json', json_encode($move));
file_put_contents('./../json/items.json', json_encode($base->items));
file_put_contents('./../json/quests.json', json_encode($base->quests));
file_put_contents('./../json/invasions.json', json_encode($base->invasions));
file_put_contents('./../json/forms.json', json_encode($base->keys));
