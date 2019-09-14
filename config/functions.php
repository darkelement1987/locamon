<?php
function monMad() {
  require('config.php');
  $result = $conn->query("SELECT * FROM pokemon");
}

function index() {
}

?>