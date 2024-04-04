<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
}else{
$enrollID = isset($_GET['enrollID'])? intval($_GET['enrollID']) : 0;


 $userid = $_SESSION['ID'];
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
            sa.academic_year,
            s.SubjectFullname,
            s.SubjectCode,
            ec.UnitTests,
            ec.Prelims,
            ec.Assignments,
            ec.Experiments,
            ec.TableName
        FROM 
            enrolled_classes ec
        INNER JOIN 
            tblcourse c ON ec.CourseID = c.ID
        INNER JOIN 
            tblsuballocation sa ON ec.SuballocationID = sa.ID
        INNER JOIN 
            tblsubject s ON ec.SubID = s.ID 
        WHERE 
            ec.ID = ?";
$query = $dbh->prepare($sql);
$query->execute([$enrollID]);
$result = $query->fetch(PDO::FETCH_OBJ);
$tableName = $result->TableName;

 // Check if the student data table exists
    $studentDataTable = $result->TableName . "_student_data";
    $checkTableSql = "SHOW TABLES LIKE '$studentDataTable'";
    $checkTableQuery = $dbh->query($checkTableSql);
    $tableExists = $checkTableQuery->rowCount() > 0;


    // If the student data table doesn't exist, display a warning message
    if (!$tableExists) {
        echo "<script>alert('Student data table not found. Please add student data first.')</script>";
        echo "<script>window.location.href ='add-students.php?TableName={$result->TableName}'</script>";
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

  <title>CO attainment calculation</title>
  

  <!-- subject css -->
         <!-- Styles -->

         <style>
        table.student-data-table th {
            white-space: nowrap; 
           
        }
        
        
        table.student-data-table th a {
            /* size: 1px; */
            font-size: 15px;
            display: block; 
            color: inherit;
            text-decoration: none;
        }
    </style>
    <link href="../assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="../assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="../assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/lib/unix.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <style>
        /* Style for the table */
#direct-attainment-table {
    width: 100%;
    border-collapse: collapse;
}

/* Style for table header */
#direct-attainment-table th {
    text-align: center;
    padding: 10px;
    background-color: #f2f2f2; /* Light gray background */
}

/* Style for table body */
#direct-attainment-table td {
    text-align: center;
    padding: 10px;
}

/* Style for CO column */
.column-co {
    width: 100px; /* Adjust width as needed */
}

/* Style for test type columns */
.column-test-type {
    width: 150px; /* Adjust width as needed */
}

/* Style for CIE and ESE columns */
.column-cie,
.column-ese {
    width: 80px; /* Adjust width as needed */
}

/* Style for Attainment column */
.column-attainment {
    font-weight: bold;
    background-color: #f0f8ff; /* Light blue background */
}

/* Style for Attainment value */
.column-attainment-value {
    font-weight: bold;
    background-color: #87ceeb; /* Light green background */
}

