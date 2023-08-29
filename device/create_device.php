<?php
require ".././vendor/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";
$user_id = $_SESSION["id"];
if (($_POST['fSubmit'] == 1 ) && (!empty($_POST['dev_id']))){
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
        $_SESSION['mType'] = mTypeError;
        $_SESSION['dispMessage'] = $decoded->message;
		echo json_encode(array("status" => "error" , "message" => $decoded->message));
        exit;
	}else{
		$_SESSION['mType'] = mTypeSucess;
		$_SESSION['dispMessage'] = 'Device created Successfully';
		echo json_encode(array("status" => "success" , "message" => 'Device created Successfully'));
        exit;
    }
}

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
    <link rel="stylesheet" href="<?php echo $iotURL; ?>assets/pages/css/pag_table.css"/>
	<?php include ('../header.php'); ?>
    <title>Create Device</title>
    <!-- plugins:css -->
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
                            <li class="breadcrumb-item active" aria-current="page">Create Device</li>
                        </ol>
                    </nav>
                </div>
                <div class="container add-btn-cont">
                    <div class="add-btn-row">
                        <button class="myPriButton">Add Device &emsp;<i class="fa fa-plus-circle"></i></button>
                    </div>
                </div>
				<?php dPMessage();?>
                <div class="row" id="addDevice" style="display: none">
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-heading">
                                Add Device
                            </div>
                            <div class="card-body">
                                <form action="" method="post" id="device_settings" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Customer </label>
                                        <div class="col-sm-9">
                                            <select name="customer" id="customer" class="form-control form-select select2" data-placeholder="Select Customer">
                                                <option value='' selected> Select Customer </option>
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
                                    <hr/>
                                    <div class="form-group row">
                                        
                                        <div >
                                            <button type="submit" name="submit_btn" id="submit_btn" class="btn btn-blue">Submit</button>
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
                <form action="" id="up-iot-device" method="post" class="form-horizontal" enctype="multipart/form-data">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-offset-1 col-md-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-sm-12 col-xs-12">
                                                <button type="button" class="btn btn-sm btn-danger pull-left" onclick="deleteDevices('delete_device.php')">
                                                    <i class="fa fa-delete-left"></i>
                                                </button>
                                                <input type="text" class="ptab_search" id="ptab_search" placeholder="Type to search">
                                                <span class="form-horizontal pull-right">
                                                <div class="form-group">
                                                    <label>Show : </label>
                                                    <?php
														$tab_num_rec = (empty($_POST['tab_rec_num'])?10:$_POST['tab_rec_num']);
														$pg = (empty($_POST['pg_num'])?0:($_POST['pg_num'] - 1));
														$start_index = $pg * $tab_num_rec;
													?>
                                                    <input type="hidden" id='tab_rec_num' value="<?php echo $tab_num_rec?>">
                                                    <input type="hidden" id='curr_pg' value="<?php echo $pg?>">
                                                    <select id="num_tab_rec" class="ptab_search">
                                                        <option value="10" <?php echo ($tab_num_rec ==10)? 'selected' : ''?>>10</option>
                                                        <option value="25" <?php echo ($tab_num_rec ==25)? 'selected' : ''?>>25</option>
                                                        <option value="50" <?php echo ($tab_num_rec ==50)? 'selected' : ''?>>50</option>
                                                    </select>
                                                </div>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body table-responsive">
                                        <table class="table">
                                            <thead>
                                            <!--                                        <tr>-->
                                            <th>
                                                <label class="ckbox"> <input type="checkbox" id="checkAll"><span></span></label>
                                            </th>
                                            <th>Action</th>
                                            <th>Customer</th>
                                            <th>Device ID</th>
                                            <th>Device Name</th>
                                            <th>Active</th>
                                            <!--                                            <th>User</th>-->
                                            <!--                                            <th>Date</th>-->
                                            <!--                                        </tr>-->
                                            </thead>
                                            <tbody id="tbody">
                                            <tr>
												<?php
													$index_left = 1;
													$index_right = 2;
													$c_query = "SELECT count(*) as tot_count FROM  iot_devices where is_deleted != 1";
													$c_qur = mysqli_query($iot_db, $c_query);
													$c_rowc = mysqli_fetch_array($c_qur);
													$tot_devices = $c_rowc['tot_count'];
													$query = "SELECT * FROM  iot_devices where is_deleted != 1  LIMIT " . $start_index . ',' . $tab_num_rec;
													$qur = mysqli_query($iot_db, $query);
													while ($rowc = mysqli_fetch_array($qur)) {
												?>
                                                <td><label class="ckbox"><input type="checkbox" id="delete_check[]" name="delete_check[]"
                                                                                value="<?php echo $rowc["device_id"]; ?>"><span></span></label></td>
                                                <!--                                            <td class="text-center">--><?php //echo ++$counter; ?><!--</td>-->
                                                <td class="">
                                                    <a href="edit_device.php?device_id=<?php echo  $rowc["device_id"]; ?>" class="edit-btn">
                                                        <i class="fa fa-pencil-alt"></i>
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
                                                <!--                                            <td>-->
                                                <!--												--><?php
													//													$created_by =  $rowc["created_by"];
													//													$qurtmp = mysqli_query($db, "SELECT firstname,lastname FROM cam_users where users_id = '$created_by'");
													//													while ($rowctmp = mysqli_fetch_array($qurtmp)) {
													//														$firstname = $rowctmp["firstname"];
													//														$lastname = $rowctmp["lastname"];
													//														$fullname = $firstname . ' ' . $lastname;
													//													}
													//												?>
                                                <!--												--><?php //echo  $fullname; ?>
                                                <!--                                            </td>-->
                                                <!---->
                                                <!--                                            <td>--><?php //echo  dateReadFormat($rowc["created_on"]); ?><!--</td>-->
                                            </tr>
											<?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="row">
											<?php
												$remainder = $tot_devices % $tab_num_rec;
												$quotient = ($tot_devices - $remainder) / $tab_num_rec;
												$tot_pg  = (($remainder == 0)?$quotient: ($quotient+1));
												$curr_page = ($pg + 1);
											?>
                                            <div class="col-sm-4 col-xs-6">Page <b><?php echo $curr_page ?></b> of <b><?php echo $tot_pg ?></b></div>
                                            <!--                                        <div class="col-sm-4 col-xs-6" style="text-align: center">Page - --><?php //echo $curr_page; ?><!--</div>-->
                                            <div class="col-sm-4 col-xs-6" style="text-align: center">Go To Page -
                                                <input id="num_tab_pg" class="ptab_goto_num" type="number" min="1" value=<?php echo $curr_page ?> />
                                            </div>
                                            <!--                                        <div class="col-sm-4 col-xs-6" style="text-align: center">Go To Page --->
                                            <!--                                            <select id="num_tab_pg" class="ptab_search">-->
                                            <!--												--><?php
												//													for ($y = 1; $y <= $tot_pg; $y++) {
												//														if($y == $curr_page){
												//															echo "<option value='$y' selected>$y</option>";
												////															echo "<li" . " class='active'" . "><a class='tab_pg' id='tab_pg_$x' val='$x' >$x</a></li>";
												//														}else{
												//															echo "<option value='$y'>$y</option>";
												//														}
												//													}
												//												?>
                                            <!--                                            </select>-->
                                            <!--                                        </div>-->
                                            <div class="col-sm-4 col-xs-6">
                                                <ul class="pagination hidden-xs pull-right">
													<?php
														
														$xx = (($curr_page -2) > 0)?($curr_page -2):1;
														$zz = (($curr_page +2) < $tot_pg)?($curr_page +2):$tot_pg;
														if($curr_page > 1){
															$pPg = $xx -1;
															echo "<li><a <a id='prev_pg' val='$pPg'>«</a></li>";
														}
														for ($x = $xx; $x <= $zz; $x++) {
															if($x == $curr_page){
																echo "<li" . " class='active'" . "><a class='tab_pg' id='tab_pg_$x' val='$x' >$x</a></li>";
															}else{
																echo "<li><a class='tab_pg'  id='tab_pg_$x' val='$x' >$x</a></li>";
															}
														}
														if($curr_page < $tot_pg){
															$nPg= $zz+1;
															echo "<li><a id='next_pg' val='$nPg'>»</a></li>";
														}
													?>
                                                </ul>
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

    $( "#submit_btn" ).click(function (e){
        e.preventDefault();
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#device_settings").serialize();
        $.ajax({
            type: 'POST',
            url: 'create_device.php',
            data: "fSubmit=1&" + data,
            success: function (data) {
                // window.location.href = window.location.href + "?aa=Line 1";
                $(':input[type="button"]').prop('disabled', false);
                var st_val =  data.split("}",2);
                var st = JSON.parse(st_val[0]+'}')['status'];
                var message_text = JSON.parse(st_val[0]+'}')['message'];
                if(st == 'error'){
                    document.getElementById('dp_fail_msg').innerText = message_text;
                    document.getElementById('aFail').style.display = 'block';
                    document.getElementById('aSucc').style.display = 'none';
                    window.scrollTo(0, 0);
                }else if(st == 'success'){
                    document.getElementById('dp_suc_msg').innerText = message_text;
                    document.getElementById('aSucc').style.display = 'block';
                    document.getElementById('addDevice').style.display = 'none';
                    document.getElementById('aFail').style.display = 'none';
                    window.scrollTo(0, 0);
                }
            }
        });
    });

    function deleteDevices(url) {
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#up-iot-device").serialize();
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

    $( ".myPriButton" ).click(function (e){
        e.preventDefault();
        // $('#addDevice').css('display','block');
        document.getElementById('addDevice').style.display = 'block';
        // $('#addDevice').toggle();
    });

    $("#num_tab_rec").change(function (e) {
        e.preventDefault();
        $(':input[type="button"]').prop('disabled', true);
        var data = "tab_rec_num="+ this.value;
        $.ajax({
            type: 'POST',
            data: data,
            url:'create_device.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $( "[id^='tab_pg']" ).click(function (e){
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ this.text;
        $.ajax({
            type: 'POST',
            data: data,
            url:'create_device.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $( "#next_pg" ).click(function (e){
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num = document.getElementById('curr_pg').value;
        var nPage = 1;
        if(pg_num != null){
            nPage = (parseInt(pg_num) + 2);
        }
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ nPage;
        $.ajax({
            type: 'POST',
            data: data,
            url:'create_device.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $( "#prev_pg" ).click(function (e){
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num = document.getElementById('curr_pg').value;
        // var nPage = 1;
        // if(pg_num != null){
        //     nPage = (parseInt(pg_num) - 1);
        // }
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ pg_num;
        // var data = "tab_rec_num="+ tab_num +"&pg_num="+ this.text;
        $.ajax({
            type: 'POST',
            data: data,
            url:'create_device.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $( "#num_tab_pg" ).change(function() {
        // e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num =  this.value;
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ pg_num;
        $.ajax({
            type: 'POST',
            data: data,
            url:'create_device.php',
            success: function (data) {
                $("body").html(data);
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
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    var $rows = $('#tbody tr');
    $('#ptab_search').keyup(function() {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });
</script>
<script>
    $('.select2').select2();
</script>
</body>
</html>




