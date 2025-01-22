<?php
session_start();
include 'db/connect.php';

if (isset($_SESSION['is_login']) && isset($_SESSION['user_name']) && isset($_SESSION['emp_id'])) {
    header('Location: views/purchase_order.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>DILG Region 6 Accounts</title>
    <meta charset="utf-8">
    <link rel="icon" href="assets/img/dilg_logo.png" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
</head>

<body class="app app-login p-0">
    <div class="row g-0 app-auth-wrapper">

    

        <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
            <div class="auth-background-holder">
            </div>
            <div class="auth-background-mask"></div>
            <div class="auth-background-overlay p-3 p-lg-5">
                <div class="d-flex flex-column align-content-end h-100">
                    <div class="h-100"></div>

                </div>
            </div>

        </div>
        <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
            <div class="d-flex flex-column align-content-end pt-5">
                <div class="app-auth-body mx-auto">
                    <div class="app-auth-branding mb-4">
                        <a class="app-logo" href="index.html">
                            <img class="logo-icon me-2" src="assets/img/dilg_logo.png" alt="logo">
                        </a>
                    </div>
                    <h2 class="auth-heading text-center">DILG R6 Accounts Portal
                    
                    <?php  //echo is_hrmis_admin(3) ? 'yes' : 'no'; ?>
                    <?php   //print_r(is_hrmis_admin(2)['hrmis_admin_role']); ?>
                    </h2>
                    <p class="text-center mb-4" style=" font-size:0.8rem">Region VI - Western Visayas</p>
                    <div class="auth-form-container text-start">
                        <form class="auth-form login-form" action="actions/login.php" method="POST">
                            <div class="email mb-3">
                                <input id="signin-email" name="user_name" type="text" class="form-control signin-email" placeholder="Username" required="required">
                            </div>
                            <div class="password mb-3">
                                <input id="signin-password" name="user_pass" type="password" class="form-control signin-password" placeholder="Password" required="required">
                                <div class="extra mt-3 row justify-content-between">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="RememberPassword">
                                            <label class="form-check-label" for="RememberPassword">
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
                            </div>
                        </form>
                    </div>
                </div>
                <footer class="app-auth-footer">
                    <div class="container text-center py-3">
                        <small class="copyright">
                            Copyright &copy; 2022 <a href="#">DILG Region VI - Human Resource Management System </a>.
                            All rights reserved.
                        </small>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</body>

</html>