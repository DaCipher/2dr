<nav class="sidebar sidebar-offcanvas" id="sidebar">

  <ul class="nav">

    <li class="nav-item">

      <a class="nav-link text-primary" href="index.php">

        <i class="mdi mdi-home menu-icon"></i>

        <span class="menu-title">Dashboard</span>

      </a>

    </li>

    <li class="nav-item">

      <a class="nav-link" data-toggle="collapse" href="#manageusers" aria-expanded="false" aria-controls="auth">

        <i class="mdi mdi-account-multiple menu-icon"></i>

        <span class="menu-title">Manage Users</span>

        <i class="menu-arrow"></i>

      </a>

      <div class="collapse" id="manageusers">

        <ul class="nav mb-0">
          <!-- All Users -->
          <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2') { ?>
            <li class="nav-item ml-4"> <a class="nav-link" href="viewusers.php">
                All Users</a>

            </li>

            <li class="nav-item ml-4"> <a class="nav-link" href="id-verification.php"> ID Verification

              </a>

            </li>

            <li class="nav-item ml-4"> <a class="nav-link" href="viewfunded.php"> Funded Users

              </a>

            </li>
            <li class="nav-item ml-4"> <a class="nav-link" href="viewdisabled.php"> Disabled

                Users </a>

            </li>
          <?php } ?>
          <li class="nav-item ml-4"> <a class="nav-link" href="updatebalance.php"> Update

              Balance </a>

          </li>
          <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2' || $_SESSION['role'] === 'admin_1' || $_SESSION['role'] === 'agent_2') { ?>
            <li class="nav-item ml-4"> <a class="nav-link" href="authuser.php"> Update State

              </a>

            </li>
          <?php } ?>

          <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3' || $_SESSION['role'] === 'admin_2' || $_SESSION['role'] === 'admin_1') { ?>
            <li class="nav-item ml-4"> <a class="nav-link" href="addhistory.php">
                Create

                History </a>

            </li>

            <li class="nav-item ml-4"> <a class="nav-link" href="updatehistory.php"> Update
                History </a>

            </li>
          <?php } ?>
          <?php if (
            $_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3'
          ) { ?>
            <li class="nav-item ml-4"> <a class="nav-link" href="resetuserpassword.php"> Reset

                Password</a>

            </li>

          <?php } ?>

        </ul>

      </div>

    </li>
    <!-- Manage Admin -->
    <?php if ($_SESSION['role'] === 'admin_4') { ?>
      <li class="nav-item">

        <a class="nav-link" data-toggle="collapse" href="#manageadmin" aria-expanded="false" aria-controls="auth">

          <i class="mdi mdi-account-star menu-icon"></i>

          <span class="menu-title">Manage Admin</span>

          <i class="menu-arrow"></i>

        </a>

        <div class="collapse" id="manageadmin">

          <ul class="nav mb-0">

            <li class="nav-item ml-4"> <a class="nav-link" href="viewadmin.php"> Admin Users

              </a>

            </li>

            <li class="nav-item ml-4"> <a class="nav-link" href="addadmin.php"> Add Admin </a>

            </li>

            <li class="nav-item ml-4"> <a class="nav-link" href="editadmin.php"> Update Role

              </a>

            </li>

            <li class="nav-item ml-4"> <a class="nav-link" href="resetadminpasscode.php"> Reset

                Passcode</a>

            </li>

            <li class="nav-item ml-4"> <a class="nav-link" href="resetadminpassword.php"> Reset

                Password </a>

            </li>



          </ul>

        </div>

      </li>
    <?php } ?>

    <!-- Miscellaneous -->
    <?php if ($_SESSION['role'] === 'admin_4' || $_SESSION['role'] === 'admin_3') { ?>
      <li class="nav-item">

        <a class="nav-link" data-toggle="collapse" href="#misc" aria-expanded="false" aria-controls="auth">

          <i class="mdi mdi-book menu-icon"></i>

          <span class="menu-title">Miscellaneous</span>

          <i class="menu-arrow"></i>

        </a>

        <div class="collapse" id="misc">

          <ul class="nav mb-0">

            <li class="nav-item ml-4"> <a class="nav-link" href="messages.php">
                Messages </a>

            </li>
            <?php if ($_SESSION['role'] === 'admin_4') { ?>
              <li class="nav-item ml-4"> <a class="nav-link" href="wallet.php"> Update Wallet </a>
              </li>
            <?php } ?>
          </ul>

        </div>

      </li>
    <?php } ?>
    <li class="nav-item">

      <a class="nav-link" data-toggle="collapse" href="#account" aria-expanded="false" aria-controls="auth">

        <i class="mdi mdi-account menu-icon"></i>

        <span class="menu-title">Account</span>

        <i class="menu-arrow"></i>

      </a>

      <div class="collapse" id="account">

        <ul class="nav mb-0">

          <li class="nav-item ml-4"> <a class="nav-link" href="profile.php"> Profile </a></li>

          <li class="nav-item ml-4"> <a class="nav-link" href="resetpasscode.php"> Reset

              Passcode </a></li>

          <li class="nav-item ml-4"> <a class="nav-link" href="settings.php">

              Settings </a>

          </li>

        </ul>

      </div>

    </li>

    <li class="nav-item">

      <a class="nav-link" href="../logout">

        <i class="mdi mdi-logout menu-icon"></i>

        <span class="menu-title">Logout</span>

      </a>

    </li>

  </ul>







</nav>