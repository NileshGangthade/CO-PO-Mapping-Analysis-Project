<?php
// login_process.php

session_start();
require 'dbconnection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users_login WHERE Email = :email";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($password, $row['Password'])) { // Assuming you stored hashed password in ProfilePic
            $_SESSION['EmpID'] = $row['EmpID'];
            $_SESSION['email'] = $row['Email'];
            $_SESSION['fname'] = $row['FirstName'];
            $_SESSION['lname'] = $row['LastName'];
            $_SESSION['user_role'] = $row['user_role'];

            if ($row['user_role'] == 'Admin' || $row['user_role'] == 'Principal') {
                header("Location: Admin/admin_frontend.php");
                exit();
            } elseif ($row['user_role'] == 'HOD') {
                header("Location: HOD/HOD_frontend.php");
                exit();
            } elseif ($row['user_role'] == 'Professor') {
                header("Location: Professor/Professor_frontend.php");
                exit();
            } else {
                header("Location: dashboard.php");
                exit();
            }
        } else {
            ?>
            <script>
                alert("Invalid password.");
                window.location.href = "login.html";
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            alert("No user found with the provided email.");
            window.location.href = "login.html";
        </script>
        <?php
    }
} else {
    ?>
    <script>
        alert("Error in processing request.");
        window.location.href = "login.html";
    </script>
    <?php
}

$dbh = null;
?>
