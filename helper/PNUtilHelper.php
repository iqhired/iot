<?php
/**
 * @author Ayesha
 */
/**
 * This methos return the SQL date in readable format
 * @param $datetime
 * @return false|string
 */
function dateReadFormat($datetime) {
	return date("d-M-Y H:i:s" , strtotime($datetime));
}
/**
 * This methos return the SQL date in readable format
 * @param $datetime
 * @return false|string
 */
function onlydateReadFormat($datetime) {
	return date("d-M-Y" , strtotime($datetime));
}
/**
 * This methos return the SQL date in readable format
 * @param $datetime
 * @return false|string
 */
function datemdY($datetime) {
	return date("m-d-Y" , strtotime($datetime));
}

/**
 * This methos return the SQL date in readable format
 * @param $datetime
 * @return false|string
 */
function datemdYHis($datetime) {
	return date("m-d-Y H:i:s" , strtotime($datetime));
}
function datemdYHi($datetime) {
    return date("m-d-Y H:i" , strtotime($datetime));
}

function convertMDYToYMD($date){
	$parts = explode('-',$date);
	$date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
	return $date;
}

function convertMDYToYMDwithTime($date){
	$pp = explode(' ',$date);
	$parts = explode('-',$pp[0]);
	$date = $parts[2] . '-' . $parts[0] . '-' . $parts[1] . ' ' . $pp[1];
	return $date;
}

/**
 * This methods returns conversion of  MDY date format to YMD date format
 * @param $date
 * @return false|string
 */
function convertYMDToMDY($date){
    $parts = explode('-',$date);
    $date = $parts[2] . '-' . $parts[0] . '-' . $parts[1];
    return $date;
}
function convertYMDToMDYwithTime($date){
    $pp = explode(' ',$date);
    $parts = explode('-',$pp[0]);
    $date = $parts[2] . '-' . $parts[0] . '-' . $parts[1] . ' ' . $pp[1];
    return $date;
}
/**
 * This method Draws a grid on the image uploaded and divide the image in yXy grid.
 * @param $image
 * @param $output
 * @param int $xgrid
 * @param int $ygrid
 */
function gridify($image, $output, $xgrid = 3, $ygrid = 3) {
	$imgpath = $image;
	$ext = pathinfo($image, PATHINFO_EXTENSION);
	if($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "JPEG")
		$img = imagecreatefromjpeg($image);
	elseif($ext == "png" || $ext == "PNG")
		$img = imagecreatefrompng($image);
	elseif($ext == "gif")
		$img = imagecreatefromgif($image);
	else
		echo 'Unsupported file extension';
	$size = getimagesize($imgpath);
	$width = $size[0];
	$height = $size[1];
	$red   = imagecolorallocate($img, 255,   0,   0);

// Number of cells
//	$xgrid = 3;
//	$ygrid = 3;

// Calulate each cell width/height
	$xgridsize = $width / $xgrid;
	$hgridsize = $height / $ygrid;

// Remember col
	$c = 'A';

// Y
	for ($j=0; $j < $ygrid; $j++) {

		// X
		for ($i=0; $i < $xgrid; $i++) {

			// Dynamic x/y coords
			$sy = $hgridsize * $j;
			$sx = $xgridsize * $i;

			// Draw rectangle
			imagerectangle($img, $sx, $sy, $sx + $xgridsize, $sy + $hgridsize, $red);

			// Draw text
			addTextToCell($img, $sx, $xgridsize, $sy + $hgridsize, $hgridsize, $c . ($i + 1));
		}

		// Bumb cols
		$c++;
	}
	// Save output as file
	//site_URL.'assets/images/part_images/cs/' path to store
	$output_name =  $output .'.jpg';
	imagejpeg($img, $output_name);
	imagedestroy($img);
//	shell_exec("open -a Preview '$output_name'");
}

/**
 * This method is used to write the text on the image
 * @param $img
 * @param $cellX
 * @param $cellWidth
 * @param $cellY
 * @param $cellHeight
 * @param $text
 */
function addTextToCell($img, $cellX, $cellWidth, $cellY, $cellHeight, $text) {

	// Calculate text size

	$text_box = imagettfbbox(100, 0, 'Arial', $text);
	$text_width = $text_box[2]-$text_box[0];
	$text_height = $text_box[7]-$text_box[1];
	$font = 'arial.ttf';
	// Calculate x/y position
	$textx = $cellX + ($cellWidth/2) - $text_width;
	$texty = $cellY - ($cellHeight/2) - $text_height;

	// Set color and draw
	$color = imagecolorallocate($img, 255, 0, 0);
//	imagettftext($img, 20, 0, $textx, $texty, $color, 'OpenSans', $text);
	imagestring($img, 2, $textx, $texty, $text, $color);
	// Add the text


}

