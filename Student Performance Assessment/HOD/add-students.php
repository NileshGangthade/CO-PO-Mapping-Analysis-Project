<?php
session_start();
error_reporting(0);  
include('../dbconnection.php');
include ('../vendor/autoload.php');
 

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

  if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
}

$TableName = $_GET['TableName'];
$student_data_TableName = $TableName . "_student_data"; // Unique table name based on the subject

// Retrieve ID from enrolled_classes table
$query = $dbh->prepare("SELECT ID FROM enrolled_classes WHERE TableName = :tableName");
$query->bindParam(':tableName', $TableName, PDO::PARAM_STR);
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "<script>alert('ID not found for the given TableName.')</script>";
    exit(); // Exit if ID not found
}

$enrollID = $row['ID']; // Assign the retrieved ID to enrollID



if (isset($_POST['submit'])) {

    // Create table if not exists
    $createTableSQL = "CREATE TABLE IF NOT EXISTS $student_data_TableName (
        RollNumber INT(20) PRIMARY KEY,
        Name VARCHAR(50)
    )";
$dbh->exec($createTableSQL);
    $numStudents = $_POST['num_students'];

    // Loop through each student
    for ($i = 1; $i <= $numStudents; $i++) {
        $rollNumber = $_POST['roll_' . $i]; // Assuming the input name is 'roll_' + $i
        $name = $_POST['name_' . $i]; // Assuming the input name is 'name_' + $i

        // Check if roll number already exists
        $query = $dbh->prepare("SELECT * FROM $student_data_TableName WHERE RollNumber = :rollNumber");
        $query->bindParam(':rollNumber', $rollNumber, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo "<script>alert('Roll number already exists for student $i.')</script>";
        } else {
            // Insert student data into students_data table
            $sql = "INSERT INTO $student_data_TableName (RollNumber, Name) VALUES (:rollNumber, :name)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':rollNumber', $rollNumber, PDO::PARAM_STR);
            $query->bindParam(':name', $name, PDO::PARAM_STR);
            $query->execute();
        }
    }
    echo "<script>alert('Student data saved successfully.')</script>";
    echo "<script>window.location.href ='co-attainment-calculation.php?enrollID=$enrollID'</script>";
    exit();
}

 // PHP code for handling uploaded Excel file
 if (isset($_POST['save_excel_data'])) {

    // Create table if not exists
    $createTableSQL = "CREATE TABLE IF NOT EXISTS $student_data_TableName (
        RollNumber INT(20) PRIMARY KEY,
        Name VARCHAR(50)
    )";
