<?php
	require "./../vendor/autoload.php";
	
	use Firebase\JWT\JWT;
	
	$status = '0';
	$message = "";
	include("./../config.php");
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
	<?php include('./../header.php'); ?>
</head>
<div class="container-scroller">
	<?php include('./../admin_menu.php'); ?>
	<!-- partial -->
	<div class="container-fluid page-body-wrapper margin-244">
		<!-- partial:partials/_navbar.html -->
		<?php include('./../nav.php'); ?>
		<!-- partial -->
		<div class="main-panel">
			<div class="content-wrapper">
				<!--                <div class="page-header">-->
				<!--                    <nav aria-label="breadcrumb">-->
				<!--                    </nav>-->
				<!--                </div>-->
				
				<div class="row">
					<div class="container">
					
					</div>
				</div>
			
			</div>
		
		</div>
	
	</div>

</div>
</a>
<script>

</script>
<?php include('./../footer.php'); ?>

</html>
</body>

