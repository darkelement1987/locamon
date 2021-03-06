<?php
$raids = getRaids();
?>


<table id="raid_table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Raid Boss</th>
            <th>Gym Name</th>
            <th>Moves</th>			
            <th>CP</th>
            <th>Level</th>
            <th>Time</th>
            <th>Scanned</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($raids)) {
            foreach ($raids as $row) { ?>
                <tr>
                    <td><?= $row->id ?></td>
                    <td><img height='42' width='42' src='<?= $row->sprite ?>'/> <?= $row->bossname ?></td>
                    <td><a href='https://www.google.com/maps?q=<?= $row->latitude?>,<?= $row->longitude ?>'><?= $row->name ?></a></td>
                    <td><?= $row->move_1 . $row->move_2 ?></td>
                    <td><?= $row->cp ?></td>
                    <td><?= str_repeat('★', $row->level) ?></td>
                    <td> <?= $row->time_start ?> - <?= $row->time_end ?></td>
                    <td><?= $row->raid_scan_time ?></td>
                </tr>
        <?php }
        } else {
            echo $raids;
        }
        ?>
    </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#raid_table').DataTable({
            order: [
                [6, "desc"]
                ],

            columnDefs: [
            { type: 'time-uni', targets: 5 },
            { type: 'time-uni', targets: 6 }
            ],

            paging: true,
            lengthChange: true,
            searching: true,
            responsive: true,
            
            language: {
                "search":         "Search:",
                "info":           "Showing _START_ to _END_ of _TOTAL_ Raids",
                "infoEmpty":      "Showing 0 to 0 of 0 Raids",
                "infoFiltered":   "(filtered from _MAX_ total Raids)",
                "emptyTable":     "No Raids available in table",
                "zeroRecords":    "No matching Raids found",
                searchPlaceholder: "Enter name/id"
                }
            
        });
    });
</script>
</body>

</html>