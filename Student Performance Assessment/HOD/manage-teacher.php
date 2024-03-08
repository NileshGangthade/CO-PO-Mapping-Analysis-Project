<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
}
else {
    if(isset($_GET['delid']))
{
        $rid=$_GET['delid'];
        $sql="delete from teachers_data where Email=:rid";
        $query=$dbh->prepare($sql);
        $query->bindParam(':rid',$rid,PDO::PARAM_STR);
        $query->execute();
                        // Second, delete from users_login table
        $sql1 = "delete from users_login where Email=:rid";
        $query1 = $dbh->prepare($sql1);
        $query1->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query1->execute();
    
        // Check if deletion was successful
        if($query->rowCount() > 0 && $query1->rowCount() > 0) {
            echo "<script>alert('Data deleted');</script>"; 
            echo "<script>window.location.href = 'manage-teacher.php'</script>"; 
        } else {
            echo "<script>alert('Failed to delete data');</script>"; 
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

    <title>Manage Teacher</title>


    <!-- manage teacher css -->
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/datatable/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/lib/datatable/buttons.bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>

<?php include_once('sidebar.php');?>
<?php include_once('header.php');?>
<!-- manage teacher -->

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 p-r-0 title-margin-right">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>Dashboard</h1>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="hod_frontend.php">Dashboard</a></li>
                                <li class="active">Manage Teacher</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /# column -->
            </div>
            <!-- /# row -->
            <div id="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card alert">
                            <div class="card-header">
                                <h4>Manage Teacher</h4>

                            </div>
                            <div class="bootstrap-data-table-panel">
                                <div class="table-responsive">
                                    <table  class="table table-striped table-bordered">
                                        <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Teacher ID</th>
                                            <th>Teacher Name</th>
                                            <th>Mobile Number</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Department</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $courseId = $_SESSION['Course'];
$sql = "SELECT td.ID, td.EmpID, td.FirstName, td.LastName, td.MobileNumber, td.Email, td.user_role, tc.CourseName, tc.BranchName
        FROM teachers_data as td  
        INNER JOIN tblcourse as tc ON td.CourseID = tc.ID WHERE tc.ID = $courseId";
$query = $dbh->query($sql);
$cnt = 1;
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    ?>
    <tr>
        <td><?php echo htmlentities($cnt);?></td>
        <td><?php echo htmlentities($row['EmpID']);?></td>
        <td><?php echo htmlentities($row['FirstName'] . ' ' . $row['LastName']);?></td>
        <td><?php echo htmlentities($row['MobileNumber']);?></td>
        <td><?php echo htmlentities($row['Email']);?></td>
        <td><?php echo htmlentities($row['user_role']);?></td>
        <td><?php echo htmlentities($row['CourseName'] . '(' . $row['BranchName'] . ')');?></td>
        <td>                                           
            <span><a href="teacher-info.php?editid=<?php echo htmlentities($row['ID']);?>"><i class="ti-pencil-alt color-success"></i></a></span>
            <span><a href="manage-teacher.php?delid=<?php echo htmlentities($row['Email']);?>"  onclick="return confirm('Do you really want to Delete ?');"><i class="ti-trash color-danger"></i> </a></span>
        </td>    
    </tr>
    <?php $cnt = $cnt + 1;
} ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->

            </div>
        </div>
    </div>
</div>
<div id="search">
    <button type="button" class="close">×</button>
    <form>
        <input type="search" value="" placeholder="type keyword(s) here" />
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>



<!-- scripts for teachers -->
<!-- jquery vendor -->
<script src="../assets/js/lib/bootstrap.min.js"></script>
    <!-- bootstrap -->
    <script src="../assets/js/lib/jquery.min.js"></script>
    <script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="../assets/js/lib/menubar/sidebar.js"></script>
    <script src="../assets/js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->
    <script src="../assets/js/lib/data-table/datatables.min.js"></script>
    <script src="../assets/js/lib/data-table/datatables-init.js"></script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
<?php } ?>