/* Style for Weightage column */
.column-weightage {
    font-weight: bold;
    background-color: #ffebcd; /* Light yellow background */
}

    </style>
    
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
                                <h1>CO attainment calculation</h1>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active">CO attainment calculation</li>
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
                               <h4> <?php echo htmlentities($result->SubjectFullname); ?> ( <?php echo htmlentities($result->SubjectCode); ?> ) </h4>
   
     
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                    <div class="fixed-size-table-wrapper">


                                    <table class="table student-data-table m-t-20">
                                        <thead>
                                            <tr>
                                            <th style="padding-right: 20px;">
                                                <a href="#"> Student Data </a> <br>
                                                <a href="view-student-data.php?studentDataTable=<?php echo $studentDataTable; ?>&enrollID=<?php echo $enrollID; ?>" class="btn btn-default btn-lg m-b-10 bg-alert  m-r-5 sbmt-btn" style="border-radius: 10px;">View</a>
                                            </th>
                                             
                                                <?php
                                                    // Generate Unit Test columns
                                                    for ($i = 1; $i <= $result->UnitTests; $i++) {
                                                       //  echo '<th><a href="#">Unit Test-' . $i . '</a></th>';
                                                       echo '<th style="padding-right: 20px;">
                                                                <a href="#">Unit Test-' . $i . '</a><br>
                                                                <a href="marks-view.php?tableName=' . urlencode($result->TableName) . '&test=' . urlencode('UnitTest_' . $i) . '&enrollID=' . $enrollID . '" class="btn btn-default btn-lg m-b-10 btn-info m-r-5 sbmt-btn" style="border-radius: 10px;">View</a>
                                                            </th>';

                                               
                                                    }

                                                    // Generate Prelim columns
                                                    for ($i = 1; $i <= $result->Prelims; $i++) {
                                                        echo '<th style="padding-right: 20px;">
                                                                <a href="#">Prelim-' . $i . '</a><br>
                                                                <a href="marks-view.php?tableName=' . urlencode($result->TableName) . '&test=' . urlencode('Prelim_' . $i) . '&enrollID=' . $enrollID . '" class="btn btn-default btn-lg m-b-10 btn-info m-r-5 sbmt-btn" style="border-radius: 10px;">View</a>
                                                            </th>';
                                                    }

                                                    // Generate Assignment columns
                                                    for ($i = 1; $i <= $result->Assignments; $i++) {
                                                        echo '<th style="padding-right: 20px;">
                                                                <a href="#">Assignment-' . $i . '</a><br>
                                                                <a href="marks-view.php?tableName=' . urlencode($result->TableName) . '&test=' . urlencode('Assignment_' . $i) . '&enrollID=' . $enrollID . '" class="btn btn-default btn-lg m-b-10 btn-info m-r-5 sbmt-btn" style="border-radius: 10px;">View</a>
                                                            </th>';
                                                    }

                                                    // Generate Experiment columns
                                                    for ($i = 1; $i <= $result->Experiments; $i++) {
                                                        echo '<th style="padding-right: 20px;">
                                                                <a href="#">Experiment-' . $i . '</a><br>
                                                                <a href="marks-view.php?tableName=' . urlencode($result->TableName) . '&test=' . urlencode('Experiment_' . $i) . '&enrollID=' . $enrollID . '" class="btn btn-default btn-lg m-b-10 btn-info m-r-5 sbmt-btn" style="border-radius: 10px;">View</a>
                                                            </th>';
                                                    }

                                                ?>
                                            </tr>
                                        </thead>

        <tbody>
          

        </tbody>
                                               
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                   
                </div>

                <div id="main-content">
                    <div class="row">
                        
                        <div class="col-md-8" style="width: 96% ">
                            <div class="card alert">
                                <div class="card-header pr">
                               <h4> Final Attainment of <?php echo htmlentities($result->SubjectFullname); ?> ( <?php echo htmlentities($result->SubjectCode); ?> ) </h4>
   
                                </div>
                                <div class="card-body">
                                <div class="table-responsive">
                                <div class="fixed-size-table-wrapper">
                        <div class="container">                                  
                                    <?php
                                    $testTypes = array();

                                    // Generate test types based on the fetched data
                                    for ($i = 1; $i <= $result->UnitTests; $i++) {
                                        $testTypes[] = 'UnitTest_' . $i;
                                    }
                                    
                                    for ($i = 1; $i <= $result->Prelims; $i++) {
                                        $testTypes[] = 'Prelim_' . $i;
                                    }
                                    
                                    for ($i = 1; $i <= $result->Assignments; $i++) {
                                        $testTypes[] = 'Assignment_' . $i;
                                    }
                                    
                                    for ($i = 1; $i <= $result->Experiments; $i++) {
                                        $testTypes[] = 'Experiment_' . $i;
                                    }

                                    // Initialize combined data array
$combinedData = array();

