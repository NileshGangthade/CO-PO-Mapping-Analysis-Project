
<?php
session_start();
error_reporting(0); // Enable error reporting to catch all errors and warnings
include('../dbconnection.php'); // Include the database connection file

// Check if user is not a professor, redirect to login page if not
if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
} else {
    // Fetch table name, test, and enrollment ID from URL parameters
    $tableName = $_GET['tableName'];
    $test = $_GET['test'];
    $enrollID = $_GET['enrollID'];

    // Construct the table names for questions and marks
    $questionsTableName = $tableName . '_' . $test . '_Questions';
    $marksTableName = $tableName . '_' . $test . '_Marks';

    // Check if the questions table exists
    $checkQuestionsTableSql = "SHOW TABLES LIKE ?";
    $checkQuestionsTableQuery = $dbh->prepare($checkQuestionsTableSql);
    $checkQuestionsTableQuery->execute([$questionsTableName]);
    $questionsTableExists = $checkQuestionsTableQuery->rowCount() > 0;

    // Check if the marks table exists
    $checkMarksTableSql = "SHOW TABLES LIKE '$marksTableName'";
    $checkMarksTableQuery = $dbh->query($checkMarksTableSql);
    $marksTableExists = $checkMarksTableQuery->rowCount() > 0;

    if (!$questionsTableExists) {
        header("Location: questions.php?tableName=$tableName&test=$test&enrollID=$enrollID");
        exit();
    }

    // If the marks table doesn't exist, redirect to add-student-mark.php
    if (!$marksTableExists) {
      header("Location: add-student-mark.php?tableName=$tableName&test=$test&enrollID=$enrollID");
      exit();
  }

    // Check if the student data table exists
    $studentDataTable = $tableName . "_student_data";
    $checkTableSql = "SHOW TABLES LIKE ?";
    $checkTableQuery = $dbh->prepare($checkTableSql);
    $checkTableQuery->execute([$studentDataTable]);
    $tableExists = $checkTableQuery->rowCount() > 0;

    // If the student data table doesn't exist, display a warning message
    if (!$tableExists) {
        echo "<script>alert('Student data table not found. Please add student data first.')</script>";
        echo "<script>window.location.href ='add-students.php?TableName={$tableName}'</script>";
        exit();
    }

        // create the Co Attainments table
        $coAttainmentTableName = $tableName . '_' . $test . '_coAttainment';
        // SQL query to create the CO attainment table if it doesn't exist
     $createTableSQL = "CREATE TABLE IF NOT EXISTS $coAttainmentTableName (
         CO VARCHAR(255) NOT NULL PRIMARY KEY,
         Attainment INT(11) NOT NULL
     )";
     
     // Execute the create table SQL query
     $dbh->exec($createTableSQL);

    // Check if the marks table exists
    $checkMarksTableSql = "SHOW TABLES LIKE ?";
    $checkMarksTableQuery = $dbh->prepare($checkMarksTableSql);
    $checkMarksTableQuery->execute([$marksTableName]);
    $marksTableExists = $checkMarksTableQuery->rowCount() > 0;


    // Initialize marks data array
    $marksData = array();

    if ($marksTableExists) {
        // Fetch marks data from the database
        $fetchMarksSql = "SELECT * FROM $marksTableName";
        $fetchMarksQuery = $dbh->query($fetchMarksSql);
        $marksData = $fetchMarksQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    // Save marks data if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $tableName = $_POST['tableName'];
        $test = $_POST['test'];
        $enrollID = $_POST['enrollID'];
        // Now, you can proceed with updating the data into the marks table
        $marks = $_POST['marks'];
    
        // Retrieve the roll numbers from the form submission
        $rollNumbers = $_POST['rollNumber'];
    
        // Initialize loop counter
        $index = 0;
    
        foreach ($marks as $rollNumber => $markData) {
            // Get the roll number for the current student
            $rollNumber = $rollNumbers[$index];
            
            // Check if the roll number exists in the marks table
            $checkRollNumberSql = "SELECT RollNumber FROM $marksTableName WHERE RollNumber = :rollNumber";
            $checkRollNumberQuery = $dbh->prepare($checkRollNumberSql);
            $checkRollNumberQuery->bindParam(':rollNumber', $rollNumber);
            $checkRollNumberQuery->execute();
            
            if ($checkRollNumberQuery->rowCount() == 0) {
                // Roll number does not exist in the table, insert it along with marks
                $insertMarksSql = "INSERT INTO $marksTableName (RollNumber";
                $insertMarksValues = "(:rollNumber";
    
                // Prepare placeholder for values
                foreach ($markData as $questionKey => $markValue) {
                    $insertMarksSql .= ", Q$questionKey";
                    $insertMarksValues .= ", :Q$questionKey";
                }
                
                // Calculate total marks for the current student
                $total = array_sum($markData);
                
                // Add total marks to the insert values
                $insertMarksSql .= ", Total)";
                $insertMarksValues .= ", :total)";
    
                // Concatenate insert fields and values
                $insertMarksSql .= " VALUES " . $insertMarksValues;
            
                // Prepare the query
                $insertMarksQuery = $dbh->prepare($insertMarksSql);
            
                // Bind parameters
                $insertMarksQuery->bindParam(':rollNumber', $rollNumber);
                foreach ($markData as $questionKey => $markValue) {
                    $insertMarksQuery->bindValue(":Q$questionKey", $markValue);
                }
                $insertMarksQuery->bindValue(":total", $total);
            
                // Execute the query
                $insertMarksQuery->execute();
            } else {
                // Roll number already exists, proceed with updating marks
                // Prepare the SQL statement to update marks data
                $updateMarksSql = "UPDATE $marksTableName SET ";
            
                // Prepare placeholder for values
                $updateMarksValues = array(); // Use the roll number obtained from the form submission
                foreach ($markData as $questionKey => $markValue) {
                    $updateMarksValues[] = "Q$questionKey = :Q$questionKey";
                }
                
                // Calculate total marks for the current student
                $total = array_sum($markData);
            
                // Add total marks to the update values
                $updateMarksValues[] = "Total = :total";
            
                // Concatenate update fields
                $updateMarksSql .= implode(", ", $updateMarksValues) . " WHERE RollNumber = :rollNumber";
            
                // Prepare the query
                $updateMarksQuery = $dbh->prepare($updateMarksSql);
            
                // Bind parameters
                foreach ($markData as $questionKey => $markValue) {
                    $updateMarksQuery->bindValue(":Q$questionKey", $markValue);
                }
                $updateMarksQuery->bindValue(":total", $total);
                $updateMarksQuery->bindValue(":rollNumber", $rollNumber);
            
                // Execute the query
                $updateMarksQuery->execute();
            }
    
            $index++;
        }
        

        // Display success message and redirect
        echo "<script>alert('Students marks are saved successfully.')</script>";
        echo "<script>window.location.href ='marks-view.php?tableName=" . urlencode($tableName) . "&test=" . urlencode($test) . "&enrollID=" . $enrollID . "'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

    <title><?php echo htmlentities(str_replace('_', '-', $test)) ?></title>

    <!-- subject css -->
    <!-- Styles -->
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        /* Add this to your CSS file */
        .fixed-size-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse; /* Collapse table borders */
        }

        .fixed-size-table th:nth-child(2),
        .fixed-size-table td:nth-child(2) {
            width: 200px; /* Adjust the width as needed for the Name column */
            white-space: nowrap; /* Prevent wrapping in the Name column */
        }
        


        /* Make other columns smaller */
        .fixed-size-table th:not(:nth-child(2)),
        .fixed-size-table td:not(:nth-child(2)) {
            width: 85px; /* Adjust the width as needed for other columns */
            white-space: nowrap; /* Prevent wrapping in other columns */
        }
        /* Total column */
        .fixed-size-table th.total-column,
        .fixed-size-table td.total-column {
            width: 90px; /* Adjust the width as needed for the Total column */
            white-space: nowrap;
        }

        .fixed-size-table th,
        .fixed-size-table td {
            padding: 8px; /* Add padding to cells */
            overflow: hidden; /* Hide overflowing content */
            text-overflow: ellipsis; /* Display ellipsis for overflowing content */
        }

        .fixed-size-table-wrapper {
            overflow-x: auto; /* Add horizontal scrollbar */
        }

        .fixed-size-table input[type="number"] {
            border: none;
            outline: none; /* Optionally remove outline */
        }

        /* Center input fields */
        .fixed-size-table input[type="number"] {
            width: 100%; /* Ensure input fields fill the cell */
            text-align: center; /* Center the text within the input field */
        }

        /* Style specific to question table */
    table.question-table {
      border-collapse: collapse;
      width: 100%;
    }
    table.question-table th, 
    table.question-table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }
    table.question-table th:first-child,
    table.question-table td:first-child {
      width: 4%; /* Make the "Q. No." column smaller */
    }
    table.question-table th:nth-child(2),
    table.question-table td:nth-child(2) {
      width: 56%; /* Make the "Question" column wider */
    }
    table.question-table th:nth-child(n+3),
    table.question-table td:nth-child(n+3) {
      width: 10%; /* Make other columns smaller */
    }
    table.question-table .main-question {
      font-weight: bold;
    }
    </style>
