<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}else{
     
 
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

  <title>Add Students Marks</title>
  

  <!-- subject css -->
         <!-- Styles -->
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>


<?php include_once('sidebar.php');?>
<?php include_once('header.php');?>
      <!-- Subject -->
      <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Add Students Marks</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">Add Students Marks</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="row">
                        <div class="col-md-4" style="width: 96% ">
                            <div class="card alert">
                                <div class="card-header pr">
                                    <h4>Add Students Marks</h4>
                                </div>
                                <?php 
// Retrieve table name from GET parameter
$TableName = $_GET['TableName'];

// Fetch data from the QuestionPaper table
$tblQ = $TableName . '_QuestionPaper';
$sql = "SELECT * FROM $tblQ";
$query = $dbh->prepare($sql);
$query->execute();
$result = $query->fetchAll(PDO::FETCH_ASSOC);
$num_sub_questions = $query->rowCount();

// Display fetched data in a table
if ($num_sub_questions > 0) {
    echo "<table border='1' style='text-align: center;'>";
    echo "<tr><th>Main Question</th><th>Subquestion Number</th><th>Subquestion</th><th>Marks</th><th>CO</th><th>BL</th></tr>";
    foreach ($result as $row) {
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
    $marks_table_name = $TableName . "_marks";
    $sql = "SHOW TABLES LIKE '$marks_table_name'";
    $query = $dbh->prepare($sql);
    $query->execute();
    
    if ($query->rowCount() == 0) {
        $sql = "CREATE TABLE $marks_table_name (
            roll_number BIGINT(11) NOT NULL,
            ";
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
            $query = $dbh->prepare($sql);
        if ($query->execute()) {
            echo "<p>Table $marks_table_name created successfully.</p>";
        } else {
            echo "Error creating table: " . $dbh->errorInfo()[2];
        }
    }
}



                                ?>

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
</body>
</html>
<?php }  ?>