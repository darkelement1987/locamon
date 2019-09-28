<?php
$mons = getDitto();
global $clock;
?>

<table id="mon_table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>ID:</th>
            <th>Pokemon:</th>
            <th>IV:</th>
            <th>CP:</th>
            <th>Level:</th>
            <th>Gender:</th>
            <th>Form:</th>
            <th>Att:</th>
            <th>Def:</th>
            <th>Sta:</th>
            <th>Catch Rate:</th>
            <th>Disappears:</th>
            <th>Scanned:</th>
            <th>Google Maps:</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($mons)) {
            foreach ($mons as $row) {
                ?>
                <tr>
                    <td><?= $row->id ?></td>
                    <td><img height='42' width='42' src='<?= $row->sprite ?>'/> <?= $row->name ?></td>
                    <td><?= $row->iv ?></td>
                    <td><?= $row->cp ?></td>
                    <td><?= $row->level ?></td>
                    <td><?= $row->gender ?></td>
                    <td><?= $row->form ?></td>
                    <td><?= $row->individual_attack ?></td>
                    <td><?= $row->individual_defense ?></td>
                    <td><?= $row->individual_stamina ?></td>
                    <td><?= $row->catch_prob_1 ?><?= $row->catch_prob_2 ?><?= $row->catch_prob_3 ?></td>
                    <td><?= date($clock, $row->disappear_time) ?></td>
                    <td><?= date($clock, $row->last_modified) ?></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->latitude ?>,<?= $row->longitude ?>'>MAP</a></td>
                </tr> <?php }
                        } else {
                            echo $mons;
                        } ?> </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#mon_table').DataTable({
            order: [
                [12, "desc"]
                ],

            columnDefs: [
            { type: 'time-uni', targets: 11 },
            { type: 'time-uni', targets: 12 },
            ],

            paging: true,
            lengthChange: false,
            searching: true,
            responsive: true,
            
            language: {
                "search":         "Search:",
                "info":           "Showing _START_ to _END_ of _TOTAL_ Raids",
                "infoEmpty":      "Showing 0 to 0 of 0 Raids",
                "infoFiltered":   "(filtered from _MAX_ total Raids)",
                "emptyTable":     "No Ditto available",
                "zeroRecords":    "No matching Raids found",
                searchPlaceholder: "Enter name/id"
                }
            
        });
    });
</script>
