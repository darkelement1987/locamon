<?php
$mons = getMons();
global $clock;
?>

<table id="mon_table" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
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
                if (empty($row->cp_multiplier)){
                    $level='-';
                    } else {
                if ($row->cp_multiplier < 0.73) {
                    $level = 58.35178527 * $row->cp_multiplier * $row->cp_multiplier - 2.838007664 * $row->cp_multiplier + 0.8539209906;
                    } elseif ($row->cp_multiplier > 0.73) {
                        $level = 171.0112688 * $row->cp_multiplier - 95.20425243;
                        }
                $level = (round($level)*2)/2;
                }
                        ?>
                <tr>
                    <td><img height='42' width='42' src='<?= $row->sprite ?>'/> <?= $row->name ?></td>
                    <td><?= $row->iv ?></td>
                    <td><?= $row->cp ?></td>
                    <td><?= $level ?></td>
                    <td><?= $row->gender ?></td>
                    <td><?= $row->form ?></td>
                    <td><?= $row->individual_attack ?></td>
                    <td><?= $row->individual_defense ?></td>
                    <td><?= $row->individual_stamina ?></td>
                    <td><?= $row->catch_prob_1 ?><?= $row->catch_prob_2 ?><?= $row->catch_prob_3 ?></td>
                    <td><?= date($clock, $row->disappear_time) ?></td>
                    <td><?= date($clock, $row->last_modified) ?></td>
                    <td>
                        <a href='https://maps.google.com/?q=<?= $row->latitude ?>,<?= $row->longitude ?>'>MAP </a> </td>
                </tr> <?php }
                        } else {
                            echo $mons;
                        } ?> </tbody>
</table>
<script>
    $(document).ready(function() {
        $('#mon_table').DataTable({
            order: [
                [11, "desc"]
                ],

            columnDefs: [
            { type: 'time-uni', targets: 10 },
            { type: 'time-uni', targets: 11 }
            ],

            paging: true,
            lengthChange: true,
            searching: true,
            responsive: true,
        });
    });
</script>