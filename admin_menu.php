<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar" style=" float: left; position: fixed;">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="#"><img src="<?php echo $iotURL; ?>assets/images/site_logo.png" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="#"><img src="<?php echo $iotURL; ?>assets/images/logo-mini.png" alt="logo" /></a>
    </div>

    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-primary"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-onepassword  text-info"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-calendar-today text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                        </div>
                    </a>
                </div>
            </div>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo $iotURL; ?>device_dashboard1.php">
              <span class="menu-icon">
                   <i class="fa-solid fa-table-columns"></i>
              </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo $iotURL; ?>user/create_users.php">
              <span class="menu-icon">
                <i class="fa-solid fa-users"></i>
              </span>
                <span class="menu-title">Users</span>
            </a>
        </li>
<!--                        <li class="nav-item menu-items">-->
<!--                            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">-->
<!--                              <span class="menu-icon">-->
<!--                                 <i class="fa-solid fa-users"></i>-->
<!--                              </span>-->
<!--                                <span class="menu-title">Admin Config</span>-->
<!--                            </a>-->
<!--                            <div class="collapse" id="ui-basic">-->
<!--                                <ul class="nav flex-column sub-menu">-->
<!--                                    <li class="nav-item "> <a class="nav-link" href="create_users.php">Create Users</a></li>-->
<!---->
<!--                                </ul>-->
<!--                            </div>-->
<!--                        </li>-->
        <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo $iotURL; ?>device/create_device.php">
              <span class="menu-icon">
                  <i class="fa-solid fa-hard-drive"></i>
              </span>
                <span class="menu-title">Device</span>
            </a>
        </li>



    </ul>
</nav>
