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
            if ($_SESSION['role'] !== 'admin_4' && $_SESSION['role'] !== 'admin_3') {

                header('location: dashboard.php');
            }
        }
    } else {

        header('location: ../logout');
    }
}


$status = '';

if (isset($_POST['delete'])) {
    $sql = $conn->query("DELETE from contact where id =" . $_POST['id']);
    if ($sql) {
        $status = '<div class="alert alert-success text-center alert-dismissible fade show" role="alert"> Message deleted.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    } else {
        $status = '<div class="alert text-center alert-danger alert-dismissible fade show" role="alert">
  <strong>Error: </strong> Something went wrong!
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

    <title>Contact Us Messages - Smart Admin</title>

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



                                        <h2>Miscellaneous</h2>

                                        <div class="d-flex">

                                            <i class="mdi mdi-home text-muted hover-cursor"></i>

                                            <p class="text-muted mb-0 hover-cursor">

                                                &nbsp;/&nbsp;Site&nbsp;/&nbsp;</p>

                                            <p class="text-primary mb-0 hover-cursor">Messages</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Dashboard Header Ends here-->
                    <?php
                    if (isset($_GET['id'])) {
                        $sql = $conn->query("select * from contact where id=" . $_GET['id']);
                        if ($sql->num_rows > 0) {
                            $msg = $sql->fetch_assoc();
                    ?>
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <div class="shadow">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-tittle text-center">Message</h4>
                                                <div class="d-flex justify-content-between my-2">
                                                    <div><a href="messages.php?id=<?php echo $_GET['id'] - 1; ?>"><i class="mdi mdi-arrow-left-circle"></i> Previous</a></div>
                                                    <div><a href="messages.php?id=<?php echo $_GET['id'] + 1; ?>">Next <i class="mdi mdi-arrow-right-circle"></i></a></div>
                                                </div>
                                                <p>Subject: <b><?php echo $msg['subject']; ?></b></p>
                                                <p>From: <?php echo $msg['name'] . ' &lt;' . $msg['email'] . '&gt;'; ?></p>
                                                <textarea readonly name="" id="" cols="30" rows="10" class="form-control mt-3"><?php echo $msg['message']; ?></textarea>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php   } else {
                            echo '<script> location.replace("messages.php"); </script>';
                        }
                    } else { ?>
                        <div class="row">
                            <?php
                            $limit = 25;
                            (isset($_GET['page'])) ? $page = $_GET['page'] : $page = 1;
                            $start = ($page - 1) * $limit;
                            $sql = $conn->query("select * from contact ORDER BY id DESC LIMIT $start, $limit");
                            $sql2 = $conn->query("select * from contact ORDER BY id DESC");
                            $total_records = $sql2->num_rows;
                            $total_pages = ceil($total_records / $limit);
                            if ($sql->num_rows > 0) {
                                $i = 0;
                            ?>

                                <!-- body content -->
                                <div class="col mx-auto">
                                    <div class="shadow">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title">Messages</h4>
                                                <?php echo $status; ?>
                                                <caption class="my-2 font-weight-bold">
                                                    Showing Records <?= (($start < 1) ? $page : $start) . " to " . ($start + $sql->num_rows) . " of " . $total_records ?>
                                                </caption>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Subject</th>
                                                            <th class="text-center">Action</th>
                                                        </tr>

                                                        <?php
                                                        while ($row = $sql->fetch_assoc()) {
                                                            $i++;
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $row['name']; ?></td>
                                                                <td><?php echo $row['email']; ?></td>
                                                                <td><?php echo $row['subject']; ?></td>
                                                                <td class="text-center">
                                                                    <form action="" method="post">

                                                                        <a href="messages.php?id=<?php echo $row['id']; ?>" class="btn text-primary">View</a> | <button type="submit" class="btn text-danger" name="delete">Delete</button>

                                                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="id">

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
                            <?php } else { ?>
                                <div class="col-md-6 col-md-4 mx-auto">
                                    <h3 class="mt-5 text-center">Inbox is empty</h3>

                                </div>




                            <?php } ?>





                            <!-- Body content ends here -->

                        </div>

                    <?php  }

                    ?>






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