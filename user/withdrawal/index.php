<?php

session_start();

require "../../middleware/auth.php";

if (!isset($_SESSION['withdrawal'])) {

    header("location:" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/user/withdraw.php");

    exit();
}

$data = getSingleRecord('investment', 'user_id', $_SESSION['id']);

$coc_error = $cot_error = "";



if (isset($_POST['submit'])) {

    if (isset($_POST['coc_code'])) {

        if ($data['coc_code'] != trim($_POST['coc_code'])) {

            $coc_error = "Code Incorrect!";
        } else {

            resetCode("coc_code", $_SESSION['id']);

            $_SESSION['code'] = "cot";
        }
    } elseif (isset($_POST['cot_code'])) {

        if ($data['cot_code'] != trim($_POST['cot_code'])) {

            $cot_error = "Code Incorrect!";
        } else {

            resetCode("cot_code", $_SESSION['id']);

            if (withdrawal($_SESSION['withdrawal'])) {

                $_SESSION['withdrawal_status'] = "success";

                unset($_SESSION['withdrawal']);

                unset($_SESSION['code']);

                header("location:" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/user/withdrawal/success/");
            } else {

                unset($_SESSION['withdrawal']);

                unset($_SESSION['code']);

                header("location:" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/user/withdrawal/success/");

                $_SESSION['withdrawal_status'] = "fail";
            }
        }
    }
}







?>

<!DOCTYPE html>

<html lang="en">



<head>



    <title>Withraw Funds - Daily Crypto Returns</title>

    <?php include "../partials/head.php"; ?>

</head>



<body style="min-width: 300px;">

    <div class="container-scroller">

        <!-- partial:partials/_navbar.html -->

        <?php include "../partials/navbar.php"; ?>

        <!-- partial -->

        <div class="container-fluid page-body-wrapper">

            <!-- partial:partials/_sidebar.html -->

            <?php include "../partials/sidebar.php"; ?>

            <!-- partial Ends -->



            <!-- Page Headers-->

            <div class="main-panel">

                <div class="content-wrapper">



                    <div class="row">

                        <div class="col-md-12 grid-margin">

                            <div class="d-flex justify-content-between flex-wrap">

                                <div class="d-flex align-items-end flex-wrap">

                                    <div class="mr-md-3 mr-xl-5">



                                        <h2>Withdrawal</h2>

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

                        <div class="col-md-8 col-lg-4 mx-auto mt-5">

                            <?php if (isset($_SESSION['code'])) : ?>

                                <div class="shadow">

                                    <div class="card py-3">



                                        <h4 class="my-3 text-center">Withdrawal Request</h4>

                                        <?php if ($_SESSION['code'] == "coc") : ?>

                                            <div class="card-body">

                                                <div class="alert alert-success">A 15% commission fee is charged on closed/ended trades. A COC code is issued after payment of commission.</div>

                                                <form action="" method="post">

                                                    <div class="mb-3" style="margin-top: 55px!important;">

                                                        <label for="" class="form-label font-weight-bold">Enter COC Code</label>

                                                        <input type="text" name="coc_code" id="" class="form-control" placeholder="" aria-describedby="helpId" required>

                                                        <div id="helpId" class="text-primary help-block"><?= $coc_error; ?></div>

                                                        <div class="my-4">

                                                            <button type="submit" class="btn btn-primary btn-block" name="submit">Submit</button>

                                                        </div>

                                                    </div>

                                                </form>



                                            </div>

                                        <?php elseif ($_SESSION['code'] == "cot") : ?>

                                            <div class="card-body">

                                                <div class="alert alert-success">A Cost of Transfer (COT) code is required to process your request.</div>

                                                <form action="" method="post">

                                                    <div class="mb-3" style="margin-top: 55px!important;">

                                                        <label for="" class="form-label font-weight-bold">Enter COT Code</label>

                                                        <input type="text" name="cot_code" id="" class="form-control" placeholder="" aria-describedby="helpId" required>

                                                        <div id="helpId" class="text-primary help-block"><?= $cot_error; ?></div>

                                                        <div class="my-4">

                                                            <button type="submit" class="btn btn-primary btn-block" name="submit">Submit</button>

                                                        </div>

                                                    </div>

                                                </form>



                                            </div>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            <?php endif; ?>

                        </div>

                    </div>







                    <!--Page contents Ends here-->

                </div>

                <!-- content-wrapper ends -->

                <!-- partial:partials/_footer.html -->

                <?php include "../partials/footer.php"; ?>

                <!-- partial -->

            </div>

            <!-- main-panel ends -->

        </div>

        <!-- page-body-wrapper ends -->

    </div>

    <!-- container-scroller -->

    <!-- plugins:js -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- endinject -->

    <script src="../js/withdrawal.js"></script>



    <script>
        <?php if (isset($_POST['submit_bank'])) : ?>

            $("#bank_wire").addClass("show active");

            $("#btc").removeClass("show active");

        <?php endif; ?>
    </script>

    <?php include "../partials/scripts.php"; ?>



</body>



</html>