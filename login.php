<?php

require_once "./constant.php";
require_once "./includes/class-db.php";

$sm_db = new SM_DB();
$mysqli = $sm_db->sql();

// Check if admin account exists
$admin = [
    'role_id' => 1,
    'first_name' => 'Admin',
    'last_name' => 'Admin',
    'email' => 'admin@admin.com',
    'password' => password_hash( 'admin', PASSWORD_BCRYPT )
];
$adminExists = $mysqli->prepare( "SELECT * FROM `users` WHERE `email`=?" );
$adminExists->bind_param( 's', $admin['email'] );
$adminExists->execute();

$adminResult = $adminExists->get_result();
if ( $adminResult->num_rows < 1 ) {
    $addAdmin = $mysqli->prepare( "INSERT INTO `users` (role_id, first_name, last_name, email, password) VALUES (?, ?, ?, ?, ?)" );
    $addAdmin->bind_param( 'issss', $admin['role_id'], $admin['first_name'], $admin['last_name'], $admin['email'], $admin['password'] );
    $addAdmin->execute();
}

// Check if database has roles
$roles = [
    'administrator',
    'teacher'
];
foreach ( $roles as $role ) {
    $roleExists = $mysqli->prepare( "SELECT * FROM `roles` WHERE `name`=?" );
    $roleExists->bind_param( 's', $role );
    $roleExists->execute();

    $roleResult = $roleExists->get_result();
    if ( $roleResult->num_rows < 1 ) {
        $addRole = $mysqli->prepare( "INSERT INTO `roles` (name) VALUES (?)" );
        $addRole->bind_param( 's', $role );
        $addRole->execute();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>PHP | School Management System</title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
</head>

<body class="bg-dark vh-100">
    <div class="container vh-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-5 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h6 class="h5 mb-0 text-center">Login | School Management System</h6>

                        <form action="/" method="POST" id="loginForm" class="py-4">
                            <div class="form-group row">
                                <div class="col-12 mb-3">
                                    <div class="input-group input-group-outline">
                                        <label for="login_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="login_email" id="login_email" required>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="input-group input-group-outline">
                                        <label for="login_password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="login_password" id="login_password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn bg-gradient-primary mb-0" id="loginSubmit">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>   
        </div>
        </div>

    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/material-dashboard.min.js?v=3.0.0"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

<script>
    window.addEventListener( 'load', () => {
        const loginForm = document.getElementById( 'loginForm' )

        if ( loginForm ) {
            const loginSubmit = document.getElementById( 'loginSubmit' )
            loginForm.addEventListener( 'submit', ( e ) => {
                e.preventDefault()

                loginSubmit.innerHTML .innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing
                `
                const formData = new FormData( loginForm )
                fetch( '/api/login', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json'
                    }
                } ).then( r => r.json() ).then( res => {
                    loginSubmit.innerHTML = `Login`
                    Swal.fire({
                        icon: res.success ? 'success' : 'info',
                        title: res.success ? 'Login Success' : 'Login Failed',
                        text: res.message
                    }).then(() => {
                        if ( res.success ) {
                            window.location.href = '/'
                        }
                    })
                } )
            } )
        }
    } )
</script>
