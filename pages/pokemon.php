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
    $('#mon_table').DataTable({
        "order": [[ 4, "desc" ]]
    });
} );</script>
</head>
<body>
<h3><?php echo $title;?> <b>POKEMON</b></h3>
<br>
<br>
<table id="mon_table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Pokemon:</th>
            <th>IV:</th>
            <th>CP:</th>
            <th>Disappears:</th>
			<th>Scanned:</th>
            <th>Google Maps:</th>
        </tr>
    </thead>
    <tbody>
            <?php
                getMons();
            ?>
    </tbody>
</table>
</body>
</html>