<?php
session_start();
require "../middleware/auth.php";

// Validate user session

require "./partials/auth.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <title>User Dashboard</title>
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
                            <p class="mt-n3">Last Login: <?php echo $_SESSION['last_login']; ?></p>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="mr-md-3 mr-xl-5">

                                        <h2>Welcome back,</h2>
                                        <div class="d-flex">
                                            <i class="mdi mdi-home text-muted hover-cursor"></i>
                                            <p class="text-muted mb-0 hover-cursor">
                                                &nbsp;/&nbsp;Dashboard&nbsp;/&nbsp;</p>
                                            <p class="text-danger mb-0 hover-cursor">Overview</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-end flex-wrap">
                                    <button type="button" class="btn btn-danger btn-icon mr-3 mt-2 mt-xl-0">
                                        <a href="deposit.php"><i class="mdi mdi-cash text-white"></i></a>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-icon mr-3 mt-2 mt-xl-0">
                                        <a href="withdraw.php"><i class="mdi mdi-cash-refund text-white"></i></a>
                                    </button>
                                    <a class="btn btn-danger mt-2 mt-xl-0" href="transaction.php">Transaction
                                        History</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Dashboard Header Ends here-->
                    <div class="row">
                        <?php if ($data['trade_status'] == "1") : ?>
                            <div class="col-md-12">
                                <div class="alert alert-success my-2"><b>Alert:</b> Your trade has ended. Proceed to remit the required 15% Commision Fee.</div>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">

                                <div class="card-body dashboard-tabs p-0">
                                    <ul class="nav nav-tabs px-4" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-0 px-0">
                                        <div class="tab-pane show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                            <div class="row">
                                                <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right align-items-center justify-content-center p-3 item">
                                                    <i class="mdi mdi-calendar-multiple-check mr-3 icon-lg text-danger"></i>
                                                    <div class="d-flex flex-column justify-content-around">
                                                        <small class="mb-1 text-muted">Registration Date</small>
                                                        <h5 class="mr-2 mb-0"><?php echo $user['reg_date']; ?></h5>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right align-items-center justify-content-center p-3 item">
                                                    <i class="mdi mdi-currency-usd ml-n4 mr-3 icon-lg text-success"></i>
                                                    <div class="d-flex flex-column justify-content-around">
                                                        <small class="mb-1 text-muted">Balance</small>
                                                        <h5 class="mr-4 mb-0">
                                                            $<?php echo number_format($_SESSION['balance']); ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right align-items-center justify-content-center p-3 item">
                                                    <i class="mdi mdi-cash-multiple ml-n3 mr-3 icon-lg text-warning"></i>
                                                    <div class="d-flex flex-column justify-content-around">
                                                        <small class="mb-1 text-muted">Total Deposits</small>
                                                        <h5 class="mr-2 mb-0">$<?php echo $_SESSION['deposits']; ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                    <i class="mdi mdi-cash-refund mr-3 icon-lg text-danger"></i>
                                                    <div class="d-flex flex-column justify-content-around">
                                                        <small class="mb-1 text-muted">Total Withdrawals</small>
                                                        <h5 class="mr-2 mb-0">$<?php echo $_SESSION['withdraws']; ?>
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-7 mx-auto grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- TradingView Widget BEGIN -->
                                    <div class="tradingview-widget-container">
                                        <div id="tradingview_88b53"></div>
                                        <script type="text/javascript" src="https://s3.tradingview.com/tv.js">
                                        </script>
                                        <script type="text/javascript">
                                            new TradingView.widget({

                                                "width": '100%',
                                                "height": 540,
                                                "symbol": "BITSTAMP:BTCUSD",
                                                "interval": "D",
                                                "timezone": "Etc/UTC",
                                                "theme": "light",
                                                "style": "1",
                                                "locale": "en",
                                                "toolbar_bg": "#f1f3f6",
                                                "enable_publishing": false,
                                                "allow_symbol_change": true,
                                                "container_id": "tradingview_88b53"
                                            });
                                        </script>
                                    </div>
                                    <!-- TradingView Widget END -->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <!-- TradingView Widget BEGIN -->
                                    <div class="tradingview-widget-container">
                                        <div class="tradingview-widget-container__widget"></div>
                                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-technical-analysis.js" async>
                                            {
                                                "interval": "1m",
                                                "width": "100%",
                                                "isTransparent": false,
                                                "height": 540,
                                                "symbol": "BITSTAMP:BTCUSD",
                                                "showIntervalTabs": true,
                                                "locale": "en",
                                                "colorTheme": "light"
                                            }
                                        </script>
                                    </div>
                                    <!-- TradingView Widget END -->
                                </div>
                            </div>
                        </div>
                    </div>

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
</body>

</html>