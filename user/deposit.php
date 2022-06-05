<?php
session_start();

require "../middleware/auth.php";
$sql = $conn->query("SELECT * FROM data");
$info = $sql->fetch_assoc();
// Validate user session

require "./partials/auth.php";

?>




<!DOCTYPE html>
<html lang="en">

<head>

    <title>Account Funding- Daily Crypto Returns</title>
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

            <!--Page Headers-->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="mr-md-3 mr-xl-5">

                                        <h2>Account Funding</h2>
                                        <div class="d-flex">
                                            <i class="mdi mdi-home text-muted hover-cursor"></i>
                                            <p class="text-muted mb-0 hover-cursor">
                                                &nbsp;/&nbsp;Transaction&nbsp;/&nbsp;</p>
                                            <p class="text-primary mb-0 hover-cursor">Funding</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Page Header Ends here-->
                    <!--Page Contents Here-->
                    <h3 class="mt-2 mb-5">Select desired plan.</h3>

                    <div class="row">

                        <div class="col-lg-3 col-md-6">
                            <div class="shadow">
                                <div class="card bg-dark text-white text-center py-3 rounded" data-aos="fade-up" data-aos-delay="100">
                                    <div class="card-body">
                                        <h3>BASIC</h3>
                                        <h4><sup>$</sup>200</h4>
                                        <ul>
                                            <li class="list-unstyled">Bonus</li>
                                            <li class="list-unstyled">Insurance</li>
                                            <li class="list-unstyled"><s>Account Manager</s></li>
                                            <li class="list-unstyled"><s>Backup</s></li>
                                            <li class="list-unstyled"><s>Credit Card</s></li>
                                        </ul>
                                        <div class="btn-wrap">
                                            <form action="" method="post" id="basic_plan" class="my-2 text-center">
                                                <input type="number" name="basic_amount" class="form-control mb-2" placeholder="Enter Amount" id="basic_amount" min="200" max="999" required>
                                                <button type="submit" id="basic_btn" class="btn btn-outline-light mt-3" style="padding: 10px 40px 12px;">SELECT</button>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-6 mt-4 mt-md-0">
                            <div class="shadow">
                                <div class="card bg-dark text-info py-3 text-center rounded" data-aos="fade-up" data-aos-delay="200">
                                    <div class="card-body">
                                        <h3>REGULAR</h3>
                                        <h4><sup>$</sup>1,000</h4>
                                        <ul>
                                            <li class="list-unstyled">Bonus x2</li>
                                            <li class="list-unstyled">Insurance</li>
                                            <li class="list-unstyled">Acount Manager</li>
                                            <li class="list-unstyled"><s>Backup</s></li>
                                            <li class="list-unstyled"><s>Credit Card</s></li>
                                        </ul>
                                        <div class="btn-wrap">
                                            <form action="" method="post" id="regular_plan" class="my-2 text-center">
                                                <input type="number" id="regular_amount" class="form-control mb-2 border border-info" placeholder="Enter Amount" value="amount" min="1000" max="4999" required>
                                                <button type="submit" id="regular_btn" class="btn btn-outline-light mt-3" style="padding: 10px 40px 12px;">SELECT</button>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-lg-3 col-md-6 mt-4 mt-lg-0">
                            <div class="shadow">
                                <div class="card bg-dark rounded py-3 text-center text-warning" data-aos="fade-up" data-aos-delay="200">
                                    <div class="card-body">
                                        <h3>VIP </h3>
                                        <h4><sup>$</sup>5,000</h4>
                                        <ul>
                                            <li class="list-unstyled">Bonus x3</li>
                                            <li class="list-unstyled">Insurance</li>
                                            <li class="list-unstyled">Account Manager</li>
                                            <li class="list-unstyled">Backup</li>
                                            <li class="list-unstyled"><s> Credit Card</s></li>
                                        </ul>
                                        <div class="btn-wrap">
                                            <form action="" method="post" id="vip_plan" class="my-2 text-center">
                                                <input type="number" id="vip_amount" class="form-control mb-2 border border-warning" placeholder="Enter Amount" value="amount" min="5000" max="9999" required>
                                                <button type="submit" id="vip_btn" class="btn btn-outline-light mt-3" style="padding: 10px 40px 12px;">SELECT</button>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-lg-3 col-md-6 mt-4 mt-lg-0">
                            <div class="shadow">
                                <div class="card bg-dark rounded py-3 text-center text-success" data-aos="fade-up" data-aos-delay="300">
                                    <div class="card-body">
                                        <h3>VIP GOLD</h3>
                                        <h4><sup>$</sup>10,000</h4>
                                        <ul>
                                            <li class="list-unstyled">Bonus x5</li>
                                            <li class="list-unstyled">Insurance</li>
                                            <li class="list-unstyled">Account Manager</li>
                                            <li class="list-unstyled">Backup</li>
                                            <li class="list-unstyled">Credit Card</li>
                                        </ul>
                                        <div class="btn-wrap">
                                            <form action="" method="post" id="vip_gold_plan" class="my-2 text-center">
                                                <input type="number" id="vip_gold_amount" class="form-control mb-2 border border-success" placeholder="Enter Amount" value="amount" min="10000" required>
                                                <button type="submit" id="vip_gold_btn" class="btn btn-outline-light mt-3" style="padding: 10px 40px 12px;">SELECT</button>
                                            </form>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- Modal -->
                    <!-- Modal -->
                    <div class="modal fade" id="payment" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="paymentLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-center text-primary" id="paymentLabel"><i class="mdi mdi-lock"></i> Secure Payment</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-inline mb-3 text-center">
                                        <p>Transaction ID: <span id="transaction_id" class="font-weight-bold"></span></p>
                                        <img src="images/logo-bitcoin-accepted-org.png" class="img-responsive ml-auto" alt="btc_accepted">
                                    </div>
                                    <p style="font-size: 0.8rem;">You selected the <span id="plan" class="font-weight-bold"></span> plan.</p>
                                    <h6 style="font-size: 0.8rem;">Select Payment Method Below: </h6> <br>
                                    <!-- Tabs for payment types -->
                                    <ul class=" nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item mr-3" role="presentation">
                                            <a style="font-size: 0.7rem!important;" class="nav-link text-primary border border-primary font-weight-bold" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Bitcoin</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a style="font-size: 0.7rem!important;" class="nav-link text-primary border border-primary font-weight-bold" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Luno</a>
                                        </li>

                                        <style>
                                            .nav-item a.active {
                                                color: #fff !important;
                                                background-color: #4d83ff !important;
                                            }
                                        </style>
                                    </ul>
                                    <div class="tab-content" style="font-size: 0.8rem" id="pills-tabContent">
                                        <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <hr>
                                            <p style="font-size: 0.8rem!important;">Please send the <b>Bitcoin</b> eqivalent of <span class="text-primary font-weight-bold" id="amount"></span> to your unique adddress below:</p>
                                            <div class="input-group mt-2 mb-4">
                                                <input type="text" name="" id="wallet" class="form-control py-1 border" style="font-size: .7rem; background-color: rgb(233, 233, 233); padding-bottom: 0;;" readonly value="<?php echo $info['wallet']; ?>">
                                                <div class="input-append"><button class="btn btn-outline-primary py-3" style="font-size: .7rem;" id="copy_btn">Copy</button>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                            <hr>
                                            <span style="font-size: 0.9rem">Please make a deposit with the details below: </span> <br> <br>
                                            <p style="font-size: 0.8rem; margin-bottom:.1rem; font-weight:bold;">Recipeint Name:</p>
                                            <h6 class="text-primary">Luno</h6>
                                            <p style="font-size: 0.8rem; margin-bottom:.1rem; font-weight:bold;">Bank Name:</p>
                                            <h6 class="text-primary">Standard Bank</h6>
                                            <p style="font-size: 0.8rem; margin-bottom:.1rem; font-weight:bold;">Account Number:</p>
                                            <h6 class="text-primary">051410583</h6>
                                            <p style="font-size: 0.8rem; margin-bottom:.1rem; font-weight:bold;">Branch:</p>
                                            <h6 class="text-primary">051001</h6>
                                            <p style="font-size: 0.8rem; margin-bottom:.1rem; font-weight:bold;">Type:</p>
                                            <h6 class="text-primary">Current/Cheque</h6>
                                            <p style="font-size: 0.8rem; margin-bottom:.1rem; font-weight:bold;">Reference:</p>
                                            <h6 class="text-primary">BX75UV4JT</h6>
                                            <p style="font-size: 0.8rem; margin-bottom:.1rem; font-weight:bold;">Amount:</p>
                                            <h6 class="text-primary amount"></h6>
                                        </div>
                                    </div>

                                    <hr>
                                    <p class="text-center" style=" font-size: 0.8rem">Payments are processed within 4 hours.<br /> Should your transaction take more than the usual, please contact support team on <a href="mailto:support@fxtrade-iq.com">support@fxtrade-iq.com</a>.
                                    </p>
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