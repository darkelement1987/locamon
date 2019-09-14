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

$useImages = "true";
$assetRepo = "https://raw.githubusercontent.com/ZeChrales/PogoAssets/"; // default = ZeChrales/PogoAssets, use raw link

// !!! Don't touch stuff below !!!

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
