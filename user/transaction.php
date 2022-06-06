<?php
session_start();

require "../middleware/auth.php";
// Validate user session

require "./partials/auth.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Transaction History - Daily Crypto Return</title>
    <?php include "./partials/head.php"; ?>
</head>

<body style="min-width: auto;">
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

                                        <h2>Transaction History</h2>
                                        <div class="d-flex">
                                            <i class="mdi mdi-home text-muted hover-cursor"></i>
                                            <p class="text-muted mb-0 hover-cursor">
                                                &nbsp;/&nbsp;Transaction&nbsp;/&nbsp;</p>
                                            <p class="text-primary mb-0 hover-cursor">History</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Page Header Ends here-->
                    <!--Page Contents Here-->
                    <?php
                    $limit = 10;
                    if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }
                    $start = ($page - 1) * $limit;
                    $sql = $conn->query("SELECT * from transactions where user_id =" . $_SESSION['id'] . " ORDER BY id DESC LIMIT $start, $limit");
                    $sql2 = $conn->query("SELECT * from transactions where user_id =" . $_SESSION['id'] . " ORDER BY id DESC");
                    $total_records = $sql2->num_rows;
                    $total_page = ceil($total_records / $limit);
                    if ($sql) :
                        if ($sql->num_rows > 0) :
                            $i = 0;
                    ?>
                            <div class="row">
                                <div class="col-md-10 mx-auto">
                                    <div class="shadow mx-auto">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Transaction History</h4>
                                                <caption class="mb-3">
                                                    Showing Records <?= ($start == 0) ? $page : $start; ?> to <?= ($start + $sql->num_rows) . " of " . $total_records; ?>
                                                </caption>
                                                <div class="table-responsive-lg">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">S/N</th>
                                                                <th scope="col">Type</th>
                                                                <th scope="col">Amount</th>
                                                                <th scope="col">Method</th>
                                                                <th scope="col">Date</th>
                                                                <th scope="col">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php while ($row = $sql->fetch_assoc()) :
                                                                $i++;
                                                                if ($row['status'] == "cancelled" || $row['status'] == "failed") {
                                                                    $color = "danger";
                                                                } else if ($row['status'] == "completed") {
                                                                    $color = "success";
                                                                } else if ($row['status'] == "processing") {
                                                                    $color = "warning text-white";
                                                                } else {
                                                                    $color = "dark";
                                                                } ?>
                                                                <tr>
                                                                    <td>
                                                                        <?= $i ?>
                                                                    </td>
                                                                    <td>
                                                                        <?= ucfirst($row['type']) ?>
                                                                    </td>
                                                                    <td> $
                                                                        <?= number_format($row['amount']) ?>
                                                                    </td>
                                                                    <td>
                                                                        <?= ucfirst($row['method']) ?>
                                                                    </td>
                                                                    <td>
                                                                        <?= $row['date'] ?>
                                                                    </td>
                                                                    <td><span class="badge badge-<?= $color ?>">
                                                                            <?= ucfirst($row['status']) ?>
                                                                        </span></td>
                                                                </tr>
                                                            <?php endwhile; ?>
                                                        </tbody>
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
                        <?php else : ?>
                            <h4 class="text-center my-5 p-3"> No Transaction found! Please fund your account.</h4>
                        <?php endif; ?>
                    <?php endif; ?>
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
</body>

</html>