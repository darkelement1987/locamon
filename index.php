<?php
include './includes.php';
if (!empty($get->page)) {
    $title = $title . ' ' . $get->page;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/fh-3.1.6/r-2.2.3/datatables.min.css" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.css' />
    <link rel="stylesheet" type="text/css" href="./css/style.css" />


</head>

<body>
    <div class="container">
        <div class="row">
            <h3><?= $title ?></h3>
        </div>
        <div class="row">
            <div class="table-container w-100">
                <?php
                index();
                ?>
            </div>
        </div>
    </div>
    <!-- TEMP LINKS TO THE PAGES -->
    <p><a class="link" href="./index.php?page=raids">RAIDS</a></p>
    <p><a class="link" href="./index.php?page=pokemon">MONS</a></p>


    <script type="text/javascript">
        var updateTime = '<?= $update ?>';
        var mapLink = '<?= $map_link ?>';
    </script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/fh-3.1.4/r-2.2.2/datatables.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.full.min.js'></script>
    <script src='./js/functions.js'></script>
    <?= js() ?>


</body>

</html>