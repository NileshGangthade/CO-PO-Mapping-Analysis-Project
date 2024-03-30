
<?php
session_start();
error_reporting(0); // Enable error reporting to catch all errors and warnings
include('../dbconnection.php'); // Include the database connection file

// Check if user is not a professor, redirect to login page if not
if ($_SESSION['user_role'] != 'Professor') {
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

    <title><?php echo htmlentities(str_replace('_', '-', $test)) ?> Marks</title>

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
            width: 70px; /* Adjust the width as needed for other columns */
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
                            <h1><?php echo htmlentities(str_replace('_', '-', $test)) ?> Marks</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="teacher_frontend.php">Dashboard</a></li>
                                <li class="active"><?php echo htmlentities(str_replace('_', '-', $test)) ?> Marks</li>
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
                                <h4>Add Students Marks</h4>
                            </div>
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
                                                        $label = "{$question['main_question']}.{$question['sub_question_number']} ({$question['marks']})";
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
                                                        echo "<input type='number' name='marks[{$student['RollNumber']}][{$question['main_question']}_{$question['sub_question_number']}]' max='{$question['marks']}' min='0' value='{$markValue}' class='marks-input' onchange='updateTotal(this)'> ";
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
    </div>
</div>

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
