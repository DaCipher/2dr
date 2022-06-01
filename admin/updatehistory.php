<?php


session_start();



require "../middleware/auth.php";



// Validate user session



if (!isset($_SESSION['login']) || $_SESSION['login'] != true) {

    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
} else if (!isset($_SESSION['authenticate']) || $_SESSION['authenticate'] !== true) {

    header("location: index.php");
} else {
    $user = getSingleRecord('users', 'id', $_SESSION['id']);

    $perm = getSingleRecord("role", 'user_id', $_SESSION["id"]);
    if ($user['status'] === 'active') {

        if (!in_array($_SESSION['role'], $_SESSION['admins'])) {

            header('location: ../user');
        } else {
            if ($_SESSION['role'] !== 'admin_4' && $_SESSION['role'] !== 'admin_3' && $_SESSION['role'] !== 'admin_2' && $_SESSION['role'] !== 'admin_1') {
                header('location: dashboard.php');
            }
        }
    } else {

        header('location: ../logout');
    }
}

// Disable
$status = '';
date_default_timezone_set('Europe/London');


if (isset($_POST['update'])) {
    $sql = $conn->query('update transactions set status = "' . $_POST['status'] . '", date = "' . $_POST['date'] . '" where id = ' . $_POST['id']);
    if ($sql) {
        $status = '<div class="alert alert-success text-center alert-dismissible fade show" role="alert">Transaction Updated.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>';
    } else {
        $status = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
  <strong>Error: </strong> Something went wrong.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>';
    }
}
if (isset($_POST['delete'])) {
    $sql0 = $conn->query("DElETE from transactions where id =" . $_POST['id']);
    if ($sql0) {
        $status = '<div class="alert alert-success text-center alert-dismissible fade show" role="alert">Transaction Deleted.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>';
    } else {
        $status = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
  <strong>Error: </strong> Something went wrong.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
    </div>';
    }
}






?>





<!DOCTYPE html>

<html lang="en">



