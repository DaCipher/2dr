<?php
session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] === "user") {
        header("location: ../user");
    } else {
        header("location: ../admin");
    }
}


if (isset($_GET['postLogin']) && !empty($_GET['postLogin'])) {
    $postLogin = $_GET['postLogin'];
} else {
    $postLogin = "";
}


require "../middleware/auth.php";


if (isset($_POST['sign_in'])) {
    $username = input($_POST["username"]);
    $password = $_POST["password"];
    $redirect = $_POST['redirect'];
    $response = [];
    $error = "";

    if (empty($username)) {
        $error = "set";
        $response["uname_err"] = "Username required!";
    }

    if (empty($password)) {
        $error = "set";
        $response['psw_err'] = "Password required!";
    }

    if (!empty($username) && !empty($password)) { // if fields are not empty
        if (!exist("username", "users", $username)) { // check  if username exsit in DB
            $error = "set";
            $response['uname_err'] = "Username does not exist";
        } else {
            $data = getSingleRecord("users", "username", $username); // get user record if exist
            $data1 = getSingleRecord('investment', "user_id", $data['id']);
            if (password_verify($password, $data['password'])) { // if password match set basic session variables
                if ($data['status'] !== 'disabled') {

                    date_default_timezone_set("Europe/London");
                    $date = date("Y-m-d H:i:s") . " UTC";
                    if ($data['last_login'] === NULL) {
                        $_SESSION['last_login'] = $date;
                    } else {
                        $_SESSION['last_login'] = $data['last_login'];
                    }
                    $conn->query("UPDATE users SET last_login = '" . $date . "' WHERE id = " . $data['id']);
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['firstname'] = $data['firstname'];
                    $_SESSION['unique_id'] = bin2hex(random_bytes(32));
                    if (isAdmin("role", $data['id'])) { // check if admin
                        // GEt all admin priviledges
                        $_SESSION['admins'] = array("agent_1", "agent_2", "admin_1", "admin_2", 'admin_3', 'admin_4');
                        $perm = getSingleRecord("role", "user_id", $data['id']); //Get admin role
                        $_SESSION['role'] = $perm['role'];
                        if ($redirect == "") { // if postLogin is set
                            $response['redirect'] = "../admin";
                        } else {
                            $response['redirect'] = $redirect;
                        }
                    } else {
                        $_SESSION['role'] = "user";
                        $deposit_sql = $conn->query("SELECT sum(amount) FROM transactions WHERE user_id =" . $data['id'] . " AND type ='deposit' AND status = 'completed'");
                        if ($deposit_sql->num_rows > 0) {
                            $deposit = $deposit_sql->fetch_assoc();
                            $_SESSION['deposits'] = number_format($deposit['sum(amount)']);
                        } else {
                            $_SESSION['deposits'] = "0";
                        }
                        $withdraw_sql = $conn->query("SELECT sum(amount) FROM transactions WHERE user_id =" . $data['id'] . " AND type ='withdraw' AND status = 'completed'");
                        if ($withdraw_sql->num_rows > 0) {
                            $withdraw = $withdraw_sql->fetch_assoc();
                            $_SESSION['withdraws'] = number_format($withdraw['sum(amount)']);
                        } else {
                            $_SESSION['withdraws'] = "0";
                        }
                        $_SESSION['balance'] = $data1['balance'];

                        if ($redirect == "") { // if postLogin is set for User
                            $response['redirect'] = "../user/index.php";
                        } else {
                            $response['redirect'] = $redirect;
                        }
                    }
                } else {
                    $response['disabled'] = 'Account disabled! <a href="../#contact" class="alert-link" style="display: inline; padding: 0;"> Contact</a> support.';
                }
            } else {
                $response['psw_err'] = "Incorrect password!";
            }
        }
    }
    exit(json_encode($response));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sign In - Daily Crypto Return</title>
    <meta content="Crytpo Currency, Stocks, Indices, Foreign Exchange, Binary" name="Global Cryptocurrency, Forex Trading and Investment">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="../assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="../assets/vendor/venobox/venobox.css" rel="stylesheet">
    <link href="../assets/vendor/aos/aos.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="../assets/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Presento - v1.1.1
  * Template URL: https://bootstrapmade.com/presento-bootstrap-corporate-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-10 d-flex align-items-center">
                    <div class="logo mr-auto"><a href="../">Daily<span class="bg-primary text-white rounded px-2">Cypto</span>Return<span>.</span></a></div>
                    <!-- Uncomment below if you prefer to use an image logo -->
                    <!-- <div class="border border-primary">
                    <a href="index.html" class="logo mr-auto">

                    </a>
                </div> -->

                    <nav class="nav-menu d-none d-lg-block">
                        <ul>
                            <li><a href="../">Home</a></li>
                            <li><a href="../#about">About</a></li>
                            <li><a href="../#services">Services</a></li>
                            <li><a href="../#market">Overview</a></li>
                            <li><a href="../#team">Team</a></li>

                            <li class="drop-down active"><a>Account</a>
                                <ul>
                                    <li class="active"><a href="#">Sign In</a></li>
                                    <li><a href="signup.php">Sign Up</a></li>
                                </ul>
                            </li>
                            <li><a href="../#contact">Contact</a></li>
                        </ul>
                    </nav>
                    <!-- .nav-menu -->


                </div>
            </div>

        </div>
    </header>
    <!-- End Header -->

    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li>Account</li>
                    <li>Sign In</li>
                </ol>


            </div>
        </section>
        <!-- End Breadcrumbs -->

        <section class="inner-page d-flex bg-light">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-4 col-md-6 m-auto">
                        <div class="shadow-lg bg-white rounded p-3">
                            <h2>Sign In</h2>
                            <hr class="bg-primary">
                            <div id="signIn_status" class="alert text-center"></div>
                            <div id="signIn_disabled" class="alert text-center">
                            </div>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="sign-in-form p-2" method="POST">

                                <div class="form-group">
                                    <label for="uname">Username</label>
                                    <input type="text" name="username" id="username" class="form-control" required>
                                    <span class="help-block text-danger" id="username_err"></span>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <span class="help-block text-danger" id="password_err"></span>
                                </div>
                                <input type="hidden" name="redirect" value="<?php echo $postLogin; ?>">
                                <input type="hidden" name="sign_in" value="sign_in">
                                <p class="mt-3 text-center">Don't have an account? <a href="signup.php"> Sign
                                        Up</a> now </p>
                                <button class="btn btn-primary btn-block" id="btn-sign-in" type="submit" name="sign_in">
                                    Sign In
                                </button>
                            </form>
                        </div>
                    </div>
                </div>




            </div>
        </section>

    </main>
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">

        <div class="footer-top">
            <div class="container">
                <div class="row mx-md-auto">

                    <div class="col-lg-4 footer-contact">
                        <h3>Daily<span class="bg-primary text-white rounded px-2">Crypto</span>Return<span>.</span></h3>
                        <p>
                            The World Leading Investment and Trading Platform.

                        </p>
                    </div>

                    <div class="col-lg-4 footer-links ml-md-auto">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="../">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="../#about">About us</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="../#services">Services</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="" class="text-primary">Sign In</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="signup.php">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 footer-links ml-md-auto">
                        <h4>Contact</h4>
                        <ul>
                            <li class="mt-4"><i class="icofont-location-arrow p-2 text-primary mt-n3"></i>10100
                                Santa Monica Blvd
                                #2200 Los Angeles, CA 90067. United States.</li>
                            <li><i class="icofont-email p-2 text-primary mt-1"></i> support@newname.com</li>
                            <li><i class="icofont-whatsapp p-2 text-success"></i> +1 412 912 7001</li>

                        </ul>
                    </div>


                </div>
            </div>
        </div>

        <div class="container d-md-flex py-4">

            <div class="m-md-auto text-center text-md-center">
                <div class="copyright">
                    &copy; Copyright <strong><span>Daily<span class="text-primary"> Crypto</span> Return</strong>. All
                    Rights Reserved
                </div>

            </div>
        </div>
    </footer>
    <!-- End Footer -->

    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <!-- Vendor JS Files -->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/vendor/jquery.easing/jquery.easing.min.js"></script>
    <script src="validate.js"></script>
    <script src="../assets/vendor/owl.carousel/owl.carousel.min.js"></script>
    <script src="../assets/vendor/waypoints/jquery.waypoints.min.js"></script>
    <script src="../assets/vendor/counterup/counterup.min.js"></script>
    <script src="../assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="../assets/vendor/venobox/venobox.min.js"></script>
    <script src="../assets/vendor/aos/aos.js"></script>

    <!-- Template Main JS File -->
    <script src="../assets/js/main.js"></script>

</body>

</html>