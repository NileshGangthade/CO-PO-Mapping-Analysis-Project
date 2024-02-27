<?php
require 'config.php';

if (isset($_SESSION['user_type'])) {
    $user_type = $_SESSION['user_type'];
} else {
    $user_type = '';
}

$is_admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
    <title>Navigation Bar</title>
    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/dev.css">
    <link rel="stylesheet" href="assets/css/animated.css">
    <!-- <link rel="stylesheet" href="admin_dashboard.css"> Add your custom styles here -->
</head>
<body>
    <!-- ***** Header Area Start ***** -->
    <header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <nav class="main-nav">
                        <!-- ***** Logo Start ***** -->
                        <!-- <a href="index.html" class="logo">
                          <img src="assets/images/logo.png" alt="Chain App Dev">
                        </a> -->
                        <!-- ***** Logo End ***** -->
                        <!-- ***** Menu Start ***** -->
                        <ul class="nav">
                            <li class="scroll-to-section"><a href="index.php" class="active">Home</a></li>
                            <li class="scroll-to-section"><a href="index.php#services">Services</a></li>
                            <li class="scroll-to-section"><a href="index.php#about">About</a></li>
                            <?php if ($is_admin == 1 && $user_type != 'hod') : ?>
                                <li class="scroll-to-section"><a href="admin_dashbord.php">Dashboard</a></li>
                            <?php elseif ($user_type == 'hod' || $user_type == 'teacher') : ?>
                                <li class="scroll-to-section"><a href="dashbord.php">Dashboard</a></li>
                                <li class="scroll-to-section"><a href="view_results.php">View Results</a></li>
                                <?php if ($user_type == 'hod') : ?>
                                    <li class="scroll-to-section"><a href="department_approval_list.php">Pending Approval</a></li>
                                    <li class="scroll-to-section"><a href="progress.php">Progress</a></li>
                                <?php endif; ?>
                            <?php else : ?>
                                <li class="scroll-to-section"><a href="register.php">Register</a></li>
                                <li class="scroll-to-section"><a href="login.php">Login</a></li>
                            <?php endif; ?>
                            <!-- <li class="scroll-to-section"><a href="#pricing">Pricing</a></li> -->
                            <!-- <li class="scroll-to-section"><a href="#newsletter">Newsletter</a></li> -->
                            <li><div class="gradient-button"><a id="modal_trigge" href="logout.php"><i class="fa fa-sign-in-alt"></i> Logout</a></div></li> 
                        </ul>        
                        <a class='menu-trigger'>
                            <span>Menu</span>
                        </a>
                        <!-- ***** Menu End ***** -->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- ***** Header Area End ***** -->
    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/owl-carousel.js"></script>
    <script src="assets/js/animation.js"></script>
    <script src="assets/js/imagesloaded.js"></script>
    <script src="assets/js/popup.js"></script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
