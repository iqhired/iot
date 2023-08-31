<?php
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
		if ($_SESSION['is_tab_user'] || $_SESSION['is_cell_login']) {
			header($redirect_tab_logout_path);
		} else {
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
    <meta http-equiv="refresh" content="300">
    <title>IOT Devices Home</title>
	<?php include('header.php'); ?>
    <style>

        .ratings i {
            color: green;
        }

        .install span {
            font-size: 12px;
        }

        .col-md-4 {
            margin-top: 27px;
        }
        img {
            height: 60px;
            width: 60px;
            margin-right: 6%;
            margin-left: 2%;
        }
        .ml-2{
            font-size: initial;
        }
        .text-black-60 {
            color: rgb(0 0 0 / 60%) !important;
            font-size: small;
        }
        div.d-flex.flex-row.mb-3{
            padding-bottom: 1rem !important;
            border-bottom: 1px solid #3333 !important;
        }
        .fa-gear , .fa-delete-left{
            font-size: initial;
        }
        .fa-gear{
            color: #606060;
        }
        .fa-delete-left{
            color: var(--col-red);
        }
        #view_link{
            width: 50%;
            text-align: end;
        }
    </style>
</head>
<div class="container-scroller">
	<?php include('admin_menu.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper margin-244">
        <!-- partial:partials/_navbar.html -->
		<?php include('nav.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
<!--                <div class="page-header">-->
<!--                    <nav aria-label="breadcrumb">-->
<!--                    </nav>-->
<!--                </div>-->

                <div class="row">
                    <div class="container">
                        <div class="row">
							<?php
								$sql = "SELECT * FROM `iot_devices` where is_deleted != 1";
								$result = mysqli_query($iot_db, $sql);
								while ($row = mysqli_fetch_array($result)) {
									$created_date = new DateTime(explode(' ',$row['created_on'])[0]);
									$current_date = new DateTime(date("Y-m-d"));
									$period = get_period_ago($current_date,$created_date);
                                    $edit_dev_loc = $iotURL .'device/edit_device.php?device_id='.$row['device_id'];
                                    $view_dev_loc = $iotURL .'device/view_device_dashboard.php?device_id='.$row['device_id'];
                                    $del_dev_loc = $iotURL .'device/delete_device.php?device_id='.$row['device_id'];
                                    $d_type_id=$row['type_id'];
									$d_type_sql = "SELECT dev_type_name FROM `iot_device_type` where type_id = '$d_type_id' and  is_deleted != 1";
									$d_type_res = mysqli_fetch_array(mysqli_query($iot_db, $d_type_sql));
									?>
                                    <div class="col-md-4">
                                        <div class="card p-3" style="font-family: inherit">
                                            <div class="d-flex flex-row mb-3"><img src="<?php $iotURL?>/assets/images/iot_sensor_icon.png">
                                                <div class="d-flex flex-column ml-2">
                                                    <span><?php echo printTextBlue($row['device_name'])?></span>
                                                    <span class="text-black-60"><?php echo 'Added ' . $period?></span>
                                                    <span class="text-black-60"><b>Type : </b><?php echo $d_type_res['dev_type_name']?></span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between install">
                                                <span style="width: 50%">
                                                    <a style="padding: 0% 5%;" href="<?php echo $edit_dev_loc?>"><i class="fa fa-gear"></i></a>
                                                    <a id="del_device" name="del_device" href="#" data-value="<?php echo $del_dev_loc?>" class="d_Id"><i class="fa fa-delete-left"></i></a>
                                                </span>
                                                <span id="view_link" class="text-primary"><a style="padding: 0% 5%;" href="<?php echo $view_dev_loc?>">View&nbsp;<i class="fa fa-angle-right"></a></i></span>
                                            </div>
                                        </div>
                                    </div>
								<?php } ?>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>
</a>
<script>
    $('.d_Id').click(function(e) {
        e.preventDefault();
        var d_ID = $(this).data('value');
        $.ajax({
            type: 'POST',
            url: d_ID,
            success: function (data) {
                location.reload();
            }
        });
    });
    function deviceDB(device_id) {
        window.open("<?php echo site_URL . "iot_device_data.php?device_id="; ?>" + device_id, "_self")
    }
</script>
<script>
    function cellDB(id, device_name) {
        window.open("<?php echo $iotURL . "device_graph.php?id="; ?>" + id + "<?php echo "&device_name="; ?>" + device_name, "_self")
    }

    // setTimeout(function () {
    //    location.reload();
    // }, 60000);
</script>


<?php include('footer.php'); ?>

</html>
</body>

