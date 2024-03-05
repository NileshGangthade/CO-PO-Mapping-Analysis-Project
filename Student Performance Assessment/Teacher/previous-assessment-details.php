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
 
 $ocasaid=$_SESSION['tsasaid'];
 $cid = $_SESSION['Course'];
  $sfname=$_POST['sfname'];
  $ssname=$_POST['ssname'];
  $subcode=$_POST['subcode'];
 
 $sql="insert into tblsubject(CourseID,SubjectFullname,SubjectShortname,SubjectCode)values(:cid,:sfname,:ssname,:subcode)";
 $query=$dbh->prepare($sql);
 $query->bindParam(':cid',$cid,PDO::PARAM_STR);
 $query->bindParam(':sfname',$sfname,PDO::PARAM_STR);
 $query->bindParam(':ssname',$ssname,PDO::PARAM_STR);
 $query->bindParam(':subcode',$subcode,PDO::PARAM_STR);
  $query->execute();
 
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
     echo '<script>alert("Subject has been added.")</script>';
 echo "<script>window.location.href ='subject.php'</script>";
   }
   else
     {
          echo '<script>alert("Something Went Wrong. Please try again")</script>';
     }
 
   
 }
 // Code for deleting product from cart
 if(isset($_GET['delid']))
 {
 $rid=intval($_GET['delid']);
 $sql="delete from tblsubject where ID=:rid";
 $query=$dbh->prepare($sql);
 $query->bindParam(':rid',$rid,PDO::PARAM_STR);
 $query->execute();
  echo "<script>alert('Data deleted');</script>"; 
   echo "<script>window.location.href = 'subject.php'</script>";     
 
 
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
                                <h1>Previous Assessment Details</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">Previous Assessment Details</li>
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
                                    <h4>Previous Assessment Details</h4>
                                    
     
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
                                                    <th>Exam</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
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
ec.Exam,
sa.academic_year,
s.SubjectFullname
FROM 
enrolled_classes ec
INNER JOIN 
tblcourse c ON ec.CourseID = c.ID
INNER JOIN 
tblsuballocation sa ON ec.SuballocationID = sa.ID
INNER JOIN 
tblsubject s ON ec.SubID = s.ID";

$query = $dbh -> prepare($sql);
$query->execute();
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
                                                        <td><?php echo htmlentities($row->Exam);?></td>
                                                       <td>
                                                        <span><a href="edit-subject.php?editid=<?php echo htmlentities ($row->sid);?>"><i class="ti-pencil-alt color-success"></i></a></span>
                                                        <span><a href="subject.php?delid=<?php echo ($row->ID);?>"  onclick="return confirm('Do you really want to Delete ?');"><i class="ti-trash color-danger"></i> </a></span>
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