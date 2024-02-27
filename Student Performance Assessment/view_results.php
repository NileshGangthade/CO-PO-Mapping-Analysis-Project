<?php

require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$department =  $_SESSION['department'];

function getDepartmentName($department)
{
  switch ($department) {
    case 'first-year':
      return 'First Year';
    case 'cse':
      return 'Computer Science and Engineering';
    case 'electrical':
      return 'Electrical Engineering';
    case 'mech':
      return 'Mechanical Engineering';
    case 'civil':
      return 'Civil Engineering';
    case 'entc':
      return 'Electronics and Telecommunication Engineering';
    default:
      return 'Unknown Department';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>View Results</title>
  <link rel="stylesheet" href="view_result.css">
  <style>
    /* Style the form */
    form {
      max-width: 800px;
      margin: 0 auto;
    }

    

    .dept {
      padding: 5px;
      /* margin-top: 25px; */
      background-color: #fff;
      padding-left: 0px;
      padding-bottom: 0px;
      border-radius: 5px;
      /* border: 2px solid #ddd; */
      margin-bottom: 20px;
      font-size: 16px;
      color: #333;
      width: 100%;
      box-sizing: border-box;
    }

    select,
    input[type=text] {
      width: 100%;
      padding: 8px;
      border-radius: 4px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      margin-bottom: 16px;
    }

    


    /* Style the table */
    #table-container {
      max-width: 800px;
      margin: 0 auto;
      margin-top: 16px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      margin-bottom: 16px;
    }

    th,
    td {
      text-align: center;
      padding: 8px;
      border: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f5f5f5;
    }

    @media print {

      button[type=submit],
      button[type=button] {
        display: none;
      }
    }

    a.paper-link {
      display: block;
      padding: 10px;
      background-color: green;
      border-radius: 5px;
      margin-bottom: 10px;
      color: white;
      text-decoration: none;
      transition: background-color 0.3s ease;
      text-align: center;
    }


    a.paper-link:hover {
      background-color: #ddd;
    }

    h2 {
      display: block;
      padding: 10px;
      background-color: lightgreen;
      border-radius: 5px;
      margin-bottom: 10px;
      color: black;
      max-width: 800px;
      margin: 0 auto;
    }

    .print-button {
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      margin-top: 16px;
    }

    .print-button:hover {
      background-color: #3e8e41;
    }

    #table-container {
      width: 80%;
      margin: 0 auto;
      text-align: center;
    }



    #table-container table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    #table-container table th,
    #table-container table td {
      padding: 10px;
      border: 1px solid black;
    }

    #table-container table th {
      background-color: lightgray;
    }

    #table-container table tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .print-button {
      /* display: block; */
      margin: 0 auto;
      padding: 10px;
      background-color: green;
      border-radius: 5px;
      color: white;
      text-decoration: none;
      transition: background-color 0.3s ease;
      width: 100px;
      text-align: center;
    }

    .print-button:hover {
      background-color: darkgreen;
      cursor: pointer;
    }

    #table-container table th {
      background-color: lightgreen;
    }

    /* Style for the container div */
    .heading-container {
      background-color: #f2f2f2;
      padding: 10px;
      margin-bottom: 20px;
    }

 
  </style>


  <?php include 'nav_bar.php'; ?>
</head>

<body>

  <form id="marks-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <div class="form_wrapper">
      <div class="form_container">
        <div class="title_container">
          <h1>Show Available Papers Direct Attainment</h1>
        </div>
        <div class="row clearfix">
          <div class="">

            <div class="input_field_dept">
            <label for="department">Department : </label>
              <span><i aria-hidden="true" class="fa fa-university"></i></span>
              <span id="department-text"><?php echo getDepartmentName($department); ?></span>
              <input type="hidden" id="department" name="department" value="<?php echo $department; ?>">
            </div>

            <input type="hidden" name="department_hidden" value="<?php echo $department; ?>">

            <div class="input_field select_option">
              <span><i aria-hidden="true" class="fa fa-calendar"></i></span>
              <select id="year" name="year" required>
                <option value="">Select Year</option>
                <option value="SY_Btech">Second Year</option>
                <option value="TY_Btech">Third Year</option>
                <option value="BE">Fourth Year</option>
              </select>
              <div class="select_arrow"></div>
            </div>

            <div class="input_field select_option">
              <span><i aria-hidden="true" class="fa fa-sort-alpha-down"></i></span>
              <select id="division" name="division" required>
                <option value="">Select Division</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="C">C</option>
                <option value="D">D</option>
                <option value="E">E</option>
                <option value="F">F</option>
                <option value="G">G</option>
                <option value="H">H</option>
              </select>
              <div class="select_arrow"></div>
            </div>

            <div class="input_field select_option">
              <span><i aria-hidden="true" class="fa fa-calendar"></i></span>
              <select id="sem" name="sem" required>
                <option value="SEM_I">First Semester</option>
                <option value="SEM_II">Second Semester</option>
              </select>
              <div class="select_arrow"></div>
            </div>

            <div class="input_field">
              <span><i aria-hidden="true" class="fa fa-book"></i></span>
              <input type="text" id="subject" name="subject" placeholder="Subject" required>
            </div>

            <div class="input_field">
              <span><i aria-hidden="true" class="fa fa-calendar"></i></span>
              <input type="text" id="academic-year" name="academic-year" pattern="\d{4}_\d{2}" placeholder="Academic Year (e.g., 2023_24)" required>
            </div>

            <div class="frame">
              <button class="custom-btn btn-9" type="submit">SEARCH</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <br>

