<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    header('Location: ./config/403.php');
}
require ".././vendor/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";
$user_id = $_SESSION["id"];
if (!empty($_POST['dev_id'])){
    $c_id = $_POST["customer"];
    $device_id = $_POST["dev_id"];
    $device_name = $_POST["dev_name"];
    $device_desc = $_POST["dev_desc"];
    $device_loc = $_POST["dev_loc"];
    $is_active = 1;
    $service_url = $rest_api_uri . "devices/iot_device.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'c_id' => $c_id,
        'device_id' => $device_id,
        'device_name' => $device_name,
        'device_description' => $device_desc,
        'device_location' => $device_loc,
        'is_active' => $is_active,
        'created_by' => $user_id,
        'created_on' => $chicagotime
    );
    $secretkey = "SupportPassHTSSgmmi";
    $payload = array(
        "author" => "Saargummi to HTS",
        "exp" => time()+1000
    );
    try{
        $jwt = JWT::encode($payload, $secretkey , 'HS256');
    }catch (UnexpectedValueException $e) {
        echo $e->getMessage();
    }
    $headers = array(
        "Accept: application/json",
        "access-token: " . $jwt . '"',
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
    $curl_response = curl_exec($curl);
    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
    }
    curl_close($curl);
    $decoded = json_decode($curl_response);
    if (isset($decoded->status) && $decoded->status == 'ERROR') {
        die('error occured: ' . $decoded->errormessage);
    }
}
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

