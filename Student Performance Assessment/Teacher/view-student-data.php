<?php
session_start();
error_reporting(0);
include('../dbconnection.php');

if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}

$studentDataTable = $_GET['studentDataTable'];
$enrollID = isset($_GET['enrollID'])? intval($_GET['enrollID']) : 0;


// Fetch data from $studentDataTable table
$query = $dbh->prepare("SELECT * FROM $studentDataTable");
$query->execute();
$studentsData = $query->fetchAll(PDO::FETCH_ASSOC);

// Delete Data
if (isset($_POST['delete_student'])) {
     $studentID = $_POST['RollNumber']; // Get the student ID
     // Delete the corresponding record from the database
         $stmt = $dbh->prepare("DELETE FROM $studentDataTable WHERE RollNumber = :roll_number AND Name IS NULL");
        $stmt->bindParam(':roll_number', $studentID);
        $stmt->execute();
     $stmt = $dbh->prepare("UPDATE $studentDataTable SET Name = NULL WHERE RollNumber = :roll_number");
     $stmt->bindParam(':roll_number', $studentID);
     $stmt->execute();
     

 
     // Redirect after deleting data
    
     echo "<script>alert('Student data Deleted successfully.')</script>";
     echo "<script>window.location.href ='view-student-data.php?studentDataTable=" . urlencode($studentDataTable) . "&enrollID=" . urlencode($enrollID) . "'</script>";
     exit();
 }
 
 // Save Changes (Update Data)
 if (isset($_POST['save_changes'])) {
     $existingRollNumbers = array(); // Array to store existing roll numbers
     $newStudents = array(); // Array to store new student data
     $updatedStudents = array(); // Array to store updated student data
 
     // Loop through submitted data to update database
     foreach ($_POST as $key => $value) {
         // Check if the key corresponds to a student name
         if (strpos($key, 'name_') === 0) {
             $index = substr($key, 5); // Extract the index
             $studentName = $_POST[$key]; // Get the student name
 
             // Check if the roll number already exists
             if (in_array($index, $existingRollNumbers)) {
                 echo "<script>alert('Roll number $index already exists. Please provide a unique roll number.')</script>";
             } else {
                 // If roll number is unique, add student data to the appropriate array
                 if (isset($studentsData[$index - 1])) {
                     // Existing student data, update name
                     $updatedStudents[$index] = $studentName;
                 } else {
                     // New student data, add to array
                     $newStudents[$index] = $studentName;
                 }
                 $existingRollNumbers[] = $index; // Add roll number to the existing roll numbers array
             }
         }
     }
 
    // Update existing students
foreach ($updatedStudents as $index => $studentName) {
     $stmt = $dbh->prepare("UPDATE $studentDataTable SET Name = :name WHERE  RollNumber = :roll_number");
     $stmt->bindParam(':name', $studentName);
     $stmt->bindParam(':roll_number', $index); // <-- Change this to use RollNumber
     $stmt->execute();
 }
 
 
     // Insert new students
     foreach ($newStudents as $index => $studentName) {
         $stmt = $dbh->prepare("INSERT INTO $studentDataTable (RollNumber, Name) VALUES (:roll_number, :name)");
         $stmt->bindParam(':roll_number', $index);
         $stmt->bindParam(':name', $studentName);
         $stmt->execute();
     }
 
     echo "<script>alert('Student data saved successfully.')</script>";
     echo "<script>window.location.href ='co-attainment-calculation.php?enrollID=" . $enrollID . "'</script>";
     exit();
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
    <title>Student Data</title>
    <!-- Styles -->
    <link href="../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
     /* CSS for input fields */
input[type="text"] {
    border: none;
    background-color: #f0f0f0;
    padding: 10px;
    width: 100%;
    border-radius: 5px;
    margin-bottom: 10px;
}

/* CSS for labels */
label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

    </style>
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
                            <h1>Student Data</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 p-l-0 title-margin-left">
                    <div class="page-header">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="teacher_frontend.php">Dashboard</a></li>
                                <li class="active">Student Data</li>
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
                            <h4>Student Data</h4>
                        </div>
                           
                        <div class="row">
    <div class="col-md-7">
        <div class="basic-form">
            <div class="form-group">
                <label>Total Students: <span id="total_students"><?php echo count($studentsData); ?></span></label>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="basic-form">
            <button class="btn btn-success bg-warning" id="add-student">Add Student</button>
        </div>
    </div>
</div>



                            
<table class="table">
    <thead>
        <tr>
            <th>Roll Number</th>
            <th>Student Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="student-fields">
        <?php foreach ($studentsData as $index => $student) { ?>
            <tr>
            <td>
            <input type="number" class="form-control border-none input-flat bg-ash roll-number" name="roll_<?php echo $index + 1; ?>" value="<?php echo $student['RollNumber']; ?>" readonly>
        </td>
        <td>
            <input type="text" class="form-control border-none input-flat bg-ash student-name" name="name_<?php echo $index + 1; ?>" value="<?php echo $student['Name']; ?>" placeholder="Enter Student Name" required>
        </td>
                <td>
                    <!-- Delete Form -->
                    <form method="post" action="">
                    <input type="hidden" name="RollNumber" value="<?php echo $student['RollNumber']; ?>">

                    <div class="text-center">
                        <button class="btn btn-danger delete-btn" type="submit" name="delete_student">Delete</button>
                    </div>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<button class="btn btn-default btn-lg m-b-10 bg-warning border-none m-r-5 sbmt-btn" type="submit" name="save_changes">Save Changes</button>
<button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" type="reset">Reset</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add Student button click event
    document.getElementById('add-student').addEventListener('click', function() {
        var totalStudentsSpan = document.getElementById('total_students');
        var totalStudents = parseInt(totalStudentsSpan.textContent);
        var newRow = '<tr>' +
        '<td>' +
    '<input type="number" class="form-control border-none input-flat bg-ash roll-number" name="roll_' + (totalStudents + 1) + '" value="' + (totalStudents + 1) + '" readonly>' +
'</td>' +
'<td>' +
    '<input type="text" class="form-control border-none input-flat bg-ash student-name" name="name_' + (totalStudents + 1) + '" placeholder="Enter Student Name" required>' +
'</td>' +
'<td style="display: flex; justify-content: center;">' +
    '<button class="btn btn-danger delete-btn" type="submit" name="delete_student">Delete</button>' +
'</td>' +
'</tr>';

        document.getElementById('student-fields').insertAdjacentHTML('beforeend', newRow);
        totalStudentsSpan.textContent = totalStudents + 1;
    });

    // Delete row button click event
    document.querySelectorAll('.delete-row').forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.closest('tr');
            row.parentNode.removeChild(row);
            var totalStudentsSpan = document.getElementById('total_students');
            totalStudentsSpan.textContent = parseInt(totalStudentsSpan.textContent) - 1;
        });
    });

</script>


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
</body>
</html>
