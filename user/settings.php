<?php
session_start();

require "../middleware/auth.php";
// Validate user session

require "./partials/auth.php";

$status = $password1_err = $password2_err = "";




if (isset($_POST['settings'])) {
    $psw1 = $_POST['password1'];
    $psw2 = $_POST['password2'];
    $response = [];
    $error = "";
    // Validate password 1
    if (empty($psw1)) {
        $error = "set";
        $response['pass1_err'] = "Password required!";
    } else if (strlen($psw1) < 8) {
        $error = 'set';
        $response['pass1_err'] = "Password must be at least 8 characters!";
    }

    // Vlaidate password 2
    if (empty($psw2)) {
        $error = 'set';
        $response['pass2_err'] = "Confirmation required!";
    } else {
        if ($psw1 == $psw2) {
            $password = password_hash($psw1, PASSWORD_BCRYPT);
        } else {
            $error = "set";
            $response['pass2_err'] = "Passwords doesn't match!";
        }
    }

    if (empty($error)) {
        $sql = "UPDATE users set password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $param_psw, $param_username);
        $param_psw = $password;
        $param_username = $_SESSION['username'];
        if ($stmt->execute()) {
            $response['success'] = "Password change successful.";
        } else {
            $response['fail'] = "Something went wrong!";
        }
    }

    exit(json_encode($response));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Account Settings - Daily Crypto Return</title>
    <?php include "./partials/head.php"; ?>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php include "./partials/navbar.php"; ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <?php include "./partials/sidebar.php"; ?>
            <!-- partial Ends -->


            <!--Page Headers-->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="mr-md-3 mr-xl-5">

                                        <h2>Settings</h2>
                                        <div class="d-flex">
                                            <i class="mdi mdi-home text-muted hover-cursor"></i>
                                            <p class="text-muted mb-0 hover-cursor">
                                                &nbsp;/&nbsp;Account&nbsp;/&nbsp;</p>
                                            <p class="text-primary mb-0 hover-cursor">Settings</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Page Header Ends here-->
                    <!--Page Contents Here-->

                    <div class="row">
                        <div class="col-lg-4 col-md-6 mx-auto">
                            <div class="shadow">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Change Password</h5>
                                        <hr class="bg-primary">
                                        <div class="alert text-center" id="settings_status"></div>
                                        <form action="settings.php" id="settings" method="post">
                                            <div class="form-group">
                                                <label for="password1">New Password</label>
                                                <input type="password" name="password1" id="password1" class="form-control" required minlength="8">
                                                <span class="help-block text-primary" id="password1_err"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="password2">Confirm Password</label>
                                                <input type="password" name="password2" id="password2" class="form-control" required minlength="8">
                                                <span class="help-block text-primary" id="password2_err"></span>
                                            </div>
                                            <input type="hidden" name="settings" value="change password">
                                            <button type="submit" class="btn btn-primary btn-block" id="settings_btn">
                                                Change Password
                                            </button>
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
                <?php include "./partials/footer.php"; ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->

    <?php include "./partials/scripts.php"; ?>
    <script src="js/validate.js"></script>
</body>

</html>