$assign_by = $_SESSION["id"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create IOT Device</title>
    <!-- plugins:css -->
</head>

<body>
<div class="container-scroller">
    <?php include ('../admin_menu.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper margin-244">
        <!-- partial:partials/_navbar.html -->
        <?php include ('../header.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Device</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Create Device</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-md-10 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Create Device</h4>

                                <form action="" method="post" id="device_settings" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Customer </label>
                                        <div class="col-sm-9">
                                            <select name="customer" id="customer" class="form-control form-select select2" data-placeholder="Select Customer">
                                                <option value="" selected> Select Customer </option>
                                                <?php

                                                $st_dashboard = $_POST['customer'];

                                                $sql1 = "SELECT * FROM `cus_account` where is_deleted != 1";
                                                $result1 = mysqli_query($db,$sql1);
                                                while ($row1 = $result1->fetch_assoc()) {
                                                    if($st_dashboard == $row1['c_id'])
                                                    {
                                                        $entry = 'selected';
                                                    }
                                                    else
                                                    {
                                                        $entry = '';

                                                    }
                                                    echo "<option value='" . $row1['c_id'] . "' $entry>" . $row1['c_name'];"</option>";
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Device Id :</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="dev_id" id="dev_id" placeholder="Enter Device Id">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Device Name :</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="dev_name" id="dev_name" placeholder="Enter Device Name  ">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Device Description : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="dev_desc" id="dev_desc" placeholder="Enter Device Description ">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Device Location : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="dev_loc" id="dev_loc" placeholder="Enter Device Location ">
                                        </div>
                                    </div>

                                    <button type="submit" name="submit_btn" id="submit_btn" class="btn btn-primary mr-2">Submit</button>
                                    <button class="btn btn-dark">Cancel</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <form action="" id="update-device" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="main-panel">
                        <div class="content-wrapper">
                            <div class="row ">
                                <div class="col-12 grid-margin">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title">
                                                <button type="button" class="btn btn-danger" onclick="submitForm('delete_device.php')">
                                                    <i>
                                                        <svg class="table-delete" xmlns="http://www.w3.org/2000/svg" height="20" color="white" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4h-3.5z"></path></svg>
                                                    </i>
                                                </button>
                                            </h4>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            <label class="ckbox"> <input type="checkbox" id="checkAll"><span></span></label>
                                                        </th>
                                                        <th class="text-center">Sl. No</th>
                                                        <th>Action</th>
                                                        <th>Customer</th>
                                                        <th>Device id</th>
                                                        <th>Device Name</th>
                                                        <th>Active</th>

                                                        <th>User</th>

                                                        <th>Date</th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <?php
                                                        $query = sprintf("SELECT * FROM  iot_devices where is_deleted != 1");
                                                        $qur = mysqli_query($iot_db, $query);
                                                        while ($rowc = mysqli_fetch_array($qur)) {
                                                        ?>
                                                        <td><label class="ckbox"><input type="checkbox" id="delete_check[]" name="delete_check[]"
                                                                                        value="<?php echo $rowc["device_id"]; ?>"><span></span></label></td>
                                                        <td class="text-center"><?php echo ++$counter; ?></td>
                                                        <td class="">
                                                            <a href="edit_device.php?device_id=<?php echo  $rowc["device_id"]; ?>" class="btn btn-primary legitRipple">
                                                                <i>
                                                                    <svg class="table-edit" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM5.92 19H5v-.92l9.06-9.06.92.92L5.92 19zM20.71 5.63l-2.34-2.34c-.2-.2-.45-.29-.71-.29s-.51.1-.7.29l-1.83 1.83 3.75 3.75 1.83-1.83c.39-.39.39-1.02 0-1.41z"></path></svg>
                                                                </i>
                                                            </a>
                                                        </td>
                                                        <td><?php $c_id =  $rowc["c_id"];
                                                            $qurtemp = mysqli_query($db, "SELECT c_name FROM  cus_account where c_id  = '$c_id'");
                                                            while ($rowctemp = mysqli_fetch_array($qurtemp)) {
                                                                $c_name = $rowctemp["c_name"];
                                                            }
                                                            ?>
                                                            <?php echo  $c_name; ?>
                                                        </td>
                                                        <td><?php echo  $rowc["device_id"]; ?></td>
                                                        <td><?php echo  $rowc["device_name"]; ?></td>
                                                        <td>
                                                            <?php
                                                            if($rowc["active"] == 1)
                                                            {
                                                                echo 'No' ;
                                                            }else
                                                            {
                                                                echo 'Yes' ;
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $created_by =  $rowc["created_by"];
                                                            $qurtmp = mysqli_query($db, "SELECT firstname,lastname FROM cam_users where users_id = '$created_by'");
                                                            while ($rowctmp = mysqli_fetch_array($qurtmp)) {
                                                                $firstname = $rowctmp["firstname"];
                                                                $lastname = $rowctmp["lastname"];
                                                                $fullname = $firstname . ' ' . $lastname;
                                                            }
                                                            ?>
                                                            <?php echo  $fullname; ?>
                                                        </td>

                                                        <td><?php echo  dateReadFormat($rowc["created_on"]); ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
    function submitForm(url) {
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#update-device").serialize();
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
<script>
    $(function() {
        /********
         * Function to disable the currently selected options
         *   on all sibling select elements.
         ********/
        $(".myselect").on("change", function() {
            // Get the list of all selected options in this select element.
            var currentSelectEl = $(this);
            var selectedOptions = currentSelectEl.find("option:checked");

            // otherOptions is used to find non-selected, non-disabled options
            //  in the current select. This will allow for unselecting. Added
            //  this to support extended multiple selects.
            var otherOptions = currentSelectEl.find("option").not(":checked").not(":disabled");

            // Iterate over the otherOptions collection, and using
            //   each value, re-enable the unselected options that
            //   match in all other selects.
            otherOptions.each(function() {
                var myVal = $(this).val();
                currentSelectEl.siblings(".myselect")
                    .children("option[value='" + myVal + "']")
                    .attr("disabled", false);
            })

            // iterate through and disable selected options.
            selectedOptions.each(function() {
                var valToDisable = $(this).val();
                currentSelectEl.siblings('.myselect')
                    .children("option[value='" + valToDisable + "']")
                    .attr("disabled", true);
            })

        })
    })
</script>
<script>
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
<script>
    $('.select2').select2();
</script>
</body>
</html>




