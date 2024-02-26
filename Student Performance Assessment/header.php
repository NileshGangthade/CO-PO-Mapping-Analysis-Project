<?php
// Create a database connection
$conn = mysqli_connect('localhost', 'root', 'root', 'mysql');
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_GET['table_name']) && isset($_GET['department']) && isset($_GET['year'])&& isset($_GET['sem']) && isset($_GET['subject']) && isset($_GET['test-type']) && isset($_GET['division']) && isset($_GET['academic_year'])) {
  $table_name = $_GET['table_name'];
  $department = $_GET['department'];
  $year = $_GET['year'];
  $sem = $_GET['sem'];
  $year = $_GET['subject'];
  $testType = $_GET['test-type'];
  $division = $_GET['division'];
  $academic_year = $_GET['academic_year'];

  
  $_SESSION['table_name'] = $table_name;
  $_SESSION['department'] = $department;
  $_SESSION['year'] = $year;
  $_SESSION['sem'] = $sem;
  $_SESSION['subject'] = $subject;
  $_SESSION['test-type'] = $testType;
  $_SESSION['division'] = $division;
  $_SESSION['academic_year'] = $academic_year;

} elseif (isset($_SESSION['table_name']) && isset($_SESSION['department']) && isset($_SESSION['year']) && isset($_SESSION['sem']) && isset($_SESSION['subject']) && isset($_SESSION['test-type']) && isset($_SESSION['division']) && isset($_SESSION['academic_year'])) {
  $table_name = $_SESSION['table_name'];
  $department = $_SESSION['department'];
  $year = $_SESSION['year'];
  $sem = $_SESSION['sem'];
  $subject = $_SESSION['subject'];
  $testType = $_SESSION['test-type'];
  $division = $_SESSION['division'];
  $academic_year = $_SESSION['academic_year'];
} else {
  die('Session variables not set.');
}

echo "<h2 style='font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 15px;'>Year: $year  |  Semester: $sem  |  Department: $department</h2>";
echo "<h2 style='font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 15px;'>Subject: $subject  |  Test type: $testType  |  Division: $division </h2>";
echo "<h2 style='font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 15px;'>Academic Year: $academic_year</h2>";

?>