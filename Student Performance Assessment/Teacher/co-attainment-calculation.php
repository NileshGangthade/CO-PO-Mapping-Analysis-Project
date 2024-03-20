<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}else{
$enrollID = isset($_GET['enrollID'])? intval($_GET['enrollID']) : 0;


 $userid = $_SESSION['ID'];
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

 // Check if the student data table exists
    $studentDataTable = $result->TableName . "_student_data";
    $checkTableSql = "SHOW TABLES LIKE '$studentDataTable'";
    $checkTableQuery = $dbh->query($checkTableSql);
    $tableExists = $checkTableQuery->rowCount() > 0;

    // If the student data table doesn't exist, display a warning message
    if (!$tableExists) {
        echo "<script>alert('Student data table not found. Please add student data first.')</script>";
        echo "<script>window.location.href ='add-students.php?TableName={$result->TableName}'</script>";
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

  <title>CO attainment calculation</title>
  

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
                                <h1>CO attainment calculation</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">CO attainment calculation</li>
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
                            <div class="card alert">
                                <div class="card-header pr">
                               <h4> <?php echo htmlentities($result->SubjectFullname); ?> ( <?php echo htmlentities($result->SubjectCode); ?> ) </h4>
   
     
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">

                                    <table class="table student-data-table m-t-20">
                                        <thead>
                                            <tr>
                                            <th style="padding-right: 20px;">
                                                <a href="#"> Student Data </a> <br>
                                                <a href="view-student-data.php?studentDataTable=<?php echo $studentDataTable; ?>&enrollID=<?php echo $enrollID; ?>" class="btn btn-sm btn-info" style="border-radius: 10px;">View</a>
                                            </th>
                                             
                                                <?php
                                                    // Generate Unit Test columns
                                                    for ($i = 1; $i <= $result->UnitTests; $i++) {
                                                       //  echo '<th><a href="#">Unit Test-' . $i . '</a></th>';
                                                       echo '<th style="padding-right: 20px;"><a href="#">Unit Test-' . $i . '</a><br><a href="#" class="btn btn-sm btn-info" style="border-radius: 10px;">View</a></th>';

                                                    }

                                                    // Generate Prelim columns
                                                    for ($i = 1; $i <= $result->Prelims; $i++) {
                                                       echo '<th style="padding-right: 20px;"><a href="#">Prelim-' . $i . '</a><br><a href="#" class="btn btn-sm btn-info" style="border-radius: 10px;">View</a></th>';
                                                  }

                                                    // Generate Assignment columns
                                                    for ($i = 1; $i <= $result->Assignments; $i++) {
                                                       echo '<th style="padding-right: 20px;"><a href="#">Assignment-' . $i . '</a><br><a href="#" class="btn btn-sm btn-info" style="border-radius: 10px;">View</a></th>';
                                                  }

                                                    // Generate Experiment columns
                                                    for ($i = 1; $i <= $result->Experiments; $i++) {
                                                       echo '<th style="padding-right: 20px;"><a href="#">Experiment-' . $i . '</a><br><a href="#" class="btn btn-sm btn-info" style="border-radius: 10px;">View</a></th>';
                                                  }
                                                ?>
                                            </tr>
                                        </thead>

        <tbody>
          

        </tbody>
                                               
                                        </table>
                                    </div>
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
</body>
</html>
<?php }  ?>