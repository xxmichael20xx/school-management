<?php

require_once "./constant.php";
require_once "./includes/class-db.php";
require_once "./includes/class-helpers.php";

require_once "./routes/web.php";
require_once "./routes/api.php";

$db = new SM_DB();
$db->create_tables();
