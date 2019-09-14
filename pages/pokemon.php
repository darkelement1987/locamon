<?php
include '../config/functions.php';
?>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.js"></script>
<script>$(document).ready( function () {
    $('#mon_table').DataTable({
        "order": [[ 3, "desc" ]]
    });
} );</script>
</head>
<h3>Locamon <b>POKEMON</b></h3>
<br>
<br>
<table id="mon_table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Pokemon</th>
            <th>lat, lon</th>
            <th>iv</th>
            <th>cp</th>
            <th>disappear_time</th>
            <th>encounter_id</th>
        </tr>
    </thead>
    <tbody>
            <?php
                monMad();
            ?>
    </tbody>
</table>
