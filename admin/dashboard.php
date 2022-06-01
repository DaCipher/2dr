<?php
session_start();

require "../middleware/auth.php";
$user = getSingleRecord('users', 'id', $_SESSION['id']);
$perm = getSingleRecord("role", 'user_id', $_SESSION["id"]);
// Validate user session

if (!isset($_SESSION['login']) && $_SESSION['login'] != true) {
    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
} else if (!isset($_SESSION['authenticate']) && $_SESSION['authenticate'] !== true) {
    header("location: index.php");
} else {
    if ($user['status'] === 'active') {
        if (!in_array($_SESSION['role'], $_SESSION['admins'])) {
            header('location: ../user');
        }
    } else {
        header('location: ../logout');
    }
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <title>Admin Dashboard</title>
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
                            <p class="mt-n3">Last Login:
                                <?php echo $_SESSION['last_login']; ?>
                            </p>
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-end flex-wrap">
                                    <div class="mr-md-3 mr-xl-5">

                                        <h2>Welcome <?php echo $user['firstname']; ?>,</h2>
                                        <div class="d-flex">
                                            <i class="mdi mdi-home text-muted hover-cursor"></i>
                                            <p class="text-muted mb-0 hover-cursor">
                                                &nbsp;/&nbsp;Dashboard&nbsp;/&nbsp;</p>
                                            <p class="text-primary mb-0 hover-cursor">Overview</p>
                                        </div>
                                    </div>
                                </div>

                                <!--
                                        <div class="d-flex justify-content-between align-items-end flex-wrap">
                                        <button type="button" class="btn btn-light bg-white btn-icon mr-3 mt-2 mt-xl-0">
                                            <a href="deposit.php"><i class="mdi mdi-cash"></i></a>
                                        </button>
                                        <button type="button" class="btn btn-light bg-white btn-icon mr-3 mt-2 mt-xl-0">
                                            <a href="withdraw.php"><i class="mdi mdi-cash-refund"></i></a>
                                        </button>
                                        <a class="btn btn-primary mt-2 mt-xl-0" href="transaction.php">Transaction
                                            History</a>
                                    </div>
                                    -->
                            </div>
                        </div>
                    </div>
                    <!-- Dashboard Header Ends here-->
                    <div class="row">
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

                                                <?php

                                                $sql1 = $conn->query("SELECT * from role");
                                                $total_admin = $sql1->num_rows;

                                                $sql2 = $conn->query("SELECT * FROM users where users.id NOT IN (select user_id from role)");
                                                $total_users = $sql2->num_rows;

                                                $sql3 = $conn->query('select * from investment where balance != 0 AND investment.user_id NOT IN (select user_id from role)');
                                                $funded_users = $sql3->num_rows;

                                                $sql4 = $conn->query("select * from contact");
                                                $messages = $sql4->num_rows;

                                                $sql5 = $conn->query("select * from users where users.id not in (select user_id from role) and status = 'disbaled'");
                                                $disabled_users = $sql5->num_rows;

                                                $sql6 = $conn->query("select * from users where status = 'verify' OR status = 'verifying' AND id NOT IN (select user_id from role)");
                                                $verification_request = $sql6->num_rows;




                                                ?>
                                                <?php if ($_SESSION['role'] === 'admin_4') { ?>

                                                    <div class="col-12 col-sm-6 col-lg-4 d-flex border-md-right align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-account-star mr-3 icon-lg text-primary"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Total Admin</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo $total_admin; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3') { ?>
                                                    <div class="col-12 col-sm-6 col-lg-4 d-flex border-md-right align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-account-multiple ml-n4 mr-3 icon-lg text-success"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Total Users</small>
                                                            <h5 class="mr-4 mb-0">

                                                                <?php echo $total_users; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2') { ?>
                                                    <div class="col-12 col-sm-6 col-lg-4 d-flex border-md-right align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-cash-multiple ml-n3 mr-3 icon-lg text-warning"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Funded Users</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo $funded_users; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3') {  ?>
                                                    <div class="col-12 col-sm-6 col-lg-4 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-email ml-n4 mr-3 icon-lg text-info"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Messages</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo $messages; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2') { ?>
                                                    <div class="col-12 col-sm-6 col-lg-4 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-account-off mr-3 icon-lg text-danger"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Disabled Users</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo $disabled_users; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2' || $_SESSION['role'] === 'admin_1') { ?>
                                                    <div class="col-12 col-sm-6 col-lg-4 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-bell-ring mr-3 ml-4 icon-lg text-secondary"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Unverified Users</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo $verification_request; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <!-- Agent Dashboard -->
                                                <?php if ($_SESSION['role'] === 'agent_1' || $_SESSION['role'] === 'agent_2') { ?>
                                                    <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-calendar mr-3 ml-4 icon-lg text-danger"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Registration Date</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo $user['reg_date']; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-account-star mr-3 ml-4 icon-lg text-primary"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Role</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo ($_SESSION['role'] === 'agent_1') ? 'Agent Level 1' : 'Agent Level 2'; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-fingerprint mr-3 ml-4 icon-lg text-success"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Priviledges</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo ($_SESSION['role'] === 'agent_1') ? '1' : '2'; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-6 col-lg-3 d-flex border-md-right py-3 align-items-center justify-content-center p-3 item">
                                                        <i class="mdi mdi-lock mr-3 ml-4 icon-lg text-dark"></i>
                                                        <div class="d-flex flex-column justify-content-around">
                                                            <small class="mb-1 text-muted">Account Status</small>
                                                            <h5 class="mr-2 mb-0">
                                                                <?php echo 'Secured'; ?>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                <?php } ?>

                                                <!-- Agent Dashboard -->



                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html --><?php include "../user/partials/footer.php"; ?>
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