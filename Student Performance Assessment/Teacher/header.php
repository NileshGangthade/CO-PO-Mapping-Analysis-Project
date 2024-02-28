<div class="header">
    <div class="pull-left">
        <div class="logo"><a href="teacher_frontend.php"><!-- <img src="assets/images/logo.png" alt="" /> --><span>HOD Dashboard</span></a></div>
        <div class="hamburger sidebar-toggle">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </div>

    <div class="pull-right p-r-15">
        <ul>
            <?php
            // Check if user is logged in and session variables are set
            if (isset($_SESSION['fname']) && isset($_SESSION['lname']) && isset($_SESSION['email'])) {
                ?>
                <li class="header-icon dib"><img class="avatar-img" src="../assets/images/images (1).png" alt="" /> <span class="user-avatar"> <?php echo $_SESSION['fname']; ?> <?php echo $_SESSION['lname']; ?> <i class="ti-angle-down f-s-10"></i></span>
                    <div class="drop-down dropdown-profile">
                        <div class="dropdown-content-heading">
                            <span class="text-left"><?php echo $_SESSION['email']; ?></span>
                            <!-- You can display additional user information here if needed -->
                        </div>
                        <div class="dropdown-content-body">
                            <ul>
                                <li><a href="../profile.php"><i class="ti-user"></i> <span>Profile</span></a></li>
                                <li><a href="change-password.php"><i class="ti-settings"></i> <span>Setting</span></a></li>
                                <li><a href="../logout.php"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
