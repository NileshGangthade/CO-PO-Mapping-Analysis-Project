<?php
session_start();
require 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
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
