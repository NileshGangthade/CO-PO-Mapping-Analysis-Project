<?php
session_start();
error_reporting(0);
include('../dbconnection.php');

if ($_SESSION['user_role'] != 'Professor') {
    header("Location: login.html");
    exit();
}

$tableName = $_GET['tableName'];
$test = $_GET['test'];
$enrollID = $_GET['enrollID'];

// Construct the table names for questions and marks
$questionsTableName = $tableName . '_' . $test . "_Questions";


$marksTableName = $tableName . '_' . $test . '_Marks';

if (isset($_POST['submit'])) {

     $tableName = $_POST['tableName'];
     $test = $_POST['test'];
     $enrollID = $_POST['enrollID'];
     $questionsTableName = $tableName . '_' . $test . "_Questions";

    $num_main_questions = intval($_POST['num_main_questions']);

    echo $questionsTableName;
    $sql = "CREATE TABLE IF NOT EXISTS  $questionsTableName  (
     main_question INT NOT NULL,
          sub_question_number INT NOT NULL,
          sub_question VARCHAR(50) NOT NULL,
          marks INT NOT NULL,
          co INT NOT NULL,
          bl INT NOT NULL
   )";
 
   // Execute the table creation query
   $query = $dbh->prepare($sql);

   if ($query->execute()) {

   

   for ($i = 1; $i <= $num_main_questions; $i++) {
     $num_sub_questions = intval($_POST["num_sub_questions_$i"]);
     for ($j = 1; $j <= $num_sub_questions; $j++) {
         $sub_question = $_POST["sub_question_{$i}_{$j}"];
         $marks = intval($_POST["marks_{$i}_{$j}"]);
         $co = intval($_POST["co_{$i}_{$j}"]);
         $bl = intval($_POST["bl_{$i}_{$j}"]);

         // Prepare the SQL statement
         $sql = "INSERT INTO $questionsTableName (main_question, sub_question_number, sub_question, marks, co, bl) VALUES (:main_question, :sub_question_number, :sub_question, :marks, :co, :bl)";
         $query = $dbh->prepare($sql);
         $query->bindParam(':main_question', $i, PDO::PARAM_INT);
         $query->bindParam(':sub_question_number', $j, PDO::PARAM_INT);
         $query->bindParam(':sub_question', $sub_question, PDO::PARAM_STR);
         $query->bindParam(':marks', $marks, PDO::PARAM_INT);
         $query->bindParam(':co', $co, PDO::PARAM_INT);
         $query->bindParam(':bl', $bl, PDO::PARAM_INT);

         // Execute the SQL statement
         if ($query->execute()) {
                    echo '<script>alert("Question Paper created, Now add the students Marks.")</script>';
                    echo "<script>window.location.href =' add-student-mark.php?tableName=$tableName&test=$test&enrollID=$enrollID'</script>";
                } else {
                    echo '<script>alert("Unable to add Data into table. Please try again")</script>';
                }
            }
        }
    } else {
        echo '<script>alert("Unable to create Table. Please try again")</script>';
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

    <title><?php echo htmlentities(str_replace('_', '-', $test)) ?> : Questions</title>
  

  <!-- subject css -->
      
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
                                <h1><?php echo htmlentities(str_replace('_', '-', $test))  ?> : Questions</h1>
                                
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="teacher_frontend.php">Dashboard</a></li>
                                    <li class="active"><?php echo htmlentities(str_replace('_', '-', $test)) ?> : Questions</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>
                <!-- /# row -->
                <div id="main-content">
                <div class="row">
                    <div class="col-md-4" style="width: 96% ">
                        <div class="card alert">
                            <div class="card-header pr">
                                
                                <h4>Create Question Paper</h4>
                            </div>
                            <div class="card-body">
                                    <form name="" method="post" action="" enctype="multipart/form-data">
                                        <label for="num_main_questions">Number of Main Questions:</label>
                                        <input type="number" id="num_main_questions" name="num_main_questions" min="1" onchange="addMainQuestions()" required><br><br>
                                        <div id="main_questions_container"></div>
                                        <input type="hidden" name="tableName" value="<?php echo htmlspecialchars($tableName); ?>">
                                        <input type="hidden" name="test" value="<?php echo htmlentities($test) ?>">
                                        <input type="hidden" name="enrollID" value="<?php echo htmlspecialchars($enrollID); ?>">
                                        <button type="submit" name="submit">Submit</button>
                                    </form>

  
                                </div>
                            </div>

                        </div>
                            
                        </div>
                      </div>
                   
                </div>
            </div>
        </div>
    </div>




    <script >

function addSubQuestions(main_question) {
      const num_sub_questions = parseInt(document.getElementById(`num_sub_questions_${main_question}`).value);
      const sub_questions_container = document.getElementById(`sub_questions_${main_question}`);
      sub_questions_container.innerHTML = '';
      for (let i = 1; i <= num_sub_questions; i++) {
        sub_questions_container.innerHTML += `
        <div>
          <label for="sub_question_${main_question}_${i}">Subquestion ${i}:</label>
          <textarea id="sub_question_${main_question}_${i}" name="sub_question_${main_question}_${i}" required maxlength="10000"></textarea>
          <label for="marks_${main_question}_${i}">Marks:</label>
          <input type="number" id="marks_${main_question}_${i}" name="marks_${main_question}_${i}" min="1" required>
          <label for="co_${main_question}_${i}">CO:</label>
          <input type="number" id="co_${main_question}_${i}" name="co_${main_question}_${i}" min="1" max="6" required>
          <label for="bl_${main_question}_${i}">BL:</label>
          <input type="number" id="bl_${main_question}_${i}" name="bl_${main_question}_${i}" min="1" max="6" required>
        </div>`;
      }
    }
    
  </script>

<script>
    function addMainQuestions() {
      const num_main_questions = parseInt(document.getElementById('num_main_questions').value);
      const main_questions_container = document.getElementById('main_questions_container');
      main_questions_container.innerHTML = '';
      for (let i = 1; i <= num_main_questions; i++) {
        main_questions_container.innerHTML += `
        <div>
          <h3>Main Question ${i}</h3>
          <label for="num_sub_questions_${i}">
            Number of Subquestions:</label>
          <input type="number" id="num_sub_questions_${i}" name="num_sub_questions_${i}" min="1" onchange="addSubQuestions(${i})" required>
          <div id="sub_questions_${i}"></div>
        </div>`;
      }
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
