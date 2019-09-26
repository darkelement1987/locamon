<?php
$raids = getRaids();
?>


<table id="raid_table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Raid Boss</th>
            <th>Gym Name</th>
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
                    <td><img height='42' width='42' src='<?= $row->sprite ?>'/> <?= $row->bossname ?></td>
                    <td><a href='https://www.google.com/maps?q=<?= $row->latitude?>,<?= $row->longitude ?>'><?= $row->name ?></a></td>
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
                [5, "desc"]
				],

            columnDefs: [
            { type: 'time-uni', targets: 4 },
			{ type: 'time-uni', targets: 5 }
            ],

            paging: true,
            lengthChange: true,
            searching: true,
        });
    });
</script>
</body>

</html>
