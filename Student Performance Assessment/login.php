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

?>
        <script>
          alert("invalid password or email.");
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
              alert("invalid password or email.");
            </script>
          <?php

          }
        } else {
          ?>
          <script>
            alert("invalid password or email.");
          </script>
    <?php

        }

        $stmt_temp->close();
      }
    }
  } else {
    ?>
    <script>
      alert("Error :".<?php $conn ?>);
    </script>
<?php

  }
}

$conn->close();

?>




<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="register.css">

  <style>
    .alert {
      padding: 15px;
      background-color: chartreuse;
      font-size: 17px;
      color: black;
      opacity: 5;
      visibility: hidden;
      transition: opacity 0.5s, visibility 100.5s;
      margin-bottom: 15px;
      border-radius: 5px;
    }

    .alert.show-alert {
      opacity: 1;
      visibility: visible;
    }
  </style>


</head>

<body>
  <?php include 'nav_bar.php'; ?>

  <div id="success-alert" class="alert">
    <span id="alert-message">
      Your form is submitted successfully. Please wait for admin approval.
    </span>
  </div>

  <form id="login-in-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

    <div class="form_wrapper">
      <div class="form_container">
        <div class="title_container">
          <h1> Login</h1>
        </div>
        <div class="row clearfix">
          <div class="">
            <form>
              <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope">✉</i></span>
                <input type="email" id="email" name="email" placeholder="Email" required />
              </div>
              <div class="input_field"> <span><i aria-hidden="true" class="fa fa-envelope"></i>🔒</span>
                <input type="password" id="password" name="password" placeholder="Password" required />
              </div>
              <div class="frame"><button class="custom-btn btn-9 , button" type="submit" id="register">Login</button>
              </div>
            </form>
            <a href="forgot_password.php">Forgot password ?</a>

          </div>
        </div>
      </div>

    </div>
  </form>
  <script src="script.js" defer></script>

  <script>
    function getParameterByName(name, url) {
      if (!url) url = window.location.href;
      name = name.replace(/[\[\]]/g, '\\$&');
      var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
      if (!results) return null;
      if (!results[2]) return '';
      return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function showAlert() {
      var alertElement = document.getElementById('success-alert');
      alertElement.classList.add('show-alert');
      setTimeout(function() {
        alertElement.classList.remove('show-alert');
      }, 5000);
    }

    window.addEventListener('DOMContentLoaded', function() {
      var fromOtpEmailVerification = getParameterByName('from') === 'otp_email_verification';
      var fromNewPassword = getParameterByName('from') === 'new_password';

      if (fromOtpEmailVerification) {
        document.getElementById('alert-message').textContent = 'Your form is submitted successfully. Please wait for admin approval.';
        showAlert();
      } else if (fromNewPassword) {
        document.getElementById('alert-message').textContent = 'Password updated successfully. Now you can login with your new password.';
        showAlert();
      }
    });
  </script>

</body>

</html>