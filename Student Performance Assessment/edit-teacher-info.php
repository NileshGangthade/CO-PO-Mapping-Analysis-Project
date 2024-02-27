<?php
session_start();
error_reporting(0);
include('dbconnection.php');
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
  header("Location: login.html");
  exit();
} else {
     if(isset($_POST['submit']))
   {
 $fname=$_POST['fname'];
 $lname=$_POST['lname'];
 $mobnum=$_POST['mobnum'];
 $email=$_POST['email'];
 $gender=$_POST['gender'];
 $dob=$_POST['dob'];
 $cid=$_POST['cid'];
 $religion=$_POST['religion'];
 $address=$_POST['address'];
 $password=md5($_POST['password']);
 
 
 $eid=$_GET['editid'];
 $sql="update tblteacher set FirstName=:fname,LastName=:lname,MobileNumber=:mobnum,Email=:email,Gender=:gender,Dob=:dob,CourseID=:cid,Religion=:religion,Address=:address where tblteacher.ID=:eid";
 $query=$dbh->prepare($sql);
 
 $query->bindParam(':fname',$fname,PDO::PARAM_STR);
 $query->bindParam(':lname',$lname,PDO::PARAM_STR);
 $query->bindParam(':mobnum',$mobnum,PDO::PARAM_STR);
 $query->bindParam(':email',$email,PDO::PARAM_STR);
 $query->bindParam(':gender',$gender,PDO::PARAM_STR);
 $query->bindParam(':dob',$dob,PDO::PARAM_STR);
 $query->bindParam(':cid',$cid,PDO::PARAM_STR);
 $query->bindParam(':religion',$religion,PDO::PARAM_STR);
 $query->bindParam(':address',$address,PDO::PARAM_STR);
 $query->bindParam(':eid',$eid,PDO::PARAM_STR);
  $query->execute();
 
     echo '<script>alert("Teacher detail has been updated.")</script>';
 
   
  
 
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

  <title>Update Teacher Information</title>
  

  <!-- teacher info update css -->
  <link href="assets/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/lib/unix.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
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
                                <h1>Update Teacher Details</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="admin_frontend.php">Dashboard</a></li>
                                    <li class="active">Teacher Information</li>
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
                                    $eid=$_GET['editid'];
$sql="SELECT tblcourse.ID as cid,tblcourse.BranchName,tblcourse.CourseName,tblteacher.ID,tblteacher.EmpID,tblteacher.FirstName,tblteacher.LastName,tblteacher.MobileNumber,tblteacher.Email,tblteacher.Gender,tblteacher.Dob,tblteacher.CourseID,tblteacher.Religion,tblteacher.Address,tblteacher.Password,tblteacher.ProfilePic,tblteacher.JoiningDate from tblteacher join tblcourse on tblcourse.ID=tblteacher.CourseID where tblteacher.ID=$eid";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>  
                            <div class="card-header m-b-20">
                                <h4>Teacher Information</h4>
                                <div class="card-header-right-icon">
                                    <ul>
                                        <li class="card-close" data-dismiss="alert"><i class="ti-close"></i></li>
                                        <li class="card-option drop-menu"><i class="ti-settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" role="link"></i>
                                            <ul class="card-option-dropdown dropdown-menu">
                                                <li><a href="#"><i class="ti-loop"></i> Update data</a></li>
                                                <li><a href="#"><i class="ti-menu-alt"></i> Detail log</a></li>
                                                <li><a href="#"><i class="ti-pulse"></i> Statistics</a></li>
                                                <li><a href="#"><i class="ti-power-off"></i> Clear ist</a></li>
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
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="mobnum" maxlength="10" pattern="[0-9]+" readonly="true" value="<?php  echo htmlentities($row->MobileNumber);?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control border-none input-flat bg-ash" name="email" readonly="true" value="<?php  echo htmlentities($row->Email);?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Gender*</label>
                                            <select class="form-control bg-ash border-none" name="gender" required="true">
                                                <option value="<?php  echo htmlentities($row->Gender);?>"><?php  echo htmlentities($row->Gender);?></option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
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
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="empid" readonly="true" value="<?php  echo htmlentities($row->EmpID);?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Course</label>
                                            <select class="form-control border-none input-flat bg-ash" name="cid" required="true">
            <option value="<?php  echo htmlentities($row->cid);?>"><?php  echo htmlentities($row->CourseName);?>(<?php  echo htmlentities($row->BranchName);?>)</option>
            <?php
$sql="SELECT * from tblcourse";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row1)
{               ?>
            <option value="<?php  echo htmlentities($row->ID);?>"><?php  echo htmlentities($row1->CourseName);?>(<?php  echo htmlentities($row1->BranchName);?>)</option><?php $cnt=$cnt+1;}} ?>
        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Religion</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="religion" required="true" value="<?php  echo htmlentities($row->Religion);?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text"  class="form-control border-none input-flat bg-ash" rows="4" cols="4" required="true" name="address" value="<?php  echo htmlentities($row->Address);?>">
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
   <script src="assets/js/lib/jquery.min.js"></script>
    <script src="assets/js/lib/jquery.nanoscroller.min.js"></script>
    <!-- nano scroller -->
    <script src="assets/js/lib/menubar/sidebar.js"></script>
    <script src="assets/js/lib/preloader/pace.min.js"></script>
    <!-- sidebar -->
    <script src="assets/js/lib/bootstrap.min.js"></script>
    <!-- bootstrap -->


    <script src="assets/js/lib/calendar-2/moment.latest.min.js"></script>
    <!-- scripit init-->
    <script src="assets/js/lib/calendar-2/semantic.ui.min.js"></script>
    <!-- scripit init-->
    <script src="assets/js/lib/calendar-2/prism.min.js"></script>
    <!-- scripit init-->
    <script src="assets/js/lib/calendar-2/pignose.calendar.min.js"></script>
    <!-- scripit init-->
    <script src="assets/js/lib/calendar-2/pignose.init.js"></script>
    <!-- scripit init-->

    <script src="assets/js/scripts.js"></script>
</body>
</html>

<?php }  ?>
