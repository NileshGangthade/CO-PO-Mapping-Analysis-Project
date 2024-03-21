<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}else{
     $tableName = $_GET['tableName'];
     $test = $_GET['test'];
     $enrollID = $_GET['enrollID'];
 
     // Construct the table names for questions and marks
     $questionsTableName = $tableName . '_' . $test . '_Questions';
     $marksTableName = $tableName . '_' . $test . '_Marks';
 
     // Check if the questions table exists
     $checkQuestionsTableSql = "SHOW TABLES LIKE '$questionsTableName'";
     $checkQuestionsTableQuery = $dbh->query($checkQuestionsTableSql);
     $questionsTableExists = $checkQuestionsTableQuery->rowCount() > 0;
 
     // Check if the marks table exists
     $checkMarksTableSql = "SHOW TABLES LIKE '$marksTableName'";
     $checkMarksTableQuery = $dbh->query($checkMarksTableSql);
     $marksTableExists = $checkMarksTableQuery->rowCount() > 0;
 
     // If the questions table doesn't exist, redirect to questions.php
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
    $checkTableSql = "SHOW TABLES LIKE '$studentDataTable'";
    $checkTableQuery = $dbh->query($checkTableSql);
    $tableExists = $checkTableQuery->rowCount() > 0;

    // If the student data table doesn't exist, display a warning message
    if (!$tableExists) {
        echo "<script>alert('Student data table not found. Please add student data first.')</script>";
        echo "<script>window.location.href ='add-students.php?TableName={$tableName}'</script>";
        exit();
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

    <title><?php echo htmlentities($test) ?> Marks</title>
  

  <!-- subject css -->
         <!-- Styles -->

         <style>
        table.student-data-table th {
            white-space: nowrap; 
           
        }
        
        
        table.student-data-table th a {
            /* size: 1px; */
            font-size: 15px;
            display: block; 
            color: inherit;
            text-decoration: none;
        }
    </style>
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
                                <h1><?php echo htmlentities($test) ?> Marks</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active"><?php echo htmlentities($test) ?> Marks</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="row">
                        
                        <div class="col-md-8" style="width: 96% ">
                            
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