</body>

</html>

<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $department = $_POST['department'] ?? '';
  $year = $_POST['year'] ?? '';
  $sem = $_POST['sem'] ?? '';
  $subject = $_POST['subject'] ?? '';
  $division = $_POST['division'] ?? '';
  $academic_year = $_POST['academic-year'] ?? '';

  // Create a database connection
  $conn = mysqli_connect('localhost', 'root', 'root', 'mysql');
  if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }


  if (empty($_POST['department']) || empty($_POST['year']) || empty($_POST['sem']) || empty($_POST['subject']) || empty($_POST['division']) || empty($_POST['academic-year'])) {
    die('');
  }
  // Select the database
  mysqli_select_db($conn, $department);
  // Search for tables with matching criteria
  $table_prefix = $year . '_' . $department . '_' . $division . '_' . $sem . '_' . $subject . '_';
  $sql = "SHOW TABLES LIKE '$table_prefix%_$academic_year'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $num_papers = mysqli_num_rows($result);
    $color = '';
    if ($num_papers <= 2) {
      $color = 'red';
    } elseif ($num_papers < 4 && $num_papers > 2) {
      $color = 'orange';
    } else {
      $color = 'green';
    }
    echo '</div>';
    echo "<h2>Available Papers:</h2>";

    $combined_data = array();
    $test_types = array();

    while ($row = mysqli_fetch_assoc($result)) {
      $table_name = array_values($row)[0];
      $table_name_array = explode('_', $table_name);
      $testType = $table_name_array[count($table_name_array) - 3];
      $test_types[] = $testType;
      $co_attainment_table_name = $table_name . '_CO_att';
      echo "<a href='http://localhost/new1/excel_interface.php?table_name=" . urlencode($table_name) . "&department=" . urlencode($department) . "&redirect=true' target='_blank' class='paper-link'>$table_name</a>";


      // Create a new database connection for each paper
      $conn_co = mysqli_connect('localhost', 'root', 'root', $department);
      if (!$conn_co) {
        die('Connection failed: ' . mysqli_connect_error());
      }

      // Query the CO_attainment table
      $co_attainment_query = "SELECT * FROM $co_attainment_table_name";
      $co_attainment_result = mysqli_query($conn_co, $co_attainment_query);

      // Fetch the CO_attainment data and merge with the combined_data array
      if (mysqli_num_rows($co_attainment_result) > 0) {
        mysqli_data_seek($co_attainment_result, 0);
        while ($co_attainment_row = mysqli_fetch_assoc($co_attainment_result)) {
          $co = $co_attainment_row['CO'];
          $attainment = $co_attainment_row['Attainment'];
          if (!isset($combined_data[$co])) {
            $combined_data[$co] = array();
          }
          $combined_data[$co][$testType] = $attainment;
        }
      }

      // Close the new connection
      mysqli_close($conn_co);
    }



    echo '<div id="table-container">';
    $test_type_sequence = array('UT1', 'UT2', 'UT3', 'Prelim', 'Assign1', 'Assign2', 'Assign3', 'Assign4');


    echo "<div style='display:flex;justify-content:center;'>";
    echo "<div style='margin: 0 10px;'><h3 style='font-size: 1.17em; font-weight: bold;'>Year: " . $year . "</h3></div>";
    echo "<div style='margin: 0 10px;'><h3 style='font-size: 1.17em; font-weight: bold;'>Department: " . $department . "</h3></div>";
    echo "<div style='margin: 0 10px;'><h3 style='font-size: 1.17em; font-weight: bold;'>Division: " . $division . "</h3></div>";
    echo "</div>";

    echo "<div style='display:flex;justify-content:center;'>";
    echo "<div style='margin: 0 10px;'><h3 style='font-size: 1.17em; font-weight: bold;'>Semester: " . $sem . "</h3></div>";
    echo "<div style='margin: 0 10px;'><h3 style='font-size: 1.17em; font-weight: bold;'>Subject: " . $subject . "</h3></div>";
    echo "<div style='margin: 0 10px;'><h3 style='font-size: 1.17em; font-weight: bold;'>Test Type: " . $testType . "</h3></div>";
    echo "</div>";

    echo "<div style='display:flex;justify-content:center;'>";
    echo "<div style='margin: 0 10px;'><h3 style='font-size: 1.17em; font-weight: bold;'>Academic Year: " . $academic_year . "</h3></div>";
    echo "</div>";



    echo "<table id='direct-attainment-table' border='1'>";
    echo "<tr>";
    echo "<th>CO</th>";
    foreach ($test_type_sequence as $testType) {
      if (in_array($testType, $test_types)) {
        echo "<th>" . $testType . "</th>";
      }
    }
    echo "<th>CIE</th>";
    echo "<th>ESE</th>";
    echo "</tr>";

    $cie_total = 0;
    $ese_total = 0;
    $row_count = 0;

    // Sort combined_data by CO names
    ksort($combined_data);

    foreach ($combined_data as $co => $attainment_data) {
      echo "<tr>";
      echo "<td>" . $co . "</td>";

      foreach ($test_type_sequence as $testType) {
        if (in_array($testType, $test_types)) {
          if (isset($attainment_data[$testType])) {
            echo "<td>" . $attainment_data[$testType] . "</td>";
          } else {
            echo "<td></td>";
          }
        } else {
          echo "<td style='display:none'></td>"; // Hide the cell if the test type is not in the list
        }
      }

      if (!empty($attainment_data)) {
        $average = array_sum($attainment_data) / count($attainment_data);
        echo "<td>" . round($average, 2) . "</td>";
        $cie_total += $average;
      } else {
        echo "<td></td>";
      }
      echo "<td>3</td>";
      $ese_total += 3;
      $row_count++;
      echo "</tr>";
    }

    $cie_average = $cie_total / $row_count;
    $ese_average = $ese_total / $row_count;

    $cie_weighted = $cie_average * 0.3;
    $ese_weighted = $ese_average * 0.7;
    $final_direct_course_attainment = $cie_weighted + $ese_weighted;

    echo "<tr>";
    echo "<td colspan='" . (count($test_types) + 1) . "'>ATTAINMENT</td>";
    echo "<td>" . round($cie_average, 2) . "</td>";
    echo "<td>" . round($ese_average, 2) . "</td>";
    echo "</tr>";

    // Add WEIGHTAGE row
    echo "<tr>";
    echo "<td colspan='" . (count($test_types) + 1) . "'>WEIGHTAGE</td>";
    echo "<td>30%</td>";
    echo "<td>70%</td>";
    echo "</tr>";

    // Add DIRECT TOTAL ATTAINMENT row
    echo "<tr>";
    echo "<td colspan='" . (count($test_types) + 1) . "'>DIRECT TOTAL ATTAINMENT</td>";
    echo "<td>" . round($cie_weighted, 2) . "</td>";
    echo "<td>" . round($ese_weighted, 2) . "</td>";
    echo "</tr>";

    // Add FINAL DIRECT COURSE ATTAINMENT row
    echo "<tr>";
    echo "<td colspan='" . (count($test_types) + 1) . "'>FINAL DIRECT COURSE ATTAINMENT</td>";
    echo "<td colspan='2'>" . round($final_direct_course_attainment, 2) . "</td>"; // Set colspan to 2 for the value cell
    echo "</tr>";
    echo "</table>";
    echo '<br><a href="#" class="print-button" onclick="printTable()">Print Table</a>';
    echo '</div>';

    echo '<script>
        function printTable() {
            var printContents = document.getElementById("direct-attainment-table").outerHTML;
            var originalContents = document.body.innerHTML;
            var header = \'<img src="college_logo.jpeg" alt="College Logo" style="width:100%; max-height:150px;"><div style="display:flex;justify-content:center;"><div style="margin: 0 10px;"><h3>Year: ' . $year . '</h2></div><div style="margin: 0 10px;"><h3>Department: ' . $department . '</h2></div><div style="margin: 0 10px;"><h3>Division: ' . $division . '</h2></div></div><div style="display:flex;justify-content:center;"><div style="margin: 0 10px;"><h3>Semester: ' . $sem . '</h3></div><div style="margin: 0 10px;"><h3>Subject: ' . $subject . '</h3></div><div style="margin: 0 10px;"><h3>Test Type: ' . $testType . '</h3></div></div><div style="display:flex;justify-content:center;"><div style="margin: 0 10px;"><h3>Academic Year: ' . $academic_year . '</h3></div></div>\';
            document.body.innerHTML = header + printContents;
            setTimeout(function() {
                window.print();
                document.body.innerHTML = originalContents;
            }, 1000);
          }
      </script>';
  } else {
    echo "No tables found.";
  }

  //show the progress bar
  echo '<div id="papers-count" style="background-color: ' . $color . '; width: 200px;">';
  echo '<p>Progress: ' . $num_papers . ' papers available</p>';
  echo '</div>';

  mysqli_close($conn);
}
?>