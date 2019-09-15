<?php
include '../config/functions.php';
require '../config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.js"></script>
<script>$(document).ready( function () {
    $('#quest_table').DataTable();
} );</script>
</head>
<body>
<h3><?php echo $title;?> <b>QUESTS</b></h3>
</body>
</html>