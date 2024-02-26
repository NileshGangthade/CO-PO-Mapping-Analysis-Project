<?php
// Retrieve table name and department
session_start();
if (!isset($_SESSION['table_name']) || !isset($_SESSION['department'])) {
  die('Session variables not set.');
}
$table_name = $_SESSION['table_name'];
$academic_year = $_SESSION['academic_year'];
$department = $_SESSION['department'];

// Create a database connection
$conn = mysqli_connect('localhost', 'root', 'root', 'mysql');
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

// Select the appropriate database
mysqli_select_db($conn, $department);

// Get the prefix from the table name
$table_name_parts = explode('_', $table_name);
$table_name_parts_count = count($table_name_parts);
if ($table_name_parts_count > 5) {
  $table_name_prefix_parts = array_slice($table_name_parts, 0, $table_name_parts_count - 2);
  $table_name_prefix = implode('_', $table_name_prefix_parts) . '_';
} else {
  $table_name_prefix = '';
}

// Get all tables with names starting with the prefix and ending with any suffix after the academic year
$sql = "SHOW TABLES LIKE '$table_name_prefix%'";
$result = mysqli_query($conn, $sql);
if (!$result) {
  die('Error: ' . mysqli_error($conn));
}

// Delete the tables
while ($row = mysqli_fetch_row($result)) {
  $table = $row[0];
  if (strpos($table, $academic_year, -strlen($academic_year)) !== false) {
    $sql = "DROP TABLE IF EXISTS `$table`";
    if (mysqli_query($conn, $sql)) {
      echo "Table `$table` deleted successfully<br>";
    } else {
      echo "Error deleting table `$table`: " . mysqli_error($conn) . "<br>";
    }
  }
}

// Delete the _CO_att table
$table_name = $table_name_prefix . $academic_year . '_CO_att';
$sql = "DROP TABLE IF EXISTS `$table_name`";
if (mysqli_query($conn, $sql)) {
  echo "Table `$table_name` deleted successfully<br>";
} else {
  echo "Error deleting table `$table_name`: " . mysqli_error($conn) . "<br>";
}

// Delete the _marks table
$table_name = $table_name_prefix . $academic_year . '_marks';
$sql = "DROP TABLE IF EXISTS `$table_name`";
if (mysqli_query($conn, $sql)) {
  echo "Table `$table_name` deleted successfully<br>";
} else {
  echo "Error deleting table `$table_name`: " . mysqli_error($conn) . "<br>";
}

// Delete the _marks_view table
$table_name = $table_name_prefix . $academic_year . '_marks_view';
$sql = "DROP VIEW IF EXISTS `$table_name`";
if (mysqli_query($conn, $sql)) {
  echo "View `$table_name` deleted successfully<br>";
} else {
  echo "Error deleting view `$table_name`: " . mysqli_error($conn) . "<br>";
}

// Close the database connection
mysqli_close($conn);

// Redirect back to test_input.php
header("Location: test_input.php");
exit();
?>
