<?php

require "../middleware/auth.php";


// Validate username Via ajax
if (isset($_POST['check']) && $_POST['check'] == "uname") {
    $data = input(trim($_POST['field']));
    if (username($data)) {
        if (exist("username", "users", $data)) {
            $response = "taken";
        } else {
            $response = "available";
        }
    } else {
        $response = "invalid";
    }

    exit(json_encode($response));
}

// Validate email with Ajax
if (isset($_POST['check']) && $_POST['check'] == "email") {
    $data = input(trim($_POST['field']));
    if (email($data)) {
        if (exist("email", "users", $data)) {
            $response = "taken";
        } else {
            $response = "available";
        }
    } else {
        $response = "invalid";
    }

    exit(json_encode($response));
}
// Validate firstname with Ajax
if (isset($_POST['check']) && $_POST['check'] == "fname") {
    $data = input(trim($_POST['field']));
    if (field($data)) {
        $response = "true";
    } else {
        $response = "false";
    }
    exit(json_encode($response));
}

// Validate Middlename with Ajax
if (isset($_POST['check']) && $_POST['check'] == "mname") {
    $data = input(trim($_POST['field']));
    if (field($data)) {
        $response = "true";
    } else {
        $response = "false";
    }
    exit(json_encode($response));
}


// Validate firstname with Ajax
if (isset($_POST['check']) && $_POST['check'] == "lname") {
    $data = input(trim($_POST['field']));
    if (field($data)) {
        $response = "true";
    } else {
        $response = "false";
    }
    exit(json_encode($response));
}
// Sign Up validation Ajax combo

// Intialis errror variables
$fname_err = $mname_err = $lname_err = $uname_err = $phone_err = "";
$email_err = $country_err = $psw1_err = $psw2_err = $error = $status = "";
$input_fname = $input_lname = $input_mname = $input_email = $input_phone = "";
$input_uname = $input_wallet = $input_psw1 = $input_psw2 = $input_country = "";
$reply = [];

