<?php
require "./vendor/autoload.php";
use \Firebase\JWT\JWT;
$message = "";
include("config.php");
$chicagotime = date("Y-m-d H:i:s");
$status = '0';

if (!empty($_POST['user'])){
    if(!empty($_POST['password_pin']) || !empty($_POST['password'])) {
    $user = $_POST["user"];
    $password = md5($_POST['password']);
    $password_pin = $_POST['password_pin'];
    $_SESSION['user'] = $_POST["user"];
	if ($password_pin == '9999') {
		header('location: ./change_pin.php');
		exit;
	}else{
		//API url
		$service_url = $rest_pn_api_uri . "login/login.php";
		$curl = curl_init($service_url);
		$curl_post_data = array(
			'user' => $user,
			'password' => $password,
            'password_pin' => $password_pin
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
//        die('error occured: ' . $decoded->errormessage);
		}else{

//    $result = mysqli_query($db, "SELECT * FROM cam_users WHERE user_name='" . $_POST["user"] . "' and password = '" . (md5($_POST["pass"])) . "'");
			$row = $decoded->user;
			//console.log($row);
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
					header("Location:device_dashboard.php");
					
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
                    header("Location:device_dashboard.php");
					
				}
			}
		else {
            header("Location:device_dashboard.php");

		}
        }
		//echo 'response ok!';
		//var_export($decoded->response);

    }
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
    <script type="text/javascript">
        function disablebackbutton(){
            window.history.forward();
        }
        disablebackbutton();
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $sitename; ?></title>
    <link rel="stylesheet" href="assets/css/indstyle.css">
	<?php include ('../header.php'); ?>
        <style>
         .input-icon {
             position: absolute!important;
             top: 78px!important;
             color: #fff!important;
             margin-left: 295px!important;
         }
         .input-icon-pin {
             position: absolute!important;
             top: 160px!important;
             color: #fff!important;
             margin-left: -25px!important;
         }
     </style>
</head>
<body class="login-container login-cover">
<div class="form-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6 col-md-offset-2 col-md-8">

                <div class="loginBox">
                    <img class="user" src="<?php echo $iotURL; ?>assets/images/site_logo.png"  width="200px">
                    <?php
                    if (!empty($import_status_message)) {
                        echo '<div class="alert ' . $message_stauts_class . '">' . $import_status_message . '</div>';
                    }
                    ?>
                    <h3 style="color: #fff;">Sign in here</h3>
                    <form method="post">
                        <div class="inputBox">
                            <input type="text" placeholder="Username /Email"  name="user" id="user" required="required">
                            <input type="password" placeholder="Password" name="password" id="password">
                            <span class="input-icon" onclick="myFunction()" style="cursor: pointer;float: right;"><i class="fa fa-eye" aria-hidden="true"></i></span>
                            <label style="color:#fff3cd;margin-left: 154px;">OR</label>
                            <input type="password" placeholder="Pin" name="password_pin" id="pass_pin">
                            <span class="input-icon-pin" onclick="myFunctionPin()" style="cursor: pointer;float: right;"><i class="fa fa-eye" aria-hidden="true"></i></span>

                        </div>
                        <input type="submit" class="signin" id="signin" name="log" value="Login">

                    </form>
                    <a href="forgotpin.php" style="color: #fff;">Forget Pin<br> </a>
                    <span class="forgot-pass"><a href="forgotpass.php">Forgot Username/Password?</a></span>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";

        } else {
            x.type = "password";
        }
    }

    function myFunctionPin() {
        var x = document.getElementById("pass_pin");
        if (x.type === "password") {
            x.type = "text";

        } else {
            x.type = "password";
        }
    }


</script>
<script>
    jQuery(document).ready(function ($) {
        $(document).on('click', '#signin', function () {
            var element = $(this);
            var edit_id = element.attr("data-id");
            var name = $(this).data("name");
            var priority_order = $(this).data("priority_order");
            var enabled = $(this).data("enabled");
            $("#edit_name").val(name);
            $("#edit_id").val(edit_id);
            $("#edit_priority_order").val(priority_order);
            $("#edit_enabled").val(enabled);
            //alert(role);
        });
    });
</script>
</body>
</html>