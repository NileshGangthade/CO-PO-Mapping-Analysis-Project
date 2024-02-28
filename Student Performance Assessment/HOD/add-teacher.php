<?php
session_start();
error_reporting(0);
include('../dbconnection.php');
if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
}

else{
     if(isset($_POST['submit']))
   {
 
 
 // Getting input data
 $fname = $_POST['fname'];
 $lname = $_POST['lname'];
 $mobnum = $_POST['mobnum'];
 $email = $_POST['email'];
 $empid = $_POST['empid'];
 $dob = $_POST['dob'];
 $cid = $_POST['cid'];
 $user_role = $_POST['user_role'];

 // Generating password
//  $password = strtolower($fname) . '#' . str_replace('/', '', substr($dob, 0, 10));
// Generate the password
$password_plain = strtolower($fname) . '#' . str_replace('-', '', date('dmY', strtotime($dob)));

// Encrypt the password
$password = password_hash($password_plain, PASSWORD_DEFAULT);


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
 $ret="select Email from teachers_data where Email=:email || MobileNumber=:mobnum || EmpID=:empid";
  $query= $dbh -> prepare($ret);
 $query->bindParam(':empid',$empid,PDO::PARAM_STR);
 $query->bindParam(':mobnum',$mobnum,PDO::PARAM_STR);
 $query->bindParam(':email',$email,PDO::PARAM_STR);
//  $query->bindParam(':user_role',$user_role,PDO::PARAM_STR);

 $query-> execute();
      $results = $query -> fetchAll(PDO::FETCH_OBJ);
 if($query -> rowCount() == 0)
 {
 
$sql="insert into teachers_data(EmpID,FirstName,LastName,MobileNumber,Email,user_role,Dob,CourseID,ProfilePic) values(:empid,:fname,:lname,:mobnum,:email,:user_role,:dob,:cid,:propic)";
$query=$dbh->prepare($sql);
 $query->bindParam(':empid',$empid,PDO::PARAM_STR);
 $query->bindParam(':fname',$fname,PDO::PARAM_STR);
 $query->bindParam(':lname',$lname,PDO::PARAM_STR);
 $query->bindParam(':mobnum',$mobnum,PDO::PARAM_STR);
 $query->bindParam(':email',$email,PDO::PARAM_STR);
 $query->bindParam(':user_role',$user_role,PDO::PARAM_STR);
 $query->bindParam(':dob',$dob,PDO::PARAM_STR); // Bind the :dob parameter
 $query->bindParam(':cid',$cid,PDO::PARAM_STR);
 $query->bindParam(':propic',$propic,PDO::PARAM_STR);
  $query->execute();
 
    $LastInsertId=$dbh->lastInsertId();
    if ($LastInsertId>0) {
         // Inserting data into users_login
         $sql = "INSERT INTO users_login (EmpID, FirstName, LastName, Email, Course, user_role, ProfilePic, Password) VALUES (:empid, :fname, :lname, :email, :cid, :user_role, :propic, :password)";
         $query = $dbh->prepare($sql);
         $query->bindParam(':empid',$empid,PDO::PARAM_STR);         
         $query->bindParam(':fname', $fname, PDO::PARAM_STR);
         $query->bindParam(':lname', $lname, PDO::PARAM_STR);
         $query->bindParam(':email', $email, PDO::PARAM_STR);
         $query->bindParam(':cid', $cid, PDO::PARAM_STR);
         $query->bindParam(':user_role', $user_role, PDO::PARAM_STR);
         $query->bindParam(':propic', $propic, PDO::PARAM_STR);
         $query->bindParam(':password', $password, PDO::PARAM_STR);
         $query->execute();

     echo '<script>alert("Teacher detail has been added.")</script>';
 echo "<script>window.location.href ='add-teacher.php'</script>";
   }
   else
     {
          echo '<script>alert("Something Went Wrong. Please try again")</script>';
     }
 
   
 }
 else
 {
 
 echo "<script>alert('Email-id,Employee Id or Mobile Number already exist. Please try again');</script>";
 }
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

  <title>Add Teacher</title>
  

  <!-- Add Teacher css -->
  <!-- Styles -->
 
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

 
  <!-- <h1>Welcome to the Admin dashboard</h1> -->
      

      <!-- Add teachers -->
      

      <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                                <h1>Add Teacher</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="hod_frontend.php">Dashboard</a></li>
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
                            <div class="card-header m-b-20">
                                <h4>Teacher Information</h4>
     
                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="fname" required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="lname" required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="mobnum" maxlength="10" pattern="[0-9]+" required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" class="form-control border-none input-flat bg-ash" name="email" required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                              
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="date" class="form-control calendar bg-ash"  name="dob" required="true">
                                            <span class="ti-calendar form-control-feedback booking-system-feedback m-t-30"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Emp ID</label>
                                            <input type="text" class="form-control border-none input-flat bg-ash" name="empid" required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>User Role</label>
                                            <select class="form-control border-none input-flat bg-ash" name="user_role" required="true">
                                                <option value="">Select Role</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Principal">Principal</option>
                                                <option value="HOD">HOD</option>
                                                <option value="Professor">Professor</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Course</label>
                                            <select class="form-control border-none input-flat bg-ash" name="cid" required="true">
            <option value="">Select Course</option>
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
            <option value="<?php  echo htmlentities($row->ID);?>"><?php  echo htmlentities($row->CourseName);?>(<?php  echo htmlentities($row->BranchName);?>)</option><?php $cnt=$cnt+1;}} ?>
        </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="basic-form">
                                        <div class="form-group image-type">
                                            <label>Upload Teacher Photo <span>(150 X 150)</span></label>
                                            <input type="file" name="propic" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Save</button>
                            <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                        </form>

                        </div>
                    </div>

                    <!-- Add teachers from Excel file -->
