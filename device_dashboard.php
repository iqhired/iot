<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    header('Location: ./config/403.php');
}
require "./vendor/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";


$tab_line = $_SESSION['tab_station'];
$is_tab_login = $_SESSION['is_tab_user'];
//Set the session duration for 10800 seconds - 3 hours
$duration = auto_logout_duration;
//Read the request time of the user
$time = $_SERVER['REQUEST_TIME'];
//Check the user's session exist or not
if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $duration) {
//Unset the session variables
    session_unset();
//Destroy the session
    session_destroy();
    if($_SESSION['is_tab_user'] || $_SESSION['is_cell_login']){
        header($redirect_tab_logout_path);
    }else{
        header($redirect_logout_path);
    }

//	header('location: ../logout.php');
    exit;
}
$is_tab_login = $_SESSION['is_tab_user'];
$is_cell_login = $_SESSION['is_cell_login'];
//Set the time of the user's last activity
$_SESSION['LAST_ACTIVITY'] = $time;
$i = $_SESSION["role_id"];
if ($i != "super" && $i != "admin" && $i != "pn_user" && $_SESSION['is_tab_user'] != 1 && $_SESSION['is_cell_login'] != 1 ) {
    header('location: ../line_status_grp_dashboard.php');
}
$assign_by = $_SESSION["id"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php echo $sitename; ?> |Quality Alert</title>
    <!-- Global stylesheets -->

    <link href="assets/css/core.css" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- Theme JS files -->
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery_ui/interactions.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/pages/datatables_basic.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/bootstrap_select.min.js"></script>
    <script type="text/javascript" src="assets/js/pages/form_bootstrap_select.js"></script>
    <script type="text/javascript" src="assets/js/pages/form_layouts.js"></script>
    <script type="text/javascript" src="assets/js/plugins/ui/ripple.min.js"></script>

    <!--Internal  Datetimepicker-slider css -->
    <link href="<?php echo $siteURL; ?>assets/css/form_css/amazeui.datetimepicker.css" rel="stylesheet">
    <link href="<?php echo $siteURL; ?>assets/css/form_css/jquery.simple-dtpicker.css" rel="stylesheet">
    <link href="<?php echo $siteURL; ?>assets/css/form_css/picker.min.css" rel="stylesheet">
    <!--Bootstrap-datepicker css-->
    <link rel="stylesheet" href="<?php echo $siteURL; ?>assets/css/form_css/bootstrap-datepicker.css">
    <!-- Internal Select2 css -->
    <link href="<?php echo $siteURL; ?>assets/css/form_css/select2.min.css" rel="stylesheet">
    <!-- STYLES CSS -->
    <link href="<?php echo $siteURL; ?>assets/css/form_css/style.css" rel="stylesheet">
    <link href="<?php echo $siteURL; ?>assets/css/form_css/style-dark.css" rel="stylesheet">
    <link href="<?php echo $siteURL; ?>assets/css/form_css/style-transparent.css" rel="stylesheet">
    <!---Internal Fancy uploader css-->
    <link href="<?php echo $siteURL; ?>assets/css/form_css/fancy_fileupload.css" rel="stylesheet" />
    <!--Internal  Datepicker js -->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/datepicker.js"></script>
    <!-- Internal Select2.min js -->
    <!--Internal  jquery.maskedinput js -->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/jquery.maskedinput.js"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/spectrum.js"></script>
    <!--Internal  jquery-simple-datetimepicker js -->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/datetimepicker.min.js"></script>
    <!-- Ionicons js -->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/jquery.simple-dtpicker.js"></script>
    <!--Internal  pickerjs js -->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/picker.min.js"></script>
    <!--internal color picker js-->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/pickr.es5.min.js"></script>
    <script src="<?php echo $siteURL; ?>assets/js/form_js/colorpicker.js"></script>
    <!--Bootstrap-datepicker js-->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/bootstrap-datepicker.js"></script>
    <!-- Internal form-elements js -->
    <script src="<?php echo $siteURL; ?>assets/js/form_js/form-elements.js"></script>
    <link href="<?php echo $siteURL; ?>assets/css/form_css/demo.css" rel="stylesheet"/>
    <link href="<?php echo $siteURL; ?>assets/css/select/select2.min.css" rel="stylesheet" />
    <script src="<?php echo $siteURL; ?>assets/js/select/select2.min.js"></script>
    <style>
        .navbar {

            padding-top: 0px!important;
        }
        .dropdown .arrow {

            margin-top: -25px!important;
            width: 1.5rem!important;
        }
        #ic .arrow {
            margin-top: -22px!important;
            width: 1.5rem!important;
        }
        .card-header:first-child {
            border-radius: 11px 11px 0 0;
            color: #fff;
        }
        .table {
            border: 1px solid #ffffff!important;

        }
        </style>
    <?php

    include("header.php");
    include("admin_menu.php");


    ?>
