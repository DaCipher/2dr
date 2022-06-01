<?php
session_start();

require "../middleware/auth.php";
// Validate user session

if (!isset($_SESSION['login']) && $_SESSION['login'] != true) {
    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
} else if (!isset($_SESSION['authenticate']) && $_SESSION['authenticate'] !== true) {
    header("location: index.php");
} else {

    $user = getSingleRecord('users', 'id', $_SESSION['id']);
    $perm = getSingleRecord("role", 'user_id', $_SESSION["id"]);
    if ($user['status'] === 'active') {
        if (!in_array($_SESSION['role'], $_SESSION['admins'])) {
            header('location: ../user');
        }
    } else {
        header('location: ../logout');
    }
}

$status = $password1_err = $password2_err = $psw1 = $psw2 = "";




if (isset($_POST['settings'])) {
    $psw1 = $_POST['password1'];
    $psw2 = $_POST['password2'];
    $error = "";
    // Validate password 1
    if (empty($psw1)) {
        $error = "set";
        $password1_err = "Password required!";
    } else if (strlen($psw1) < 8) {
        $error = 'set';
        $password1_err = "Password must be at least 8 characters!";
    }

    // Vlaidate password 2
    if (empty($psw2)) {
        $error = 'set';
        $password2_err = "Confirmation required!";
    } else {
        if ($psw1 == $psw2) {
            $password = password_hash($psw1, PASSWORD_BCRYPT);
        } else {
            $error = "set";
            $password2_err = "Passwords doesn't match!";
        }
    }

    if (empty($error)) {
        $sql = "UPDATE users set password = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $param_psw, $param_username);
        $param_psw = $password;
        $param_username = $_SESSION['username'];
        if ($stmt->execute()) {
            $psw1 = $psw2 = "";
            $status = "<div class='alert alert-success text-center'>Password change successful.</div>";
        } else {
            $status = "<div class='alert alert-danger text-danger'>Something went wrong!</div>";
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->

    <title>Settings - Smart Admin</title>
    <?php include "../user/partials/head.php"; ?>
</head>

<body>

    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->

        <?php include "../user/partials/navbar.php"; ?>
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
                    <!-- Dashboard Header Ends here-->
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mx-auto">
                            <div class="shadow">
                                <div class="card">
                                    <div class="card-body">
                                        <h5>Change Password</h5>
                                        <hr class="bg-primary">
                                        <?php echo $status; ?>
                                        <form action="settings.php" id="settings" method="post">
                                            <div class="form-group">
                                                <label for="password1">New Password</label>
                                                <input type="password" name="password1" id="password1" class="form-control" required minlength="8" value="<?php echo $psw1; ?>">
                                                <span class="help-block text-danger" id="password1_err"><?php echo $password1_err; ?></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="password2">Confirm Password</label>
                                                <input type="password" name="password2" id="password2" class="form-control" required minlength="8" value="<?php echo $psw2; ?>">
                                                <span class="help-block text-danger" id="password2_err"><?php echo $password2_err; ?></span>
                                            </div>
                                            <input type="hidden" name="settings" value="change password">
                                            <button type="submit" class="btn btn-block btn-primary" id="settings_btn">
                                                Change Password
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- content-wrapper ends -->

                <!-- partial:partials/_footer.html -->

                <?php include "../user/partials/footer.php"; ?>

                <!-- partial -->

            </div>

            <!-- main-panel ends -->

        </div>

        <!-- page-body-wrapper ends -->

    </div>





    <!-- container-scroller -->



    <?php include "../user/partials/scripts.php"; ?>



</body>

</html>