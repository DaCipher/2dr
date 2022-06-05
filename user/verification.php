<?php
session_start();
require "../middleware/auth.php";

// Validate user session

if (
    !isset($_SESSION['login']) || $_SESSION['login'] != true
) {
    header("location: ../account/signin.php?postLogin=.." . $_SERVER['REQUEST_URI']);
    exit();
} else {
    $data = getSingleRecord('investment', 'user_id', $_SESSION['id']);
    $user = getSingleRecord('users', 'id', $_SESSION['id']);
    if ($user['status'] === 'active') {
        if ($_SESSION['role'] !== 'user') {
            header('location: ../admin');
        } else {
            header('location: index.php');
        }
    } elseif ($user['status'] == 'disbaled') {
        header('location: ../logout');
    }
}

$back_id_error = $front_id_error = $status_error = "";
$account = checkStatus(($_SESSION['id']));

// echo "<pre>";
// var_dump($account);
// echo "</pre>";
// die();

if (isset($_POST['upload'])) {
    // Error Counter
    $error = "";
    // Allowed image types
    $fileTypes = ['png', 'jpg', 'jpeg'];
    // Upload dir
    $upload_dir = "../admin/id_uploads/";
    $db_dir = "/admin/id_uploads/";
    // get uploaded  id
    $upload_file_front = $_FILES['id-front'];
    $upload_file_back = $_FILES['id-back'];
    // full path to ID
    $front_id_file = $upload_dir . basename($upload_file_front['name']);
    $back_id_file = $upload_dir . basename($upload_file_back['name']);
    // Get Image extension
    $front_id_type = getImageFileType($front_id_file);
    $back_id_type = getImageFileType($back_id_file);
    // inc username in image name
    $upload_file_front['name'] = changeImageName("id-front.") . $front_id_type;
    $upload_file_back['name'] = changeImageName("id-back.") . $back_id_type;


    if (empty($_FILES['id-front']) || empty($_FILES['id-back'])) {
        die("Upload Empty");
    } else {
        if (imageExist(strtolower($_SESSION['id']))) {
            $error = "set";
            $status_error = "File Already Exist!";
        } else {
            if (!validateImage($upload_file_front)) {
                $error = "set";
                $front_id_error = "Only images are allowed";
            } elseif (!validateImage($upload_file_back)) {
                $error = "set";
                $back_id_error = "Only images are allowed";
            }
            if (!checkImageSize($upload_file_front)) {
                $error = "set";
                $front_id_error = "Image is bigger than 2mb";
            } elseif (!checkImageSize($upload_file_back)) {
                $error = "set";
                $back_id_error = "Image is bigger than 2mb";
            }

            if (!in_array($front_id_type, $fileTypes) || !in_array($back_id_type, $fileTypes)) {
                $error = "set";
                $status_error = "Only JPEG, JPG and PNG format is allowed";
            }

            if (empty($error)) {
                if (storeImage($upload_file_front['tmp_name'], $upload_dir . $upload_file_front['name']) && storeImage($upload_file_back['tmp_name'], $upload_dir . $upload_file_back['name'])) {
                    SaveImage(strtolower($_SESSION['id']), $db_dir . $upload_file_front['name'], $db_dir . $upload_file_back['name']);
                    $account = checkStatus(($_SESSION['id']));
                } else {
                    $status_error = "Error Uploading File";
                }
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <title>User Verification - Daily Crypto Returns</title>
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
                    <div class="container">
                        <?php if ($account['status'] == "verify") : ?>
                            <div class="mx-md-5 p-3">
                                <h2 class="text-center mb-4">UPLOAD ID</h2>
                                <div>
                                    <p class="mb-5" style="line-height: 30px;">
                                        FXTradeIQ is legally required to hold on record (to file) the necessary documentation in support of your application. Trading access and/or withdrawals will not be permitted until your documents have been received and verified. You should either upload 'proof of identity' or 'proof of residency' document.
                                    </p>

                                    <h4>PROOF OF IDENTITY:</h4>
                                    <p class="mb-5" style="line-height: 30px;">
                                        A color copy of valid passport or other official state ID (e.g. driver's license, identity card, etc). The ID must be valid and contain the client's full name, an issue or expiry date, the client's place and date of birth OR tax identification number and the client's signature.
                                    </p>

                                    <h4>PROOF OF RESIDENCY:</h4>
                                    <p class="mb-5" style="line-height: 30px;">
                                        A recent utility bill (e.g. electricity, gas, water, phone, oil, Internet and/or cable TV connections), or bank statement dated within the last 6 months confirming your registered address.
                                    </p>
                                </div>

                                <form action="" method="post" enctype="multipart/form-data">
                                    <span class="help-block text-primary my-3"><?= $status_error; ?></span>
                                    <div class="mb-4 p-3">
                                        <label for="" class="font-weight-bold">ID Front</label>
                                        <input type="file" name="id-front" id="id-front" accept=".pdf, .png, .jpeg, .jpg" class="form-control-file" required>
                                        <span class="help-block text-primary"><?= $front_id_error; ?></span>
                                    </div>
                                    <div class="mb-4 p-3">
                                        <label for="" class="font-weight-bold">ID Back</label>
                                        <input type="file" name="id-back" id="id-front" accept=".pdf, .png, .jpeg, .jpg" class="form-control-file" required>
                                        <span class="help-block text-primary"><?= $front_id_error; ?></span>
                                    </div>
                                    <div>
                                        <button type="submit" name="upload" class="btn btn-block btn-primary">UPLOAD DOCUMENT</button>
                                    </div>
                                </form>
                            </div>
                        <?php elseif ($account['status'] == "verifying") : ?>
                            <div class="d-flex  align-items-center">
                                <div class="shadow">
                                    <div class="card">
                                        <div class="card-body py-5">
                                            <div class="text-center">
                                                <div class="d-flex justify-content-center mb-4">
                                                    <i class="mdi mdi-thumb-up-outline text-white bg-success px-3 py-2 rounded-circle" style="font-size: 3.6rem;"></i>
                                                </div>
                                                <h4 class="mb-3">Your document has been upload!</h4>
                                                <h6>Our Legal Team is reviewing your documents.</h6>
                                                <div>
                                                    <p>Pleae note that this review can take some time. Once the verification is done, you will have full access to your trading platform.</p>
                                                    <p>For more information contact us on <span class="font-weight-bold"> support@fxtrade-iq.com</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
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