<?php
	require "./../vendor/autoload.php";
	
	use Firebase\JWT\JWT;
	
	$status = '0';
	$message = "";
	include("../config.php");
	//include("../sup_config.php");
	$chicagotime = date("Y-m-d H:i:s");
	$temp = "";
	$device_id = $_GET['device_id'];
	$temperature_data = '';
	$humidity_data = '';
	$pressure_data = '';
	$iaq_data = '';
	$voc_data = '';
	$co2_data = '';
	$datetime = '';
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
	if (!empty($device_id)) {
		$cURLConnection = curl_init();
		
		curl_setopt($cURLConnection, CURLOPT_URL, 'http://13.214.116.35:3001/environment');
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		
		$curl_response = curl_exec($cURLConnection);
		if ($curl_response === false) {
			$info = curl_getinfo($cURLConnection);
			curl_close($cURLConnection);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($cURLConnection);
		
		$decoded = json_decode($curl_response);
		
		
		if (!empty($decoded->Temperature)) {
//			$device_id = $decoded->DeviceID;
			$temperature_data = $decoded->Temperature;
			$humidity_data = $decoded->Humidity;
			$pressure_data = $decoded->Pressure;
			$iaq_data = $decoded->IAQ;
			$voc_data = $decoded->VOC;
			$co2_data = $decoded->CO2;
			$datetime = $decoded->Date_Time;
		}
		
	}
	$temperature[] = $temperature_data;
	$humidity[] = $humidity_data;
	$pressure[] = $pressure_data;
	$iaq[] = $iaq_data;
	$voc[] = $voc_data;
	$co2[] = $co2_data;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="300">
    <title>IOT Devices Home</title>
    <link rel="stylesheet" href="<?php echo $iotURL; ?>assets/pages/css/vDeviceDashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.1/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

</head>
<?php include('./../header.php'); ?>

<div class="container-scroller">
	<?php include('./../admin_menu.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper margin-244">
        <!-- partial:partials/_navbar.html -->
		<?php include('./../nav.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
				<?php $sql = "SELECT * FROM `iot_devices` where device_id='$device_id' and is_deleted != 1";
					$result = mysqli_query($iot_db, $sql);
					while ($row = mysqli_fetch_array($result)) {
						$created_date = new DateTime(explode(' ', $row['created_on'])[0]);
						$current_date = new DateTime(date("Y-m-d"));
						$period = get_period_ago($current_date, $created_date);
						$edit_dev_loc = $iotURL . '/device/edit_device.php?device_id=' . $row['device_id'];
						$d_type_id = $row['type_id'];
						$d_type_sql = "SELECT dev_type_name FROM `iot_device_type` where type_id = '$d_type_id' and  is_deleted != 1";
						$d_type_res = mysqli_fetch_array(mysqli_query($iot_db, $d_type_sql));
						?>
                        <div class="page-content page-container" id="page-content">
                            <div class="row container d-flex justify-content-center">
                                <div class="col-xl-12 col-md-12">
                                    <div class="card user-card-full">
                                        <div class="row m-l-0 m-r-0">
                                            <div class="col-sm-4 bg-c-lite-green user-profile">
                                                <div class="card-block text-center text-white">
                                                    <div class="m-b-25">
                                                        <img src="<?php $iotURL ?>/assets/images/iot_sensor_icon.png"
                                                             class="img-radius" alt="Device-Profile-Image">
                                                    </div>
                                                    <div class="row">
                                                        <div class="bio-row">
                                                            <p><span>Name </span>: <?php echo $row['device_name'] ?></p>
                                                        </div>
                                                        <div class="bio-row">
                                                            <p>
                                                                <span>Type </span>: <?php echo $d_type_res['dev_type_name'] ?>
                                                            </p>
                                                        </div>
                                                        <div class="bio-row">
                                                            <p>
                                                                <span>Location </span>: <?php echo $row['device_location'] ?>
                                                            </p>
                                                        </div>
                                                        <div class="bio-row">
                                                            <p>
                                                                <span>Description </span>: <?php echo $row['device_description'] ?>
                                                            </p>
                                                        </div>

                                                    </div>
                                                    <hr style="border-top: 1px solid rgb(255 255 255 / 40%) !important;"/>
                                                    <p><?php echo 'Device Added ' . $period ?></p>

                                                    <span style="width: 50%">
                                                    <p><span>Edit Device </span>: <a
                                                                style="padding: 0% 5%;color: inherit"
                                                                href="<?php echo $edit_dev_loc ?>"><i
                                                                    class="fa fa-edit"></i></a>
                                                </span>
                                                </div>
                                            </div>
                                            <div class="col-sm-8  user-profile">
                                                <div class="card-block" style="text-align: center !important;">
                                                    <div class="row ">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-3">
                                                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">
                                                                Temperature</h6>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Humidity</h6>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Pressure</h6>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-3">
                                                            <canvas id="myChart" width=""></canvas>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <canvas id="myChart1" width=""></canvas>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <canvas id="myChart2" width=""></canvas>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                    </div>
                                                    <div class="row m-t-40">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-3">
                                                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">IAQ</h6>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">VOC</h6>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">CO2</h6>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-md-1"></div>
                                                        <div class="col-md-3">
                                                            <canvas id="myChart3"></canvas>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <canvas id="myChart4"></canvas>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <canvas id="myChart5"></canvas>
                                                        </div>
                                                        <div class="col-md-2"></div>
                                                    </div>
                                                </div>
                                                <p style="text-align: center;"><?php echo 'Time : ' . dateReadFormat($datetime) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					<?php } ?>
            </div>

        </div>

    </div>

</div>
</a>
<!----------------->
<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = (height / 200).toFixed(2);
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($temperature); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($temperature); ?>,
        color: "#F7464A"

    }];

    var DoughnutTextInsideChart = new Chart($('#myChart')[0].getContext('2d')).DoughnutTextInside(data, {
        responsive: true
    });
</script>


<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = (height / 200).toFixed(2);
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($humidity); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($humidity); ?>,
        color: "#1ca2bd"

    }];

    var DoughnutTextInsideChart = new Chart($('#myChart1')[0].getContext('2d')).DoughnutTextInside(data, {
        responsive: true
    });
</script>

<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = (height / 200).toFixed(2);
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($pressure); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($pressure); ?>,
        color: "#7a2c2c"

    }];

    var DoughnutTextInsideChart = new Chart($('#myChart2')[0].getContext('2d')).DoughnutTextInside(data, {
        responsive: true
    });
</script>

<!----------------->

<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = (height / 200).toFixed(2);
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($iaq); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($iaq); ?>,
        color: "#ff8400"

    }];

    var DoughnutTextInsideChart = new Chart($('#myChart3')[0].getContext('2d')).DoughnutTextInside(data, {
        responsive: true
    });
</script>


<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = (height / 200).toFixed(2);
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($voc); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($voc); ?>,
        color: "#ff4000"

    }];

    var DoughnutTextInsideChart = new Chart($('#myChart4')[0].getContext('2d')).DoughnutTextInside(data, {
        responsive: true
    });
</script>

<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = (height / 200).toFixed(2);
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($co2); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($co2); ?>,
        color: "#1f7330"

    }];

    var DoughnutTextInsideChart = new Chart($('#myChart5')[0].getContext('2d')).DoughnutTextInside(data, {
        responsive: true
    });
</script>
<?php include('./../footer.php'); ?>

</html>
</body>

