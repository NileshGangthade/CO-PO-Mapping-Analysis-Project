<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'HOD') {
  header("Location: login.html");
  exit();
}
else {
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

  <title>Teacher Details</title>
  

  <!-- teacher info css -->
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

      <!-- manage teacher info -->

      <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Teacher Details</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="hod_frontend.php">Dashboard</a></li>
                                    <li class="active">Teacher Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                <div class="card-body">
    <?php
    $eid=$_GET['editid'];
    $sql = "SELECT td.ID, td.EmpID, td.FirstName, td.LastName, td.MobileNumber, td.Email, td.Dob, td.user_role, td.ProfilePic, tc.CourseName, tc.BranchName  
            FROM teachers_data as td  
            INNER JOIN tblcourse as tc ON td.CourseID = tc.ID
            WHERE td.ID = $eid";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>  
  <div class="user-profile m-t-15">
<div class="row">
<div class="col-lg-4">
  <div class="user-photo m-b-30">
<img class="img-responsive" src="../assets/ProfilePic/<?php  echo htmlentities($row->ProfilePic);?>" alt="Profile Picture" />
 </div>
</div>
 <div class="col-lg-8">
 <div class="user-profile-name dib"><?php  echo htmlentities($row->FirstName);?> <?php  echo htmlentities($row->LastName);?></div>
  <div class="useful-icon dib pull-right">
<span><a href="edit-teacher-info.php?editid=<?php echo htmlentities ($row->ID);?>" title="Edit Details"><i class="ti-pencil-alt"></i></a> </span>
</div>
<div class="custom-tab user-profile-tab">
<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#1" aria-controls="1" role="tab" data-toggle="tab">About</a></li>
</ul>
<div class="tab-content">
<div role="tabpanel" class="tab-pane active" id="1">
<div class="contact-information">
<div class="phone-content">
<div class="email-content">
 <span class="contact-title">Teacher ID:</span>
<span class="contact-email"><?php  echo htmlentities($row->EmpID);?></span>
</div>
<span class="contact-title">Name:</span>
<span class="phone-number"><?php  echo htmlentities($row->FirstName);?> <?php  echo htmlentities($row->LastName);?></span>
</div>

<div class="email-content">
<span class="contact-title">Department:</span>
<span class="contact-email"><?php  echo htmlentities($row->CourseName);?> (<?php  echo htmlentities($row->BranchName);?>)</span>
</div>



<div class="email-content">
<span class="contact-title">Role:</span>
<span class="contact-email"><?php  echo htmlentities($row->user_role);?></span>
</div>
 <div class="email-content">
 <span class="contact-title">Email:</span>
<span class="contact-email"><?php  echo htmlentities($row->Email);?></span>
</div>

<div class="email-content">
 <span class="contact-title">Mobile Number:</span>
<span class="contact-email"><?php  echo htmlentities($row->MobileNumber);?></span>
</div>

<div class="birthday-content">
 <span class="contact-title">Date of Birth:</span>
    <span class="birth-date"><?php  echo htmlentities($row->Dob);?> </span>
 </div>

 
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div><?php $cnt=$cnt+1;}} ?>  
</div>
</div>
 </div>

</div>
</div>
</div>
    </div> 
    
 

  

  <!-- scripts for teachers -->
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

<?php }?>