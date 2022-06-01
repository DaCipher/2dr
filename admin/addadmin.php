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
            if ($_SESSION['role'] !== 'admin_4') {
                header("location: dashboard.php");
            }
        }
    } else {

        header('location: ../logout');
    }
}

// Disable
$status = $error = $search = $status_search = $username_err =  $disabled = '';
if (isset($_POST['search'])) {
    $uname = trim($_POST['username']);
    $sql = $conn->query('select * from users where username ="' . $uname . '"'); {
        if ($sql->num_rows > 0) {
            $data = $sql->fetch_assoc();
            if (isAdmin('role', $data['id'])) {
                $status_search = '<div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                <strong>Error: </strong> Already an Admin!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 </div>';
            } else {
                $search = "set";
            }
        } else {
            $username_err = 'Username not found!';
        }
    }
}

if (isset($_POST['add'])) {
    $uname = trim($_POST['username']);
    $level = $_POST['role'];
    $data = getSingleRecord('users', 'username', $uname);
    $sql = $conn->query("insert into role (role, user_id) values ('" . $level . "', '" . $data['id'] . "')");
    if ($sql) {
        $status = "";
        $status_search = '<div class="alert alert-success alert-dismissible fade show" role="alert">User added as admin.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
    } else {
        $search = 'set';
        $status = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
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

    <title>Add Admin - Smart Admin</title>
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



                                        <h2>Add Admin User</h2>

                                        <div class="d-flex">

                                            <i class="mdi mdi-home text-muted hover-cursor"></i>

                                            <p class="text-muted mb-0 hover-cursor">

                                                &nbsp;/&nbsp;Admin&nbsp;/&nbsp;</p>

                                            <p class="text-primary mb-0 hover-cursor">Add</p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Page contents goes here -->

                    <!-- Page content ends here -->


                    <div class="row">
                        <div class="col-md-6 col-lg-4 mx-auto">
                            <div class="shadow">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="text-center">Add Admin User</h5>
                                        <?php if (empty($search)) { ?>
                                            <?php echo $status_search; ?>
                                            <form action="addadmin.php" class="my-3" method="post">
                                                <div class="form-group">
                                                    <label for="username">Enter Username</label>
                                                    <input type="text" name="username" id="username" class="form-control" required>
                                                    <span class="help-block text-danger"><?php echo $username_err; ?></span>
                                                </div>
                                                <input type="submit" class="btn form-control btn-primary" name="search" value="Search">
                                            </form>
                                        <?php } else { ?>
                                            <?php echo $status; ?>
                                            <div class="my-4">Username: <?php echo $uname; ?></div>
                                            <form action="addadmin.php" method="post">
                                                <div class="form-group">
                                                    <label for="pass1">Admin Level</label>
                                                    <select name="role" id="" class="form-control" required>
                                                        <option value="">--- Select Level ---</option>
                                                        <option value="agent_1">Agent Level 1</option>
                                                        <option value="agent_2">Agent Level 2</option>
                                                        <option value="admin_1">Admin Level 1</option>
                                                        <option value="admin_2">Admin Level 2</option>
                                                        <option value="admin_3">Admin Level 3</option>
                                                        <option value="admin_4">Admin Level 4</option>
                                                    </select>
                                                </div>
                                                <input type="hidden" name="username" value="<?php echo $uname; ?>">
                                                <input type="submit" name="add" value="Make Admin" class="btn form-control btn-primary" required>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>



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