<?php
include("config.php");
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style>@font-face { font-family: Arial !important; font-display: swap !important; }</style>
    <link href="#" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <script type="text/javascript" src="#"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="assets/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="assets/js/pages/components_dropdowns.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.5.4/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.0-beta1/js/bootstrap.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0
        }
        .navbar-conts {
            width: 60px;
            height: 60px;
            background-color: transparent;
            content-visibility: hidden;
        }
        .imgfluid{
            position: absolute;
            width: 50px;
            height: 50px;
            top: 1%;
        }
        header{
            position: fixed;
            top: 0;
        }

        ul li a{
            font-family: Georgia, 'Times New Roman', Times, serif;
        }
        ul li a:hover {
            border-bottom: #000000 2px solid;
            box-shadow: rgba(255, 255, 255, 0.1) 0px 1px 1px 0px inset, rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px;
        }
        .navbar-conts{
            padding-left: 40PX;
            padding-right: 40PX;
            animation-name: navbar-conts;
            animation-duration: 0.5s;
            animation-timing-function: ease-in-out;
            animation-iteration-count: 1;
            animation-fill-mode: both;
            content-visibility: visible;
        }

        @keyframes navbar-conts {
            0% {
                width: 25%;
                background: #f0f0f0;
            }

            25% {
                width: 50%;
                background: #f8f8f8;
            }

            50% {
                width: 75%;
                background: #f8f8f8;
            }

            100% {

                width: 100%;
                background: #fafafa;

            }

        }
        .rounded-pill {
            border-radius: 0rem!important;
        }
    </style>
</head>
<body classname="snippet-body" data-new-gr-c-s-check-loaded="14.1115.0" data-gr-ext-installed="">
<div class="d-flex vw-100 vh-100 align-items-center justify-content-center">

    <header class="d-flex navbar-conts flex-wrap align-items-center justify-content-center justify-content-md-between py-1 mb-4 border-bottom rounded-pill">
        <a href="~/HOME" class="navbarlogo d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none w-auto">
            <!--<img src="assets/images/site_logo.png" alt="" style="width: 150px!important;margin-left: 72px!important;"/>-->
        </a>
        <div class="col-md-3"></div>
        <div class="navbar-center col-md-3">
            <h3 id="screen_header" style="margin-left: -86px;"><span class="text-semibold"><?php echo $cam_page_header; ?></span></h3>
        </div>
        <div class="dropdown text-end" style="z-index: 1;">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1"
               data-bs-toggle="dropdown" aria-expanded="false">
                <?php if(!empty($_SESSION["uu_img"])){ ?>
                    <img src="user_images/<?php echo $_SESSION["uu_img"]; ?>" alt="" width="32" height="32" class="rounded-circle">
                <?php }else{ ?>
                    <img src="user_images/user.png" alt="" width="32" height="32" class="rounded-circle">
                <?php } ?>

                <span><?php echo $_SESSION['fullname']; ?></span>
            </a>
            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1" style="z-index: 1;">
                <li><a href="<?php echo $siteURL; ?>profile.php" class="dropdown-item"><i class="icon-user-plus"></i> Profile</a></li>
                <li><a href="<?php echo $siteURL; ?>change_pass.php" class="dropdown-item"><i class="icon-cog5"></i> Change Password</a></li>
                <li><a href="logout.php" class="dropdown-item"><i class="icon-switch2"></i> Logout</a></li>
            </ul>
        </div>
    </header>
</div>
</body>
</html>