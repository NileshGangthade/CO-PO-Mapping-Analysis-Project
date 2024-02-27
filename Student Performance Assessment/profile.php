<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION["user_id"];
$user_type = $_SESSION["user_type"];
$sql = "SELECT * FROM main_table WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
?>
    <script>
        alert("User not found.");
    </script>
<?php

    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="register.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

</head>

<body>
    
    <nav>
        <div class="left-nav">
            <ul class="nav-links">

                <?php
                if ($_SESSION['is_admin'] == 1  && $user_type != 'hod') {
                ?>
                    <a href="admin_dashbord.php">
                        <button class="nav_button">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span> dashbord
                        </button>
                    </a>
                <?php
                } else {
                ?>
                    <a href="dashbord.php">
                        <button class="nav_button">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span> dashbord
                        </button>
                    </a>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="right-nav">

            <a href="logout.php">

                <button class="nav_button">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span> Logout
                </button>
            </a>
        </div>

    </nav>

    <div class="profile-container">
        <div class="profile-icon">
            <?php echo strtoupper(substr($user["name"], 0, 1)); ?>
        </div>
        <div class="profile-info">
            <h2><?php echo $user["name"]; ?></h2>
            <p>Email: <?php echo $user["email"]; ?></p>
            <p>User Type: <?php echo ucfirst($user["user_type"]); ?></p>
            <?php if (!empty($user["department"])) : ?>
                <p>Department: <?php echo ucfirst($user["department"]); ?></p>
            <?php endif; ?>




            <!-- Add this code after the profile-info div -->
            <div class="change-password-link">
                <a href="#" id="show-change-password-form">Change Password</a>
            </div>

            <div class="change-password-form" id="change-password-form" style="display:none;">
                <form action="change_password.php" method="post" onsubmit="return validatePassword();">
                    <div class="form_wrapper">
                        <div class="form_container">
                            <div class="title_container">
                                <h1> Change password</h1>
                            </div>
                            <div class="row clearfix">
                                <div class="">
                                    <form>

                                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i>🔒</span>
                                            <input type="password" id="old_password" name="old_password" placeholder=" Old Password" required />
                                        </div>
                                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i>🔒</span>
                                            <input type="password" id="new_password" name="new_password" placeholder=" New Password" required />
                                        </div>
                                        <div class="input_field"> <span><i aria-hidden="true" class="fa fa-lock"></i>🔒</span>
                                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter Password" required />
                                        </div>





                                        <div class="frame"><button class="custom-btn btn-9 , button" type="submit" value="Change Password" id="register">submit</button> </div>
                           
                                    </form>
                                </div>
                            </div>

                        </div>

                        <!-- Add this JavaScript code before the closing </body> tag -->
                        <script>
                            document.getElementById("show-change-password-form").addEventListener("click", function(event) {
                                event.preventDefault();
                                var form = document.getElementById("change-password-form");
                                form.style.display = (form.style.display === "none") ? "block" : "none";
                            });

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