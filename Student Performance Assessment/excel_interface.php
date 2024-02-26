<?php
// Initialize the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Table with CSS</title>
  <style>
    h2 {
    text-align: center;
    margin: 2px 0;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      text-align: center;
      margin-bottom: 20px;
    }
    th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: center;
      font-size: 14px;
    }
    th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    tr:hover {
      background-color: #ddd;
    }
    h2 {
  display: block;
  padding: 10px;

  border-radius: 5px;
  margin-bottom: 10px;
  color: black;
  max-width: 800px;
  margin: 0 auto;
}
  </style>
</head>
<body>


<?php
// Create a database connection
$conn = mysqli_connect('localhost', 'root', 'root', 'mysql');
if (!$conn) {
  die('Connection failed: ' . mysqli_connect_error());
}

if (isset($_GET['table_name']) && isset($_GET['department'])) {
  $table_name = $_GET['table_name'];
  $department = $_GET['department'];
  
  $_SESSION['table_name'] = $table_name;
  $_SESSION['department'] = $department;

} elseif (isset($_SESSION['table_name']) && isset($_SESSION['department'])) {
  $table_name = $_SESSION['table_name'];
  $department = $_SESSION['department'];
} else {
  die('Session variables not set.');
}


include 'header.php';
// Select the appropriate database
mysqli_select_db($conn, $department);
// Retrieve data from the table
$sql = "SELECT * FROM $table_name";
$result = mysqli_query($conn, $sql);

include 'create_table.php';

echo '<form method="post" action="delete_table.php">';
echo '<input type="submit" name="submit" value="Delete Table" onclick="return confirm(\'Are you sure you want to delete this table?\')" style="background-color: red; color: white;">';
echo '</form>';
echo "<br><br>";


include 'num_student.php';

include 'save_marks.php';



include 'marks_view.php';
$marks_table_name = $_SESSION['marks_table_name'];
$sql = rtrim($sql, ", ");
$sql .= " FROM $marks_table_name";
if (mysqli_query($conn, $sql)) {
  echo "";
} else {
    echo "Error creating view: " . mysqli_error($conn);
}


include 'display_view.php'; 

include 'header.php';
echo '<button onclick="printTable()">Print Table</button>';
mysqli_close($conn);

?>

</body>
</html>