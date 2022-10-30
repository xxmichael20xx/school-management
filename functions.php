<?php

session_start();

require_once "./constant.php";
require_once "./includes/class-db.php";
require_once "./includes/class-controller.php";
require_once "./includes/class-helpers.php";

require_once "./includes/model/class-schools.php";
require_once "./includes/model/class-teachers.php";

require_once "./routes/web.php";
require_once "./routes/api.php";

$db = new SM_DB();
$db->create_tables();

$api = new Api();

function has_session() {
    if ( ! isset( $_SESSION['current_user_id'] )  ) {
        echo "<script>window.location.href = '/login.php'</script>";
        exit();
    }
}
