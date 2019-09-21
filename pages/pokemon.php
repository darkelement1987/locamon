<?php
$mons = getMons();
?>

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
        <?php if (is_array($mons)) {
            foreach ($mons as $row) { ?>
                <tr>
                    <td>
                        <img height='42' width='42' src='<?= $row->sprite ?>' />
                        <?= $row->name ?>
                    </td>
                    <td><?= $row->iv ?>%</td>
                    <td><?= $row->cp ?></td>
                    <td><?= date('g:i:s', $row->disappear_time) ?></td>
                    <td><?= date('g:i:s', $row->last_modified) ?></td>
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
                [4, "desc"]
            ],
            paging: true,
            lengthChange: true,
            searching: true,
        });
    });
</script>