<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
} else {
     if(isset($_POST['submit']))
   {

    $eid=$_GET['editid'];
$propic=$_FILES["propic"]["name"];
$extension = substr($propic,strlen($propic)-4,strlen($propic));
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Profile Pics has Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}
else
{

$propic=md5($propic).time().$extension;
 move_uploaded_file($_FILES["propic"]["tmp_name"],"../assets/ProfilePic/".$propic);

 try {
    // Start a transaction
    $dbh->beginTransaction();

    // Update the first table
    $sql1 = "UPDATE teachers_data SET ProfilePic=:propic WHERE ID=:eid";
    $query1 = $dbh->prepare($sql1);
    $query1->bindParam(':propic', $propic, PDO::PARAM_STR);
    $query1->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query1->execute();

    // Update the second table
    $sql2 = "UPDATE users_login SET ProfilePic=:propic WHERE ID=:eid";
    $query2 = $dbh->prepare($sql2);
    $query2->bindParam(':propic', $propic, PDO::PARAM_STR);
    $query2->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query2->execute();

    // If all queries are successful, commit the transaction
    $dbh->commit();

    // Redirect or show success message
    echo '<script>alert("Profile pic has been updated")</script>';
    echo "<script>window.location.href = 'teacher_frontend.php'</script>";
    exit();
} catch (PDOException $e) {
    // If any error occurs, roll back the transaction
    $dbh->rollback();

    // Handle the error (e.g., display an error message)
    echo "Error: " . $e->getMessage();
    exit();
}
  
}}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  
    <title>TSAS : Update Profile Pic </title>

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

    <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Update Profile Picture</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
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
$sql="SELECT tblcourse.ID as cid,tblcourse.BranchName,tblcourse.CourseName,teachers_data.ID,teachers_data.EmpID,teachers_data.FirstName,teachers_data.LastName,teachers_data.MobileNumber,teachers_data.Email,teachers_data.Dob,teachers_data.CourseID, teachers_data.ProfilePic from teachers_data join tblcourse on tblcourse.ID=teachers_data.CourseID where teachers_data.ID=$eid";
$query = $dbh -> prepare($sql);
$query->execute();
$results=$query->fetchAll(PDO::FETCH_OBJ);

$cnt=1;
if($query->rowCount() > 0)
{
foreach($results as $row)
{               ?>  
                            <div class="card-header m-b-20">
                                <h4>Profile Pic</h4>
                            </div>
                           
                            <div class="row">
                               
                               
                              
                            </div>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="basic-form">
                                        <div class="form-group image-type">
                                            <label>Old Teacher Photo</label>
                                            <img src="../assets/ProfilePic/<?php echo $row->ProfilePic;?>" width="100" height="100" value="<?php  echo $row->ProfilePic;?>">
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="basic-form">
                                        <div class="form-group image-type">
                                            <label>New Teacher Photo</label>
                                            <input type="file" name="propic" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div><?php $cnt=$cnt+1;}} ?>
                            <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Update</button>
                            <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                        </form>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
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

</html><?php }  ?>