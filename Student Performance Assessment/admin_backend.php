<?php
session_start();
require 'config.php';
if ($_SESSION['user_role'] != 'Admin'  && $_SESSION['user_role'] != 'Principal') {
  header("Location: login.html");
  exit();
}

// Fetch waiting for approval list
$sql_waiting = "SELECT id, user_type, department, name, email FROM temp_table WHERE email_status = 1 AND user_type = 'hod';";
$result_waiting = $conn->query($sql_waiting);

// Fetch approved list
$sql_approved = "SELECT id, user_type, department, name, email FROM main_table WHERE is_approve = 1  AND user_type = 'hod';";
$result_approved = $conn->query($sql_approved);
?>
