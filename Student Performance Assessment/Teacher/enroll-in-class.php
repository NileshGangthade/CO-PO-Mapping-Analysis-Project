<?php
session_start();
error_reporting(0);  include('../dbconnection.php');

if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
} else {
    if (isset($_POST['submit'])) {
        $cid = $_SESSION['Course'];
        $sfname = $_POST['sfname'];
        $ssname = $_POST['ssname'];
        $subcode = $_POST['subcode'];
        $years = $_POST['years'];
        $div = $_POST['div'];
        $sem = $_POST['sem'];
        $SuballocationID = $_GET['suballid'];

        $UT_count = $_POST['UT_count'];
        $Prelims_count = $_POST['Prelims_count'];
        $Assignments_count = $_POST['Assignments_count'];
        $Experiments_count = $_POST['Experiments_count'];

        // Insert data into the enrolled_classes table
        $sql = "INSERT INTO enrolled_classes (CourseID, SuballocationID, SubID, Year, Division, Sem, UnitTests, Prelims, Assignments, Experiments) 
        VALUES (:cid, :suballid, :subid, :years, :div, :sem, :UT_count, :Prelims_count, :Assignments_count, :Experiments_count)";
$query = $dbh->prepare($sql);
$query->bindParam(':cid', $cid, PDO::PARAM_INT);
$query->bindParam(':suballid', $_GET['suballid'], PDO::PARAM_INT); // Assuming you get suballocation ID from the URL
$query->bindParam(':subid', $_GET['subid'], PDO::PARAM_INT); // Assuming you get subject ID from the URL
$query->bindParam(':years', $years, PDO::PARAM_INT);
$query->bindParam(':div', $div, PDO::PARAM_STR);
$query->bindParam(':sem', $sem, PDO::PARAM_STR);
$query->bindParam(':UT_count', $UT_count, PDO::PARAM_INT);
$query->bindParam(':Prelims_count', $Prelims_count, PDO::PARAM_INT);
$query->bindParam(':Assignments_count', $Assignments_count, PDO::PARAM_INT);
$query->bindParam(':Experiments_count', $Experiments_count, PDO::PARAM_INT);

        
        if ($query->execute()) {
            // Retrieve the last inserted ID
            $lastInsertId = $dbh->lastInsertId();
            // $_SESSION['ssname']= $ssname;
            // $_SESSION['SuballocationID']= $SuballocationID;
            // $_SESSION['enrollCID']= $lastInsertId;
            $TableName = $lastInsertId . '_' . $ssname . '_' . $SuballocationID;

             // Update the enrolled_classes table with the $TableName value
             $updateSql = "UPDATE enrolled_classes SET TableName = :TableName WHERE ID = :lastInsertId";
             $updateQuery = $dbh->prepare($updateSql);
             $updateQuery->bindParam(':TableName', $TableName, PDO::PARAM_STR);
             $updateQuery->bindParam(':lastInsertId', $lastInsertId, PDO::PARAM_INT);
             $updateQuery->execute();

            // Redirect to subject.php with the last inserted ID as a query parameter
            echo '<script>alert("Subject Enrolled for the class , Now Add students Data.")</script>';
            // echo "<script>window.location.href ='subject-setup.php?TableName=$TableName'</script>";
            echo "<script>window.location.href ='add-students.php?TableName=$TableName'</script>";
            exit(); // Exit to prevent further execution
        } else {
            echo '<script>alert("Error in Enrolling subject for the class. Please try again.")</script>';
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
    <title>Enroll in Class</title>
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
                            <h1>Enroll in Class</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="teacher_frontend.php">Dashboard</a></li>
                                <li class="active">Enroll in Class</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div id="main-content">
                <div class="row">
                    <div class="col-md-10 offset-md-2">
                        <div class="card alert">
                            <div class="card-header pr">
                                <h4>Enroll in Class</h4>
                                <form method="post" name="hjhgh">
                                    <div class="row">
                                        <!-- Left Column -->
                                        <div class="col-md-6">
                                            <div class="basic-form m-t-20">
                                                <div class="form-group">
                                                    <label>Course Name</label>
                                                    <select class="form-control border-none input-flat bg-ash" id="cid" name="cid" required="true" readonly="true">
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
                                            <div class="form-group">
                                                            <label>Academic Year</label>
                                                            <input type="text" class="form-control border-none input-flat bg-ash" id="asy" name="say" required="true" readonly="true" value="<?php echo htmlentities($row->academic_year); ?>">
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
                                                            <input type="text" class="form-control border-none input-flat bg-ash" id="sfname" name="sfname" required="true" readonly="true" value="<?php echo htmlentities($row->SubjectFullname); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Subject Short Name</label>
                                                            <input type="text" class="form-control border-none input-flat bg-ash" id="ssname" name="ssname" required="true" readonly="true" value="<?php echo htmlentities($row->SubjectShortname); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Subject Code</label>
                                                            <input type="text" class="form-control border-none input-flat bg-ash" id="subcode" name="subcode" required="true" readonly="true" value="<?php echo htmlentities($row->SubjectCode); ?>">
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>

                                                
                                            </div>
                                        </div>
                                        <!-- Right Column -->
                                        <div class="col-md-6">
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
                                            
                                                    <div class="basic-form m-t-20">
                                                        
                                                        <div class="form-group">
                                                            <label>Semester</label>
                                                            <select class="form-control border-none input-flat bg-ash" id="sem" name="sem" required="true">
                                                                <option value="">Select Semester</option>
                                                                <option value="SEM_I">First Semester</option>
                                                                <option value="SEM_II">Second Semester</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
    <label>Unit Tests</label>
    <input type="number" class="form-control border-none input-flat bg-ash" id="UT" name="UT_count" min="0" placeholder="Number of Unit Tests" required="true">
</div>
<div class="form-group">
    <label>Prelims</label>
    <input type="number" class="form-control border-none input-flat bg-ash" id="Prelim" name="Prelims_count" min="0" placeholder="Number of Prelims" required="true">
</div>
<div class="form-group">
    <label>Assignments</label>
    <input type="number" class="form-control border-none input-flat bg-ash" id="Assignment" name="Assignments_count" min="0" placeholder="Number of Assignments" required="true">
</div>
<div class="form-group">
    <label>Experiments</label>
    <input type="number" class="form-control border-none input-flat bg-ash" id="Experiment" name="Experiments_count" min="0" placeholder="Number of Experiments" required="true">
</div>

                                                    </div>
                                            
                                            
                                        </div>
                                    </div>
                                    </div>
                                    <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Enroll</button>
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
</body>

</html>
