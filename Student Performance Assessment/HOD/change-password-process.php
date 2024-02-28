<?php
session_start();
require '../dbconnection.php';

if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $old_password = $_POST["old_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        if ($new_password !== $confirm_password) {
            ?>
            <script>
                alert("New password and confirm password do not match.");
                window.location.href = 'change-password.php';
            </script>
            <?php
            exit();
        }

        $user_id = $_SESSION['ID']; // Make sure $_SESSION['ID'] is properly set

        $sql = "SELECT * FROM users_login WHERE ID = :user_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            if (password_verify($old_password, $result["Password"])) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                $sql = "UPDATE users_login SET Password = :new_password_hash WHERE ID = :user_id";
                $query = $dbh->prepare($sql);
                $query->bindParam(':new_password_hash', $new_password_hash, PDO::PARAM_STR);
                $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $query->execute();

                ?>
                <script>
                    alert("Password updated successfully.");
                    window.location.href = 'hod_frontend.php';
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
                window.location.href = 'change-password.php';
            </script>
            <?php
        }
    }
}
?>
