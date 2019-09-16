<?php
require '../config/config.php';
switch ($type) {
    case "rdm":
        include '../config/functions_rdm.php';
		break;
    case "mad":
        include '../config/functions_mad.php';
		break;
	default:
	include '../config/functions_mad.php';
    }
        ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.js"></script>
<script>$(document).ready( function () {
    $('#raid_table').DataTable();
} );</script>
</head>
<body>
<h3><?php echo $title;?> <b>RAIDS</b></h3>
<br>
<br>
<table id="raid_table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Raid Boss</th>
            <th>Gym Name</th>
            <th>cp</th>
            <th>Level</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
            <?php
                getRaids();
            ?>
    </tbody>
</table>
</body>
</html>