<head>

    <!-- Required meta tags -->

    <title>Transaction History Update - Smart Admin</title>

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



                                        <h2>History Update</h2>

                                        <div class="d-flex">

                                            <i class="mdi mdi-home text-muted hover-cursor"></i>

                                            <p class="text-muted mb-0 hover-cursor">

                                                &nbsp;/&nbsp;Users&nbsp;/&nbsp;</p>

                                            <p class="text-primary mb-0 hover-cursor">History Update</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Page contents goes here -->
                    <?php
                    // Pagination

                    $limit = 25;
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }
                    $start = ($page - 1) * $limit;
                    $sql = $conn->query('select users.firstname, users.lastname, users.username, investment.balance, investment.level, transactions.id, transactions.user_id, transactions.type, transactions.method, transactions.status, transactions.date, transactions.amount, transactions.bank_name, transactions.acc_number, transactions.swift from users JOIN transactions join investment where users.id not in (select user_id from role) AND users.id = investment.user_id and investment.user_id = transactions.user_id ORDER BY transactions.id DESC LIMIT ' . $start . ',' . $limit);
                    $last_record = $sql->num_rows;
                    $sql2 = $conn->query("select * from transactions");
                    $total_records = $sql2->num_rows;
                    $total_page = ceil($total_records / $limit);
                    if ($sql->num_rows > 0) {
                    ?>
                        <div class="row">
                            <div class="col-md">
                                <div class="shadow">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="text-center">Update History</h4>
                                            <caption class="mb-3">
                                                Showing Records <?= ($start == 0) ? $page : $start; ?> to <?= ($start + $last_record) . " of " . $total_records; ?>
                                            </caption>
                                            <?php echo $status; ?>
                                            <div class="table-responsive">
                                                <table class="table">

                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Balance</th>
                                                        <th>State</th>
                                                        <th>Amount</th>
                                                        <th>Type</th>
                                                        <th>Method</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    <?php
                                                    $i = 0;
                                                    while ($row = $sql->fetch_assoc()) {
                                                        $i++;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo ucfirst($row['firstname']) . ' ' . ucfirst($row['lastname']); ?>
                                                            </td>
                                                            <td><?php echo $row['balance']; ?></td>
                                                            <td><?php echo ucfirst($row['level']); ?></td>
                                                            <td><?php echo $row['amount']; ?></td>
                                                            <td><?php echo ucfirst($row['type']); ?></td>
                                                            <td><?php echo ucfirst($row['method']); ?><?= ($row['method'] == "bank" && $row['type'] == "withdraw") ? "<i trans_id='" . $row['id'] . "' class='mdi mdi-alert-circle btn_info'></i>" : ""; ?></td>
                                                            <form action="updatehistory.php" method="post">
                                                                <td>
                                                                    <input type="date" name="date" id="date" class="form-control" value="<?= $row['date']; ?>" required>
                                                                </td>
                                                                <td>
                                                                    <select name="status" id="" class="form-control" required>
                                                                        <option value="<?php echo $row['status']; ?>" selected>
                                                                            <?php echo ucfirst($row['status']); ?></option>
                                                                        <option value="cancelled">Cancelled</option>
                                                                        <option value="completed">Completed</option>
                                                                        <option value="failed">Failed</option>
                                                                        <option value="pending">Pending</option>
                                                                        <option value="processing">Processing</option>
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <button type="submit" name="update" class="btn btn-outline-primary">Update</button>
                                                                        <span class="mx-1"> | </span>
                                                                        <button type="submit" name="delete" class="btn btn-outline-danger">Delete</button>
                                                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                    </div>

                                                                </td>
                                                            </form>
                                                            <!-- Modal -->
                                                            <div class="modal fade" id="bank_info_modal_<?= $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="bank_info_modalTitle" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLongTitle">Bank Info</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="my-3">Name: <?php echo ucfirst($row['firstname']) . ' ' . ucfirst($row['lastname']); ?> </div>
                                                                            <div class="my-3">Bank: <?= ucwords($row['bank_name']); ?></div>
                                                                            <div class="my-3">Account Number: <?= $row['acc_number']; ?></div>
                                                                            <div class="my-3">Swift Code: <?= $row['swift']; ?></div>
                                                                            <div class="my-3">Amount: $<?php echo $row['amount']; ?></div>
                                                                            <div class="my-3">Date: <?= $row['date'] ?></div>

                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </tr>


                                                    <?php } ?>

                                                </table>

                                                <!-- Pagination -->
                                                <nav>
                                                    <ul class="pagination">

                                                        <li class="page-item  <?= ($page <= 1) ? "disabled" : ""; ?>">
                                                            <a class="page-link" href="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?page=" . ($page - 1); ?>" aria-label="Previous">

                                                                <span>Previous</span>
                                                            </a>
                                                        </li>
                                                        <?php for ($j = 1; $j <= $total_page; $j++) : ?>
                                                            <li class="page-item <?= ($page == $j) ? "active" : ""; ?>"><a class="page-link" href="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?page=" . $j; ?>"><?= $j; ?></a></li>
                                                        <?php endfor; ?>

                                                        <li class=" page-item <?= ($page == $total_page) ? "disabled" : ""; ?>">
                                                            <a class="page-link" href="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?page=" . ($page + 1); ?>" aria-label="Next">
                                                                <span>Next</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </nav>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } else { ?>

                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <h3 class="text-center">No Transaction Record Found.</h3>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Page content ends here -->






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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <?php include "../user/partials/scripts.php"; ?>

    <!-- End custom js for this page-->

    <script src="js/jquery.cookie.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {

            $('.btn_info').click(function() {
                var id = $(this).attr("trans_id");
                $('#bank_info_modal_' + id).modal("show");
            });
        });
    </script>



</body>



</html>