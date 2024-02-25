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
            header("Location: admin_dashbord.php");
            exit();
          }
        } else {
          header("Location: dashbord.php");
          exit();
        }
      } else {
        $_SESSION['error'] = "Invalid email or password.";
        header("Location: login.html");
        exit();
      }
    } else {
      $_SESSION['error'] = "Invalid email or password.";
      header("Location: login.html");
      exit();
    }
  } else {
    $_SESSION['error'] = "Error occurred.";
    header("Location: login.html");
    exit();
  }
}

$conn->close();
?>
