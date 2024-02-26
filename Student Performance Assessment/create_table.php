<?php
// Generate table for displaying question paper
if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' style='text-align: center;'>";
    echo "<tr><th>Main Question</th><th>Subquestion Number</th><th>Subquestion</th><th>Marks</th><th>CO</th><th>BL</th></tr>";
    $rows = array();
    $num_sub_questions = 0;
    while($row = mysqli_fetch_assoc($result)) {
      $rows[] = $row;
      $num_sub_questions++;
      echo "<tr>";
      echo "<td>" . $row['main_question'] . "</td>";
      echo "<td>" . $row['sub_question_number'] . "</td>";
      echo "<td>" . $row['sub_question'] . "</td>";
      echo "<td>" . $row['marks'] . "</td>";
      echo "<td>" . $row['co'] . "</td>";
      echo "<td>" . $row['bl'] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
  
    // Create a new table for storing marks input if it doesn't exist
    $marks_table_name = $table_name . "_marks";
    $_SESSION['marks_table_name'] = $marks_table_name;
    $_SESSION['num_sub_questions'] = $num_sub_questions;
    $sql = "SHOW TABLES LIKE '$marks_table_name'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
      $sql = "CREATE TABLE $marks_table_name (
        roll_number INT(11) NOT NULL,
        ";
      // Generate columns for each main question and subquestion
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
        // Add columns for the subquestion and its marks
        $sql .= "main_question" . $current_question_number . "_sub_question" . $current_subquestion_number . "_marks INT(11) NOT NULL, ";
      }
      $sql .= "PRIMARY KEY (roll_number)
        )";
      if (mysqli_query($conn, $sql)) {
        echo "<p>Table $marks_table_name created successfully.</p>";
      } else {
        echo "Error creating table: " . mysqli_error($conn);
      }
    } else {
      echo "<br>";
    }
  }
?>