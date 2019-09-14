<?php
require 'config/config.php';
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $title;?></title>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
</head>
<body>
<?php
include 'config/functions.php';
index();
?>
</body>
</html>