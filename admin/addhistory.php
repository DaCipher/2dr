<?php

session_start();



require "../middleware/auth.php";



// Validate user session



if (!isset($_SESSION['login']) && $_SESSION['login'] != true) {

    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
} else if (!isset($_SESSION['authenticate']) && $_SESSION['authenticate'] !== true) {

    header("location: dashboard.php");
} else {
    $user = getSingleRecord('users', 'id', $_SESSION['id']);

    $perm = getSingleRecord("role", 'user_id', $_SESSION["id"]);
    if ($user['status'] === 'active') {

        if (!in_array($_SESSION['role'], $_SESSION['admins'])) {

            header('location: ../user');
        } else {
            if ($_SESSION['role'] !== 'admin_4' && $_SESSION['role'] !== 'admin_3' && $_SESSION['role'] !== 'admin_2' && $_SESSION['role'] !== 'admin_1') {
                header('location: index.php');
            }
        }
    } else {

        header('location: ../logout');
    }
}

// Add
$status = '';
date_default_timezone_set('Europe/London');


if (isset($_POST['add'])) {
    $date = $_POST['date'];
    $sql = $conn->query('insert into transactions (amount, type, method, status, user_id, date) values ("' . $_POST['amount'] . '", "' . $_POST['type'] . '","' . $_POST['method'] . '", "pending", ' . $_POST['id'] . ', "' . $date . '")');
    if ($sql) {
        $status = '<div class="alert alert-success text-center alert-dismissible fade show" role="alert">Transaction Added.
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

    <title>Transaction Update - Smart Admin</title>

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



                                        <h2>Create History</h2>

                                        <div class="d-flex">

                                            <i class="mdi mdi-home text-muted hover-cursor"></i>

                                            <p class="text-muted mb-0 hover-cursor">

                                                &nbsp;/&nbsp;Users&nbsp;/&nbsp;</p>

                                            <p class="text-primary mb-0 hover-cursor">Create History</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Page contents goes here -->
                    <?php
                    $limit = 25;
                    (isset($_GET['page'])) ? $page = $_GET['page'] : $page = 1;
                    $start = ($page - 1) * $limit;
                    $sql = $conn->query("select * from users JOIN investment where users.id not in (select user_id from role) AND users.id = investment.user_id ORDER BY users.id DESC LIMIT $start, $limit");
                    $sql2 = $conn->query('select * from users JOIN investment where users.id not in (select user_id from role) AND users.id = investment.user_id ORDER BY users.id DESC');
                    $total_records = $sql2->num_rows;
                    $total_pages = ceil($total_records / $limit);
                    if ($sql->num_rows > 0) {
                    ?>
                        <div class="row">
                            <div class="col-md">
                                <div class="shadow">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="text-center">Add Transaction</h4>
                                            <?php echo $status; ?>
                                            <caption class="my-2 font-weight-bold">
                                                Showing Records <?= (($start < 1) ? $page : $start) . " to " . ($start + $sql->num_rows) . " of " . $total_records ?>
                                            </caption>
                                            <div class="table-responsive">
                                                <table class="table">

                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>State</th>
                                                        <th>Balance</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Method</th>
                                                        <th>Type</th>
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
                                                            <td><?php echo $row['username']; ?></td>
                                                            <td><?php echo $row['level']; ?></td>
                                                            <td><?php echo $row['balance']; ?></td>
                                                            <form action="addhistory.php" method="post">
                                                                <td>
                                                                    <input type="date" name="date" id="" class="form-control" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="amount" id="" class="form-control" required>
                                                                </td>
                                                                <td>
                                                                    <select name="method" id="" class="form-control" required>
                                                                        <option value="wallet">Wallet</option>
                                                                        <option value="bank">Bank Wire</option>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <select name="type" id="" class="form-control" required>
                                                                        <option value="deposit">Deposit</option>
                                                                        <option value="withdraw">Withdraw</option>
                                                                    </select>
                                                                </td>

                                                                <td>

                                                                    <button type="submit" name="add" class="btn btn-primary">Add</button>
                                                                    <input type="hidden" name="id" value="<?php echo $row['user_id']; ?>">
                                                                </td>
                                                            </form>
                                                        </tr>


                                                    <?php } ?>
                                                </table>
                                                <!-- Pagination -->
                                                <nav aria-label="Page navigation example">
                                                    <ul class="pagination">
                                                        <li class="page-item <?= ($page <= 1) ? "disabled" : "" ?>">
                                                            <a class="page-link" href="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?page=" . ($page - 1); ?>" aria-label="Previous">
                                                                <span aria-hidden="true">&laquo;</span>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                        </li>
                                                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                                                            <li class="page-item <?= ($page == $i) ? "active" : "" ?>"><a class="page-link" href="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?page=" . $i; ?>"><?= $i; ?></a></li>
                                                        <?php endfor; ?>
                                                        <li class="page-item <?= ($page >= $total_pages) ? "disabled" : "" ?>">
                                                            <a class="page-link" href="<?= htmlspecialchars($_SERVER['PHP_SELF']) . "?page=" . ($page + 1); ?>" aria-label="Next">
                                                                <span aria-hidden="true">&raquo;</span>
                                                                <span class="sr-only">Next</span>
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



    <?php include "../user/partials/scripts.php"; ?>



</body>



</html>