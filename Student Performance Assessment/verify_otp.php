<?php
session_start();
require 'config.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.html");
    exit();
}

$current_time = date("Y-m-d H:i:s");
$otp_expiry = $_SESSION['otp_expiry'];

if ($current_time > $otp_expiry) {
    unset($_SESSION['otp_expiry']);
    unset($_SESSION['reset_email']);
    echo "<script>alert('OTP has expired! Please generate a new one.'); window.location.href='forgot_password.html';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_SESSION['reset_email'];
    // Combine the OTP digits into a single variable
    $entered_otp = (isset($_POST['otp1']) ? trim($_POST['otp1']) : '') . (isset($_POST['otp2']) ? trim($_POST['otp2']) : '') . (isset($_POST['otp3']) ? trim($_POST['otp3']) : '') . (isset($_POST['otp4']) ? trim($_POST['otp4']) : '') . (isset($_POST['otp5']) ? trim($_POST['otp5']) : '') . (isset($_POST['otp6']) ? trim($_POST['otp6']) : '');

    $stmt = $conn->prepare("SELECT * FROM main_table WHERE email = ? AND otp = ?");
    $stmt->bind_param("ss", $email, $entered_otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        header("Location: new_password.html?email=" . urlencode($email) . "&from=verify_otp");
        exit();
    } else {
        ?>
        <script>
            alert("Invalid OTP. Please try again.");
            window.location.href = "verify_otp.html"; // Redirect to the OTP verification page
        </script>
        <?php
    }

    $stmt->close();
    $conn->close();
}
?>
