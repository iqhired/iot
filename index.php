<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    header('Location: ./config/403.php');
}
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
					header("Location:device_dashboard1.php");
					
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
                    header("Location:device_dashboard1.php");
					
				}
			}
		else {
            header("Location:device_dashboard1.php");

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