//If datas are sent
if (isset($_POST['sign_up'])) {
    // get inputs
    $input_fname = input($_POST['firstname']);
    $input_lname = input($_POST['lastname']);
    $input_mname = input($_POST['middlename']);
    $input_uname = input($_POST['username']);
    $input_email = input($_POST['email']);
    $input_phone = input($_POST['phone']);
    $input_country = trim($_POST['country']);
    $input_wallet = trim($_POST['wallet']);
    $input_psw1 = $_POST['password1'];
    $input_psw2 = $_POST['password2'];


    // validate firstname
    if (empty($input_fname)) {
        $error = "set";
        $fname_err = "Field is required!";
        $reply['fname_err'] = $fname_err;
    } else {
        if (field($input_fname)) {
            $firstname = $input_fname;
        } else {
            $error = "set";
            $fname_err = "Invalid name!";
            $reply['fname_err'] = $fname_err;
        }
    }

    // Validate lastname
    if (empty($input_lname)) {
        $error = "set";
        $lname_err = "Field is required";
        $reply['lname_err'] = $lname_err;
    } else {
        if (field($input_lname)) {
            $lastname = $input_lname;
        } else {
            $error = "set";
            $lname_err = "Invalid name!";
            $reply['lname_err'] = $lname_err;
        }
    }

    // validate middle name
    if (!empty($input_mname)) {
        if (field($input_mname)) {
            $middlename = $input_mname;
        } else {
            $error = "set";
            $mname_err = "Invalid name!";
            $reply['mname_err'] = $mname_err;
        }
    } else {
        $middlename = $input_mname;
    }

    // validate username
    if (empty($input_uname)) {
        $error = "set";
        $uname_err = "Username required!";
        $reply['uname_err'] = $uname_err;
    } else {
        if (username($input_uname)) {
            if (exist("username", "users", $input_uname)) {
                $error = "set";
                $uname_err = "Username taken!";
                $reply["uname_err"] = $uname_err;
            } else {
                $username = $input_uname;
            }
        } else {
            $error = "set";
            $uname_err = "Invalid username!";
            $reply['uname_err'] = $uname_err;
        }
    }

    // validate email
    if (empty($input_email)) {
        $error = "set";
        $email_err = "Email required";
        $reply['email_err'] = $email_err;
    } else {
        if (email($input_email)) {
            if (exist("email", "users", $input_email)) {
                $error = "set";
                $email_err = "Email already in use!";
                $reply['email_err'] = $email_err;
            } else {
                $email = $input_email;
            }
        } else {
            $error = "set";
            $email_err = "Invalid email!";
            $reply['email_err'] = $email_err;
        }
    }

    // validate phone 
    if (empty($input_phone)) {
        $error = "set";
        $phone_err = "Phone number required!";
        $reply['phone_err'] = $phone_err;
    } else {
        $phone = $input_phone;
    }

    // validate country
    if (empty($input_country)) {
        $error = "set";
        $country_err = "Country required!";
        $reply["country_err"] = $country_err;
    } else {
        $country = $input_country;
    }

    // Validate password 1 and 2
    if (empty($input_psw1)) {
        $error = "set";
        $psw1_err = "Password required!";
        $reply['psw1_err'] = $psw1_err;
    } else {
        if (empty($input_psw2)) {
            $error = "set";
            $psw2_err = "Confirm password!";
            $reply['psw2_err'] = $psw2_err;
        } else {
            if ($input_psw1 !== $input_psw2) {
                $error = "set";
                $psw2_err = "Passwords do not match!";
                $reply['psw2_err'] = $psw2_err;
            } else {
                $password = password_hash($input_psw1, PASSWORD_BCRYPT);
            }
        }
    }

    // Check for !error then insert -> DB
    if (empty($error)) {
        // insert statemnt for user details
        $sql = "INSERT INTO users (firstname, middlename, lastname, username, email, phone, password, wallet, country, reg_date) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // Statement for investment

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $param_fname, $param_mname, $param_lname, $param_uname, $param_email, $param_phone, $param_psw, $param_wallet, $param_country, $para_date);
        // Assign Variables
        $param_fname = $firstname;
        $param_mname = $middlename;
        $param_lname = $lastname;
        $param_uname = $username;
        $param_email = $email;
        $param_phone = $phone;
        $param_psw = $password;
        $param_wallet = $input_wallet;
        $param_country = $country;
        $date = date("Y-m-d H:i:s");
        $para_date = date("Y-m-d");
        if ($stmt->execute()) {
            $inv_id = $conn->insert_id;
            $conn->query("INSERT into investment (user_id, reg_time) VALUES ('$inv_id', '$date')");
            $status = "<div class='alert alert-success' role='alert'>Congrats! Sign Up Successful!<br> Proceed to <a class='alert-link'href='signin.php'  style='display: inline; padding: 0; margin: 0;'>Sign in</a> page</div>";
            $reply['success'] = "Signup successful. Proceed to login";
            $input_fname = $input_lname = $input_mname = $input_email = $input_phone = "";
            $input_uname = $input_wallet = $input_psw1 = $input_psw2 = $input_country = "";
        } else {
            $status = '<div class="alert alert-primary alert-dismissible fade show" role="alert">
            <strong>Error:</strong> Something went wrong!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
            $reply['fail'] = "<b>Error:</b>Something Went wrong!";
        }
    }

    exit(json_encode($reply));
}