$dbh->exec($createTableSQL);
    

     $fileName = $_FILES['import_file']['name'];
     $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
     $allowed_ext = ['xls', 'csv', 'xlsx'];
 
     if (in_array($file_ext, $allowed_ext)) {
         $inputFileNamePath = $_FILES['import_file']['tmp_name'];
         $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
         $data = $spreadsheet->getActiveSheet()->toArray();
         $count = 0; 
         foreach ($data as $row) {
            if ($count > 0) { // Skip the first row (column names)
                $rollNumber = (int)$row[0]; // Convert to integer
                $name = $row[1];
                 // Check if roll number already exists
                 $query = $dbh->prepare("SELECT * FROM $student_data_TableName WHERE RollNumber = :rollNumber");
                 $query->bindParam(':rollNumber', $rollNumber, PDO::PARAM_STR);
                 $query->execute();
 
                 if ($query->rowCount() > 0) {
                     echo "<script>alert('Roll number already exists for row $count.')</script>";
                 } else {
                     // Insert student data into students_data table
                     $sql = "INSERT INTO $student_data_TableName (RollNumber, Name) VALUES (:rollNumber, :name)";
                     $query = $dbh->prepare($sql);
                     $query->bindParam(':rollNumber', $rollNumber, PDO::PARAM_STR);
                     $query->bindParam(':name', $name, PDO::PARAM_STR);
                     $query->execute();
                 }
             }
             $count++;
            
         }
         echo "<script>alert('Student data uploaded successfully.')</script>";
         echo "<script>window.location.href ='co-attainment-calculation.php?enrollID=$enrollID'</script>";
         exit();
     } else {
         echo "<script>alert('Invalid File')</script>";
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
    <title>Add Student Data</title>
    <!-- Styles -->
    <link href="../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
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
                            <h1>Add Student Data</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="teacher_frontend.php">Dashboard</a></li>
                                <li class="active">Add Student Data</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div id="main-content">
                <div class="card alert">
                    <div class="card-body">
                        <form name="" method="post" action="" enctype="multipart/form-data">
                            <div class="card-header m-b-20">
                                <h4>Students Data</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Total Students</label>
                                            <input type="number" class="form-control border-none input-flat bg-ash" name="num_students" required="true" min="1" placeholder="Enter total Students">
                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="student-fields">
                                </div>
                                <!-- Student fields will be dynamically generated here -->
                            </div>
                            </div>
                            
                            
                            <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="submit">Save</button>
                            <button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>
                        </form>
                    </div>
                </div>

                <!-- Add Students from Excel file -->
                <div class="card alert">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="card-header m-b-20">
                                <h4>Add Student Data from Excel File</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="basic-form">
                                        <div class="form-group">
                                            <label>Upload Excel File</label>
                                            <input type="file" name="import_file" required accept=".xls, .xlsx, .csv">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="save_excel_data">Import</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- scripts for dashboard -->
<script src="../assets/js/lib/jquery.min.js"></script>
<script src="../assets/js/lib/jquery.nanoscroller.min.js"></script>
<script src="../assets/js/lib/menubar/sidebar.js"></script>
<script src="../assets/js/lib/bootstrap.min.js"></script>
<script src="../assets/js/lib/circle-progress/circle-progress.min.js"></script>
<script src="../assets/js/lib/chartist/chartist.min.js"></script>
<script src="../assets/js/lib/sparklinechart/jquery.sparkline.min.js"></script>
<script src="../assets/js/lib/peitychart/jquery.peity.min.js"></script>
<script src="../assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
<script src="../assets/js/lib/morris-chart/raphael-min.js"></script>
<script src="../assets/js/scripts.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('input[name="num_students"]').addEventListener("change", function() {
            var numStudents = parseInt(this.value);
            var studentFields = document.getElementById("student-fields");
            studentFields.innerHTML = ""; // Clear previous fields
            for (var i = 1; i <= numStudents; i++) {
                var studentDiv = document.createElement("div");
                studentDiv.setAttribute("class", "col-md-6");
                var basicFormDiv = document.createElement("div");
                basicFormDiv.setAttribute("class", "basic-form");
                var formGroupDiv = document.createElement("div");
                formGroupDiv.setAttribute("class", "form-group");
                var label = document.createElement("label");
                label.innerHTML = "Roll No : " + i;
                var input = document.createElement("input");
                input.setAttribute("type", "text");
                input.setAttribute("class", "form-control border-none input-flat bg-ash");
                input.setAttribute("name", "name_" + i);
                input.setAttribute("placeholder", "Enter Student Name");
                input.setAttribute("required", "true");

                // Create hidden input field for roll number
                var rollInput = document.createElement("input");
                rollInput.setAttribute("type", "hidden");
                rollInput.setAttribute("name", "roll_" + i);
                rollInput.value = i; // Assuming the roll number is the index itself

                formGroupDiv.appendChild(label);
                formGroupDiv.appendChild(input);
                formGroupDiv.appendChild(rollInput); // Append the hidden input field
                basicFormDiv.appendChild(formGroupDiv);
                studentDiv.appendChild(basicFormDiv);
                studentFields.appendChild(studentDiv);
            }
        });
    });
</script>
</body>
</html>
