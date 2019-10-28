<?php
require_once __DIR__ . '/config/config.php';
define('DIRECTORY', __Dir__);
if (isset($_GET) && !empty($_GET)) {
    $get = new stdClass();
    foreach ($_GET as $k => $v) {
        $get->$k = htmlspecialchars($v);
    }
}
if (isset($_POST) && !empty($_POST)) {
    $post = new stdClass();
    foreach ($_POST as $k => $v) {
        $post->$k = htmlspecialchars($v);
    }
}
// Create connection
$conn = new mysqli($servername, $username, $password, $database);
$conn->set_charset('utf8');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

switch ($type) {
    case 'rdm':
        require_once __DIR__ . '/functions/functions_rdm.php';
        break;
    default:
        require_once __DIR__ . '/functions/functions_mad.php';
        break;
}

switch ($clock) {
    case '24':
        $clock = 'H:i:s';
        break;
    case '12':
        $clock = 'g:i:s';
        break;
    default:
        $clock = 'g:i:s';
        break;
}

function index()
{
    global $get;
    if (isset($get->page) && !empty($get->page)) {
        $page = $get->page;
        if (file_exists(DIRECTORY . '/pages/' . $page . '.php')) {
            require_once(DIRECTORY  . '/pages/' . $page . '.php');
        } else if (file_exists(DIRECTORY . '/pages/' . $page . '.html')) {
            require_once(DIRECTORY  . '/pages/' . $page . '.html');
        } else {
            echo "Does not exist";
        }
    }
}
function js()
{
    global $get;
    if (isset($get->page) && !empty($get->page)) {
        $page = $get->page;
        if (file_exists(DIRECTORY . '/js/' . $page . '.js')) {
            require_once(DIRECTORY  . '/js/' . $page . '.js');
        }
    }
}
