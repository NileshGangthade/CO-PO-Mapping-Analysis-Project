<?php
session_start();
error_reporting(0);
include('../dbconnection.php');

if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
} else {
    if (isset($_POST['submit'])) {
        $cid = $_SESSION['Course'];
        $sfname = $_POST['sfname'];
        $ssname = $_POST['ssname'];
        $subcode = $_POST['subcode'];

        $sql = "INSERT INTO tblsubject (CourseID, SubjectFullname, SubjectShortname, SubjectCode) VALUES (:cid, :sfname, :ssname, :subcode)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cid', $cid, PDO::PARAM_STR);
        $query->bindParam(':sfname', $sfname, PDO::PARAM_STR);
        $query->bindParam(':ssname', $ssname, PDO::PARAM_STR);
        $query->bindParam(':subcode', $subcode, PDO::PARAM_STR);
        $query->execute();

        $LastInsertId = $dbh->lastInsertId();
        if ($LastInsertId > 0) {
            echo '<script>alert("Subject has been added.")</script>';
            echo "<script>window.location.href ='subject.php'</script>";
        } else {
            echo '<script>alert("Something Went Wrong. Please try again")</script>';
        }
    }

    // Code for deleting subject
    if (isset($_GET['delid'])) {
        $rid = intval($_GET['delid']);
        $sql = "DELETE FROM tblsubject WHERE ID=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_STR);
        $query->execute();
        echo "<script>alert('Subject deleted');</script>";
        echo "<script>window.location.href = 'subject.php'</script>";
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
    <title>Create Question Paper</title>
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

    <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Create Question Paper</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">Create Question Paper</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="main-content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card alert">
                                <div class="card-header pr">
                                    <h4>Create Question Paper</h4>
                                    <form method="post" name="hjhgh">
                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Course Name</label>
                                                <select class="form-control border-none input-flat bg-ash" id = "cid" name="cid" required="true" readonly = "true">
                                                    <?php
                                                    $courseId = $_SESSION['Course'];
                                                    $sql = "SELECT * FROM tblcourse WHERE ID = :courseId";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':courseId', $courseId, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    foreach ($results as $row) {
                                                    ?>
                                                        <option value="<?php echo htmlentities($row->ID); ?>"><?php echo htmlentities($row->CourseName); ?>(<?php echo htmlentities($row->BranchName); ?>)</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <?php
                                        $eid = $_GET['suballid'];
                                        $sql = "SELECT * FROM tblsuballocation WHERE ID = :eid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>

                                        <div class="basic-form m-t-20">
                                                    <div class="form-group">
                                                        <label>Academic Year</label>
                                                        <input type="text" class="form-control border-none input-flat bg-ash" id = "asy" name="say" required="true" readonly = "true" value="<?php echo htmlentities($row->academic_year); ?>">
                                                    </div>
                                        </div>

                                        <?php
                                            }
                                        }
                                        ?>

                                      
                                        <?php
                                        $eid = $_GET['subid'];
                                        $sql = "SELECT tblcourse.CourseName, tblcourse.BranchName, tblcourse.ID as cid, tblsubject.SubjectFullname, tblsubject.SubjectShortname, tblsubject.SubjectCode, tblsubject.ID as sid FROM tblsubject JOIN tblcourse ON tblcourse.ID = tblsubject.CourseID WHERE tblsubject.ID = :eid";
                                        $query = $dbh->prepare($sql);
                                        $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                        $query->execute();
                                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                                        if ($query->rowCount() > 0) {
                                            foreach ($results as $row) {
                                        ?>
                                                
                                                <div class="basic-form m-t-20">
                                                    <div class="form-group">
                                                        <label>Subject Full Name</label>
                                                        <input type="text" class="form-control border-none input-flat bg-ash" id="sfname" name="sfname" required="true" readonly = "true" value="<?php echo htmlentities($row->SubjectFullname); ?>">
                                                    </div>
                                                </div>
                                                <div class="basic-form m-t-20">
                                                    <div class="form-group">
                                                        <label>Subject Short Name</label>
                                                        <input type="text" class="form-control border-none input-flat bg-ash" id="ssname" name="ssname" required="true" readonly = "true" value="<?php echo htmlentities($row->SubjectShortname); ?>">
                                                    </div>
                                                </div>
                                                <div class="basic-form m-t-20">
                                                    <div class="form-group">
                                                        <label>Subject Code</label>
                                                        <input type="text" class="form-control border-none input-flat bg-ash" id="subcode" name="subcode" required="true" readonly = "true" value="<?php echo htmlentities($row->SubjectCode); ?>">
                                                    </div>
                                                </div>
                                        <?php
                                            }
                                        }
                                        ?>

                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Year</label>
                                                <select class="form-control border-none input-flat bg-ash" id="years" name="years" required="true">
                                                    <option value="">Select Year</option>
                                                    <?php
                                                    $sql = "SELECT * FROM tblcourse WHERE ID = :courseId";
                                                    $query = $dbh->prepare($sql);
                                                    $query->bindParam(':courseId', $courseId, PDO::PARAM_STR);
                                                    $query->execute();
                                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                    if ($query->rowCount() > 0) {
                                                        foreach ($results as $row) {
                                                            $years = $row->Years;
                                                            for ($i = 1; $i <= $years; $i++) {
                                                                echo "<option value='$i'>$i</option>";
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Division</label>
                                                <select class="form-control border-none input-flat bg-ash" id="div" name="div" required="true">
                                                    <option value="">Select Division</option>
                                                    <option value="A">A</option>
                                                    <option value="B">B</option>
                                                    <option value="C">C</option>
                                                    <option value="D">D</option>
                                                    <option value="E">E</option>
                                                    <option value="F">F</option>
                                                    <option value="G">G</option>


                                                </select>
                                            </div>
                                        </div>

                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Semester</label>
                                                <select class="form-control border-none input-flat bg-ash" id="sem" name="sem" required="true">
                                                    <option value="">Select Semester</option>
                                                    <option value="SEM_I">First Semester</option>
                                                    <option value="SEM_II">Second Semester</option>
                                                   
                                                </select>
                                            </div>
                                        </div>

                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Exam</label>
                                                <select class="form-control border-none input-flat bg-ash" id="exam" name="exam" required="true">
                                                       <option value="">Select Exam</option>
                                                       <option value="UT1">UT1</option>
                                                       <option value="UT2">UT2</option>
                                                       <option value="UT3">UT3</option>
                                                       <option value="Prelim">Prelims</option>
                                                       <option value="Assign1">Assignment_1</option>
                                                       <option value="Assign2">Assignment_2</option>
                                                       <option value="Assign3">Assignment_3</option>
                                                       <option value="Assign4">Assignment_4</option>
                                                   
                                                </select>
                                            </div>
                                        </div>




                                        <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Create</button>
                                        <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card alert">
                                <div class="card-header pr">
                                    <h4>ALL Subject</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table student-data-table m-t-20">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Course Name</th>
                                                    <th>Subject Full Name</th>
                                                    <th>Subject Short Name</th>
                                                    <th>Subject Code</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT tblcourse.CourseName, tblcourse.BranchName, tblcourse.ID as cid, tblsubject.SubjectFullname, tblsubject.SubjectShortname, tblsubject.SubjectCode, tblsubject.ID as sid FROM tblsubject JOIN tblcourse ON tblcourse.ID = tblsubject.CourseID WHERE tblcourse.ID = :courseId";
                                                $query = $dbh->prepare($sql);
                                                $query->bindParam(':courseId', $courseId, PDO::PARAM_STR);
                                                $query->execute();
                                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                                $cnt = 1;
                                                if ($query->rowCount() > 0) {
                                                    foreach ($results as $row) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo htmlentities($cnt); ?></td>
                                                            <td><?php echo htmlentities($row->CourseName); ?>(<?php echo htmlentities($row->BranchName); ?>)</td>
                                                            <td><?php echo htmlentities($row->SubjectFullname); ?></td>
                                                            <td><?php echo htmlentities($row->SubjectShortname); ?></td>
                                                            <td><?php echo htmlentities($row->SubjectCode); ?></td>
                                                            <td>
                                                                <span><a href="edit-subject.php?editid=<?php echo htmlentities($row->sid); ?>"><i class="ti-pencil-alt color-success"></i></a></span>
                                                                <span><a href="subject.php?delid=<?php echo ($row->sid); ?>" onclick="return confirm('Do you really want to Delete ?');"><i class="ti-trash color-danger"></i> </a></span>
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
