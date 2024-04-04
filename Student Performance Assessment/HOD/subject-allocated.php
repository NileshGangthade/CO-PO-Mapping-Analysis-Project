<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
} else {
 
    // Code for deleting product from cart
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblsuballocation WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Data deleted');</script>";
        echo "<script>window.location.href = 'subject-allocated.php'</script>";
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
    <title>Allocated Subjects</title>
    <!-- subject allocation css -->
    <!-- Styles -->
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
    <?php include_once('sidebar.php'); ?>
    <?php include_once('header.php'); ?>
    <!-- Subject allocation-->
    <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Allocated Subjects</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">Allocated Subjects</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="col-md-8" style="width: 96% ">
                        <div class="card alert">
                            <div class="card-header pr">
                                <h4>Subject Allocation Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table student-data-table m-t-20">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Employee Name</th>
                                                <th>Course Name</th>
                                                <th>Subject Name</th>
                                                <th>Academic Year</th>
                                                <th>Allocation Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // $userid = $_SESSION['ID'];
                                            $courseId = $_SESSION['Course'];
                                            $sql = "SELECT tblsuballocation.ID as suballid, tblsuballocation.CourseID, tblsuballocation.Teacherempid, tblsuballocation.Subid, tblsuballocation.academic_year, tblsuballocation.AllocationDate, teachers_data.EmpID, teachers_data.FirstName, teachers_data.LastName, tblcourse.BranchName, tblcourse.CourseName, tblsubject.ID as subid, tblsubject.CourseID, tblsubject.SubjectFullname, tblsubject.SubjectShortname, tblsubject.SubjectCode 
                                            FROM tblsuballocation 
                                            JOIN teachers_data ON teachers_data.ID = tblsuballocation.Teacherempid 
                                            JOIN tblcourse ON tblcourse.ID = tblsuballocation.CourseID 
                                            JOIN tblsubject ON tblsubject.ID = tblsuballocation.Subid 
                                            WHERE tblcourse.ID = :courseId  
                                            ORDER BY tblsuballocation.ID DESC"; // Order by ID in descending order
                                                                                $query = $dbh->prepare($sql);
                                            $query->bindParam(':courseId', $courseId, PDO::PARAM_STR);
                                            // $query->bindParam(':userid', $userid, PDO::PARAM_STR);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                                            $cnt = 1;
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $row) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo htmlentities($cnt); ?></td>
                                                        <td>
                                                            <?php echo htmlentities($row->FirstName); ?> <?php echo htmlentities($row->LastName); ?>(<?php echo htmlentities($row->Teacherempid); ?>)
                                                        </td>
                                                        <td>
                                                            <?php echo htmlentities($row->BranchName); ?>(<?php echo htmlentities($row->CourseName); ?>)
                                                        </td>
                                                        <td>
                                                            <?php echo htmlentities($row->SubjectFullname); ?>(<?php echo htmlentities($row->SubjectCode); ?>)
                                                        </td>
                                                        <td>
                                                            <?php echo htmlentities($row->academic_year); ?>
                                                        </td>
                                                        <td>
                                                            <?php echo htmlentities($row->AllocationDate); ?>
                                                        </td>
                                                        <td>
                                                        <span><a href="enroll-in-class.php?subid=<?php echo ($row->subid); ?>&suballid=<?php echo ($row->suballid); ?>" class="btn btn-primary">Enroll in Class </a></span>
                                                        <span><a href="subject-allocated.php?delid=<?php echo ($row->suballid); ?>" onclick="return confirm('Do you really want to Delete ?');" class="btn btn-danger">DELETE </a></span>
                                                        </td>
                                                    </tr>
                                            <?php
                                                    $cnt = $cnt + 1;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
            </div>
        </div>
    </div>
    <!-- scripts for subject allocation-->
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
