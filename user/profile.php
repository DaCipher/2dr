<?php
session_start();


require "../middleware/auth.php";
// Validate user session

require "./partials/auth.php";

$user = getSingleRecord('users', "username", $_SESSION['username']);
$user_firstname = $user['firstname'];
$user_lastname = $user['lastname'];
$user_middlename = $user['middlename'];
$user_username = $user['username'];
$user_email = $user['email'];
$user_phone = $user['phone'];
$user_country = $user['country'];
$user_wallet = $user['wallet'];



// On info update
$fname_err = $mname_err = $lname_err = $uname_err = $phone_err = "";
$email_err = $country_err = $error = $status = "";
$input_fname = $input_lname = $input_mname = $input_email = $input_phone = "";
$input_uname = $input_wallet = $input_country = "";
$reply = [];

if (isset($_POST['profile'])) {
    $input_fname = input($_POST['firstname']);
    $input_lname = input($_POST['lastname']);
    $input_mname = input($_POST['middlename']);
    $input_phone = input($_POST['phone']);
    $input_country = trim($_POST['country']);
    $input_wallet = trim($_POST['wallet']);


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

    if (empty($error)) {
        $sql = "UPDATE users SET firstname = ?,  lastname = ?, middlename = ?, phone = ?, country = ?, wallet = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $param_fname, $param_lname, $param_mname, $param_phone, $param_country, $param_wallet, $param_uname);
        $param_fname = $firstname;
        $param_lname = $lastname;
        $param_mname = $middlename;
        $param_phone = $phone;
        $param_country = $country;
        $param_wallet = $input_wallet;
        $param_uname = $_SESSION['username'];
        if ($stmt->execute()) {
            $status = "<div class='alert alert-success' role='alert'>Record(s) Updated Successfully!</div>";
            $reply['success'] = "Record(s) Updated Successfully!";
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

    <title>User Profile - Daily Crypto Return</title>
    <?php include "./partials/head.php"; ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php include "./partials/navbar.php"; ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <?php include "./partials/sidebar.php"; ?>
            <!-- partial Ends -->

            <!--Dashboard Headers-->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="mr-md-3 mr-xl-5">

                                        <h2>Profile</h2>
                                        <div class="d-flex">
                                            <i class="mdi mdi-home text-muted hover-cursor"></i>
                                            <p class="text-muted mb-0 hover-cursor">&nbsp;/&nbsp;User&nbsp;/&nbsp;
                                            </p>
                                            <p class="text-primary mb-0 hover-cursor">Profile</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Dashboard Header Ends here-->
                    <!--Page Contents Here-->
                    <div class="row">
                        <div class="col-lg-6 col-md-8 mx-auto">
                            <div class="shadow">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Update Profile</h5>
                                        <hr class="bg-primary">
                                        <?php echo $status; ?>

                                        <form action="profile.php" id="profile" method="post">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="firstname">First Name</label>
                                                        <input type="text" name="firstname" id="firstname" class="form-control" value="<?php echo ucfirst($user_firstname); ?>" required>
                                                        <span class="help-block text-primary" id="firstname_err"><?php echo $fname_err; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="lastname"> Last Name</label>
                                                        <input type="text" name="lastname" id="lastname" class="form-control" value="<?php echo ucfirst($user_lastname); ?>" required>
                                                        <span class="help-block text-primary" id="lastname_err"><?php echo $lname_err; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="middlename">Middle Name</label>
                                                        <input type="text" name="middlename" id="middlename" class="form-control" value="<?php echo ucfirst($user_middlename); ?>">
                                                        <span class="help-block text-primary" id="middlename_err"><?php echo $mname_err; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="username"> Username</label>
                                                        <input type="text" name="username" id="username" class="form-control" value="<?php echo $user_username; ?>" required disabled>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $user_email; ?>" required disabled>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="phone"> Phone Number</label>
                                                        <input type="text" name="phone" id="phone" class="form-control" value="<?php echo $user_phone; ?>" required>
                                                        <span class="help-block text-primary" id="phone_err"><?php echo $phone_err; ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="email">Country</label>
                                                        <select name="country" class="countries order-alpha form-control" id="countryId" required>
                                                            <option value="">Select Country</option>
                                                            <option value="<?php echo $user_country; ?>" selected>
                                                                <?php echo $user_country; ?></option>
                                                        </select>

                                                        <span class="help-block text-primary" id="country_err"><?php echo $country_err; ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="wallet">Wallet</label>
                                                        <input type="text" name="wallet" id="wallet" class="form-control" value="<?php echo $user_wallet; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="profile" value="edit profile">
                                            <button type="submit" class="btn btn-block btn-primary" id="profile_btn">Update</button>
                                        </form>
                                        <div class="alert text-center mt-2" id="profile_status"></div>
                                    </div>

                                </div>

                            </div>
                            <?php include "./partials/footer.php"; ?>
                        </div>

                    </div>
                    <!-- main-panel ends -->
                    <?php include "./partials/scripts.php"; ?>
                </div>
                <!-- page-body-wrapper ends -->
            </div>
            <!-- container-scroller -->


            <!-- End custom js for this page-->
            <script src="js/validate.js"></script>
            <!-- Geo Data API-->
            <script src="//geodata.solutions/includes/countrystate.js"></script>
</body>

</html>