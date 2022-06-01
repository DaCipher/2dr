<?php
session_start();
if (!isset($_SESSION['withdrawal_status'])) {
    header("location:" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/user/withdraw.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Withraw - SmartFXCrypto</title>
    <?php include "../../partials/head.php"; ?>
</head>

<body style="min-width: 300px;">
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php include "../../partials/navbar.php"; ?>
        <!-- partial -->
        <div class="container page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <!-- partial Ends -->

            <!-- Page Headers-->
            <div class="container">

                <div class="row">
                    <div class="col-md-12 grid-margin pt-3">
                        <div class="d-flex align-items-end flex-wrap">
                            <div class="mr-md-3 mr-xl-5">
                                <div class="d-flex">
                                    <p class="text-primary mb-0 hover-cursor" onclick="location.assign('../../withdraw.php');">Back to Withdrawal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dashboard Header Ends here-->
                <!--Page Contents Here-->
                <div class="row" style="height: 70vh;">
                    <div class="col-md-8 col-lg-4 m-auto">
                        <?php if ($_SESSION['withdrawal_status']) : ?>
                            <div class="shadow">
                                <?php if ($_SESSION['withdrawal_status'] == "success") : ?>
                                    <div class="card py-5 text-center px-2">
                                        <div class="d-flex justify-content-center">
                                            <i class="mdi mdi-check rounded-circle text-white bg-success px-2 rounded" style="font-size: 5.8rem;"></i>
                                        </div>
                                        <div class="mt-5">
                                            <p class="font-weight-bold" style="font-size: 1.2rem;">Withdrawal Request Submitted!</p>
                                        </div>
                                    </div>
                                <?php elseif ($_SESSION['withdrawal_status'] == "fail") : ?>
                                    <div class="card pb-5 pt-4 text-center px-2">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <i class="mdi mdi-alert-circle text-danger p-2" style="font-size: 6.8rem;"></i>
                                        </div>
                                        <div class="mt-3">
                                            <p class="font-weight-bold" style="font-size: 1.2rem;">Error: Request Failed!</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php unset($_SESSION['withdrawal_status']);
                        endif; ?>
                    </div>
                </div>



                <!--Page contents Ends here-->
            </div>
            <!-- content-wrapper ends -->
            <!-- partial:partials/_footer.html -->
            <!-- partial -->

            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- endinject -->

    <?php include "../../partials/scripts.php"; ?>

</body>

</html>