<body class="ltr main-body app horizontal">
<!-- container -->
<div class="main-container container">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <ol class="breadcrumb">
                <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">Events Module</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quality Alert</li>
            </ol>

        </div>

    </div>



            <div class="row row-sm">

                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header  d-flex custom-card-header border-bottom-0 ">
                            <h5 class="card-title">Device ID</h5>

                        </div>
                        <div class="card-body">
                            <div class="d-flex custom-card-header border-bottom-0 ">

                                <table class="table table-borderless">

                                    <tbody>
                                    <tr>

                                        <td colspan="2">Device ID</td>
                                        <td colspan="3">Larry the Bird</td>

                                        <td colspan="2">
                                            <label class="custom-switch form-switch mb-0  p-0">
                                                <input type="checkbox" name="custom-switch-radio" class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Active</span>
                                            </label>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td colspan="2">Device Name</td>
                                        <td colspan="3">Larry the Bird</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm br-5" onclick="submitForm('delete_quality_alert.php')">
                                                <i>
                                                    <svg class="table-delete" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4h-3.5z"></path></svg>
                                                </i>
                                            </button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="2">Device Desc</td>
                                        <td colspan="3">Larry the Bird</td>

                                    </tr>
                                    </tbody>
                                </table>


                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header  d-flex custom-card-header border-bottom-0 ">
                            <h5 class="card-title">Device ID</h5>

                        </div>
                        <div class="card-body">
                            <div class="d-flex custom-card-header border-bottom-0 ">

                                <table class="table table-borderless">

                                    <tbody>
                                    <tr>

                                        <td colspan="2">Device ID</td>
                                        <td colspan="3">Larry the Bird</td>

                                        <td colspan="2">
                                            <label class="custom-switch form-switch mb-0  p-0">
                                                <input type="checkbox" name="custom-switch-radio" class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Active</span>
                                            </label>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td colspan="2">Device Name</td>
                                        <td colspan="3">Larry the Bird</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm br-5" onclick="submitForm('delete_quality_alert.php')">
                                                <i>
                                                    <svg class="table-delete" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4h-3.5z"></path></svg>
                                                </i>
                                            </button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="2">Device Desc</td>
                                        <td colspan="3">Larry the Bird</td>

                                    </tr>
                                    </tbody>
                                </table>


                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
                    <div class="card custom-card">
                        <div class="card-header  d-flex custom-card-header border-bottom-0 ">
                            <h5 class="card-title">Device ID</h5>

                        </div>
                        <div class="card-body">
                            <div class="d-flex custom-card-header border-bottom-0 ">

                                <table class="table table-borderless">

                                    <tbody>
                                    <tr>

                                        <td colspan="2">Device ID</td>
                                        <td colspan="3">Larry the Bird</td>

                                        <td colspan="2">
                                            <label class="custom-switch form-switch mb-0  p-0">
                                                <input type="checkbox" name="custom-switch-radio" class="custom-switch-input">
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Active</span>
                                            </label>
                                        </td>

                                    </tr>
                                    <tr>

                                        <td colspan="2">Device Name</td>
                                        <td colspan="3">Larry the Bird</td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm br-5" onclick="submitForm('delete_quality_alert.php')">
                                                <i>
                                                    <svg class="table-delete" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4h-3.5z"></path></svg>
                                                </i>
                                            </button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="2">Device Desc</td>
                                        <td colspan="3">Larry the Bird</td>

                                    </tr>
                                    </tbody>
                                </table>


                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- End Row -->





        </div>
        <!-- Container closed -->
    </div>
    <!-- main-content closed -->



</div>
<!-- End Page -->
<?php include ('footer1.php'); ?>

</body>
</html>

