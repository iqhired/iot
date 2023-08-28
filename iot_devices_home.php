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
	<meta http-equiv="refresh" content="300">
	<title>IOT Devices Home</title>
	<?php include ('header.php'); ?>
</head>
<div class="container-scroller">
	<?php include ('admin_menu.php'); ?>
	<!-- partial -->
	<div class="container-fluid page-body-wrapper margin-244">
		<!-- partial:partials/_navbar.html -->
		<?php include ('nav.php'); ?>
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
							$id[] = $row['id'];
							$device_name[] = $row['device_name'];
							
							//TODO make an api call
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
							if (isset($decoded->status) && $decoded->status == 'ERROR') {
								die('error occured: ' . $decoded->errormessage);
							}
							?>
							<div class="col-md-4 grid-margin stretch-card" onclick="cellDB('<?php echo $row["id"] ?>' , '<?php echo $row["device_name"] ?>')">
								<div class="card">
									
									<div class="card-body">
										<h4 class="card-title"><?php echo $row["device_name"]; ?>
											
											<button class="btn btn btn-sm"
													id="center" style="color:white">
												<label class="switch">
													<input type="checkbox" name="is_active" id="is_active" value="<?php echo $row["device_id"]; ?>" <?php echo ($row['is_active']==1 ? 'checked' : '');?>>
													<span class="slider round"></span>
												
												</label>
											</button>
											
											<button class="btn btn-danger btn-sm float-right"
													id="right" style="color:white">
												<a href="device/del_device.php?device_id=<?php echo  $row["device_id"]; ?>"  >
													<i>
														<svg class="table-delete" xmlns="http://www.w3.org/2000/svg" color="white" height="20" viewBox="0 0 24 24" width="16"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9zm7.5-5l-1-1h-5l-1 1H5v2h14V4h-3.5z"></path></svg>
													</i>
												</a>
											</button>
										</h4>
									</div>
								
								</div>
							
							</div>
						<?php } ?>
				</div>
			
			</div>
		
		</div>
	
	</div>

</div>
</a>
<script>
    function deviceDB(device_id) {
        window.open("<?php echo site_URL . "iot_device_data.php?device_id=" ; ?>" + device_id , "_self")
    }
</script>
<script>
    function cellDB(id , device_name) {
        window.open("<?php echo $iotURL . "device_graph.php?id=" ; ?>" + id + "<?php echo "&device_name=" ; ?>" + device_name , "_self")
    }
    // setTimeout(function () {
    //    location.reload();
    // }, 60000);
</script>


<?php include ('footer.php'); ?>

</html>
</body>

