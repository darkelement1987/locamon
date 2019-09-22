<?php

// Connect to Database
$servername = "";
$username = "";
$password = "";
$database = "";
$type = "mad"; // 'mad' or 'rdm', default = mad

// Frontend

$title = "Locamon";

// Images

// !!! Don't touch stuff below !!!

$assetRepo = "https://raw.githubusercontent.com/geekygreek7/pokedave_shuffle_icons_-PMSF-/master/"; // default = ZeChrales/PogoAssets, use raw link

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