?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Sign Up - Daily Crypto Return</title>
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
                    <div class="logo mr-auto"><a href="../">Daily<span class="bg-primary text-white rounded px-2">Crypto</span>Return<span>.</span></a></div>
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
                                    <li><a href="signin.php">Sign In</a></li>
                                    <li class="active"><a href="">Sign Up</a></li>
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
                    <li>Sign Up</li>
                </ol>


            </div>
        </section>
        <!-- End Breadcrumbs -->

        <section class="inner-page d-flex bg-light">
            <div class="container" data-aos="fade-up">
                <div class="row">
                    <div class="col-lg-6 col-md-8 m-auto">
                        <div class="shadow-lg bg-white rounded p-3" id="success">
                            <h2>Sign Up</h2>
                            <hr class="bg-primary">
                            <?php echo $status; ?>
                            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" id="sign-up-form" class="sign-up-form p-2" method="POST" autocomplete="off">
                                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fullname">First Name</label>
                                            <input type="text" name="firstname" id="firstname" class="form-control" required value="<?php echo $input_fname; ?>">
                                            <span class="help-block text-danger" id="firstname_err"><?php echo $fname_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Last Name</label>
                                            <input type="text" name="lastname" id="lastname" class="form-control" required value="<?php echo $input_lname; ?>">
                                            <span class="help-block text-danger" id="lastname_err"><?php echo $lname_err; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fullname">Middle Name</label>
                                            <input type="text" name="middlename" id="middlename" class="form-control" value="<?php echo $input_mname; ?>">
                                            <span class="help-block text-danger" id="middlename_err"><?php echo $mname_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" id="username" class="form-control" required minlength="5" value="<?php echo $input_uname; ?>">
                                            <span class="help-block text-danger" id="username_err"><?php echo $uname_err; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" id="email" class="form-control" required value="<?php echo $input_email; ?>">
                                            <span class="help-block text-danger" id="email_err"><?php echo $email_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="text" name="phone" id="phone" class="form-control" required minlength="10" value="<?php echo $input_phone; ?>">
                                            <span class="help-block text-danger" id="phone_err"><?php echo $phone_err; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Country</label>
                                            <select name="country" class="countries order-alpha form-control" id="countryId" required>
                                                <option value="">Select Country</option>
                                            </select>
                                            <select name="state" class="states order-alpha d-none" id="stateId">
                                                <option value="">Select State</option>
                                            </select>
                                            <span class="help-block text-danger" id="country_err"><?php echo $country_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="wallet">Wallet</label>
                                            <input type="text" name="wallet" id="wallet" class="form-control" value="<?php echo $input_wallet; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password1">Password</label>
                                            <input type="password" name="password1" id="password1" class="form-control" required>
                                            <span class="help-block text-danger" id="password1_err"><?php echo $psw1_err; ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password2">Confirm Password</label>
                                            <input type="password" name="password2" id="password2" class="form-control" required>
                                            <span class="help-block text-danger" id="password2_err"><?php echo $psw2_err; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-none" id="status"></div>
                                <div class="mx-auto">
                                    <input type="hidden" name="sign_up" value="sign_up">
                                    <button class="btn btn-primary rounded btn-block" id="btn-sign-up" type="submit">
                                        Sign Up
                                    </button>
                                </div>

                                <p class="mt-3 text-center">Already have an account? <a href="signin.php"> Sign
                                        In</a> </p>
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
                            Best Digital Crypto FX Investment and Trading Platform.

                        </p>
                    </div>

                    <div class=" col-lg-4 footer-links ml-md-auto">
                        <h4>Useful Links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="../">Home</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="../#about">About us</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="../#services">Services</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="signin.php">Sign In</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="" class="text-primary">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 footer-links ml-md-auto">
                        <h4>Contact</h4>
                        <ul>
                            <li class="mt-4"><i class="icofont-location-arrow p-2 text-primary mt-n3"></i>10100
                                Santa Monica Blvd
                                #2200 Los Angeles, CA 90067. United States.</li>
                            <li><i class="icofont-email p-2 text-primary mt-1"></i> support@dailycryptoreturn.com</li>
                            <li><i class="icofont-whatsapp p-2 text-success"></i> +1 412 912 7001</li>

                        </ul>
                    </div>


                </div>
            </div>
        </div>

        <div class="container d-md-flex py-4">

            <div class="m-md-auto text-center text-md-center">
                <div class="copyright">
                    &copy; Copyright <strong><span>Daily<span class="text-primary">Crypto</span>Return</strong>. All
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

    <!-- Geo Data API-->
    <script src="//geodata.solutions/includes/countrystate.js"></script>



</body>

</html>