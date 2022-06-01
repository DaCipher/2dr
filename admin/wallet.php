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
        } else {
            if ($_SESSION['role'] !== 'admin_4') {
                header('location: dashboard.php');
            }
        }
    } else {
        header('location: ../logout');
    }
}
$data = getSingleRecord('data', 'id', '1');
$data_wallet = $data['wallet'];



$wallet_err = $status = "";

if (isset($_POST['wallet'])) {
    $input_wallet = $_POST['wallet'];

    if (empty($input_wallet)) {
        $wallet_err = "Wallet is required";
    } else {
        $wallet = $input_wallet;
    }

    if (empty($error)) {
        $sql = "UPDATE data set wallet = ? WHERE id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $param_wallet);
        $param_wallet = $wallet;
        if ($stmt->execute()) {
            $psw1 = $psw2 = "";
            $status = "<div class='alert alert-success text-center'>Wallet Update Successful.</div>";
        } else {
            $status = "<div class='alert alert-danger text-danger'>Something Went Wrong!</div>";
        }
    }
}



?>


<!DOCTYPE html>
<html lang="en">

<head>

    <title>Site Data - Smart Admin</title>

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

                                        <h2>Miscellaneous</h2>
                                        <div class="d-flex">
                                            <i class="mdi mdi-home text-muted hover-cursor"></i>
                                            <p class="text-muted mb-0 hover-cursor">
                                                &nbsp;/&nbsp;Site&nbsp;/&nbsp;</p>
                                            <p class="text-primary mb-0 hover-cursor">Data</p>
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
                                        <h5>Update Data</h5>
                                        <hr class="bg-primary">
                                        <?php echo $status; ?>
                                        <form action="wallet.php" method="post">
                                            <div class="form-group">
                                                <label for="wallet">BTC Wallet</label>
                                                <input type="text" name="wallet" class="form-control" required minlength="8" value="<?php echo $data_wallet; ?>">
                                            </div>
                                            <input type="hidden" name="wallet_update" value="update wallet">
                                            <button type="submit" class="btn btn-block btn-primary" id="wallet_btn">
                                                Update Wallet
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