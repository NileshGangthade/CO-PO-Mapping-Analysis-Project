<?php
session_start();
require 'config.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['reset_email'];
    $new_password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE main_table SET password = ?, otp = NULL, otp_expiry = NULL WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    $is_updated = $stmt->execute();

    if ($is_updated) {
        ?>
            <script>
                alert("Password updated successfully");
                window.location.href = "login.html"; // Redirect to login page
            </script>
        <?php
    } else {
        ?>
        <script>
            alert("Cannot update the password. Please try again");
            window.location.href = "forgot_password.html"; // Redirect to forgot password page
        </script>
    <?php
    }

    $stmt->close();
    $conn->close();
}
?>
