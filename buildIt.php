<?php

require_once('buildRadiansClass.php');

$buildRadians = new buildRadians('db.host', 'dbuser', 'dbPassword', 'dbTable');

$buildRadians->buildRadians();

?>
