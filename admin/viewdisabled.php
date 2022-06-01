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

$status = "";

if (isset($_POST['enable'])) {
    $sql = $conn->query('update users set status = "active" where id=' . $_POST['id']);
    if ($sql) {
        $status = '<div class="alert alert-success text-center alert-dismissible fade show" role="alert">User Account Enabled.
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
    $sql = $conn->query('delete from users where id=' . $_POST['id']);
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
    <title>Disabled Users - Smart Admin</title>





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



                                        <h2>Disabled Users</h2>

                                        <div class="d-flex">

                                            <i class="mdi mdi-home text-muted hover-cursor"></i>

                                            <p class="text-muted mb-0 hover-cursor">

                                                &nbsp;/&nbsp;Users&nbsp;/&nbsp;</p>

                                            <p class="text-primary mb-0 hover-cursor">Disabled Users</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Page contents goes here -->
                    <?php
                    $sql = $conn->query('select * from users where users.id not in (select user_id from role) and status = "disabled"');
                    if ($sql->num_rows > 0) {
                    ?>
                        <div class="row">
                            <div class="col-md">
                                <div class="shadow">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="text-center">Disabled Users</h4>
                                            <?php echo $status; ?>
                                            <div class="table-responsive">
                                                <table class="table">

                                                    <tr>
                                                        <th>#</th>
                                                        <th>Name</th>
                                                        <th>Username</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
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
                                                            <td><?php echo $row['username']; ?></td>
                                                            <td><?php echo $row['email']; ?></td>
                                                            <td><?php echo $row['phone']; ?></td>
                                                            <td><?php echo ($row['status'] !== 'active') ? 'Disabled' : ucfirst($row['status']); ?>
                                                            </td>
                                                            <td>
                                                                <form action="viewdisabled.php" method="post">
                                                                    <?php echo ($row['status'] === 'active') ? '<button type="submit" class="btn btn-warning my-1 text-white"
                                                                    name="disable">Disable</button>' : '<button type="submit" class="btn btn-success my-1"
                                                                    name="enable">Enable</button>'; ?> |
                                                                    <button type="submit" name="delete" class="btn btn-danger my-1">Delete</button>
                                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                </form>
                                                            </td>
                                                        </tr>


                                                    <?php } ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } else { ?>

                        <div class="row">
                            <div class="col-md-6 mx-auto">
                                <h3 class="text-center">No disabled user found.</h3>
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

    <script src="vendors/base/vendor.bundle.base.js"></script>

    <!-- endinject -->

    <!-- Plugin js for this page-->

    <script src="vendors/chart.js/Chart.min.js"></script>

    <script src="vendors/datatables.net/jquery.dataTables.js"></script>

    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>

    <!-- End plugin js for this page-->
    <?php include "../user/partials/scripts.php"; ?>

</body>



</html>