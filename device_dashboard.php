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


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Users</title>


    <style>
        p {
            font-size: 16px!important;
        }
        .btn btn-danger btn-sm br-2 {
            margin-left: 304px;
        }
        label {
            display: inline-block;
            margin-bottom: -0.5rem!important;
        }


        .switch {
            position: relative;
            display: inline-block;
            width: 49px;
            height: 23px;
            margin-left: 197px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 16px!important;
            width: 16px!important;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #1270ba!important;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
</head>
<div class="container-scroller">
    <?php include ('admin_menu.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <?php include ('header.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <nav aria-label="breadcrumb">
                    </nav>
                </div>

                <div class="row">
                    <?php
                    $sql = "SELECT * FROM `iot_devices` where is_deleted != 1";
                    $result = mysqli_query($iot_db, $sql);
                    while($row = mysqli_fetch_array($result)){
                        ?>
                        <div class="col-md-4 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $row["device_name"]; ?>
                                        <label class="switch">
                                            <input type="checkbox" name="is_active" id="is_active" value="<?php echo $row["device_id"]; ?>" <?php echo ($row['is_active']==1 ? 'checked' : '');?>>
                                            <span class="slider round"></span>

                                        </label>
                                        <a href="device/del_device.php?device_id=<?php echo  $row["device_id"]; ?>" class="btn btn-danger btn-sm br-2" >
                                            <i>
                                                <svg class="table-delete" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4h-3.5z"></path></svg>
                                            </i>
                                        </a>
                                    </h4>
                                    <hr>
                                    <table class="table table-borderless">
                                        <tbody>
                                        <tr>
                                            <td colspan="2" >Device ID : </td>
                                            <td colspan="3" ><?php echo $row['device_id']; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" >Device Name : </td>
                                            <td colspan="3" ><?php echo $row['device_name']; ?></td>

                                        </tr>
                                        <tr>
                                            <td colspan="2" >Device Desc : </td>
                                            <td colspan="3" ><?php echo $row['device_description']; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" >Device Loc : </td>
                                            <td colspan="3" ><?php echo $row['device_location']; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th scope="col">Temperature</th>
                                            <th scope="col">Humidity</th>
                                            <th scope="col">Procedure</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><?php echo '0'; ?></td>
                                            <td><?php echo '0'; ?></td>
                                            <td><?php echo '0'; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th scope="col">IAQ</th>
                                            <th scope="col">VOC</th>
                                            <th scope="col">CO2</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><?php echo '0'; ?></td>
                                            <td><?php echo '0'; ?></td>
                                            <td><?php echo '0'; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                </div>

                            </div>

                        </div>
                    <?php } ?>
                </div>

            </div>

        </div>

    </div>

</div>
<script>
    function deviceDB(device_id) {
        window.open("<?php echo site_URL . "iot_device_data.php?device_id=" ; ?>" + device_id , "_self")
    }
</script>
<script>
    $("input#is_active").click(function () {
        var isChecked = $(this)[0].checked;
        var val = $(this).val();
        var data_1 = "&is_active=" + val+ "&isChecked=" + isChecked;
        $.ajax({
            type: 'POST',
            url: "device_backend.php",
            data: data_1,
            success: function (response) {

            }
        });

    });
</script>
<script>
    function submitForm(url) {
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#device_dashboard").serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (data) {
                // window.location.href = window.location.href + "?aa=Line 1";
                $(':input[type="button"]').prop('disabled', false);
                location.reload();
            }
        });
    }
</script>
<?php include ('footer.php'); ?>

</html>
</body>

