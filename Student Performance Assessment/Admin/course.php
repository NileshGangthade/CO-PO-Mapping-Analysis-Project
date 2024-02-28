<?php
session_start();
error_reporting(0);
include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Admin'  && $_SESSION['user_role'] != 'Principal') {
    header("Location: login.html");
    exit();
}
else{
     if(isset($_POST['submit']))
   {
 
 $tsasaid=$_SESSION['tsasaid'];
  $bname=$_POST['branchname'];
  $cname=$_POST['coursename'];
  
 
 $sql="insert into tblcourse(BranchName,CourseName)values(:branchname,:coursename)";
 $query=$dbh->prepare($sql);
 $query->bindParam(':branchname',$bname,PDO::PARAM_STR);
 $query->bindParam(':coursename',$cname,PDO::PARAM_STR);
 
  $query->execute();
 
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
     echo '<script>alert("Course has been added.")</script>';
 echo "<script>window.location.href ='course.php'</script>";
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
 $sql="delete from tblcourse where ID=:rid";
 $query=$dbh->prepare($sql);
 $query->bindParam(':rid',$rid,PDO::PARAM_STR);
 $query->execute();
  echo "<script>alert('Data deleted');</script>"; 
   echo "<script>window.location.href = 'course.php'</script>";     
 
 
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

  <title>Course Create</title>
  

  <!-- Courses css -->
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
      

      <!-- course -->

      <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Course</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="admin_frontend.php">Dashboard</a></li>
                                    <li class="active">Course</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card alert">
                                <div class="card-header pr">
                                    <h4>Create A New Course</h4>
                                    <form method="post" name="hjhgh">
                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Course Name</label>
        <input type="text" class="form-control border-none input-flat bg-ash" placeholder="Course Name" name="coursename" required="true">
                                            </div>
                                        </div>
                                        <div class="basic-form m-t-20">
                                            <div class="form-group">
                                                <label>Branch Name</label>
                                                <input type="text" class="form-control border-none input-flat bg-ash" placeholder="Branch Name" name="branchname" required="true">
                                            </div>
                                        </div>
                                   
                                </div>
                                <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Save</button>
                                <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button> 
                            </form>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card alert">
                                <div class="card-header pr">
                                    <h4>ALL Course</h4>
                                    
                               
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table student-data-table m-t-20">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Course Name</th>
                                                    <th>Branch Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
$sql="SELECT * from tblcourse";
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
                                                    <td>
                                                        <?php  echo htmlentities($row->CourseName);?>
                                                    </td>
                                                    <td>
                                                        <?php  echo htmlentities($row->BranchName);?>
                                                    </td>
                                                   
                                                    <td>
                                                       
                                                        <span><a href="edit-course.php?editid=<?php echo htmlentities ($row->ID);?>"><i class="ti-pencil-alt color-success"></i></a></span>
                                                        <span><a href="course.php?delid=<?php echo ($row->ID);?>"  onclick="return confirm('Do you really want to Delete ?');"><i class="ti-trash color-danger"></i> </a></span>
                                                    </td>
                                                </tr>
                                                 <?php $cnt=$cnt+1;}} ?> 
                                            
                                             

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
    </div>

    
 

  <!-- scripts for courses -->
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