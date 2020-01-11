<?php
// なにもない

// echo '<pre>';
// var_Dump($_SERVER);
// var_Dump($_SERVER['HTTP_ACCEPT']);
exit((require dirname(__DIR__) . '/bootstrap.php')('web', 'NoraApp'));
