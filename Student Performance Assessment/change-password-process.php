<?php
session_start();
require 'config.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if ($new_password !== $confirm_password) {
?>
        <script>
            alert("New password and confirm password do not match.");
        </script>
        <?php
        

        exit();
    }

    $user_id =$_SESSION["user_id"];
    $sql = "SELECT * FROM main_table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($old_password, $user["password"])) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            $sql = "UPDATE main_table SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password_hash, $user_id);
            $stmt->execute();

        ?>
            <script>
                alert("Password updated successfully.");
                window.location.href = 'admin_frontend.php';
            </script>
        <?php

        } else {
        ?>
            <script>
                alert("Old password is incorrect.");
                window.location.href = 'change-password.php';
            </script>
        <?php


        }
    } else {
        ?>
        <script>
            alert("User not found");
            return false;
        </script>
<?php
    }

    $stmt->close();
    $conn->close();
}
?>