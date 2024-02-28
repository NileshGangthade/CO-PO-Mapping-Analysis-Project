<?php
session_start();
error_reporting(0);
include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}else{
     if(isset($_POST['submit']))
   {
 
  $cid=$_POST['cid'];
  $sfname=$_POST['sfname'];
  $ssname=$_POST['ssname'];
  $subcode=$_POST['subcode'];
   $eid=$_GET['editid'];
 
 $sql="update tblsubject set CourseID=:cid,SubjectFullname=:sfname,SubjectShortname=:ssname,SubjectCode=:subcode where ID=:eid";
 $query=$dbh->prepare($sql);
 $query->bindParam(':cid',$cid,PDO::PARAM_STR);
 $query->bindParam(':sfname',$sfname,PDO::PARAM_STR);
 $query->bindParam(':ssname',$ssname,PDO::PARAM_STR);
 $query->bindParam(':subcode',$subcode,PDO::PARAM_STR);
 $query->bindParam(':eid',$eid,PDO::PARAM_STR);
 
  $query->execute();
          echo '<script>alert("Subject has been updated")</script>';
    echo "<script>window.location.href ='subject.php'</script>";
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

  <title>Subject Update</title>
  

  <!-- subject update css -->
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
  <!-- <h1>Welcome to the Admin dashboard</h1> -->
     

      <!-- Subject  update-->
      <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Subject</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">Subject</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card alert">
                                <div class="card-header pr">
                                    <h4>Update Subject</h4>
                                    <form method="post" name="hjhgh">
                                        <?php
                   $eid=$_GET['editid'];
$sql="SELECT tblcourse.CourseName,tblcourse.BranchName,tblcourse.ID as cid,tblsubject.SubjectFullname,tblsubject.SubjectShortname,tblsubject.SubjectCode, tblsubject.ID as sid from tblsubject join tblcourse on tblcourse.ID=tblsubject.CourseID where tblsubject.ID=:eid";
$query = $dbh -> prepare($sql);
$query->bindParam(':eid',$eid,PDO::PARAM_STR);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>
                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Course Name</label>
        <select class="form-control border-none input-flat bg-ash" name="cid" >
            <option value="<?php  echo htmlentities($row->cid);?>"><?php  echo htmlentities($row->CourseName);?>(<?php  echo htmlentities($row->BranchName);?>)</option>

        </select>
                                            </div>
                                        </div>
                                         <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Subject Full Name</label>
                                                <input type="text" class="form-control border-none input-flat bg-ash" name="sfname" required="true" value="<?php  echo htmlentities($row->SubjectFullname);?>">
                                            </div>
                                        </div>
                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Subject Short Name</label>
                                                <input type="text" class="form-control border-none input-flat bg-ash" name="ssname" required="true" value="<?php  echo htmlentities($row->SubjectShortname);?>">
                                            </div>
                                        </div>
                                   <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Subject Code</label>
                                                <input type="text" class="form-control border-none input-flat bg-ash" name="subcode" required="true" value="<?php  echo htmlentities($row->SubjectCode);?>">
                                            </div>
                                        </div>
                                </div><?php $cnt=$cnt+1;}} ?> 
                                <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Update</button>
                                <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button> 
                            </form>
                            </div>
                        </div>
                      
                        <!-- /# column -->

                    </div>
                    <!-- /# row -->

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

  <!-- scripts for subject update-->
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