</head>
<body>

<?php include_once('sidebar.php');?>
<?php include_once('header.php');?>
<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1><?php echo htmlentities(str_replace('_', '-', $test)) ?></h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="teacher_frontend.php">Dashboard</a></li>
                                <li class="active"><?php echo htmlentities(str_replace('_', '-', $test)) ?></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div id="main-content">
                <div class="row">
                    <div class="col-md-4" style="width: 96% ">
                        <div class="card alert">
                            <div class="card-header pr">
                            <button class="btn btn-link dropdown-toggle" type="button" data-toggle="collapse" data-target="#marksCardBody" aria-expanded="false" aria-controls="marksCardBody">
                            <span class="user-avatar"> <?php echo htmlentities(str_replace('_', '-', $test)) ?> : Questions  <span>&#8595;</span></span>
                             </button>
                            </div>
                            <div class="card-body collapse" id="marksCardBody">

                            
                            <div class="card-body">
                               
                                <table class="question-table">
                                    <thead>
                                    <tr>
                                        <th>Q. No.</th>
                                        <th>Question</th>
                                        <th>Marks</th>
                                        <th>CO</th>
                                        <th>BL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Fetch data from the database
                                            $fetchQuestionsSql = "SELECT * FROM $questionsTableName ORDER BY main_question, sub_question_number";
                                            $fetchQuestionsQuery = $dbh->query($fetchQuestionsSql);
                                            $questions = $fetchQuestionsQuery->fetchAll(PDO::FETCH_ASSOC);
                                                                                // Output data of each row
                                                                                // Output data into HTML table
                                        foreach ($questions as $row) {
                                            echo "<tr>";
                                            echo "<td>" . $row['main_question'].'.'.$row['sub_question_number'] . "</td>";
                                            echo "<td>" . $row['sub_question'] . "</td>";
                                            echo "<td>" . $row['marks'] . "</td>";
                                            echo "<td>" . $row['co'] . "</td>";
                                            echo "<td>" . $row['bl'] . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                              
                                
                           
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="main-content">
                <div class="row">
                    <div class="col-md-4" style="width: 96% ">
                        <div class="card alert">
                            <div class="card-header pr">

                            <button class="btn btn-link dropdown-toggle" type="button" data-toggle="collapse" data-target="#questionsCardBody" aria-expanded="false" aria-controls="questionsCardBody">
                                <span class="user-avatar"> <?php echo htmlentities(str_replace('_', '-', $test)) ?> : Students Marks <span>&#8595;</span></span> 
                            </button>
                            </div>
                            <div class="card-body collapse" id="questionsCardBody">
                            <div class="card-body">
                            <form name="" method="post" action="" enctype="multipart/form-data">
                                         <input type="hidden" name="tableName" value="<?php echo htmlspecialchars($tableName); ?>">
                                        <input type="hidden" name="test" value="<?php echo htmlentities($test) ?>">
                                        <input type="hidden" name="enrollID" value="<?php echo htmlspecialchars($enrollID); ?>">
                                        <div class="fixed-size-table-wrapper">
                                        <table class="table table-bordered fixed-size-table">
                                            <thead>
                                                <tr>
                                                    <th>Roll No</th>
                                                    <th>Name</th>
                                                   
                                                    <?php
                                                    // Fetch questions from the questions table
                                                    $fetchQuestionsSql = "SELECT * FROM $questionsTableName ORDER BY main_question, sub_question_number";
                                                    $fetchQuestionsQuery = $dbh->query($fetchQuestionsSql);
                                                    $questions = $fetchQuestionsQuery->fetchAll(PDO::FETCH_ASSOC);

                                                    // Display question columns
                                                    foreach ($questions as $question) {
                                                        // Construct the label with marks
                                                        $label = "Q - {$question['main_question']}.{$question['sub_question_number']}<br> (M - {$question['marks']})";
                                                        echo "<th>$label</th>";
                                                    }
                                                    ?>
                                                    <th class="total-column">Total (<?php
                                                        // Calculate total marks
                                                        $totalMarks = 0;
                                                        foreach ($questions as $question) {
                                                            $totalMarks += $question['marks'];
                                                        }
                                                        echo $totalMarks;
                                                    ?>)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Fetch student data
                                                $fetchStudentDataSql = "SELECT * FROM $studentDataTable";
                                                $fetchStudentDataQuery = $dbh->query($fetchStudentDataSql);
                                                $students = $fetchStudentDataQuery->fetchAll(PDO::FETCH_ASSOC);

                                                foreach ($students as $student) {
                                                    echo "<tr>";
                                                    echo "<td>{$student['RollNumber']}</td>";
                                                    echo "<td>{$student['Name']}</td>";
                                                    echo "<input type='hidden' name='rollNumber[]' value='{$student['RollNumber']}'>";
                                                        
                                                    // Display input fields for marks
                                                    foreach ($questions as $question) {
                                                        echo "<td>";
                                                        $markValue = 0; // Default value if no marks are found
                                                        foreach ($marksData as $mark) {
                                                            if ($mark['RollNumber'] == $student['RollNumber'] && $mark["Q{$question['main_question']}_{$question['sub_question_number']}"]) {
                                                                $markValue = $mark["Q{$question['main_question']}_{$question['sub_question_number']}"];
                                                                break;
                                                            }
                                                        }
                                                        echo "<input type='number' name='marks[{$student['RollNumber']}][{$question['main_question']}_{$question['sub_question_number']}]' max='{$question['marks']}' min='0' value='{$markValue}' class='marks-input' onchange='updateTotal(this)' required> ";
                                                        echo "</td>";
                                                    }
                                                    $totalValue = 0;
                                                    foreach ($marksData as $mark) {
                                                        if ($mark['RollNumber'] == $student['RollNumber']) {
                                                            $totalValue = $mark['Total'];
                                                            break;
                                                        }
                                                    }
                                                    echo "<td class='total-column' id='total[{$student['RollNumber']}]'>$totalValue</td>";
                                                    echo "</tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <br>
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-lg m-b-10 border-none m-r-5 sbmt-btn">Save Marks</button>
                                    <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="main-content">
                <div class="row">
                    <div class="col-md-4" style="width: 96% ">
                        <div class="card alert">
                            <div class="card-header pr">
                            <h4><?php echo htmlentities(str_replace('_', '-', $test)) ?> : CO Attainment</h4>

                            </div>

                            
                        <div class="card-body">  

                        <div class="fixed-size-table-wrapper">
                        <div class="container">
                        <table id="co-attainment-table" class="table table-bordered fixed-size-table" >
    <thead>
        <tr>
            <th>Roll No</th>
            <th>Name</th>
            <?php
            // Fetch questions from the questions table
            $fetchQuestionsSql = "SELECT * FROM $questionsTableName ORDER BY main_question, sub_question_number";
            $fetchQuestionsQuery = $dbh->query($fetchQuestionsSql);
            $questions = $fetchQuestionsQuery->fetchAll(PDO::FETCH_ASSOC);

            // Store CO-wise total marks
            $coTotalMarks = array();
            foreach ($questions as $question) {
                $co = $question['co'];
                if (!isset($coTotalMarks[$co])) {
                    $coTotalMarks[$co] = 0;
                }
                $coTotalMarks[$co] += $question['marks'];
            }

            // Display question columns
            foreach ($questions as $question) {
                // Construct the label with marks and CO total marks
                $label = "Q - {$question['main_question']}.{$question['sub_question_number']}<br>(CO{$question['co']} - {$question['marks']})";
                echo "<th>$label</th>";
            }

            // Display CO columns with their total marks
            foreach ($coTotalMarks as $co => $totalMarks) {
                echo "<th>CO - $co <br> Total: $totalMarks</th>"; // Display CO columns with total marks
            }
            ?>
            <th class="total-column">Total(<?php
                // Calculate total marks
                $totalMarks = array_sum($coTotalMarks);
                echo $totalMarks;
            ?>)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch student data
        $fetchStudentDataSql = "SELECT * FROM $studentDataTable";
        $fetchStudentDataQuery = $dbh->query($fetchStudentDataSql);
        $students = $fetchStudentDataQuery->fetchAll(PDO::FETCH_ASSOC);

        // Initialize arrays to store attempt counts, students secured above threshold, and percentage attainment for each question
$attemptCountsByQuestion = array();
$aboveThresholdCountsByQuestion = array();
$percentageAttainmentByQuestion = array();


 
         // Iterate through each question
foreach ($questions as $question) {
    // Initialize counts for the current question
    $attemptCountsByQuestion[$question['main_question']][$question['sub_question_number']] = 0;
    $aboveThresholdCountsByQuestion[$question['main_question']][$question['sub_question_number']] = 0;
    $percentageAttainmentByQuestion[$question['main_question']][$question['sub_question_number']] = 0;
}


// Initialize an array to keep track of whether a student has attempted any question related to a particular CO
$studentAttemptedCOs = array();
// Initialize an array to store the count of students who attempted each CO
$coAttemptCounts = array_fill_keys(array_keys($coTotalMarks), 0);
// Initialize an array to store the count of students who scored more than 50% of the total marks for each CO
$studentsAboveThresholdByCO = array_fill_keys(array_keys($coTotalMarks), 0);
// Initialize an array to store the % Attainment of the total marks for each CO

$coPercentageAttainment = array();

foreach ($coTotalMarks as $co => $totalMarks) {
    $coPercentageAttainment[$co] = 0;
}



        foreach ($students as $student) {

             // Reset the array for each student
    $studentAttemptedCOs[$student['RollNumber']] = array();

            echo "<tr>";
            echo "<td>{$student['RollNumber']}</td>";
            echo "<td>{$student['Name']}</td>";
            // echo "<input type='hidden' name='rollNumber[]' value='{$student['RollNumber']}'>";
                
            // Display input fields for marks
            foreach ($questions as $question) {
                echo "<td>";
                $markValue = 0; // Default value if no marks are found
                foreach ($marksData as $mark) {
                    if ($mark['RollNumber'] == $student['RollNumber'] && $mark["Q{$question['main_question']}_{$question['sub_question_number']}"]) {
                        $markValue = $mark["Q{$question['main_question']}_{$question['sub_question_number']}"];

                         // Increment attempt count if student attempted the question
                if ($markValue > 0) {
                    $attemptCountsByQuestion[$question['main_question']][$question['sub_question_number']]++;
                    // Increment count if student secured above 50% threshold
                    if ($markValue > ($question['marks'] * 0.5)) {
                        $aboveThresholdCountsByQuestion[$question['main_question']][$question['sub_question_number']]++;
                    }

                    
                }

                // Increment attempt count if student attempted the question
                if ($markValue > 0) {
                    // Mark the CO as attempted for this student
                    $studentAttemptedCOs[$student['RollNumber']][$question['co']] = true;
                }
                        break;
                    }
                }
               
                // Add the readonly attribute to the input fields
                // echo "<input type='number' name='marks[{$student['RollNumber']}][{$question['main_question']}_{$question['sub_question_number']}]' max='{$question['marks']}' min='0' value='{$markValue}' class='marks-input' readonly> ";
                // If the mark value is greater than 0, increment the count for this question
                echo "$markValue";
                echo "</td>";
            }

            // Count the number of unique COs attempted by this student
    $uniqueCOsAttempted = count($studentAttemptedCOs[$student['RollNumber']]);

     // Increment the attempt count for each CO attempted by this student
     foreach ($studentAttemptedCOs[$student['RollNumber']] as $co => $attempted) {
        $coAttemptCounts[$co]++;
    }



          
            // Display CO total columns
        
                foreach ($coTotalMarks as $co => $totalMarks) {
                    // Calculate the threshold for 50%
                    $threshold = $totalMarks * 0.5;

                $coTotal = 0;
                foreach ($questions as $question) {
                    if ($question['co'] == $co) {
                        $markValue = 0; // Default value if no marks are found
                        foreach ($marksData as $mark) {
                            if ($mark['RollNumber'] == $student['RollNumber'] && $mark["Q{$question['main_question']}_{$question['sub_question_number']}"]) {
                                $markValue = $mark["Q{$question['main_question']}_{$question['sub_question_number']}"];
                                break;
                            }
                        }
                        $coTotal += $markValue;

                    }  
                  
                }

                
                echo "<td>{$coTotal}</td>"; // Display CO total marks  
                // Check if the student scored more than 50% of the total marks for this CO
            if ($coTotal > $threshold) {
                $studentsAboveThresholdByCO[$co]++;
                
            }

            }

            $totalValue = 0;
            foreach ($marksData as $mark) {
                if ($mark['RollNumber'] == $student['RollNumber']) {
                    $totalValue = $mark['Total'];
                    break;
                }
            }
            echo "<td class='total-column' id='total[{$student['RollNumber']}]'>$totalValue</td>";
            echo "</tr>";
        }

        ?>
            <th colspan='2'></th>
            <?php
            foreach ($questions as $question) {
                // Construct the label with marks and CO total marks
                $label = "Q - {$question['main_question']}.{$question['sub_question_number']}<br>(CO{$question['co']})";
                echo "<th>$label</th>";
            }
            foreach ($coTotalMarks as $co => $totalMarks) {
                echo "<th>CO - $co</th>"; // Display CO columns with total marks
            }
            ?>
            <th></th>
                    <?php
       

// Calculate percentage attainment for each question
foreach ($questions as $question) {
    $mainQuestion = $question['main_question'];
    $subQuestion = $question['sub_question_number'];

    if ($attemptCountsByQuestion[$mainQuestion][$subQuestion] > 0) {
        $percentageAttainmentByQuestion[$mainQuestion][$subQuestion] = ($aboveThresholdCountsByQuestion[$mainQuestion][$subQuestion] / $attemptCountsByQuestion[$mainQuestion][$subQuestion]) * 100;
    } else {
        $percentageAttainmentByQuestion[$mainQuestion][$subQuestion] = 0;
    }
}



    //COS Calculate percentage attainment for the current question
    // Iterate through each CO
foreach ($coTotalMarks as $co => $totalMarks) {
    if ($coAttemptCounts[$co] > 0) {
        $coPercentageAttainment[$co] = ($studentsAboveThresholdByCO[$co] / $coAttemptCounts[$co]) * 100;
    }
    else {
        $coPercentageAttainment[$co] = 0;
    }
}



// Display the attempt counts in the specified table cell
echo "<tr>";
                                echo "<td colspan='2'>No. of Students Attempted</td>";

                                // Question

                                foreach ($questions as $question) {
                                    $mainQuestion = $question['main_question'];
                                    $subQuestion = $question['sub_question_number'];
                                    echo "<td>{$attemptCountsByQuestion[$mainQuestion][$subQuestion]}</td>";
                                }
                                // cos
                                foreach ($coAttemptCounts as $coAttemptCount) {
                                    echo "<td>{$coAttemptCount}</td>";
                                }
                                echo "<td></td>";
                                echo "</tr>";

    
            echo "<tr>
            <td colspan='2'>Questionwise maximum CO-Marks</td>";

            // Question
        foreach ($questions as $question) {
        $label = "{$question['marks']}";
        echo "<td>$label </td>";
        }
        // cos
        foreach ($coTotalMarks as $co => $totalMarks) {
        echo "<td>$totalMarks</td>"; // Display CO columns with total marks
        }
        echo "<td></td>";
        echo "</tr>";

        echo "<tr>
            <td colspan='2'>Competence 50% Threshold</td>";

            // question
            foreach ($questions as $question) {
                $label = "{$question['marks']}";
                $threshold = ($label * 0.5);
                echo "<td>$threshold </td>";
                }
                // cos
                foreach ($coTotalMarks as $co => $totalMarks) {
                    $threshold = ($totalMarks * 0.5); 
                echo "<td>$threshold</td>"; // Display CO columns with total marks
                }
                echo "<td></td>";
        echo "</tr>";


        echo "<tr>
                                        <td colspan='2'>Total Students secured above 50% threshold</td>";

                                        // question
                                        foreach ($questions as $question) {
                                            $mainQuestion = $question['main_question'];
                                            $subQuestion = $question['sub_question_number'];
                                            echo "<td>{$aboveThresholdCountsByQuestion[$mainQuestion][$subQuestion]}</td>";
                                        }
                                        // cos
                                foreach ($studentsAboveThresholdByCO as $co => $count) {
                                    echo "<td>{$count}</td>";
                                }

                                
                                echo "<td></td>";
                                echo "</tr>";

       echo "<tr>
                                        <td colspan='2'>Total Percentage Attainment</td>";

                                        // question

                                        foreach ($percentageAttainmentByQuestion as $mainQuestion => $subQuestions) {
                                            foreach ($subQuestions as $subQuestion => $attainment) {
                                                $attainmentLevel = 0;
                                                // Determine attainment level based on predefined criteria
                                                if ($attainment >= 70) {
                                                    $attainmentLevel = 3;
                                                } elseif ($attainment >= 60) {
                                                    $attainmentLevel = 2;
                                                } elseif ($attainment >= 50) {
                                                    $attainmentLevel = 1;
                                                }
                                                // Output attainment level in the table cell
                                                echo "<td>{$attainmentLevel}</td>";
                                            }
                                        }
                                        

                                        // cos
                                        foreach ($coPercentageAttainment as $co => $attainment) {
                                            $attainmentLevel = 0;
                                            // Determine attainment level based on predefined criteria
                                            if ($attainment >= 70) {
                                                $attainmentLevel = 3;
                                            } elseif ($attainment >= 60) {
                                                $attainmentLevel = 2;
                                            } elseif ($attainment >= 50) {
                                                $attainmentLevel = 1;
                                            } else {
                                                $attainmentLevel = 0;
                                            }
                                            // Output attainment level in the table cell
                                            echo "<td>{$attainmentLevel}</td>";
                                             // Insert or update values into the coAttainment table
                                $insertOrUpdateQuery = "INSERT INTO $coAttainmentTableName (CO, Attainment) VALUES ('$co', $attainmentLevel) ON DUPLICATE KEY UPDATE Attainment = $attainmentLevel";
                                $dbh->exec($insertOrUpdateQuery); 
                                        }

                                
                                echo "<td></td>";
                                echo "</tr>";

                                $sql = "SELECT 
            ec.ID,
            ec.CourseID,
            ec.SuballocationID,
            c.CourseName,
            c.BranchName,
            ec.SubID,
            ec.Year,
            ec.Division,
            ec.Sem,
            sa.academic_year,
            s.SubjectFullname,
            s.SubjectCode,
            ec.UnitTests,
            ec.Prelims,
            ec.Assignments,
            ec.Experiments,
            ec.TableName
        FROM 
            enrolled_classes ec
        INNER JOIN 
            tblcourse c ON ec.CourseID = c.ID
        INNER JOIN 
            tblsuballocation sa ON ec.SuballocationID = sa.ID
        INNER JOIN 
            tblsubject s ON ec.SubID = s.ID 
        WHERE 
            ec.ID = ?";
$query = $dbh->prepare($sql);
$query->execute([$enrollID]);
$result = $query->fetch(PDO::FETCH_OBJ);

// Assign fetched values to JavaScript variables
$department = htmlentities($result->BranchName); // Use BranchName for Department
$academicYear = htmlentities($result->academic_year);
$subjectFullname = htmlentities($result->SubjectFullname);
$subjectCode = htmlentities($result->SubjectCode);
$className = htmlentities($result->Year); // Assuming Year represents the class name
$division = htmlentities($result->Division);
$semester = htmlentities($result->Sem);
$test = htmlentities(str_replace('_', '-', $test));


                                
                                echo '<script>
                                    function printTable() {
                                        var printContents = document.getElementById("co-attainment-table").outerHTML;
                                        var originalContents = document.body.innerHTML;
                                        var collegeLogo = \'<img src="../assets/images/college_logo.jpeg" alt="College Logo"  style=" width:100%; border:none">\';
        
                                        // Use fetched values here
                                        var department = "' . $department . '"; // Department
                                        var academicYear = "' . $academicYear . '"; // Academic Year
                                        var subjectInfo = "' . $subjectFullname . ' (' . $subjectCode . ')"; // Subject
                                        var test = "' . $test . '";
                                        var classInfo = "' . $className . ' '. 'Year'. '"; // Class
                                        var division = "' . $division . '"; // Division
                                        var semester = "' . $semester . '"; // Semester
                                
                                        var header = collegeLogo + "<div style=\'text-align: center; font-size: 18px;\'>Department : " + department + "  |  Academic Year : " + academicYear + "  |  Subject : " + subjectInfo + "</div>";
                                        header += "<div style=\'text-align: center; font-size: 18px;\'>Test : " + test + "  |  Class : " + classInfo + "  |  Division : " + division + "  |  Semester : " + semester + "</div>";
                                        
                                        document.body.innerHTML = header + printContents;
                                        setTimeout(function() {
                                            window.print();
                                            document.body.innerHTML = originalContents;
                                        }, 1000);
                                    }
                                </script>';
                    


        ?>


            </tbody>
        </table>


                           
                        </div>
                        </div>

                        <br>
                        <br>
                        <button class="btn btn-primary btn-lg m-b-10 border-none m-r-5 sbmt-btn" onclick="exportData()">Export In Excel</button>
                        <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" onclick="printTable()">Print</button>
  
                            </div>
                        </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for Export Data in excel -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


<script>
  function exportData() {
/* Get table HTML */
var table = document.getElementById('co-attainment-table');

/* Convert table to worksheet */
var ws = XLSX.utils.table_to_sheet(table);

/* Create workbook and add the worksheet */
var wb = XLSX.utils.book_new();
XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

/* Save workbook */
XLSX.writeFile(wb, '<?php echo htmlentities($subjectFullname) . " (" . htmlentities(str_replace('_', '-', $test)) . ") CO_attainment.xlsx"; ?>');
}

</script>


<!-- scripts for subject-->
<!-- jquery vendor -->
<script src="../assets/js/lib/jquery.min.js"></script>
<script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
<!-- nano scroller -->
<script src="../assets/js/lib/menubar/sidebar.js"></script>
<script src="../assets/js/lib/preloader/pace.min.js"></script>
<!-- sidebar -->
<script src="../assets/js/lib/bootstrap.min.js"></script>
<!-- bootstrap -->
<script src="../assets/js/scripts.js"></script>

<script>
    function updateTotal(inputField) {
        var total = 0;
        var row = inputField.parentNode.parentNode;
        var marksInputs = row.querySelectorAll('.marks-input');
        marksInputs.forEach(function(input) {
            total += parseInt(input.value) || 0;
        });
        row.querySelector('.total-column').textContent = total;
    }
</script>
</body>
</html>
