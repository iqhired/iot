<?php
include("config.php");
$devc_check = $_POST['devc_id'];
$devc_is_checked = $_POST['isChecked'];
if ($devc_check != "") {

    if($devc_is_checked =='true') {
        $sql1 = "UPDATE `iot_devices` set  is_active = 1  WHERE `device_id`='$devc_check'";
    }else{
        $sql1 = "UPDATE `iot_devices` set  is_active = 0  WHERE `device_id`='$devc_check'";
    }
    if (mysqli_query($db, $sql1)) {
        $_SESSION['message_stauts_class'] = 'alert-success';
        $_SESSION['import_status_message'] = 'Required Sucessfully';
    } else {
        $_SESSION['message_stauts_class'] = 'alert-danger';
        $_SESSION['import_status_message'] = 'please retry.';
    }
}

header("Location:line.php");
?>
