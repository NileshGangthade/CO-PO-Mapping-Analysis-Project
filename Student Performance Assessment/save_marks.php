<?php
// Save marks to the database
if (isset($_POST['roll_number']) && isset($_POST['marks'])) {
    $roll_numbers = $_POST['roll_number'];
    $marks = $_POST['marks'];
    $num_students = $_SESSION['num_students'];
    $marks_table_name = $_SESSION['marks_table_name'];
    $num_sub_questions = $_SESSION['num_sub_questions'];
    $sql = "REPLACE INTO $marks_table_name (roll_number, ";
    // Generate column names for each main question and subquestion
    $current_question = '';
    $current_question_number = 0;
    foreach ($rows as $row) {
      if ($row['main_question'] != $current_question) {
        // Increment the current question number
        $current_question_number++;
        $current_question = $row['main_question'];
        // Reset the subquestion number
        $current_subquestion_number = 0;
      }
      // Increment the subquestion number
      $current_subquestion_number++;
      // Add column name for the subquestion
      $sql .= "main_question" . $current_question_number . "_sub_question" . $current_subquestion_number . "_marks, ";
    }
    $sql = rtrim($sql, ", ");
    $sql .= ") VALUES ";
    // Generate values for each student
    for ($i = 0; $i < $num_students; $i++) {
      $sql .= "(" . intval($roll_numbers[$i]) . ", ";
      for ($j = 0; $j < $num_sub_questions; $j++) {
        $sql .= intval($marks[$i * $num_sub_questions + $j]) . ", ";
      }
      $sql = rtrim($sql, ", ");
      $sql .= "), ";
    }
    $sql =rtrim($sql, ", ");
    if (mysqli_query($conn, $sql)) {
      echo "";
    } else {
      echo "Error saving marks: " . mysqli_error($conn);
    }
  }
?>