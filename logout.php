<?php

session_start();
unset( $_SESSION['current_user_id'] );
unset( $_SESSION['role_type'] );
header( "Location: ./login.php" );
exit();