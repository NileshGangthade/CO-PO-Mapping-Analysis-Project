<?php

session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM main_table WHERE email = ?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();

      if (password_verify($password, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['is_admin'] = $row['is_admin'];
        $_SESSION['user_type'] = $row['user_type'];
        $_SESSION['department'] = $row['department'];

        if ($row['is_admin'] == 1) {
          if ($row['user_type'] == 'hod') {
            header("Location: view_results.php");
          } else {
            header("Location: admin_dashboard.php");
            exit();
          }
        } else {
          header("Location: dashboard.php");
          exit();
        }
      } else {
        ?>
        <script>
          alert("Invalid password or email.");
          window.location.href = "login.html";
        </script>
        <?php
      }
    } else {
      $stmt->close();

      // Check if the user exists in the temp_table
      $sql_temp = "SELECT * FROM temp_table WHERE email = ?";
      $stmt_temp = $conn->prepare($sql_temp);

      if ($stmt_temp) {
        $stmt_temp->bind_param("s", $email);
        $stmt_temp->execute();
        $result_temp = $stmt_temp->get_result();

        if ($result_temp->num_rows > 0) {
          $row_temp = $result_temp->fetch_assoc();

          if (password_verify($password, $row_temp['password'])) {
            $_SESSION['user_id'] = $row_temp['id'];
            $_SESSION['user_email'] = $row_temp['email'];
            header("Location: wait_for_approval.php");
            exit();
          } else {
            ?>
            <script>
              alert("Invalid password or email.");
              window.location.href = "login.html";
            </script>
            <?php
          }
        } else {
          ?>
          <script>
            alert("Invalid password or email.");
            window.location.href = "login.html";
          </script>
          <?php
        }

        $stmt_temp->close();
      }
    }
  } else {
    ?>
    <script>
      alert("Error: <?php echo $conn->error; ?>");
    </script>
    <?php
  }
}

$conn->close();

?>
