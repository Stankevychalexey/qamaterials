<?php
require_once dirname(__FILE__) . "/../config/config.php";
require_once dirname(__FILE__) . "/../include/app.php";

$app = new app($config);
$app->execute();
