<?php
$raids = getRaids();
?>


<table id="raid_table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Raid Boss</th>
			<th>Boss Name</th>
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
                    <td>
                        <img height='42' width='42' src='<?= $row->sprite ?>' />
                    </td>
					<td><?= $row->bossname ?></td>
                    <td><?= $row->name ?></td>
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
        let raidTable = $('#raid_table').DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true
        });

    });
</script>
</body>

</html>
