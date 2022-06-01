<?php
if (isset($_SESSION)) $account = checkStatus($_SESSION['id']);

?>
<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="navbar-brand-wrapper justify-content-center d-none d-lg-flex">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand brand-logo" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/">
                <h4 class="text-dark">FX<span class='bg-danger rounded text-white px-1'>TRADE</span>IQ<span class='text-danger'><b>.</b></span></h4>
            </a>
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="mdi mdi-sort-variant text-danger"></span>
            </button>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end flex-fill">
        <a class="d-lg-none" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/">
            <h4 class="text-dark">FX<span class='bg-danger rounded text-white px-1'>TRADE</span>IQ<span class='text-danger'><b>.</b></span></h4>
        </a>
        <ul class="navbar-nav mr-lg-4 w-100">
            <li class="nav-item nav-search d-none d-lg-block w-100">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="search">
                            <i class="mdi mdi-lock">

                            </i>
                        </span>
                    </div>
                    <input type="text" readonly class="form-control-plaintext" value="SESSION ID: <?php echo $_SESSION['unique_id']; ?>" aria-label="search" aria-describedby="search">
                </div>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/images/faces/face5.jpg" alt="profile" />
                    <span class="nav-profile-name"><?php echo $_SESSION['username']; ?></span>
                </a>

                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                    <?php if ($account['status'] == "active") : ?>
                        <a href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/settings.php" class="dropdown-item">
                            <i class="mdi mdi-settings text-danger"></i> Settings
                        </a>
                    <?php endif; ?>
                    <a class="dropdown-item" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/logout">
                        <i class="mdi mdi-logout text-danger"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
        </button>
    </div>
</nav>