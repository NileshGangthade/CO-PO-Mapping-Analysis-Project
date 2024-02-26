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

  <title>Admin dashboard</title>
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/admin_dashboard.css">

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">



<!-- Additional CSS Files -->
<link rel="stylesheet" href="assets/css/dev.css">
<link rel="stylesheet" href="assets/css/animated.css">
</head>
<body>


<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <!-- <a href="index.html" class="logo">
              <img src="assets/images/logo.png" alt="Chain App Dev">
            </a> -->
            <!-- ***** Logo End ***** -->
            <!-- ***** Menu Start ***** -->
            <ul class="nav">
              <li class="scroll-to-section"><a href="index.php" class="active">Home</a></li>
              <li class="scroll-to-section"><a href="index.php#services">Services</a></li>
              <li class="scroll-to-section"><a href="index.php#about">About</a></li>
              <!-- <li class="scroll-to-section"><a href="#pricing">Pricing</a></li> -->
              <!-- <li class="scroll-to-section"><a href="#newsletter">Newsletter</a></li> -->
              <li><div class="gradient-button"><a id="modal_trigge" href="login.php"><i class="fa fa-sign-in-alt"></i> Sign In Now</a></div></li> 
            </ul>        
            <a class='menu-trigger'>
                <span>Menu</span>
            </a>
            <!-- ***** Menu End ***** -->
          </nav>
        </div>
      </div>
    </div>
  </header>
  <!-- ***** Header Area End ***** -->
  <h1>Welcome to the Admin dashboard</h1>

  <h2>Waiting for Approval</h2>
  <div class="container">
    <table class="display" id="waitingTable">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">User Type</th>
          <th scope="col">Department</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result_waiting->fetch_assoc()) : ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user_type']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
              <button onclick="handleAction('approve', <?php echo $row['id']; ?>)">Approve</button>
              <button onclick="handleAction('reject', <?php echo $row['id']; ?>)">Reject</button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <h2>Approved List</h2>
  <div class="container">
    <table class="display" id="approvalTable">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">User Type</th>
          <th scope="col">Department</th>
          <th scope="col">Name</th>
          <th scope="col">Email</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result_approved->fetch_assoc()) : ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user_type']; ?></td>
            <td><?php echo $row['department']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td>
              <button onclick="handleAction('delete', <?php echo $row['id']; ?>)">Delete</button> <!-- Add this line -->
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    function handleAction(action, id) {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'handle_approval.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          alert(xhr.responseText);
          location.reload();
        }
      };
      xhr.send('action=' + action + '&id=' + id);
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#myTable').DataTable();
    });
  </script>
  <script>
    $(document).ready(function() {
      $('table').DataTable();
    });
  </script>

  <!-- Scripts -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/owl-carousel.js"></script>
  <script src="assets/js/animation.js"></script>
  <script src="assets/js/imagesloaded.js"></script>
  <script src="assets/js/popup.js"></script>
  <script src="assets/js/custom.js"></script>
</body>
</html>
