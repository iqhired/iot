<?php
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;
$message = "";
include("config.php");
$chicagotime = date("Y-m-d H:i:s");
$status = '0';

if (!empty($_POST['user'])){
    if(!empty($_POST['password'])) {
    $user = $_POST["user"];
    $password = md5($_POST['password']);
    $_SESSION['user'] = $_POST["user"];
		//API url
		$service_url = $rest_pn_api_uri . "login/login.php";
		$curl = curl_init($service_url);
		$curl_post_data = array(
			'user' => $user,
			'password' => $password
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
		if (isset($decoded->status) && $decoded->status == 'Error') {
			$message_stauts_class = $_SESSION["alert_danger_class"];
			$import_status_message = $_SESSION["error_1"];
		}else{
			$row = $decoded->user;
			if ($row != null && !empty($row)) {
				$logid = $row->users_id;
				$_SESSION['id'] = $logid;
				$_SESSION['available'] = $row->available;
				$user_nm = $row->user_name;
				$_SESSION['user'] = $user_nm;
				
				$_SESSION['name'] = $user_nm;
				$_SESSION['role_id'] = $row->role;
				$_SESSION['uu_img'] = $row->profile_pic;
				$_SESSION['sqq1'] = $row->s_question1;
				$status = $row->u_status;
				$_SESSION['session_user'] = $logid;
				$_SESSION['fullname'] = $row->firstname . "&nbsp;" . $row->lastname;
				$_SESSION['pin'] = $row->pin;
				$_SESSION['pin_flag'] = $row->pin_flag;
				$_SESSION['is_cust_dash'] = $row->is_cust_dash;
				$_SESSION['line_cust_dash'] = $row->line_cust_dash;
				$_SESSION['tab_station'] = null;
				$_SESSION['is_tab_user'] = null;
				$pin = $row->pin;
				$pin_flag = $row->pin_flag;
				$uip=$_SERVER['REMOTE_ADDR'];
				$host=$_SERVER['HTTP_HOST'];
				$time = date("H:i:s");
				$date = date("Y-m-d");
				$email = $row->email;
				
				//mysqli_query($db, "INSERT INTO `cam_session_log`(`users_id`,`created_at`) VALUES ('$logid','$chicagotime')");
				mysqli_query($db, "INSERT INTO `cam_session_log_p`(`users_id`,`created_at`,`uip`,`host`,`username`,`logoutdate`,`logouttime`) VALUES ('$logid','$chicagotime','$uip','$host','$user','$date','$time')");
				
				//	mysqli_query($db, "INSERT INTO `cam_session_log`(`users_id`,`created_at`) VALUES ('$logid','$chicagotime')");
				$roleid = $row->role;
				$result11 = mysqli_query($db, "SELECT * FROM `cam_role` WHERE role_id ='$roleid'");
				$row11 = mysqli_fetch_array($result11);
				$_SESSION['role_id'] = $row11['type'];
				$_SESSION['side_menu'] = $row11['side_menu'];
				
				$result12 = mysqli_query($db, "SELECT * FROM `sg_user_group` WHERE user_id = '$logid'");
				while ($row12 = mysqli_fetch_array($result12)) {
					$value = $row12['group_id'];
					$result14 = mysqli_query($db, "SELECT * FROM `sg_taskboard` WHERE group_id ='$value'");
					while ($row14 = mysqli_fetch_array($result14)) {
						$value1 = $row14['sg_taskboard_id'];
					}
					if ($value1 != "") {
						$_SESSION["taskavailable"] = "1";
					}
				}
			} else {
				$message_stauts_class = $_SESSION["alert_danger_class"];
				$import_status_message = $_SESSION["error_1"];
			}
			if(!empty($email)) {
				if ($status == '1') {
					header("Location:change_pin.php");
					exit;
				} else {
					header("Location:iot_devices_home.php");
					
				}
			}
//		else{
//			header("Location:line_status_grp_dashboard.php");
//		}
			if ($pin_flag == "1") {
				if ($pin == "0") {
					$_SESSION['message_stauts_class'] = 'alert-danger';
					$_SESSION['import_status_message'] = 'Please Fill Pin';
					header("Location:profile.php");
					exit;
				} else {
					header("Location:iot_devices_home.php");
					
				}
			}
			else {
				header("Location:iot_devices_home.php");
				
			}
		}
		//echo 'response ok!';
		//var_export($decoded->response);
 }
}
$tmp = $_SESSION['temp'];
$_SESSION['temp'] = "";
if ($tmp == "forgotpass_success") {
    $message_stauts_class = $_SESSION["alert_success_class"];
    $import_status_message = $_SESSION["error_2"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Design by foolishdeveloper.com -->
    <title>IOT</title>
    <script src="<?php echo $iotURL; ?>/assets/js/libs/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <!--Stylesheet-->
    <style media="screen">
        *,
        *:before,
        *:after{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body{
            background-color: #03194d;
            /*background-color: #554daf;*/
        }
        .background{
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%,-50%);
            left: 50%;
            top: 50%;
        }
        .background .shape{
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }
        .shape:first-child{
            background: linear-gradient(
                    #1845ad,
                    #23a2f6
            );
            left: -80px;
            top: -80px;
        }
        .shape:last-child{
            background: linear-gradient(
                    to right,
                    #ff512f,
                    #f09819
            );
            right: -30px;
            bottom: -80px;
        }
        form{
            height: 520px;
            width: 400px;
            background-color: rgba(255,255,255,0.13);
            position: absolute;
            transform: translate(-50%,-50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.1);
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            padding: 30px 35px;
        }
        form *{
            font-family: 'Poppins',sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }
        form h3{
            font-size: 28px;
            font-weight: 500;
            line-height: 32px;
            text-align: center;
        }

        label{
            display: block;
            margin-top: 25px;
            font-size: 16px;
            font-weight: 500;
        }
        input{
            display: block;
            height: 40px;
            width: 100%;
            background-color: rgba(255,255,255,0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }
        ::placeholder{
            color: #e5e5e5;
        }
        button{
            margin-top: 20px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        .social{
            margin-top: 20px;
            display: flex;
            font-size: small;
            /*text-align: center;*/
            /*align-items: center;*/
        }
        .social div{
            /*background: red;*/
            /*width: 150px;*/
            border-radius: 3px;
            padding: 5px 10px 10px 0px;
            /*background-color: rgba(255,255,255,0.27);*/
            color: #eaf0fb;
            /*text-align: center;*/
            /*min-height: 50px;*/
        }
        .social div:hover{
            background-color: rgba(255,255,255,0.47);
        }
        .social .fb{
            margin-left: 25px;
        }
        .social i{
            margin-right: 4px;
        }
        .logo{
            text-align: center;
            margin-bottom: 20px;
        }

    </style>
    <script type="text/javascript">
        function disablebackbutton(){
            window.history.forward();
        }
        disablebackbutton();
    </script>
</head>
<body>
<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>
<form method="post">
    <div class="logo">
        <img class="user" src="./assets/images/site_logo.png"  width="120px">
    </div>
	<?php
		if (!empty($import_status_message)) {
			echo '<div class="alert ' . $message_stauts_class . '">' . $import_status_message . '</div>';
		}
	?>
    <h3>Login Here</h3>
    <label for="username">Username</label>
    <input type="text" placeholder="Email or Phone" name="user" id="user" required="required">

    <label for="password">Password</label>
    <input type="password" placeholder="Password" id="password"  name="password">

    <button type="submit" id="signin">Log In</button>
<!--    <input type="submit" class="signin" id="signin" name="log" value="Login">-->
    <div class="social">
        <div class="go"><a href="forgotpass.php">Forgot Username/Password?</a></div>
    </div>
</form>
</body>
</html>
