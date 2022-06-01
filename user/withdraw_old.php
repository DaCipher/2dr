<?php
session_start();

require "../middleware/auth.php";
// Validate user session

if (!isset($_SESSION['login']) || $_SESSION['login'] != true) {
    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
    exit();
} else {
    $data = getSingleRecord('investment', 'user_id', $_SESSION['id']);
    $level = getSingleRecord('users', 'id', $_SESSION['id']);
    if ($level['status'] === 'active') {
        if ($_SESSION['role'] !== 'user') {
            header('location: ../admin');
        }
    } else {
        header('location: ../logout');
    }
}






$status = $wallet_err = $amount_err = "";
if (isset($_POST['submit'])) {
    $balance = $_SESSION['balance'];
    $amount = $_POST['amount'];
    $wallet = trim($_POST['wallet']);
    $state = $data['level'];
    $error = '';
    //Validate Amount
    if (empty($amount)) {
        $error = "set";
        $amount_err = "Amount is required!";
    } else if ($amount < 100) {
        $error = "set";
        $amount_err = "Minimum withdrawal is $100!";
    }

    // validate wallet
    if (empty($wallet)) {
        $error = "set";
        $wallet_err = "Wallet address required for withdrawals!";
    } else if (strlen($wallet) < 15) {
        $error = "set";
        $wallet_err = "Invalid Wallet address!";
    }


    if (empty($error)) {
        // Validate balance 
        if ($amount > $balance) {
            $status = '<div class="alert alert-danger text-center" role= "alert"><b>Error:  </b> Insufficient funds!</div>';
        } else {
            if ($state === "pending") {
                $date = date("Y-m-d");
                $pending = $conn->query('INSERT INTO transactions (user_id, type, amount, date, status) VALUES (' . $_SESSION['id'] . ', "withdraw", ' . $amount . ', "' . $date . '", "pending")');
                if ($pending) {
                    $status = '<div class="alert alert-success text-center" role= "alert">Withdrawal request submitted.</div>';
                }
            } else if ($state === "upgrade") {
                $status =  '<div class="alert alert-danger text-center" role= "alert">Account Upgrade required!</div>';
            } else if ($state === "authenticate") {
                $status = '<div class="alert alert-danger text-center" role= "alert">Wallet authentication required!</div>';
            } else if ($state === "release") {
                $status = '<div class="alert alert-danger text-center" role= "alert">Profit release required!</div>';
            } else if (empty($state)) {
                $status = '<div class="alert alert-danger text-center" role= "alert"><b>Error:  </b> Something went wrong!</div>';
            } else {
                $status = '<div class="alert alert-danger text-center" role= "alert"><b>Error:  </b> Unable to submit request!</div>';
            }
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
        <title>Withraw Funds - SmartFXCrypto</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- plugin css for this page -->
        <!-- End plugin css for this page -->
        <!-- inject:css -->
        <link rel="stylesheet" href="css/style.css">
        <!-- endinject -->
        <link rel="shortcut icon" href="images/favicon.png">
    </head>

    <body style="min-width: 300px;">
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper justify-content-center d-none d-lg-flex">
                    <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
                        <a class="navbar-brand brand-logo" href="../">
                            <h4 class="text-dark">Smart<span class='bg-primary rounded text-white px-1'>FX</span>Crypto<span class='text-primary'><b>.</b></span></h4>
                        </a>
                        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                            <span class="mdi mdi-sort-variant text-primary"></span>
                        </button>
                    </div>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end flex-fill">
                    <a class="d-lg-none" href="../">
                        <h4 class="text-dark">Smart<span class='bg-primary rounded text-white px-1'>FX</span>Crypto<span class='text-primary'><b>.</b></span></h4>
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
                                <input type="text" class="form-control-plaintext" value="Session ID: <?php echo $_SESSION['unique_id']; ?>" aria-label="search" aria-describedby="search">
                            </div>
                        </li>
                    </ul>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                                <img src="images/faces/face5.jpg" alt="profile" />
                                <span class="nav-profile-name"><?php echo $_SESSION['username']; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" class="settings.php">
                                    <i class="mdi mdi-settings text-primary"></i> Settings
                                </a>
                                <a class="dropdown-item" href="../logout">
                                    <i class="mdi mdi-logout text-primary"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
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
                            <a class="nav-link" href="deposit.php">
                                <i class="mdi mdi-cash-multiple menu-icon"></i>
                                <span class="menu-title">Fund Account</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="withdraw.php">
                                <i class="mdi mdi-wallet menu-icon"></i>
                                <span class="menu-title">Withdraw</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="transaction.php">
                                <i class="mdi mdi-square-inc-cash menu-icon"></i>
                                <span class="menu-title">Transaction History</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                                <i class="mdi mdi-account menu-icon"></i>
                                <span class="menu-title">Account</span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="collapse" id="auth">
                                <ul class="nav mb-0">
                                    <li class="nav-item ml-4"> <a class="nav-link" href="profile.php"><i
                                                class="mdi mdi-account menu-icon"></i> Profile </a></li>
                                    <li class="nav-item ml-4"> <a class="nav-link" href="settings.php"><i
                                                class="mdi mdi-settings menu-icon"></i> Settings </a></li>
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

                                            <h2>Withdraw</h2>
                                            <div class="d-flex">
                                                <i class="mdi mdi-home text-muted hover-cursor"></i>
                                                <p class="text-muted mb-0 hover-cursor">
                                                    &nbsp;/&nbsp;Transactions&nbsp;/&nbsp;</p>
                                                <p class="text-primary mb-0 hover-cursor">Withdrawal</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Dashboard Header Ends here-->
                        <!--Page Contents Here-->
                        <div class="row">
                            <div class="col-md-8 col-lg-6 mx-auto mt-5">
                                <div class="shadow">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-tittle text-center">Withdrawal Form</h4>
                                            <div class="text-right my-3">
                                                <span class="p-2 font-weight-bold text-primary shadow rounded" style="background-color: rgba(240, 239, 239, 0.877);">Current Balance: $<?php echo number_format($_SESSION['balance']); ?>
                                                </span>
                                            </div>

                                            <?php echo $status; ?>
                                            <form action="withdraw.php" method="post">
                                                <div class="form-group">
                                                    <label for="amount">Amount</label>
                                                    <input type="number" name="amount" id="amount" class="form-control" min="100" required>
                                                    <span class="help-block text-danger" id="amount_err"><?php echo $amount_err; ?></span>
                                                </div>
                                                <div class="form-group">
                                                    <label for="wallet">BTC Wallet Address</label>
                                                    <input type="text" name="wallet" id="wallet" class="form-control" required minlength="10" value="<?php echo $level['wallet']; ?>">
                                                    <span class="help-block text-danger" id="wallet_err"><?php echo $wallet_err; ?></span>
                                                </div>

                                                <button type="submit" class="btn btn-primary my-1" id="submit" name="submit">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!--Page contents Ends here-->
                    </div>
                    <!-- content-wrapper ends -->
                    <!-- partial:partials/_footer.html -->
                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-center">
                            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                                SmartFXCrypto
                                2021</span>

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