<?php
require_once(__DIR__ . '/../includes.php');
$type = $get->type;
if (!empty($get->update)) {
    $api_update = '1';
} else {
    $api_update = null;
}
switch ($type) {
    case 'raids':
        $raids = new STDClass();
        $raids->data = getRaids($api_update);
        $output = json_encode($raids, JSON_UNESCAPED_SLASHES);
        break;
    case 'pokemon':
        $mon = new STDClass();
        $mon->data = getMons($api_update);
        $output = json_encode($mon, JSON_UNESCAPED_SLASHES);
        break;
    default:
        $output = null;
        break;
}

echo $output;
