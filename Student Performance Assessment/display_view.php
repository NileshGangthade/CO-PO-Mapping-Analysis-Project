<?php
// Display the view
$view_name = $marks_table_name . "_view";

$sql = "SELECT * FROM $view_name";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  echo "<table border='1' id='marks-table' style='text-align: center;'>";
    echo "<tr><th>Roll Number</th>";
    foreach ($rows as $row) {
        echo "<th>Q" . $row['main_question'] . " - " . $row['sub_question_number'] . "</th>";
    }
    echo "<Br><Br>";
    echo "</tr>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row['roll_number'] . "</td>";
      foreach ($rows as $question) {
          $col_name = "Q" . $question['main_question'] . " - " . $question['sub_question_number'];
          echo "<td>" . $row[$col_name] . "</td>";
      }
      echo "<td><form method='post'>";
      echo "<input type='hidden' name='roll_number' value='" . $row['roll_number'] . "'/>";
      echo "<input type='submit' name='delete' value='Delete'/>";
      echo "</form></td>";
      echo "</tr>";
      }   
      echo "<tr>";

      echo "<br>";
      echo "<style>";
      echo "table, th, td { border: 1px solid black; }";
      echo "th, td { padding: 10px; }";
      echo "th { background-color: #ccc; }";
      echo "tr:nth-child(even) { background-color: #f2f2f2; }";
      echo "th { font-size: 16px; }";
      echo "</style>";
      echo "<table >";
      echo "<br><br><br>";

      
    echo "<table border='1' id='marks-table1' style='text-align: center;'>";

      echo "<br><br>";

      echo "<tr><th>Roll Number</th>";
      // Add column headers for each question
      $co_totals = array();
      $max_marks = array();
      $total_marks = 0;
      foreach ($rows as $question) {
        
        if (isset($question['marks'])) {
            if (!isset($max_marks[$question['co']])) {
                $max_marks[$question['co']] = 0;
            }
            $max_marks[$question['co']] = max($max_marks[$question['co']], $question['marks']);
        }
        echo "<th>Q" . $question['main_question'] . " - " . $question['sub_question_number'] . " <br>(CO" . $question['co'] .")(/". $question['marks'] . ")</th>";
        if (!isset($co_totals[$question['co']])) {
            $co_totals[$question['co']] = 0;
        }
    }
    
    // Calculate the sum of marks for each CO
    $co_max_marks = array();
    foreach ($rows as $question) {
        if (isset($question['marks'])) {
            if (!isset($co_max_marks[$question['co']])) {
                $co_max_marks[$question['co']] = 0;
            }
            $co_max_marks[$question['co']] += $question['marks'];
        }
    }
    
    // Add column headers for CO totals
    foreach ($co_totals as $co => $total) {
      $max_marks_co = $co_max_marks[$co] ?? '';
      $max_marks_co = $max_marks_co !== '' ? " /$max_marks_co" : '';
      echo "<th>CO" . $co . " Total <br>(" . trim($max_marks_co) . ")</th>";
      }
      
      // Add total column header with total marks for all questions
      $total_marks_all_questions = array_sum(array_column($rows, 'marks'));
      echo "<th>Total <br>(" . $total_marks_all_questions . ")</th>";
      echo "</tr>";
      
      $result = mysqli_query($conn, "SELECT * FROM $view_name");
      if (mysqli_num_rows($result) > 0) {
          $students_attempted = array_fill(0, count($rows), 0);
          while ($row = mysqli_fetch_assoc($result)) {
              $roll_number = $row['roll_number'];
              echo "<tr>";
              echo "<td>" . $roll_number . "</td>";

              // Add marks for each question and CO
              $total_marks = 0;
              $student_co_totals = array(); // CO total marks for this student
              foreach ($rows as $index => $question) {
                  $col_name = "Q" . $question['main_question'] . " - " . $question['sub_question_number'];
                  $marks_obtained = $row[$col_name];
                  echo "<td>" . $marks_obtained . "</td>";
                  $total_marks += $marks_obtained;
                  $co_totals[$question['co']] += $marks_obtained;
                  if ($marks_obtained > 0) {
                      $students_attempted[$index]++;
                      if (!isset($student_co_totals[$question['co']])) {
                          $student_co_totals[$question['co']] = 0;
                      }
                      $student_co_totals[$question['co']] += $marks_obtained;
                  }
              }

              // Add CO total marks columns
              foreach ($co_totals as $co => $total) {
                  $student_co_total = $student_co_totals[$co] ?? 0;
                  echo "<td>" . $student_co_total . "</td>";
              }

              // Add total marks column
              echo "<td>" . $total_marks . "</td>";
              echo "</tr>";
          }

          echo "<td></td>";
          foreach ($rows as $question) {
            if (isset($question['marks'])) {
                $max_marks[$question['co']] = $question['marks'];
            }
            echo "<th>Q" . $question['main_question'] . " - " . $question['sub_question_number'] . " <br>(CO" . $question['co'].")";
            if (!isset($co_totals[$question['co']])) {
                $co_totals[$question['co']] = 0;
            }
        }
  
        // Add column headers for CO totals
        foreach ($co_totals as $co => $total) {
            $max_marks_co = $max_marks[$co] ?? '';
            echo "<th>CO" . $co ;
        }
  
        echo "<td></td>";
          
          // Add row to display the number of students who attempted each question
          echo "<tr>";
          echo "<td>No. of Students Attempted</td>";
          foreach ($students_attempted as $num_attempted) {
              echo "<td>" . $num_attempted . "</td>";
          }

          // Initialize an array to store the number of students who attempted each CO
          $co_students_attempted = array();
          foreach ($co_totals as $co => $total) {
              $co_students_attempted[$co] = array();
          }

          $result = mysqli_query($conn, "SELECT * FROM $view_name");
          if (mysqli_num_rows($result) > 0) {
              // Calculate the number of unique students who attempted each CO
              while ($row = mysqli_fetch_assoc($result)) {
                  foreach ($rows as $index => $question) {
                      $col_name = "Q" . $question['main_question'] . " - " . $question['sub_question_number'];
                      $marks_obtained = $row[$col_name];
                      $roll_number = $row['roll_number'];

                      if ($marks_obtained > 0) {
                          $co_students_attempted[$question['co']][$roll_number] = true;
                      }
                  }
              }
          }

          // Display the number of students who attempted each CO
          foreach ($co_students_attempted as $co_attempts) {
              echo "<td>" . count($co_attempts) . "</td>";
          }
          echo "<td></td>";
          // Add row for CO wise max marks
          echo "<tr>";
          echo "<td>QUESTIONWISE MAXIMUM CO_MARKS</td>";
          foreach ($rows as $question) {
              echo "<td>" . $question['marks'] . "</td>";
          }

          // Add CO wise max marks for CO total columns
          foreach ($co_totals as $co => $total) {
              $max_marks_co = 0;
              foreach ($rows as $question) {
                  if ($question['co'] == $co) {
                      $max_marks_co += $question['marks'];
                  }
              }
              echo "<td>" . $max_marks_co . "</td>";
          }

          echo "<td></td>"; // empty cell for Total column
          echo "</tr>";
          
          // Add row for Competence 50% threshold
          echo "<tr>";
          echo "<td>COMPETENCE 50% THRESHOLD</td>";
          foreach ($rows as $question) {
              echo "<td>" . ($question['marks'] * 0.5) . "</td>";
          }

          // Add 50% threshold for CO total columns
          foreach ($co_totals as $co => $total) {
              $max_marks_co = 0;
              foreach ($rows as $question) {
                  if ($question['co'] == $co) {
                      $max_marks_co += $question['marks'];
                  }
              }
              $threshold = ($max_marks_co * 0.5);
              echo "<td>" . $threshold . "</td>";
          }

          echo "<td></td>"; // empty cell for Total column
          echo "</tr>";


          // Calculate the number of students who secured above threshold 50% for each question
          $result = mysqli_query($conn, "SELECT * FROM $view_name");
          $above_threshold_students = array_fill(0, count($rows), 0);
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  foreach ($rows as $index => $question) {
                      $col_name = "Q" . $question['main_question'] . " - " . $question['sub_question_number'];
                      $marks_obtained = $row[$col_name];
                      if ($marks_obtained > ($question['marks'] * 0.5)) {
                          $above_threshold_students[$index]++;
                      }
                  }
              }
          }

          // Calculate the number of students who secured above threshold 50% for each CO
          $result = mysqli_query($conn, "SELECT * FROM $view_name");
          $co_above_threshold_students = array();
          foreach ($co_totals as $co => $total) {
              $co_above_threshold_students[$co] = 0;
          }
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  $student_co_totals = array(); // CO total marks for this student
                  foreach ($rows as $index => $question) {
                      $col_name = "Q" . $question['main_question'] . " - " . $question['sub_question_number'];
                      $marks_obtained = $row[$col_name];
                      if (!isset($student_co_totals[$question['co']])) {
                          $student_co_totals[$question['co']] = 0;
                      }
                      $student_co_totals[$question['co']] += $marks_obtained;
                  }
                  foreach ($co_totals as $co => $total) {
                      $threshold = ($co_max_marks[$co] * 0.5);
                      if ($student_co_totals[$co] > $threshold) {
                          $co_above_threshold_students[$co]++;
                      }
                  }
              }
          }

          

          // Add row for Number of Students secured above threshold 50%
          echo "<tr>";
          echo "<td>Number of Students secured above threshold 50%</td>";

          // Display the number of students who secured above threshold 50% for each question
          foreach ($above_threshold_students as $num_above_threshold) {
              echo "<td>" . $num_above_threshold . "</td>";
          }

          // Display the number of students who secured above threshold 50% for each CO
          foreach ($co_above_threshold_students as $co_above_threshold) {
              echo "<td>" . $co_above_threshold . "</td>";
          }
          $_SESSION['above_threshold_students'] = $above_threshold_students;

          echo "<td></td>"; // empty cell for Total column
          echo "</tr>";

        // Add row for Total Percentage Attainment
        echo "<tr>";
        echo "<td>TOTAL PERCENTAGE ATTAINMENT</td>";

        // Calculate attainment for each question
        foreach ($above_threshold_students as $index => $num_above_threshold) {
            if ($students_attempted[$index] > 0) {
                $percentage = ($num_above_threshold / $students_attempted[$index]) * 100;
            } else {
                $percentage = 0;
            }
            if ($percentage >= 70) {
                $attainment = 3;
            } elseif ($percentage >= 60) {
                $attainment = 2;
            } elseif ($percentage >= 50) {
                $attainment = 1;
            } else {
                $attainment = 0;
            }
            echo "<td>" . $attainment . "</td>";
        }

        $co_attainment_values = array();

        // Calculate attainment for each CO
        foreach ($co_above_threshold_students as $co => $co_above_threshold) {
            if (count($co_students_attempted[$co]) > 0) {
                $percentage = ($co_above_threshold / count($co_students_attempted[$co])) * 100;
            } else {
                $percentage = 0;
            }
            if ($percentage >= 70) {
                $attainment = 3;
            } elseif ($percentage >= 60) {
                $attainment = 2;
            } elseif ($percentage >= 50) {
                $attainment = 1;
            } else {
                $attainment = 0;
            }
            $co_attainment_values["CO".$co] = $attainment;
            echo "<td>" . $attainment . "</td>";
        }
        echo '<script>
                function printTable() {
                    var printContents = document.getElementById("marks-table1").outerHTML;
                    var originalContents = document.body.innerHTML;
                    var header = \'<img src="college_logo.jpeg" alt="College Logo" style="width:100%; max-height:150px;"><div style="display:flex;justify-content:center;"><div style="margin: 0 10px;"><h3>Year: ' . $year . '</h3></div><div style="margin: 0 10px;"><h3>Department: ' . $department . '</h3></div><div style="margin: 0 10px;"><h3>Division: ' . $division . '</h3></div></div><div style="display:flex;justify-content:center;"><div style="margin: 0 10px;"><h3>Semester: ' . $sem . '</h3></div><div style="margin: 0 10px;"><h3>Subject: ' . $subject . '</h3></div></div><div style="display:flex;justify-content:center;"><div style="margin: 0 10px;"><h3>Academic Year: ' . $academic_year . '</h3></div></div>\';
                    document.body.innerHTML = header + printContents;
                    setTimeout(function() {
                        window.print();
                        document.body.innerHTML = originalContents;
                    }, 1000);
                }
                </script>';

            
            // Set the table name
            $table_CO_attainment = $table_name . "_CO_att";
            
            // Create the table
            $sql = "CREATE TABLE IF NOT EXISTS `$table_CO_attainment` (
              `CO` varchar(255) NOT NULL,
              `Attainment` int(11) NOT NULL,
              PRIMARY KEY (`CO`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            if (mysqli_query($conn, $sql)) {
            echo "";
            } else {
            echo "Error creating table: " . mysqli_error($conn);
            }
                
            // Insert data into the table
            foreach ($co_attainment_values as $co_name => $attainment) {
                $sql = "INSERT INTO $table_CO_attainment (CO, Attainment)
                        VALUES ('$co_name', '$attainment')
                        ON DUPLICATE KEY UPDATE Attainment = '$attainment'";

                // Execute the query
                if (mysqli_query($conn, $sql)) {
                    echo "";
                } else {
                    echo "Error inserting/updating attainment values: " . mysqli_error($conn);
                }
            }
        }
      }  
?>