// Loop through each test type
foreach ($testTypes as $test) {
    // Construct the CO Attainment table name
    $coAttainmentTableName = $tableName . '_' . $test . '_coAttainment';

    // Check if the CO Attainment table exists
    $checkCoAttainmentTableSql = "SHOW TABLES LIKE ?";
    $checkCoAttainmentTableQuery = $dbh->prepare($checkCoAttainmentTableSql);
    $checkCoAttainmentTableQuery->execute([$coAttainmentTableName]);
    $coAttainmentTableExists = $checkCoAttainmentTableQuery->rowCount() > 0;

    if ($coAttainmentTableExists) {
        // Table exists, fetch CO and Attainment data
        $coAttainmentData = array();

        $fetchCoAttainmentSql = "SELECT CO, Attainment FROM $coAttainmentTableName";
        $fetchCoAttainmentQuery = $dbh->query($fetchCoAttainmentSql);
        while ($row = $fetchCoAttainmentQuery->fetch(PDO::FETCH_ASSOC)) {
            $co = $row['CO'];
            $coAttainmentData[$row['CO']] = $row['Attainment'];
        }

        // Store CO and Attainment data for the test type
        $combinedData[$test] = $coAttainmentData;
    }
}

// var_dump($combinedData);
?>


<?php 
// Start building the HTML table
echo '<div id="table-container">';
$testTypes = array_keys($combinedData); // Assuming $combinedData is already populated
$testTypeSequence = array_keys($combinedData); // Assuming $combinedData is already populated

echo "<table id='direct-attainment-table' class='table table-bordered fixed-size-table'>";
echo "<tr>";
echo "<th class='column-co'>CO</th>";

foreach ($testTypeSequence as $testType) {
    echo "<th class='column-test-type'>" . str_replace('_', ' - ', $testType) . "</th>";
}
echo "<th class='column-cie'>CIE</th>";
echo "<th class='column-ese'>ESE</th>";
echo "</tr>";

$cieTotal = 0;
$eseTotal = 0;
$rowCount = 0;

// Create an array to store COs and their corresponding Attainment data for each test type
$coAttainmentByTestType = [];

// Organize COs and Attainment data by test type
foreach ($combinedData as $testType => $coData) {
    foreach ($coData as $co => $attainment) {
        // If CO already exists for the current test type, append Attainment data
        if (isset($coAttainmentByTestType[$co])) {
            $coAttainmentByTestType[$co][$testType] = $attainment;
        } else { // Otherwise, create a new entry
            $coAttainmentByTestType[$co] = [$testType => $attainment];
        }
    }
}

// Sort COs in increasing order
ksort($coAttainmentByTestType);

// Display the table
foreach ($coAttainmentByTestType as $co => $testTypeData) {
    echo "<tr>";
    echo "<td class='column-co'>CO" . $co . "</td>"; // Display CO

    foreach ($testTypeSequence as $testType) {
        if (isset($testTypeData[$testType])) {
            echo "<td class='column-test-type'>" . $testTypeData[$testType] . "</td>"; // Display Attainment data for the test type
        } else {
            echo "<td class='column-test-type'></td>";
        }
    }

    // Calculate and display average
    $average = array_sum($testTypeData) / count($testTypeData);
    echo "<td class='column-cie'>" . round($average, 2) . "</td>";

    // Display fixed values for CIE and ESE
    echo "<td class='column-ese'>3</td>";

    // Update total counts
    $cieTotal += $average;
    $eseTotal += 3;
    $rowCount++;

    echo "</tr>";
}

$cieAverage = $cieTotal / $rowCount;
$eseAverage = $eseTotal / $rowCount;

$cieWeighted = $cieAverage * 0.3;
$eseWeighted = $eseAverage * 0.7;
$finalDirectCourseAttainment = $cieWeighted + $eseWeighted;

echo "<tr>";
echo "<td colspan='" . (count($testTypeSequence) + 1) . "' class='column-attainment'>Attainment</td>";
echo "<td class='column-cie'>" . round($cieAverage, 2) . "</td>";
echo "<td class='column-ese'>" . round($eseAverage, 2) . "</td>";
echo "</tr>";

