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
            $status = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
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

    <title>Sign Up - Daily Digital Returns</title>
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
                    <div class="logo mr-auto"><a href="../">Daily<span class="bg-danger text-white rounded px-2">Digital</span>Returns<span>.</span></a></div>
                    <!-- Uncomment below if you prefer to use an image logo -->
                    <!-- <div class="border border-danger">
                    <a href="index.html" class="logo mr-auto">

                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="80px" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd" viewBox="0 0 884 368"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                   <defs>
                    <font id="FontID1" horiz-adv-x="711" font-variant="normal" style="fill-rule:nonzero" font-weight="400">
                    <font-face 
                      font-family="Intro ">
                    </font-face>
                     <missing-glyph><path d="M0 0z"/></missing-glyph>
                     <glyph unicode="t" horiz-adv-x="562"><path d="M192.001 540l0 -540c58.9989,0 117.999,0 177,0l0 540 171 0c0,54 0,106.999 0,160l-520.001 0c0,-53.0008 0,-106 0,-160l172.001 0z"/></glyph>
                     <glyph unicode="c" horiz-adv-x="785"><path d="M46.9999 350c1.00063,-238 181.001,-357.999 359,-357 149,0 306,77.9996 332.001,276 -61.0002,0 -116.001,0 -176,0 -19.0006,-74.9991 -79.9994,-112.999 -156.001,-112.999 -105.999,0.999213 -173.999,90.9993 -173.999,193.999 0,116.001 68.9996,195 173.999,195 75.0005,0 128,-31.9989 153,-103 61.0002,0 115,0 176,0 -27,187 -184,266.001 -329.001,266.001 -177.999,0 -357.999,-120.001 -359,-358z"/></glyph>
                     <glyph unicode="y" horiz-adv-x="664"><path d="M421 294l236 379.001 0 27 -194 0 -127.001 -233.001 -6.99875 0 -127.001 233.001 -193 0 0 -27 234.999 -379.001 0 -294 177.001 0 0 294z"/></glyph>
                     <glyph unicode="p" horiz-adv-x="693"><path d="M268 0l0 179 128.999 0c335,0 335,520.001 0,521.001 -103,0 -207,0 -308,0 0,-234 0,-467.001 0,-700.001 59.0004,0 119,0 179.001,0zm128 340l-128 0c0,56.9991 0,142.999 0,200 40.9989,0 85.999,0.999213 128,0 104,-1.00063 97.9994,-200 0,-200z"/></glyph>
                     <glyph unicode="r" horiz-adv-x="699"><path d="M542.999 248.001c177.001,119.999 127.001,451 -148,452 -101.999,0 -206,0 -306.999,0 0,-234 0,-467.001 0,-700.001 57.9997,0 119,0 179,0l0 206 93.0005 0 119.999 -206 200 0 0 25.9994 -137 222.001zm-148 119l-128 0c0,55.9999 0,115.999 0,173 41.9996,0 86.0004,0.999213 128,0 102.001,-1.00063 97.0002,-173 0,-173z"/></glyph>
                     <glyph unicode="o" horiz-adv-x="810"><path d="M764 349c0,483.999 -717.999,483.999 -717.999,0 0,-485 717.999,-485 717.999,0zm-533.999 0c0,252 350.999,252 350.999,0 0,-254 -350.999,-254 -350.999,0z"/></glyph>
                    </font>
                    <font id="FontID0" horiz-adv-x="730" font-variant="normal" style="fill-rule:nonzero" font-style="normal" font-weight="400">
                    <font-face 
                      font-family="Bandy">
                    </font-face>
                     <missing-glyph><path d="M0 0z"/></missing-glyph>
                     <glyph unicode="t" horiz-adv-x="349"><path d="M223 515.999l99.0001 0 0 -103 -99.0001 0 0 -413 -99.0001 0 0 413 -99.0001 0 0 103 99.0001 0 0 207 99.0001 0 0 -207z"/></glyph>
                     <glyph unicode="s" horiz-adv-x="492"><path d="M25.0002 366.5c19.1665,47.5002 50.3334,86.0004 93.1664,115.834 21.9997,14.8323 44.3339,25.9994 67.167,33.4999 22.6658,7.49906 47.1671,11.1657 73.1665,11.1657 12.3336,0 23.3334,-0.498898 32.8337,-1.49953 9.49891,-0.999213 18.9992,-2.83323 28.8326,-5.66646 9.83339,-1.99984 19.501,-5.00032 28.834,-8.83276 9.49891,-3.83386 19.3323,-8.66694 29.5002,-14.5006 33.6657,-17.8328 61.9994,-42.6671 84.9998,-73.9999l-81.0001 -61.0002c-12.8339,18.5003 -30.5008,33.4999 -53.1667,45.1673 -22.6673,11.5002 -46.3337,17.3325 -70.8336,17.3325 -21.9997,0 -41.1662,-3.83244 -57.3336,-11.5002 -8.83276,-3.83244 -21.3336,-11.9991 -37.4996,-24.4999l307.333 -234c-18.8334,-47.333 -50.6665,-86.332 -95.3335,-117.499 -44.8328,-30.9997 -93.8325,-46.501 -147.166,-46.501 -19.8326,0 -38.8333,2.16709 -56.9991,6.33402 -18,4.16693 -35.3339,11.1671 -51.8344,21.0005 -14.4992,7.00016 -28.1665,15.4999 -40.9989,25.6663 -12.8339,10.1665 -25.6677,22.5 -38.5002,37.3337l74.9991 68.6665 13.1669 -13.6673 9.00001 -7.50048 23.8337 -17.1666c21.6666,-11.8332 44.1666,-17.6655 67.5001,-17.6655 16.166,0 34.3333,2.50016 54.1659,7.66631 9.16725,2.66599 17.5011,5.99953 25.1674,9.83339 7.50048,3.99969 14.4992,8.83276 21.0005,14.4992l-304 241.501z"/></glyph>
                     <glyph unicode="m" horiz-adv-x="837"><path d="M591.5 526.999c47.5002,0 87.4999,-12.6666 119.999,-37.9999 25.0002,-19.6668 45.3345,-47.4988 61.3333,-83.6661 4.66725,-13.5 8.66694,-27.6662 12.1677,-42.4999 3.33355,-14.8337 4.9989,-28.9999 4.9989,-42.4999l0 -320.334 -98.9987 0 0 320.499c0,13.1669 -2.83465,27.1673 -8.66694,41.6679 -17.1666,41.1662 -47.5002,61.8322 -90.8335,61.8322 -28.501,0 -52.1674,-10.1665 -71.0008,-30.6666 -18.9992,-20.5002 -28.4995,-44.6655 -28.4995,-72.8334l0 -320.499 -99.0001 0 0 320.499c0,13.1669 -2.83323,27.1673 -8.66694,41.6679 -17.1666,41.1662 -47.5002,61.8322 -90.8335,61.8322 -28.4995,0 -52.166,-10.1665 -70.9994,-30.6666 -19.0006,-20.5002 -28.4995,-44.6655 -28.4995,-72.8334l0 -320.499 -99.0001 0 0 373.666 -70.0002 73.501 63.8334 69.3327 60.6657 -62.8328 16.8336 18.3331c13.8331,13.1669 30.3336,25.3332 49.6673,36.1673 12.1663,5.83229 25.166,10.3323 39.0005,13.6658 13.8331,3.50079 26.6655,5.16615 38.4988,5.16615 33.1668,0 63,-8.33245 89.667,-24.9988 18.6676,-10.834 37.9999,-29.1671 58.3342,-54.8334l18.1658 20.8332c13.8331,13.5 31.3342,26.4997 52.1674,38.666 13.1655,6.83292 26.3325,11.8332 39.4994,15.1668 5.83371,1.49953 12.1663,2.6674 19.3337,3.66662 7.16599,1.00063 13.9989,1.49953 20.8332,1.49953z"/></glyph>
                     <glyph unicode="r" horiz-adv-x="488"><path d="M95.0004 0l0 373.666 -70.0002 73.501 63.8334 69.3327 50.6665 -54.1659 20.1671 16.8321c17.1666,12.1677 35.6655,22.5 55.6668,31.0011 28.9999,11.1657 58.3328,16.8321 87.9988,16.8321 32.1676,0 63.1673,-5.66646 93.5009,-17.1666 30.1663,-11.4987 55.8326,-28.3323 77.1662,-50.4993l-61.3333 -82.4996c-28.3337,31.5 -64.6668,47.1657 -109.334,47.1657 -24.6657,0 -46.3323,-4.66583 -65.333,-14.1662 -7.00016,-3.33355 -14.1662,-7.99938 -21.1663,-13.6673 -3.83386,-3.1663 -7.33323,-6.16678 -10.6668,-9.49891 -3.33355,-3.16772 -7.50048,-7.50048 -12.1663,-12.8339l0 -373.833 -99.0001 0z"/></glyph>
                     <glyph unicode="a" horiz-adv-x="575"><path d="M282.833 -10.0006c-71.1667,0 -132.165,26.5011 -182.999,79.5005 -24.334,27 -42.8329,56.5002 -55.6668,88.1675 -12.8339,31.8331 -19.1665,65.333 -19.1665,100.833 0,70.3333 25.3332,132.5 75.8325,187 50.6679,54.3331 111.334,81.499 182.167,81.499 41.5007,0 82.0007,-10.6654 121.5,-31.9989 39.3336,-21.1677 69.3327,-48.5008 89.4998,-81.5004l0 -271.167 69.833 -72.5004 -63.832 -69.833 -50.8337 52.0002 -10.1665 -7.50048 -10.6668 -8.66694c-15.1668,-9.33308 -34.6677,-19.3323 -58.6673,-29.666 -29.666,-10.834 -58.5,-16.1674 -86.8338,-16.1674zm112.167 153.335l0 230.332c-14.4992,14.6679 -30.8325,26.667 -48.9997,36.1673 -10.8326,4.66725 -21.3336,8.16662 -31.3328,10.6668 -10.1679,2.33292 -21.0005,3.49937 -32.3334,3.49937 -44.5011,0 -82.0007,-16.3332 -112.5,-48.9997 -30.5008,-32.6665 -45.8334,-71.4997 -45.8334,-116.5 0,-43.9994 15.8329,-82.4996 47.333,-115.666 31.5,-33.1668 68.5007,-49.8331 111.001,-49.8331 23.8323,0 45.3331,4.5 64.1665,13.5 18.8334,9.00001 34.9994,21.1663 48.4994,36.8334z"/></glyph>
                    </font>
                    <style type="text/css">
                     <![CDATA[
                      @font-face { font-family:"Intro ";src:url("#FontID1") format(svg)}
                      @font-face { font-family:"Bandy";src:url("#FontID0") format(svg)}
                      .fil0 {fill:#e03a3c}
                      .fnt0 {font-weight:normal;font-size:214.884px;font-family:'Bandy'}
                      .fnt1 {font-weight:normal;font-size:214.884px;font-family:'Intro '}
                     ]]>
                    </style>
                   </defs>
                   <g id="Layer_x0020_1">
                    <metadata id="CorelCorpID_0Corel-Layer"/>
                    <g id="_403937768">
                     <text x="14" y="170"  class="fil0 fnt0">Smart</text>
                     <path class="fil0" d="M672 0l116 0c22,0 40,18 40,40l0 88c0,22 -18,40 -40,40l-116 0c-22,0 -40,-18 -40,-40l0 -88c0,-22 18,-40 40,-40zm40 105c0,-8 0,-12 0,-19l-27 0 0 -19 30 0c0,-7 0,-12 0,-19 -17,0 -34,0 -51,0l0 83c7,0 14,0 21,0l0 -26 27 0zm49 0l16 26 23 0 0 -3 -27 -41 24 -35 0 -4 -22 0 -14 22 0 0 -13 -22 -23 0 0 4 24 35 -26 41 0 3 23 0 15 -26 0 0z"/>
                     <text x="-10" y="339"  class="fil0 fnt1">Crypto</text>
                    </g>
                   </g>
                  </svg>
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
                            <hr class="bg-danger">
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
                                    <button class="btn btn-danger rounded btn-block" id="btn-sign-up" type="submit">
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
                        <h3>Smart<span class="bg-danger text-white rounded px-2">FX</span>Crypto<span>.</span></h3>
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
                            <li><i class="bx bx-chevron-right"></i> <a href="" class="text-danger">Sign Up</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-4 footer-links ml-md-auto">
                        <h4>Contact</h4>
                        <ul>
                            <li class="mt-4"><i class="icofont-location-arrow p-2 text-danger mt-n3"></i>10100
                                Santa Monica Blvd
                                #2200 Los Angeles, CA 90067. United States.</li>
                            <li><i class="icofont-email p-2 text-danger mt-1"></i> support@smartfxcrypto.live</li>
                            <li><i class="icofont-whatsapp p-2 text-success"></i> +1 412 912 7001</li>

                        </ul>
                    </div>


                </div>
            </div>
        </div>

        <div class="container d-md-flex py-4">

            <div class="m-md-auto text-center text-md-center">
                <div class="copyright">
                    &copy; Copyright <strong><span>Smart<span class="text-danger">FX</span>Crypto</strong>. All
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