<?php

// Handle delete operation
if (isset($_POST['delete'])) {
    $roll_number = $_POST['roll_number'];
    $num_students = $_SESSION['num_students'];
    $marks_table_name = $_SESSION['marks_table_name'];
    $sql = "DELETE FROM $marks_table_name WHERE roll_number = $roll_number";
    if (mysqli_query($conn, $sql)) {
      echo "Record deleted successfully";
    } else {
      echo "Error deleting record: " . mysqli_error($conn);
    }
  }

// Create a view of the marks table
$view_name = $marks_table_name . "_view";
$sql = "CREATE OR REPLACE VIEW $view_name AS
SELECT roll_number, ";
$current_question = '';
$current_question_number = 0;
foreach ($rows as $row) {
    if ($row['main_question'] != $current_question) {
        $current_question_number++;
        $current_question = $row['main_question'];
        $current_subquestion_number = 0;
    }
    $current_subquestion_number++;
    $sql .= "main_question" . $current_question_number . "_sub_question" . $current_subquestion_number . "_marks AS 'Q" . $row['main_question'] . " - " . $row['sub_question_number'] . "', ";
}
?>