// Add WEIGHTAGE row
echo "<tr>";
echo "<td colspan='" . (count($testTypeSequence) + 1) . "' class='column-weightage'>Weightage</td>";
echo "<td class='column-cie'>30%</td>";
echo "<td class='column-ese'>70%</td>";
echo "</tr>";

// Add DIRECT TOTAL ATTAINMENT row
echo "<tr>";
echo "<td colspan='" . (count($testTypeSequence) + 1) . "' class='column-attainment'>Direct Total Attainment</td>";
echo "<td class='column-cie'>" . round($cieWeighted, 2) . "</td>";
echo "<td class='column-ese'>" . round($eseWeighted, 2) . "</td>";
echo "</tr>";

// Add FINAL DIRECT COURSE ATTAINMENT row
echo "<tr>";
echo "<td colspan='" . (count($testTypeSequence) + 1) . "' class='column-attainment'>Final Direct Course Attainment</td>";
echo "<td colspan='2' class='column-attainment-value'>" . round($finalDirectCourseAttainment, 2) . "</td>"; // Set colspan to 2 for the value cell
echo "</tr>";

echo "</table>";



echo '<br>';
echo '<br>';

// Assign fetched values to JavaScript variables
$department = htmlentities($result->BranchName); // Use BranchName for Department
$academicYear = htmlentities($result->academic_year);
$subjectFullname = htmlentities($result->SubjectFullname);
$subjectCode = htmlentities($result->SubjectCode);
$className = htmlentities($result->Year); // Assuming Year represents the class name
$division = htmlentities($result->Division);
$semester = htmlentities($result->Sem);

echo '<script>
    function printTable() {
        var printContents = document.getElementById("direct-attainment-table").outerHTML;
        var originalContents = document.body.innerHTML;
        var collegeLogo = \'<img src="../assets/images/college_logo.jpeg" alt="College Logo"  style=" width:100%; border:none">\';
        
        // Use fetched values here
        var department = "' . $department . '"; // Department
        var academicYear = "' . $academicYear . '"; // Academic Year
        var subjectInfo = "' . $subjectFullname . ' (' . $subjectCode . ')"; // Subject
        var classInfo = "' . $className . ' '. 'Year'. '"; // Class
        var division = "' . $division . '"; // Division
        var semester = "' . $semester . '"; // Semester

        var header = collegeLogo + "<div style=\'text-align: center; font-size: 18px;\'>Department : " + department + "  |  Academic Year : " + academicYear + "  |  Subject : " + subjectInfo + "</div>";
        header += "<div style=\'text-align: center; font-size: 18px;\'>Class : " + classInfo + "  |  Division : " + division + "  |  Semester : " + semester + "</div>";

        document.body.innerHTML = header + printContents;
        setTimeout(function() {
            window.print();
            document.body.innerHTML = originalContents;
        }, 1000);
    }
</script>';

?>

                            


                                    
                                </div>
                                </div>
                                </div>
                                </div>
                                <br>
<br>
<button class="btn btn-primary btn-lg m-b-10 border-none m-r-5 sbmt-btn" onclick="exportData()">Export In Excel</button>
<button class="btn btn-default btn-lg m-b-10 m-l-5 sbmt-btn" onclick="printTable()">Print</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      </div>
      

      <!-- Scripts for Export Data in excel -->

      <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>


      <script>
        function exportData() {
    /* Get table HTML */
    var table = document.getElementById('direct-attainment-table');
    
    /* Convert table to worksheet */
    var ws = XLSX.utils.table_to_sheet(table);
    
    /* Create workbook and add the worksheet */
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    
    /* Save workbook */
    XLSX.writeFile(wb, '<?php echo htmlentities($subjectFullname) . " (" . htmlentities($subjectCode) . ") Final_attainment.xlsx"; ?>');
}

      </script>

  

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