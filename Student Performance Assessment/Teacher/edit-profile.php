<?php
session_start();
error_reporting(0);
include('../dbconnection.php');
if ($_SESSION['user_role'] !='Professor') {
    header("Location: login.html");
    exit();
} else {
     if(isset($_POST['submit']))
   {
 $fname=$_POST['fname'];
 $lname=$_POST['lname'];
 $mobnum=$_POST['mobnum'];
 $email=$_POST['email'];
 $dob=$_POST['dob'];
 $cid=$_POST['cid'];
 $user_role = $_POST['user_role'];
 $password=md5($_POST['password']);
 
 
 $eid=$_GET['editid'];
 try {
    // Start a transaction
    $dbh->beginTransaction();

    // Update the first table
    $sql1 = "UPDATE teachers_data SET FirstName=:fname, LastName=:lname, MobileNumber=:mobnum, Email=:email, Dob=:dob, CourseID=:cid, user_role=:user_role WHERE teachers_data.ID=:eid";
    $query1 = $dbh->prepare($sql1);
    $query1->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query1->bindParam(':lname', $lname, PDO::PARAM_STR);
    $query1->bindParam(':mobnum', $mobnum, PDO::PARAM_STR);
    $query1->bindParam(':email', $email, PDO::PARAM_STR);
    $query1->bindParam(':dob', $dob, PDO::PARAM_STR);
    $query1->bindParam(':cid', $cid, PDO::PARAM_STR);
    $query1->bindParam(':user_role', $user_role, PDO::PARAM_STR);
    $query1->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query1->execute();

    // Update the second table
    $sql2 = "UPDATE users_login SET FirstName=:fname, LastName=:lname, Email=:email, Course=:cid, user_role=:user_role WHERE users_login.ID=:eid";
    $query2 = $dbh->prepare($sql2);
    $query2->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query2->bindParam(':lname', $lname, PDO::PARAM_STR);
    $query2->bindParam(':email', $email, PDO::PARAM_STR);
    $query2->bindParam(':cid', $cid, PDO::PARAM_STR);
    $query2->bindParam(':user_role', $user_role, PDO::PARAM_STR);
    $query2->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query2->execute();

    // If all queries are successful, commit the transaction
    $dbh->commit();
    

    echo '<script>alert("Teacher detail has been updated.")</script>';
     echo "<script>window.location.href = 'profile.php'</script>"; 
    exit();
} catch (PDOException $e) {
    // If any error occurs, roll back the transaction
    $dbh->rollback();

    // Handle the error (e.g., display an error message)
    echo "Error: " . $e->getMessage();
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

  <title>Update Profile</title>
  

  <!-- teacher info update css -->
  <link href="../assets/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
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

      <!-- edit teacher info -->

      <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Update Profile</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="hod_frontend.php">Dashboard</a></li>
                                    <li class="active">Profile</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="card alert">
                        <div class="card-body">
                            <form name="" method="post" action="" enctype="multipart/form-data">
                            <?php
$eid = $_GET['editid'];
$sql = "SELECT tblcourse.ID as cid, tblcourse.BranchName, tblcourse.CourseName, teachers_data.ID, teachers_data.EmpID, teachers_data.FirstName, teachers_data.LastName, teachers_data.MobileNumber, teachers_data.Email, teachers_data.Dob, teachers_data.CourseID, teachers_data.ProfilePic, teachers_data.user_role FROM teachers_data JOIN tblcourse ON tblcourse.ID = teachers_data.CourseID WHERE teachers_data.ID = $eid";
$query = $dbh->prepare($sql);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

$cnt = 1;
if ($query->rowCount() > 0) {
    foreach ($results as $row) {
                               ?>  
                            <div class="card-header m-b-20">
                                <h4>Profile</h4>
                                <div class="card-header-right-icon">
                                    <ul>
                                        <li class="card-close" data-dismiss="alert"><i class="ti-close"></i></li>
                                        <li class="card-option drop-menu"><i class="ti-settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="link"></i>
                                            <ul class="card-option-dropdown dropdown-menu">
                                                <li><a href="#"><i class="ti-loop"></i> Update data</a></li>
                                                <li><a href="#"><i class="ti-menu-alt"></i> Detail log</a></li>
                                                <li><a href="#"><i class="ti-pulse"></i> Statistics</a></li>
                                                <li><a href="#"><i class="ti-power-off"></i> Clear list</a></li>
                                            </ul>
                                        </li>
                                        <li class="doc-link"><a href="#"><i class="ti-link"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="fname" required="true" value="<?php  echo htmlentities($row->FirstName);?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="lname" required="true" value="<?php  echo htmlentities($row->LastName);?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="mobnum" maxlength="10" pattern="[0-9]+" required="true" value="<?php  echo htmlentities($row->MobileNumber);?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control border-none input-flat bg-ash" name="email" required="true" value="<?php  echo htmlentities($row->Email);?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="date" class="form-control calendar bg-ash"  name="dob" required="true" value="<?php  echo htmlentities($row->Dob);?>">
                                            <span class="ti-calendar form-control-feedback booking-system-feedback m-t-30"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Emp ID</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="empid" required="true" value="<?php  echo htmlentities($row->EmpID);?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
        <div class="col-md-3">
            <div class="basic-form">
                <div class="form-group">
                    <label>User Role</label>
                    <select class="form-control border-none input-flat bg-ash" name="user_role" required="true">
                        <option value="">Select User Role</option>
                         <option value="HOD" <?php if ($row->user_role == 'HOD') echo 'selected'; ?>>HOD</option>
                        <option value="Professor" <?php if ($row->user_role == 'Professor') echo 'selected'; ?>>Professor</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
                                   
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Course</label>
                                            <select class="form-control border-none input-flat bg-ash" name="cid" required="true">
                <?php
                $courseId = $_SESSION['Course'];
                $sql = "SELECT * FROM tblcourse WHERE ID = $courseId";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                foreach ($results as $row1) {
                    if ($row1->ID == $row->CourseID) {
                        echo '<option value="' . $row1->ID . '" selected>' . $row1->CourseName . '(' . $row1->BranchName . ')</option>';
                    } else {
                        echo '<option value="' . $row1->ID . '">' . $row1->CourseName . '(' . $row1->BranchName . ')</option>';
                    }
                }
                ?>
            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="basic-form">
                                        <div class="form-group image-type">
                                            <label> Teacher Photo <span>(150 X 150)</span></label>
                                            <img src="../assets/ProfilePic/<?php echo $row->ProfilePic;?>" width="100" height="100" value="<?php  echo $row->ProfilePic;?>" alt="Profile Picture">
<a href="changeimage.php?editid=<?php echo $row->ID;?>"> &nbsp; Edit Image</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                           <?php $cnt=$cnt+1;}} ?>
                            <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Update</button>
                            <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                        </form>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
    
 

  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>

  <!-- scripts for teachers info edit -->
   <!-- jquery vendor -->
   <script src="../assets/js/lib/jquery.min.js"></script>
    <script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="../assets/js/lib/menubar/sidebar.js"></script>
    <script src="../assets/js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->
    <script src="../assets/js/lib/bootstrap.min.js"></script>
    <!-- bootstrap -->


    <script src="../assets/js/lib/calendar-2/moment.latest.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/semantic.ui.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/prism.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/pignose.calendar.min.js"></script>
    <!-- scripit init-->
    <script src="../assets/js/lib/calendar-2/pignose.init.js"></script>
    <!-- scripit init-->

    <script src="../assets/js/scripts.js"></script>
</body>
</html>

<?php }  ?>
