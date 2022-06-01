<?php

session_start();



require "../middleware/auth.php";

$user = getSingleRecord('users', 'id', $_SESSION['id']);

$perm = getSingleRecord("role", 'user_id', $_SESSION["id"]);

// Validate user session



if (!isset($_SESSION['login']) && $_SESSION['login'] != true) {

    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
} else if (!isset($_SESSION['authenticate']) && $_SESSION['authenticate'] !== true) {

    header("location: index.php");
} else {

    if ($user['status'] === 'active') {

        if (!in_array($_SESSION['role'], $_SESSION['admins'])) {

            header('location: ../user');
        } else {
            if ($_SESSION['role'] !== 'admin_4' && $_SESSION['role'] !== 'admin_3' && $_SESSION['role'] !== 'admin_2' && $_SESSION['role'] !== 'admin_1') {
                header('location: dashboard.php');
            }
        }
    } else {

        header('location: ../logout');
    }
}

// Disable
$status = '';
date_default_timezone_set('Europe/London');
$date = date("Y-m-d H:i:s");

if (isset($_POST['update'])) {
    $sql = $conn->query('update investment set balance = "' . $_POST['balance'] . '", updated_by = "' . $_SESSION['username'] . '", update_time_time = "' . $date . '" where user_id=' . $_POST['id']);
    if ($sql) {
        $status = '<div class="alert alert-success text-center alert-dismissible fade show" role="alert">Withdrawal Status Updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    } else {
        $status = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
  <strong>Error: </strong> Something went wrong.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    }
}



















?>





<!DOCTYPE html>

