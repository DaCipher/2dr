<?php
session_start();

require "../middleware/auth.php";
// Validate user session

if (!isset($_SESSION['login']) && $_SESSION['login'] != true) {
    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
} else {

    $user = getSingleRecord('users', 'id', $_SESSION['id']);
    $perm = getSingleRecord("role", 'user_id', $_SESSION["id"]);
    if ($user['status'] === 'active') {
        if (!in_array($_SESSION['role'], $_SESSION['admins'])) {
            header('location: ../user');
            return false;
        }
    } else {
        header('location: ../logout');
        return false;
    }
}
if (isset($_SESSION['authenticate']) && $_SESSION['authenticate'] === true) {
    header("location: dashboard.php");
}
$input_pass1 = $input_pass2 = '';
$pass_status = $pass1_err = $pass2_err = $passcode_set =  $pass_err = "";
if (isset($_POST['submit'])) {
    $error = "";
    $input_pass1 = trim($_POST['passcode1']);
    $input_pass2 = trim($_POST['passcode2']);

    // Validate pass1
    if (empty($input_pass1)) {
        $pass1_err = "Passcode is required";
        $error = "set";
    } else if (strlen($input_pass1) < 5) {
        $pass1_err = "Passcode must be minimum of 5 characters!";
        $error = "set";
    } else {
        $pass1 = password_hash($input_pass1, PASSWORD_BCRYPT);
    }

    // validate pass 2

    if (empty($input_pass2)) {
        $pass2_err = "Passcode confirmation required";
        $error = 'set';
    } else if ($input_pass1 !== $input_pass2) {
        $error = "set";
        $pass2_err = "Passcodes doesn't match!";
    }

    if (empty($error)) {
        $submit = $conn->query("update role set passcode = '" . $pass1 . "' where user_id =" . $_SESSION['id']);
        if ($submit) {
            $pass_status = "<div class='alert alert-success text-center'>Passcode Saved.</div>";
            $input_pass1 = $input_pass2 = "";
            $passcode_set = "set";
            $_SESSION['authenticate'] = true;
            header("refresh:3; url=dashboard.php");
        } else {
            $pass_status = "<div class='alert alert-danger text-center'><b>Error:</b> Something went wrong!</div>";
        }
    }
}

//ON Procced

if (isset($_POST['proceed'])) {
    $input_pass = $_POST['passcode'];

    if (empty($input_pass)) {
        $pass_err = "Passcode required!";
    } else {
        if (password_verify($input_pass, $perm['passcode'])) {
            $_SESSION['authenticate'] = true;
            header('location: dashboard.php');
        } else {
            $pass_err = "Incorrect passcode!";
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Authentication </title>


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
    <?php

    if (empty($perm['passcode'])) {
    ?>


        <!-- new page -->


        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper justify-content-center d-none d-lg-flex">
                    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
                        <a class="navbar-brand brand-logo" href="../">
                            <h4 class="text-dark">Daily<span class='bg-primary rounded text-white p-1'>Crypto</span>Returns<span class='text-primary'><b>.</b></span></h4>
                        </a>
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                            <span class="mdi mdi-sort-variant text-primary"></span>
                        </button>
                    </div>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between flex-fill">
                    <a class="d-lg-none" href="../">
                        <h4 class="text-dark">Daily<span class='bg-primary rounded text-white p-1'>Crypto</span>Returns<span class='text-primary'><b>.</b></span></h4>
                    </a>

                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="images/faces/face5.jpg" alt="profile" />
                                <span class="nav-profile-name">
                                    <?php echo $_SESSION['username']; ?>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="../logout">
                                    <i class="mdi mdi-logout text-primary"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- Main -->
            <div class="container page-body-wrapper">

                <!-- Main content-->
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-4 mt-5 mx-auto">
                            <div class="shadow rounded">
                                <div class="card rounded">
                                    <div class="card-body">
                                        <h4 class="card-tittle pl-4 text-primary">Choose Passcode</h4>

                                        <hr class="bg-primary">
                                        <p class="text-center"><i>Passcode required for added security</i></p>
                                        <?php echo $pass_status; ?>
                                        <form action="index.php" method="post" class="p-4">
                                            <div class="form-group">
                                                <label for="Passcode">New Passcode:</label>
                                                <input type="password" name="passcode1" id="passcode1" class="form-control" minlength="5" required value="<?php echo $input_pass1; ?>">
                                                <span class="help-block text-danger"><?php echo $pass1_err; ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="Passcode">Confirm Passcode:</label>
                                                <input type="password" name="passcode2" id="passcode2" class="form-control" minlength="5" required value="<?php echo $input_pass2; ?>">
                                                <span class="help-block text-danger"><?php echo $pass2_err; ?></span>
                                            </div>
                                            <button type="submit" class="btn btn-block btn-outline-primary" name="submit">Submit</button>
                                        </form>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>


                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-center">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            FXTradeIQ <script>new Date().getFullYear();</script></span>

                    </div>
                </footer>
                <!-- partial -->0
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>



        -->
    <?php } else if (empty($passcode_set)) {
    ?>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper justify-content-center d-none d-lg-flex">
                    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
                        <a class="navbar-brand brand-logo" href="../">
                            <h4 class="text-dark">Daily<span class='bg-primary rounded text-white p-1'>Crypto</span>Returns<span class='text-primary'><b>.</b></span></h4>
                        </a>
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                            <span class="mdi mdi-sort-variant text-primary"></span>
                        </button>
                    </div>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between flex-fill">
                    <a class="d-lg-none" href="../">
                        <h4 class="text-dark">Smart<span class='bg-primary rounded text-white px-1'>FX</span>Crypto<span class='text-primary'><b>.</b></span></h4>
                    </a>

                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="images/faces/face5.jpg" alt="profile" />
                                <span class="nav-profile-name">
                                    <?php echo $_SESSION['username']; ?>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="../logout">
                                    <i class="mdi mdi-logout text-primary"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- Main -->
            <div class="container page-body-wrapper">

                <!-- Main content-->
                <div class="container">


                    <div class="row mt-3">
                        <div class="col-md-6 col-lg-4 mx-auto mt-5">
                            <div class="shadow">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-tittle text-center text-primary"><i class="mdi mdi-lock"></i>Authentication</h4>
                                        <hr class="bg-primary">
                                        <p class="text-center card-text"><i>Additional information
                                                required for
                                                user
                                                authentication.</i></p>

                                        <form action="index.php" method="post" class="mt-5">
                                            <div class="form-group">
                                                <label for="Passcode">Passcode:</label>
                                                <input type="password" name="passcode" id="passcode" class="form-control" required>
                                                <span class="help-block text-danger"><?php echo $pass_err; ?></span>
                                            </div>
                                            <p>Forgot Passcode? Conatct <b>Portal Admin</b>.</p>
                                            <button type="submit" class="btn btn-block btn-outline-primary" name="proceed">Proceed</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->

                <!-- partial -->

                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>


    <?php
    }


    ?>



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