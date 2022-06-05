<?php
session_start();

require "../middleware/auth.php";
// Validate user session

require "./partials/auth.php";

$status = $wallet_err = $amount_err = $bank_err = $iban_err = $swift_err = $amount = $bank_name = $swift = $iban = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $balance = $_SESSION['balance'];
    $amount = $_POST['amount'];
    $method = $_POST["method"];
    if (isset($_POST['wallet'])) $wallet = trim($_POST['wallet']);
    $state = $data['level'];
    if (isset($_POST["submit_bank"])) {
        $bank_name = trim($_POST["bank_name"]);
        $iban = trim($_POST['iban']);
        $swift = trim($_POST['swift']);
    }
    $error = '';

    //Validate Amount
    if (empty($amount)) {
        $error = "set";
        $amount_err = "Amount is required!";
    } else if ($amount < 100) {
        $error = "set";
        $amount_err = "Minimum withdrawal is $100!";
    }

    // validate wallet
    if (isset($_POST['submit_btc'])) {
        if (empty($wallet)) {
            $error = "set";
            $wallet_err = "Wallet address required for withdrawals!";
        } else if (strlen($wallet) < 15) {
            $error = "set";
            $wallet_err = "Invalid Wallet address!";
        }
    }

    // Validate Bank Details
    if (isset($_POST["submit_bank"])) {
        // Bank
        if (empty($bank_name)) {
            $error = "set";
            $bank_err = "Bank Name Required!";
        } else {
            if (!is_string($bank_name)) {
                $error = "set";
                $bank_err = "Invalid Bank Name!";
            }
        }

        // IBAN
        if (empty($iban)) {
            $error = "set";
            $iban_err = "IBAN required!";
        } else {
            if (!is_numeric($iban)) {
                $error = "set";
                $iban_err = "Invalid IBAN!";
            }
        }

        // SWIFT
        if (empty($swift)) {
            $error = "set";
            $swift_err = "Swift Code required!";
        } else {
            if (!is_string($swift)) {
                $error = "set";
                $swift_err = "Invalid Swift Code!";
            }
        }
    }



    if (empty($error)) {
        // Validate balance 
        if ($amount > $balance) {
            $status = '<div class="alert alert-primary text-center" role= "alert"><b>Error:  </b> Insufficient funds!</div>';
        } else {
            if ($data['trade_status'] != "0") {
                if ($state === "pending") {
                    $date = date("Y-m-d");
                    $datas = [
                        "id" => $_SESSION['id'],
                        "type" => "withdraw",
                        "amount" => $amount,
                        "method" => $method,
                        "date" => $date,
                        "status" => "pending",
                    ];
                    if (isset($_POST["submit_bank"])) {
                        $datas['iban'] = $iban;
                        $datas['swift'] = $swift;
                        $datas['bank_name'] = $bank_name;
                    }
                    $_SESSION['withdrawal'] = $datas;
                    $_SESSION['code'] = "coc";
                    header("location:" . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/user/withdrawal");
                } else if ($state === "upgrade") {
                    $status =  '<div class="alert alert-primary text-center" role= "alert">Account Upgrade required!</div>';
                } else if ($state === "authenticate") {
                    $status = '<div class="alert alert-primary text-center" role= "alert">Wallet authentication required!</div>';
                } else if ($state === "release") {
                    $status = '<div class="alert alert-primary text-center" role= "alert">Profit release required!</div>';
                } else if (empty($state)) {
                    $status = '<div class="alert alert-primary text-center" role= "alert"><b>Error:  </b> Something went wrong!</div>';
                } else {
                    $status = '<div class="alert alert-primary text-center" role= "alert"><b>Error:  </b> Unable to submit request!</div>';
                }
            } else {
                $status = '<div class="alert alert-primary text-center" role= "alert"><b>Error:  </b> Trade is still active!</div>';
            }
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>

    <title>Withraw Funds - Daily Crypto Returns</title>
    <?php include "./partials/head.php"; ?>
</head>

<body style="min-width: 300px;">
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <?php include "./partials/navbar.php"; ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.html -->
            <?php include "./partials/sidebar.php"; ?>
            <!-- partial Ends -->

            <!-- Page Headers-->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row">
                        <div class="col-md-12 grid-margin">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="mr-md-3 mr-xl-5">

                                        <h2>Withdraw</h2>
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
                        <div class="col-md-8 col-lg-6 mx-auto mt-5">
                            <div class="shadow">
                                <div class="card">
                                    <h4 class="my-3 text-center">Withdrawal Form</h4>
                                    <div class="card-body">




                                        <ul class="nav nav-pills nav-justified mb-3" id="pills-tab" role="tablist">
                                            <li class="nav-item mr-2" style="border-radius: 5px;">
                                                <a class="nav-link border  border-primary <?= (!isset($_POST['submit_bank'])) ? 'active' : ''; ?>" id="btc-tab" data-toggle="pill" hreff="#btc" role="tab" aria-controls="btc" aria-selected="true">Bitcoin</a>
                                            </li>
                                            <li class="nav-item" style="border-radius: 5px;">
                                                <a class="nav-link border border-primary <?= (isset($_POST['submit_bank'])) ? 'active' : ''; ?>" id="bank_wire-tab" data-toggle="pill" hreff="#bank_wire" role="tab" aria-controls="bank_wire" aria-selected="false">Bank Wire</a>
                                            </li>

                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="btc" role="tabpanel" aria-labelledby="btc-tab">

                                                <!-- BTC Withdrawal -->
                                                <div id="btc_form">
                                                    <?php echo $status; ?>

                                                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                                        <div class="text-right my-3">
                                                            <span class="p-2 font-weight-bold text-info shadow rounded" style="background-color: rgba(240, 239, 239, 0.877);">Current Balance: $<?php echo number_format($_SESSION['balance']); ?>
                                                            </span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="amount">Amount</label>
                                                            <input type="number" name="amount" id="amount" class="form-control" min="100" value="<?= $amount; ?>" required>
                                                            <span class="help-block text-primary" id="amount_err"><?php echo $amount_err; ?></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="wallet">BTC Wallet Address</label>
                                                            <input type="text" name="wallet" id="wallet" class="form-control" required minlength="10" value="<?php echo $user['wallet']; ?>">
                                                            <span class="help-block text-primary" id="wallet_err"><?php echo $wallet_err; ?></span>
                                                        </div>
                                                        <input type="hidden" name="method" value="wallet">
                                                        <button type="submit" class="btn btn-primary btn-block my-1" id="submit" name="submit_btc">Submit</button>
                                                    </form>

                                                </div>
                                                <!-- BTC Withdrawal ends here -->
                                            </div>
                                            <div class="tab-pane fade" id="bank_wire" role="tabpanel" aria-labelledby="bank_wire-tab">
                                                <!-- Bank form -->
                                                <div id="bank_form">
                                                    <?php echo $status; ?>
                                                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                                        <div class="text-right my-3">
                                                            <span class="p-2 font-weight-bold text-info shadow rounded" style="background-color: rgba(240, 239, 239, 0.877);">Current Balance: $<?php echo number_format($_SESSION['balance']); ?>
                                                            </span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="bank_name">Bank Name</label>
                                                            <input type="text" class="form-control" name="bank_name" id="bank_name" value="<?= $bank_name; ?>">
                                                            <span class="help-block text-primary"><?= $bank_err ?></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="iban">Account Number (IBAN)</label>
                                                            <input type="text" class="form-control" name="iban" id="iban" value="<?= $iban ?>">
                                                            <span class="help-block text-primary"><?= $iban_err ?></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="name">Account Name</label>
                                                            <input type="text" class="form-control" name="name" id="name" value="<?= $user['firstname'] . " " . $user['lastname']; ?>" readonly>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="swfit">SWIFT Code</label>
                                                            <input type="text" class="form-control" name="swift" id="swift" value="<?= $swift; ?>">
                                                            <span class="help-block text-primary"><?= $swift_err ?></span>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="amount">Amount</label>
                                                            <input type="text" class="form-control" name="amount" id="amount" value="<?= $amount; ?>">
                                                            <span class="help-block text-primary" id="amount_err"><?= $amount_err; ?></span>
                                                        </div>
                                                        <input type="hidden" name="method" value="bank">
                                                        <button type="submit" class="btn btn-primary btn-block my-1" id="submit" name="submit_bank">Submit</button>
                                                    </form>
                                                </div>
                                                <!-- Bank Form ends here -->
                                            </div>
                                        </div>


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
    <!-- plugins:js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- endinject -->
    <script src="js/withdrawal.js"></script>

    <script>
        <?php if (isset($_POST['submit_bank'])) : ?>
            $("#bank_wire").addClass("show active");
            $("#btc").removeClass("show active");
        <?php endif; ?>
    </script>
    <?php include "./partials/scripts.php"; ?>

</body>

</html>