<html lang="en">



    <head>

        <!-- Required meta tags -->

        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">



        <title>Transaction Update - Smart Admin</title>





        <!-- plugins:css -->

        <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">

        <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">

        <!-- endinject -->

        <!-- plugin css for this page -->

        <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">

        <!-- End plugin css for this page -->

        <!-- inject:css -->

        <link rel="stylesheet" href="css/style.css">

        <!-- endinject -->

        <link rel="shortcut icon" href="images/favicon.png" />

    </head>



    <body>

        <div class="container-scroller">

            <!-- partial:partials/_navbar.html -->

            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

                <div class="navbar-brand-wrapper justify-content-center d-none d-lg-flex">

                    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">

                        <a class="navbar-brand brand-logo" href="../">

                            <h4 class="text-dark">Smart<span
                                    class='bg-primary rounded text-white px-1'>FX</span>Crypto<span
                                    class='text-primary'><b>.</b></span></h4>

                        </a>

                        <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                            data-toggle="minimize">

                            <span class="mdi mdi-sort-variant text-primary"></span>

                        </button>

                    </div>

                </div>

                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end flex-fill">

                    <a class="d-lg-none" href="../">

                        <h4 class="text-dark">Smart<span class='bg-primary rounded text-white px-1'>FX</span>Crypto<span
                                class='text-primary'><b>.</b></span></h4>

                    </a>

                    <ul class="navbar-nav mr-lg-4 w-100">

                        <li class="nav-item nav-search d-none d-lg-block w-100">

                            <div class="input-group">

                                <div class="input-group-prepend">

                                    <span class="input-group-text" id="search">

                                        <i class="mdi mdi-lock">



                                        </i>

                                    </span>

                                </div>

                                <input type="text" readonly class="form-control-plaintext"
                                    value="Session ID: <?php echo $_SESSION['unique_id']; ?>" aria-label="search"
                                    aria-describedby="search">

                            </div>

                        </li>

                    </ul>

                    <ul class="navbar-nav navbar-nav-right">

                        <li class="nav-item nav-profile dropdown">

                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">

                                <img src="images/faces/face5.jpg" alt="profile" />

                                <span class="nav-profile-name"><?php echo $_SESSION['username']; ?> </span>

                            </a>

                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                                aria-labelledby="profileDropdown">

                                <a href="settings.php" class="dropdown-item">

                                    <i class="mdi mdi-settings text-primary"></i> Settings

                                </a>

                                <a class="dropdown-item" href="../logout">

                                    <i class="mdi mdi-logout"></i> Logout

                                </a>

                            </div>

                        </li>

                    </ul>

                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">

                        <span class="mdi mdi-menu"></span>

                    </button>

                </div>

            </nav>

            <!-- partial -->

            <div class="container-fluid page-body-wrapper">

                <!-- partial:partials/_sidebar.html -->

                <nav class="sidebar sidebar-offcanvas" id="sidebar">

                    <ul class="nav">

                        <li class="nav-item">

                            <a class="nav-link" href="index.php">

                                <i class="mdi mdi-home menu-icon"></i>

                                <span class="menu-title">Dashboard</span>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" data-toggle="collapse" href="#manageusers" aria-expanded="false"
                                aria-controls="auth">

                                <i class="mdi mdi-account-multiple menu-icon"></i>

                                <span class="menu-title">Manage Users</span>

                                <i class="menu-arrow"></i>

                            </a>

                            <div class="collapse" id="manageusers">

                                <ul class="nav mb-0">
                                    <!-- All Users -->
                                    <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2') { ?>
                                    <li class="nav-item ml-4"> <a class="nav-link" href="viewusers.php">
                                            All Users</a>

                                    </li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="viewfunded.php"> Funded Users

                                        </a>

                                    </li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="viewdisabled.php"> Disabled

                                            Users </a>

                                    </li>
                                    <?php } ?>
                                    <li class="nav-item ml-4"> <a class="nav-link" href="updatebalance.php"> Update

                                            Transaction </a>

                                    </li>
                                    <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2' || $_SESSION['role'] === 'admin_1' || $_SESSION['role'] === 'agent_2') { ?>
                                    <li class="nav-item ml-4"> <a class="nav-link" href="authuser.php"> Update State

                                        </a>

                                    </li>
                                    <?php } ?>

                                    <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2' || $_SESSION['role'] === 'admin_1') { ?>
                                    <li class="nav-item ml-4"> <a class="nav-link" href="addhistory.php">
                                            Create

                                            History </a>

                                    </li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="updatehistory.php"> Update
                                            History </a>

                                    </li>
                                    <?php } ?>
                                    <?php if (
                                    $_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3'
                                ) { ?>
                                    <li class="nav-item ml-4"> <a class="nav-link" href="resetuserpassword.php"> Reset

                                            Password</a>

                                    </li>

                                    <?php } ?>

                                </ul>

                            </div>

                        </li>
                        <!-- Manage Admin -->
                        <?php if ($_SESSION['role'] === 'admin_4') { ?>
                        <li class="nav-item">

                            <a class="nav-link" data-toggle="collapse" href="#manageadmin" aria-expanded="false"
                                aria-controls="auth">

                                <i class="mdi mdi-account-star menu-icon"></i>

                                <span class="menu-title">Manage Admin</span>

                                <i class="menu-arrow"></i>

                            </a>

                            <div class="collapse" id="manageadmin">

                                <ul class="nav mb-0">

                                    <li class="nav-item ml-4"> <a class="nav-link" href="viewadmin.php"> Admin Users

                                        </a>

                                    </li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="addadmin.php"> Add Admin </a>

                                    </li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="editadmin.php"> Update Role

                                        </a>

                                    </li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="resetadminpasscode.php"> Reset

                                            Passcode</a>

                                    </li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="resetadminpassword.php"> Reset

                                            Password </a>

                                    </li>



                                </ul>

                            </div>

                        </li>
                        <?php } ?>

                        <!-- Miscellaneous -->
                        <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3') { ?>
                        <li class="nav-item">

                            <a class="nav-link" data-toggle="collapse" href="#misc" aria-expanded="false"
                                aria-controls="auth">

                                <i class="mdi mdi-book menu-icon"></i>

                                <span class="menu-title">Miscellaneous</span>

                                <i class="menu-arrow"></i>

                            </a>

                            <div class="collapse" id="misc">

                                <ul class="nav mb-0">

                                    <li class="nav-item ml-4"> <a class="nav-link" href="messages.php">
                                            Messages </a>

                                    </li>
                                    <?php if ($_SESSION['role'] === 'admin_4') { ?>
                                    <li class="nav-item ml-4"> <a class="nav-link" href="wallet.php"> Update Wallet </a>
                                    </li>
                                    <?php } ?>
                                </ul>

                            </div>

                        </li>
                        <?php } ?>
                        <li class="nav-item">

                            <a class="nav-link" data-toggle="collapse" href="#account" aria-expanded="false"
                                aria-controls="auth">

                                <i class="mdi mdi-account menu-icon"></i>

                                <span class="menu-title">Account</span>

                                <i class="menu-arrow"></i>

                            </a>

                            <div class="collapse" id="account">

                                <ul class="nav mb-0">

                                    <li class="nav-item ml-4"> <a class="nav-link" href="profile.php"> Profile </a></li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="resetpasscode.php"> Reset

                                            Passcode </a></li>

                                    <li class="nav-item ml-4"> <a class="nav-link" href="settings.php">

                                            Settings </a>

                                    </li>

                                </ul>

                            </div>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link" href="../logout">

                                <i class="mdi mdi-logout menu-icon"></i>

                                <span class="menu-title">Logout</span>

                            </a>

                        </li>

                    </ul>







                </nav>

                <!-- partial Ends -->



                <!--Dashboard Headers-->

                <div class="main-panel">

                    <div class="content-wrapper">



                        <div class="row">

                            <div class="col-md-12 grid-margin">

                                <div class="d-flex justify-content-between flex-wrap">

                                    <div class="d-flex align-items-end flex-wrap">

                                        <div class="mr-md-3 mr-xl-5">



                                            <h2>Balance Update</h2>

                                            <div class="d-flex">

                                                <i class="mdi mdi-home text-muted hover-cursor"></i>

                                                <p class="text-muted mb-0 hover-cursor">

                                                    &nbsp;/&nbsp;Users&nbsp;/&nbsp;</p>

                                                <p class="text-primary mb-0 hover-cursor">Balance Update</p>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- Page contents goes here -->
                        <?php
                    $sql = $conn->query('select * from users JOIN investment where users.id not in (select user_id from role) AND users.id = investment.user_id and investment.balance > 0 ORDER BY users,id DESC');
                    if ($sql->num_rows > 0) {
                    ?>
                        <div class="row">
                            <div class="col-md">
                                <div class="shadow">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="text-center">Funded Users</h4>
                                            <?php echo $status; ?>
                                            <div class="table-responsive">
                                                <table class="table">

                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Reg Time</th>
                                                        <th>Status</th>
                                                        <th>Balance</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    <?php
                                                    $i = 0;
                                                    while ($row = $sql->fetch_assoc()) {
                                                        $i++;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo ucfirst($row['firstname']) . ' ' . ucfirst($row['lastname']); ?>
                                                        </td>
                                                        <td><?php echo $row['username']; ?></td>
                                                        <td><?php echo $row['reg_time']; ?></td>
                                                        <td><?php echo $row['level']; ?></td>
                                                        <form action="updatetransaction.php" method="post">
                                                            <td>
                                                                <input type="number" name="balance" id=""
                                                                    class="form-control"
                                                                    value="<?php echo $row['balance']; ?>" required>
                                                            </td>

                                                            <td>

                                                                <button type="submit" name="update"
                                                                    class="btn btn-primary">Update</button>
                                                                <input type="hidden" name="id"
                                                                    value="<?php echo $row['user_id']; ?>">
                                                            </td>
                                                        </form>
                                                    </tr>


                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php } else { ?>

                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <h3 class="text-center">No Transaction Record Found.</h3>
                            </div>
                        </div>
                        <?php } ?>

                        <!-- Page content ends here -->






                    </div>

                    <!-- content-wrapper ends -->

                    <!-- partial:partials/_footer.html -->

                    <footer class="footer">

                        <div class="d-sm-flex justify-content-center justify-content-sm-center">

                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©

                                SmartFXCrypto 2021</span>



                        </div>

                    </footer>

                    <!-- partial -->

                </div>

                <!-- main-panel ends -->

            </div>

            <!-- page-body-wrapper ends -->

        </div>





        <!-- container-scroller -->



        <!-- plugins:js -->

        <script src="vendors/base/vendor.bundle.base.js"></script>

        <!-- endinject -->

        <!-- Plugin js for this page-->

        <script src="vendors/chart.js/Chart.min.js"></script>

        <script src="vendors/datatables.net/jquery.dataTables.js"></script>

        <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

        <!-- End plugin js for this page-->

        <!-- inject:js -->

        <script src="js/off-canvas.js"></script>

        <script src="js/hoverable-collapse.js"></script>

        <script src="js/template.js"></script>

        <!-- endinject -->

        <!-- Custom js for this page-->

        <script src="js/dashboard.js"></script>

        <script src="js/data-table.js"></script>

        <script src="js/jquery.dataTables.js"></script>

        <script src="js/dataTables.bootstrap4.js"></script>

        <!-- End custom js for this page-->

        <script src="js/jquery.cookie.js" type="text/javascript"></script>



    </body>



</html>