<?php
session_start();
error_reporting(0);  include('../dbconnection.php');
if ($_SESSION['user_role'] != 'HOD') {
    header("Location: login.html");
    exit();
}
else {

?>   
    
<div class="header">
    <div class="pull-left">
        <div class="logo"><a href="hod_frontend.php"><!-- <img src="assets/images/logo.png" alt="" /> --><span>HOD Dashboard</span></a></div>
        <div class="hamburger sidebar-toggle">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </div>

    <div class="pull-right p-r-15">
        <ul>
            <?php
            // Check if user is logged in and session variables are set
            $ID = $_SESSION['ID'];
            $sql ="SELECT * FROM teachers_data WHERE ID=$ID";
            $query = $dbh -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
        
                        $cnt=1;
                        if($query->rowCount() > 0)
                        {
                        foreach($results as $row)
                        { 
                        ?> 
                        <!-- <option value="<?php echo htmlentities($row->EmpID); ?>"><?php echo htmlentities($row->FirstName); ?> <?php echo htmlentities($row->LastName); ?>(<?php echo htmlentities($row->EmpID); ?>)</option> -->
                     
                 
                
                <li class="header-icon dib"><img class="avatar-img" src="../assets/ProfilePic/<?php echo htmlentities($row->ProfilePic); ?>" alt="" /> <span class="user-avatar"> <?php echo htmlentities($row->FirstName); ?> <?php echo htmlentities($row->LastName); ?> <i class="ti-angle-down f-s-10"></i></span>
                    <div class="drop-down dropdown-profile">
                        <div class="dropdown-content-heading">
                            <span class="text-left"><?php echo htmlentities($row->Email); ?></span>
                            <!-- You can display additional user information here if needed -->
                        </div>
                        <div class="dropdown-content-body">
                            <ul>
                                <li><a href="profile.php"><i class="ti-user"></i> <span>Profile</span></a></li>
                                <li><a href="change-password.php"><i class="ti-settings"></i> <span>Setting</span></a></li>
                                <li><a href="../logout.php"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                
        </ul>
    </div>
</div>

<?php $cnt=$cnt+1;}} ?><?php } ?>