<div class="card alert">
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            <div class="card-header m-b-20">
                <h4>Add Teachers Data from Excel File</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="basic-form">
                        <div class="form-group">
                            <label>Upload Excel File</label>
                            <input type="file" name="file" required accept=".xls, .xlsx">
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="upload">Upload</button>
        </form>
    </div>
</div>

<!-- PHP code for handling uploaded Excel file -->
<?php
if (isset($_POST['upload'])) {
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_ext = strtolower(end(explode('.', $file_name)));
    $extensions = array("xls", "xlsx");

    if (in_array($file_ext, $extensions) === false) {
        echo "<script>alert('Extension not allowed, please choose a valid Excel file.')</script>";
    } else {
        // Load PHPExcel library if not already loaded
        require_once '../PHPExcel/Classes/PHPExcel.php';

        $objPHPExcel = PHPExcel_IOFactory::load($file_tmp);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestDataRow();

        for ($row = 2; $row <= $highestRow; $row++) {
            $fname = $sheet->getCellByColumnAndRow(0, $row)->getValue();
            $lname = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $mobnum = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $email = $sheet->getCellByColumnAndRow(3, $row)->getValue();
            $empid = $sheet->getCellByColumnAndRow(4, $row)->getValue();
            $dob = $sheet->getCellByColumnAndRow(5, $row)->getValue();
            $cid = $sheet->getCellByColumnAndRow(6, $row)->getValue();
            $user_role = $sheet->getCellByColumnAndRow(7, $row)->getValue();

            // Check if email or empid already exists
            $query = $dbh->prepare("SELECT * FROM teachers_data WHERE Email = :email OR EmpID = :empid");
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':empid', $empid, PDO::PARAM_STR);
            $query->execute();

            if ($query->rowCount() > 0) {
                echo "<script>alert('Email or Employee ID already exists for row $row.')</script>";
            } else {
                // Insert teacher data into teachers_data table
                $sql = "INSERT INTO teachers_data (EmpID, FirstName, LastName, MobileNumber, Email, user_role, Dob, CourseID) VALUES (:empid, :fname, :lname, :mobnum, :email, :user_role, :dob, :cid)";
                $query = $dbh->prepare($sql);
                $query->bindParam(':empid', $empid, PDO::PARAM_STR);
                $query->bindParam(':fname', $fname, PDO::PARAM_STR);
                $query->bindParam(':lname', $lname, PDO::PARAM_STR);
                $query->bindParam(':mobnum', $mobnum, PDO::PARAM_STR);
                $query->bindParam(':email', $email, PDO::PARAM_STR);
                $query->bindParam(':user_role', $user_role, PDO::PARAM_STR);
                $query->bindParam(':dob', $dob, PDO::PARAM_STR);
                $query->bindParam(':cid', $cid, PDO::PARAM_STR);
                $query->execute();

                // Insert user data into users_login table
                $password_plain = strtolower($fname) . '#' . str_replace('-', '', date('dmY', strtotime($dob)));
                $password = password_hash($password_plain, PASSWORD_DEFAULT);

                $sql_user = "INSERT INTO users_login (EmpID, FirstName, LastName, Email, Course, user_role, ProfilePic, Password) VALUES (:empid, :fname, :lname, :email, :cid, :user_role, '', :password)";
                $query_user = $dbh->prepare($sql_user);
                $query_user->bindParam(':empid', $empid, PDO::PARAM_STR);
                $query_user->bindParam(':fname', $fname, PDO::PARAM_STR);
                $query_user->bindParam(':lname', $lname, PDO::PARAM_STR);
                $query_user->bindParam(':email', $email, PDO::PARAM_STR);
                $query_user->bindParam(':cid', $cid, PDO::PARAM_STR);
                $query_user->bindParam(':user_role', $user_role, PDO::PARAM_STR);
                $query_user->bindParam(':password', $password, PDO::PARAM_STR);
                $query_user->execute();
            }
        }
        echo "<script>alert('Teachers data from Excel file uploaded successfully.')</script>";
    }
}
?>
                   
                </div>
            </div>
        </div>
    </div>

    

    
 

  <!-- scripts for add Teacher -->
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