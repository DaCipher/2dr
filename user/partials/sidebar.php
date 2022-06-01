<?php
// require_once "../middleware/auth.php";
// if (isset($_SESSION)) $account = checkStatus($_SESSION['username']);

?>
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <?php if ($account['status'] == "active") : ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/index.php">
                    <i class="mdi mdi-home menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/deposit.php">
                    <i class="mdi mdi-cash-multiple menu-icon"></i>
                    <span class="menu-title">Fund Account</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/withdraw.php">
                    <i class="mdi mdi-wallet menu-icon"></i>
                    <span class="menu-title">Withdraw</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/transaction.php">
                    <i class="mdi mdi-square-inc-cash menu-icon"></i>
                    <span class="menu-title">Transaction History</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                    <i class="mdi mdi-account menu-icon"></i>
                    <span class="menu-title">Account</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="auth">
                    <ul class="nav mb-0">
                        <li class="nav-item ml-4"> <a class="nav-link" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/profile.php"><i class="mdi mdi-account menu-icon"></i> Profile </a></li>
                        <li class="nav-item ml-4"> <a class="nav-link" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/user/settings.php"><i class="mdi mdi-settings menu-icon"></i> Settings </a></li>
                    </ul>
                </div>
            </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST']; ?>/logout">
                <i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Logout</span>
            </a>
        </li>
    </ul>
</nav>