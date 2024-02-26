<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get the number of main questions and subquestions
  $num_main_questions = intval($_POST['num_main_questions']);
  
  // Create a database connection
  $conn = mysqli_connect('localhost', 'root', 'root', 'mysql');
  if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  // Retrieve table name and department
  session_start();
  if (!isset($_SESSION['table_name']) || !isset($_SESSION['department'])) {
    die('Session variables not set.');
  }
  $table_name = $_SESSION['table_name'];
  $department = $_SESSION['department'];

  
  // Select the appropriate database
  mysqli_select_db($conn, $department);

  // Create the table if it doesn't exist
  $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    main_question INT NOT NULL,
    sub_question_number INT NOT NULL,
    sub_question VARCHAR(255) NOT NULL,
    marks INT NOT NULL,
    co INT NOT NULL,
    bl INT NOT NULL
  )";
  if (mysqli_query($conn, $sql)) {
    echo "Table created successfully<br>";
  } else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
    }
    
    // Insert data into the table
    for ($i = 1; $i <= $num_main_questions; $i++) {
    $num_sub_questions = intval($_POST["num_sub_questions_$i"]);
    for ($j = 1; $j <= $num_sub_questions; $j++) {
    $sub_question = mysqli_real_escape_string($conn, $_POST["sub_question_{$i}_{$j}"]);
    $marks = intval($_POST["marks_{$i}_{$j}"]);
    $co = intval($_POST["co_{$i}_{$j}"]);
    $bl = intval($_POST["bl_{$i}_{$j}"]);
    $sql = "INSERT INTO $table_name (main_question, sub_question_number, sub_question, marks, co, bl) VALUES ($i, $j, '$sub_question', $marks, $co, $bl)";
    if (mysqli_query($conn, $sql)) {
      echo "Data for sub_question {$i}_{$j} inserted successfully.<br>";
    } else {
      echo "Error inserting data for sub_question {$i}_{$j}: " . mysqli_error($conn) . "<br>";
    }
   }
  }  
  
  // Close the database connection
  mysqli_close($conn);
  
  // Redirect to the table display page
  header("Location: excel_interface.php");
  exit();
}
?>
<input type="hidden" name="num_main_questions" value="<?php echo $num_main_questions ?>">
<input type="hidden" name="num_sub_questions" value="<?php echo $num_sub_questions; ?>">

<!DOCTYPE html>
<html lang="en">
<head>
<style>
    input[type="text"] {
      width: 500px;
    }
  </style>
  <meta charset="UTF-8">
  <title>Test Input</title>
  <script>
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
</head>
<body>
  <h1>Enter Test Details</h1>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <label for="num_main_questions">Number of Main Questions:</label>
    <input type="number" id="num_main_questions" name="num_main_questions" min="1" onchange="addMainQuestions()" required><br><br>
    <div id="main_questions_container"></div>
    <button type="submit">Submit</button>
  </form>
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
</body>
</html>