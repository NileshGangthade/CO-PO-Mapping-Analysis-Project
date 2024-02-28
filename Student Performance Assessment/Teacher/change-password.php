<?php
session_start();
error_reporting(0);
include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}else{



     ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Change Password</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- for nav bar -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- Bootstrap core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="../assets/css/dev.css">
    <link rel="stylesheet" href="../assets/css/animated.css">

<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="../vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="../assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/main.css">
     <!--===============================================================================================-->
     <!-- change password -->

    <!-- Styles -->
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/datatable/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/lib/datatable/buttons.bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once('sidebar.php');?>
<?php include_once('header.php');?>


<!-- change password -->
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" id="change-password-form" action="change-password-process.php" method="post" onsubmit="return validatePassword();">
                <span class="login100-form-title p-b-26">
                    Change Password
                </span>

                <div class="wrap-input100 validate-input" data-validate="Enter old password">
                              <span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
                    <input class="input100" type="password" id="old_password" name="old_password" placeholder="" required />
                    <span class="focus-input100" data-placeholder="Old Password"></span>

                </div>

                <div class="wrap-input100 validate-input" data-validate="Enter new password">
                              <span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
                    <input class="input100" type="password" id="new_password" name="new_password" placeholder="" required />
                    <span class="focus-input100" data-placeholder="New Password"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="Confirm new password">
                              <span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
                    <input class="input100" type="password" id="confirm_password" name="confirm_password" placeholder="" required />
                    <span class="focus-input100" data-placeholder="Confirm Password"></span>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" type="submit" id="change-password-btn">Change Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

	

	
<!--===============================================================================================-->
	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/bootstrap/js/popper.js"></script>
	<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/daterangepicker/moment.min.js"></script>
	<script src="../vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="../vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="../assets/js/main.js"></script>

     <!-- change password scripts-->

     <!-- scripts for search-->
 <!-- jquery vendor -->
 <script src="../assets/js/lib/bootstrap.min.js"></script>
    <!-- bootstrap -->
    <script src="../assets/js/lib/jquery.min.js"></script>
    <script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="../assets/js/lib/menubar/sidebar.js"></script>
    <script src="../assets/js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->
    <script src="../assets/js/lib/data-table/datatables.min.js"></script>
    <script src="../assets/js/lib/data-table/datatables-init.js"></script>
    <script src="../assets/js/scripts.js"></script>

    <script>
     function validatePassword() {
                                var newPassword = document.getElementById("new_password").value;
                                var confirmPassword = document.getElementById("confirm_password").value;

                                if (newPassword !== confirmPassword) {
                                    alert("New password and confirm password do not match.");
                                    return false;
                                }

                                var minLength = 8;
                                var hasAlphabet = /[a-zA-Z]/.test(newPassword);
                                var hasNumber = /[0-9]/.test(newPassword);
                                var hasSymbol = /[^a-zA-Z0-9]/.test(newPassword);

                                if (newPassword.length < minLength || !hasAlphabet || !hasNumber || !hasSymbol) {
                                    alert("Password must be at least 8 characters long, contain at least 1 alphabet, 1 number, and 1 symbol.");
                                    return false;
                                }

                                return true;
                            }
    </script>


	

</body>
</html>

<?php }  ?>