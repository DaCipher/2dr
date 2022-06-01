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
            if ($_SESSION['role'] !== 'admin_4' && $_SESSION['role'] !== 'admin_3' && $_SESSION['role'] !== 'admin_2') {
                header('location: dashboard.php');
            }
        }
    } else {

        header('location: ../logout');
    }
}

// Disable

if (isset($_POST['delete'])) {
    $sql = $conn->query('delete from id_upload where user_id=' . $_POST['id']);
    if ($sql) {
        $status = '<div class="alert alert-success text-center alert-dismissible fade show" role="alert">User Account Deleted.
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
    <?php include "../user/partials/head.php"; ?>
    <title>ID Verification - Smart Admin</title>

</head>

<style>
    .table-img {
        width: 80px !important;
        border-radius: 0 !important;
        height: 60px !important;
    }
</style>

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



                                        <h2>ID Verification</h2>

                                        <div class="d-flex">

                                            <i class="mdi mdi-home text-muted hover-cursor"></i>

                                            <p class="text-muted mb-0 hover-cursor">

                                                &nbsp;/&nbsp;Users&nbsp;/&nbsp;</p>

                                            <p class="text-primary mb-0 hover-cursor">ID Verification</p>

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
                    $sql = $conn->query("select * from users JOIN id_upload where users.id not in (select user_id from role)  and users.id = id_upload.user_id ORDER BY id_upload.id LIMIT $start, $limit");
                    $sql2 = $conn->query('select * from users JOIN id_upload where users.id not in (select user_id from role)  and users.id = id_upload.user_id');
                    $total_records = $sql2->num_rows;
                    $total_pages = ceil($total_records / $limit);
                    if ($sql->num_rows > 0) {
                    ?>
                        <div class="row">
                            <div class="col-md">
                                <div class="shadow">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="text-center">Transaction Records</h4>
                                            <caption class="my-2 font-weight-bold">
                                                Showing Records <?= (($start < 1) ? $page : $start) . " to " . ($start + $sql->num_rows) . " of " . $total_records ?>
                                            </caption>
                                            <div class="table-responsive">
                                                <table class="table">

                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Reg Time</th>
                                                        <th>ID Front</th>
                                                        <th>ID Back</th>
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
                                                            <td><?php echo $row['reg_date']; ?></td>
                                                            <td>
                                                                <img src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . htmlspecialchars($row['dir_front']); ?>" alt="" srcset="" data-name="<?php echo ucfirst($row['firstname']) . ' ' . ucfirst($row['lastname']) . " ID Front"; ?>" class="table-img">
                                                            </td>
                                                            <td>
                                                                <img src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . htmlspecialchars($row['dir_back']); ?>" alt="" srcset="" data-name="<?php echo ucfirst($row['firstname']) . ' ' . ucfirst($row['lastname']) . " ID Back"; ?>" class="table-img">
                                                            </td>
                                                            <td>
                                                                <form action="" method="post">
                                                            <td>
                                                                <input type="hidden" name="id" value="<?= $row['user_id']; ?>">
                                                                <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                                            </td>
                                                            </form>
                                                            </td>


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
                        <!-- Modal -->
                        <div class="modal fade" id="idViewModal" tabindex="-1" role="dialog" aria-labelledby="idViewModal" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="idViewModalTitle"></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center my-3">
                                            <img id="modal-img" src="" alt="" style="max-width: 100%; height:auto">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <a class="btn btn-primary" id="download-btn" href="" download>Download</a>
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



    <!-- plugins:js -->

    <?php include "../user/partials/scripts.php"; ?>
    <script src="./js/id-verification.js"></script>


</body>



</html>