/**
 * This method is used to check if session has expired
 * and then to redirect to appropriate Login screen
 */
function checkSession(){
	if (!isset($_SESSION['user'])) {
		if ($_SESSION['is_tab_user'] || $_SESSION['is_cell_login']) {
			header('location:'.site_URL.'/tab_logout.php');
		} else {
			header('location:'.site_URL.'/logout.php');
		}
	}

//Set the session duration for 10800 seconds - 3 hours
	$duration = '10800';
//Read the request time of the user
	$time = $_SERVER['REQUEST_TIME'];
//Check the user's session exist or not
	if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $duration) {
		//Unset the session variables
		session_unset();
		//Destroy the session
		session_destroy();
		if ($_SESSION['is_tab_user'] || $_SESSION['is_cell_login']) {
			header('location:'.site_URL.'/tab_logout.php');
		} else {
			header('location:'.site_URL.'/logout.php');
		}
		exit;
	}
//Set the time of the user's last activity
	$_SESSION['LAST_ACTIVITY'] = $time;

	$i = $_SESSION["role_id"];
	if ($i != "super" && $i != "admin" && $i != "pn_user" && $_SESSION['is_tab_user'] != 1 && $_SESSION['is_cell_login'] != 1 ) {
		header('location: '.site_URL.'/line_status_overview_dashboard.php');
	}
}

/**
 * 
 */
function goHome(){
	$i = $_SESSION["role_id"];
	$is_tab_login = $_SESSION['is_tab_user'];
	$is_cell_login = $_SESSION['is_cell_login'];
	if(!empty($is_cell_login) && $is_cell_login == 1){
		$path = site_URL. "cell_line_dashboard.php";
	}else{
		if(!empty($i) && ($is_tab_login != null)){
			$path = site_URL . "/line_tab_dashboard.php";
		}else{
			$path = site_URL . "/line_status_grp_dashboard.php";
		}
	}
	header('location:'.$path);
}

/**
 *
 */
function displaySuccessMessage(){
	session_start();
	if (isset($_SESSION['success'])) {
		set_time_limit(10);
		echo '<p style="color:green">' . $_SESSION['success'] . "</p>";
		unset($_SESSION['success']);
	}
}

/**
 *
 */
function displayFailureMessage(){
	session_start();
	if (isset($_SESSION['error'])) {
		echo '<p style="color:red">' . $_SESSION['error'] . "</p>";
		unset($_SESSION['error']);
	}
}


/**
 *
 */
function displaySFMessage(){
	session_start();
	if (!empty($_SESSION['import_status_message'])) {
		echo '<div><div class="alert ' . $_SESSION['message_stauts_class'] . '">' . $_SESSION['import_status_message'] . '</div></div>';
		unset($_SESSION['message_stauts_class']);
		unset($_SESSION['import_status_message']);
	}
}
	
	
	function dPMessage(){
		echo ' <div id="aSucc" class="alert alert-success" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                <p id="dp_suc_msg"></p>
            </div>
            <div id="aFail"  class="alert alert-danger" style="display: none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
			×</button>
                <p id="dp_fail_msg"></p>
            </div>';
	}
	function displayMessage(){
		session_start();
		$messType = $_SESSION['mType'];
		$message = $_SESSION['dispMessage'];
		if($messType == mTypeSucess){
			echo ' <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
               <span class="glyphicon glyphicon-ok"></span> <strong>Success Message</strong>
                <hr class="message-inner-separator">
                <p>'. $message .'</p>
            </div>';
		}else if($messType == mTypeError){
			echo '<div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    ×</button>
                <span class="glyphicon glyphicon-hand-right"></span> <strong>Error Message</strong>
                <hr class="message-inner-separator">
                <p>'. $message .'</p>
            </div>';
		}
		unset($_SESSION['mType']);
		unset($_SESSION['dispMessage']);
	}

// Function to check string starting
// with given substring
	function startsWith ($string, $startString)
	{
		$len = strlen($startString);
		return (substr($string, 0, $len) === $startString);
	}
	
	/**
	 * @param $string
	 * @return string
	 */
	function printTextBlue($string){
		return '<span class="text-primary font-weight-bold">' . $string .'</span>';
	}

?>