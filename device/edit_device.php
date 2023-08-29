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
$modified_by = $_SESSION["id"];
if (!empty($_POST['edit_device_id'])){
    $edit_cust_id = $_POST['edit_cust_id'];
    $dd_id = $_POST['edit_device_id'];
    $edit_dev_id = $_POST["edit_dev_id"];
    $edit_dev_desc = $_POST["edit_dev_desc"];
    $edit_dev_loc = $_POST["edit_dev_loc"];

    $service_url = $rest_api_uri . "devices/edit_iot_device.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'device_id' => $dd_id,
        'c_id' => $edit_cust_id,
        'device_description' => $edit_dev_desc,
        'device_location' => $edit_dev_loc,
        'modified_by' => $modified_by,
        'modified_on' => $chicagotime
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
        $errors[] = "Iot Device Not Updated.";
        $message_stauts_class = 'alert-danger';
        $import_status_message = 'Iot Device Not Updated.';
    }
    $errors[] = "Iot Device Updated Successfully.";
    $message_stauts_class = 'alert-success';
    $import_status_message = 'Iot Device Updated Successfully.';
    header('Location: create_device.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include ('../header.php'); ?>
    <title>Edit Device</title>
</head>
<body>
   <div class="container-scroller">
      <?php include ('../admin_menu.php'); ?>
    <!-- partial -->
      <div class="container-fluid page-body-wrapper margin-244">
        <!-- partial:partials/_navbar.html -->
        <?php include ('../nav.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Devices</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Device</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-md-10 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-heading">
                                Edit Device
                            </div>
                            <div class="card-body">
                                <form action="" method="post" id="device_settings" enctype="multipart/form-data">
                                    <?php
                                    $device_id = $_GET['device_id'];
                                    $sql = "select * from iot_devices where device_id = '$device_id' and is_deleted != 1";
                                    $res = mysqli_query($iot_db, $sql);
                                    $row = mysqli_fetch_array($res);
                                    $customer = $row['c_id'];
                                    $dev_id = $row['device_id'];
                                    $dev_name = $row['device_name'];
                                    $dev_desc = $row['device_description'];
                                    $dev_loc = $row['device_location'];
                                    $is_active = $row['is_active'];
                                    ?>

                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Customer </label>
                                        <div class="col-sm-9">
                                            <select name="edit_cust_id" id="edit_cust_id" class="form-control form-select select2" data-placeholder="Select Customer" >
                                                <option value="" selected> Select Customer </option>
                                                <?php
                                                $st_dashboard = $customer;
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
                                            <input type="hidden" name="edit_device_id" id="edit_device_id" value="<?php echo $dev_id; ?>">
                                            <input type="text" class="form-control" name="edit_dev_id" id="edit_dev_id" value="<?php echo $dev_id; ?>"
                                                   disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Device Name :</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="edit_dev_name" id="edit_dev_name" value="<?php echo $dev_name; ?>"
                                                   class="form-control" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Device Description : </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="edit_dev_desc" id="edit_dev_desc" value="<?php echo $dev_desc; ?>"
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Device Location : </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="edit_dev_loc" id="edit_dev_loc" value="<?php echo $dev_loc; ?>"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Is Active : </label>
                                        <div class="col-sm-9">
                                            <label class="custom-switch form-switch mb-0  p-0" style="margin-left: 1px;margin-top: 9px;">
                                                <input type="checkbox" class="custom-switch-input" name="edit_is_active" id="edit_is_active" value="<?php echo $row["device_id"]; ?>" <?php echo ($is_active==1 ? 'checked' : '');?>>
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Active</span>
                                            </label>
                                        </div>
                                    </div>
                                    <hr/>

                                    <div class="form-group row">
                                        <div >
                                            <button type="submit" name="submit_btn" id="submit_btn" class="btn btn-blue">Update</button>
                                        </div>&ensp;
                                        <div>
                                            <button class="btn btn-red">Cancel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("input#edit_is_active").click(function () {
        var isChecked = $(this)[0].checked;
        var val = $(this).val();
        var data_1 = "&edit_is_active=" + val+ "&isChecked=" + isChecked;
        $.ajax({
            type: 'POST',
            url: "../device_backend.php",
            data: data_1,
            success: function (response)
            {

            }
        });

    });
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
    $('#edit_customer').on('change', function (e) {
        $("#device_settings").submit();
    });
</script>
</body>
</html>

