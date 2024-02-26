<?php
// Retrieve number of students
echo "<form action='excel_interface.php' method='post'>";
echo "<label for='num_of_students'>Enter number of students:</label>";
echo "<input type='number' name='num_students'id='num_students' required>";
echo "<br><br>";
echo "<input type='submit' value='Continue'>";
echo "</form>";

// Check if the number of students has been submitted
if (isset($_POST['num_students'])) {
$num_students = intval($_POST['num_students']);
$_SESSION['num_students'] = $num_students;
echo "<form action='excel_interface.php' method='post'>";
echo "<table border='1'>";
echo "<tr><th>Roll Number</th>";
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
// Add column header for the subquestion
echo "<th>" . $row['main_question'] . " - " . $row['sub_question_number'] . "</th>";
}
echo "</tr>";
// Generate rows for each student
for ($i = 1; $i <= $num_students; $i++) {
  echo "<tr>";
  echo "<td><input type='text' name='roll_number[]' required></td>";
  // Generate columns for each main question and subquestion
  foreach ($rows as $row) {
    $max_marks = $row['marks'];
    echo "<td><input type='number' name='marks[]' max='$max_marks' required></td>";
  }
  echo "</tr>";
}

echo "</table>";
echo "<br><br>";
echo "<input type='submit' value='Save'>";
echo "</form>";
}
