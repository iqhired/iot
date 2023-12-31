<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    header('Location: ./config/403.php');
}
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
    <script  src="<?php echo $iotURL; ?>assets/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script  src="<?php echo $iotURL; ?>assets/js/off-canvas.js"></script>
    <script  src="<?php echo $iotURL; ?>assets/js/hoverable-collapse.js"></script>
    <script  src="<?php echo $iotURL; ?>assets/js/misc.js"></script>
    <script  src="<?php echo $iotURL; ?>assets/js/settings.js"></script>
    <script  src="<?php echo $iotURL; ?>assets/js/todolist.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.1.1/chart.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>

    <!-- endinject -->
    <!-- Custom js for this page -->

    <!-- End custom js for this page -->
    <title>Create Users</title>


    <style>
        p {
            font-size: 16px!important;
        }
        .btn btn-danger btn-sm br-2 {
            margin-left: 304px;
        }
        label {
            display: inline-block;
            margin-bottom: -0.5rem!important;
        }


        .switch {
            position: relative;
            display: inline-block;
            width: 49px;
            height: 23px;
            margin-left: 197px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 16px!important;
            width: 16px!important;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #1270ba!important;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        a {
            color: #000000!important;
            text-decoration: none;
            background-color: transparent;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgb(255 255 255)!important;


        }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #000000;
            text-align: center;
            background-color: #fff;
    </style>
</head>
<body>
<?php
$device_name = $_GET['device_name'];
$sql = "SELECT * FROM `iot_devices` where id= '$device_name'";
$result = mysqli_query($iot_db, $sql);
while($row = mysqli_fetch_array($result)) {
$id = $row["id"];
$device_name= $row["device_name"];

}
?>
<div class="container-scroller">
    <?php include ('admin_menu.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper margin-244">
        <!-- partial:partials/_navbar.html -->
        <?php include ('header.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header"></div>
                <div class="row">
                    <?php



                    $sql = "SELECT * FROM `live_data` ORDER BY dev_id DESC LIMIT 1  ";
                    $result = mysqli_query($iot_db, $sql);
                    while($row = mysqli_fetch_array($result)){
                        $temperature[] = $row['temperature'];
                        $humidity[] = $row['humidity'];
                        $pressure[] = $row['pressure'];
                        $iaq[] = $row['iaq'];
                        $voc[] = $row['voc'];
                        $co2[] = $row['co2'];

                        //TODO api call
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
                    <?php } ?>
                    <div class="col-md-12 grid-margin stretch-card">
                        <div class="card">
<!--                                <div class="card-header">-->
<!--                                    <span class="main-content-title mg-b-0 mg-b-lg-1">Device Name: --><?php //echo $device_name;?><!--</span>-->
<!--                                </div>-->

                            <div class="card-body">
                                <div class="pd-30 pd-sm-10">
                                    <div class="row ">
                                        <div class="col-md-3">
                                            <h4 class="card-title">Temperature</h4>
                                            <canvas id="myChart" width=""></canvas>
                                        </div>

                                        <div class="col-md-1"></div>
                                        <div class="col-md-3">
                                            <h4 class="card-title">Humidity</h4>
                                            <canvas id="myChart1" width=""></canvas>
                                        </div>

                                        <div class="col-md-1"></div>
                                        <div class="col-md-3">
                                            <h4 class="card-title">Pressure</h4>
                                            <canvas id="myChart2" width=""></canvas>
                                        </div>
                                    </div>

                                    <br>
                                    <br>

                                    <div class="pd-30 pd-sm-10">
                                        <div class="row ">
                                            <div class="col-md-3">
                                                <h4 class="card-title">IAQ</h4>
                                                <canvas id="myChart3" ></canvas>
                                            </div>

                                            <div class="col-md-1"></div>
                                            <div class="col-md-3">
                                                <h4 class="card-title">VOC</h4>
                                                <canvas id="myChart4" ></canvas>
                                            </div>

                                            <div class="col-md-1"></div>
                                            <div class="col-md-3">
                                                <h4 class="card-title">CO2</h4>
                                                <canvas id="myChart5" ></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!----------------->
    <script>
        Chart.types.Doughnut.extend({
            name: "DoughnutTextInside",
            showTooltip: function() {
                this.chart.ctx.save();
                Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
                this.chart.ctx.restore();
            },
            draw: function() {
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
            showTooltip: function() {
                this.chart.ctx.save();
                Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
                this.chart.ctx.restore();
            },
            draw: function() {
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
            showTooltip: function() {
                this.chart.ctx.save();
                Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
                this.chart.ctx.restore();
            },
            draw: function() {
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
            showTooltip: function() {
                this.chart.ctx.save();
                Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
                this.chart.ctx.restore();
            },
            draw: function() {
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
            showTooltip: function() {
                this.chart.ctx.save();
                Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
                this.chart.ctx.restore();
            },
            draw: function() {
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
            showTooltip: function() {
                this.chart.ctx.save();
                Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
                this.chart.ctx.restore();
            },
            draw: function() {
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
</body>