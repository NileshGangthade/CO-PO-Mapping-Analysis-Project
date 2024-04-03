<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}else{
 // Code for deleting product from cart
 if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);

    // Check if the student data table exists
    $sql = "SELECT TableName FROM enrolled_classes WHERE ID = ?";
    $query = $dbh->prepare($sql);
    $query->execute([$rid]);
    $row = $query->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $tableNamePrefix = $row['TableName'];

        // Construct wildcard pattern to match table names
        $wildcardTableName = $tableNamePrefix . "_%";

        // Get all tables matching the wildcard pattern
        $checkTableSql = "SHOW TABLES LIKE ?";
        $checkTableQuery = $dbh->prepare($checkTableSql);
        $checkTableQuery->execute([$wildcardTableName]);
        $matchingTables = $checkTableQuery->fetchAll(PDO::FETCH_COLUMN);

        // Drop each matching table
        foreach ($matchingTables as $matchingTable) {
            $dropTableSql = "DROP TABLE $matchingTable";
            $dbh->exec($dropTableSql);
        }
    }

    // Delete the row from enrolled_classes table
    $sql = "DELETE FROM enrolled_classes WHERE ID = ?";
    $query = $dbh->prepare($sql);
    $query->execute([$rid]);

    echo "<script>alert('Data deleted');</script>";
    echo "<script>window.location.href = 'previous-assessment-details.php'</script>";
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

  <title>Previous Assessment Details</title>
  

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
                                <h1>Assessment Details</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">Assessment Details</li>
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
                                    <h4>Assessment Details</h4>
                                    
     
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table student-data-table m-t-20">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Course Name</th>
                                                    <th>Subject Full Name</th>
                                                    <th>Academic Year </th>
                                                    <th>Year</th>
                                                    <th>Division</th>
                                                    <th>Semester</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
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
            ec.TableName,
            sa.academic_year,
            s.SubjectFullname
        FROM 
            enrolled_classes ec
        INNER JOIN 
            tblcourse c ON ec.CourseID = c.ID
        INNER JOIN 
            tblsuballocation sa ON ec.SuballocationID = sa.ID
        INNER JOIN 
            tblsubject s ON ec.SubID = s.ID 
            WHERE 
            sa.Teacherempid = ?
            ORDER BY 
            ec.ID DESC"; // Order by ID in descending order
            $query = $dbh->prepare($sql);
$query->execute([$userid]);

//  WHERE ec.CourseID = $cid

$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                                                <tr>
                                                        <td><?php echo htmlentities($cnt);?></td>
                                                        <td><?php echo htmlentities($row->CourseName ); ?>(<?php echo htmlentities($row->BranchName); ?>)</td>
                                                        <td><?php echo htmlentities($row->SubjectFullname);?></td>
                                                        <td><?php echo htmlentities($row->academic_year);?></td>
                                                        <td><?php echo htmlentities($row->Year);?></td>
                                                        <td><?php echo htmlentities($row->Division);?></td>
                                                        <td><?php echo htmlentities($row->Sem);?></td>
                                                       <td>
                                                       <span><a href="co-attainment-calculation.php?enrollID=<?php echo htmlentities($row->ID); ?>"><i class="ti-pencil-alt color-success"></i></a></span>
                                                        <span><a href="previous-assessment-details.php?delid=<?php echo htmlentities($row->ID);?>"  onclick="return confirm('Do you really want to Delete ?');"><i class="ti-trash color-danger"></i> </a></span>
                                                       </td>
                                                </tr>
                                                 <?php $cnt=$cnt+1;}} ?> 
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