<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    header('Location: ./config/403.php');
}
require "../vendor/autoload.php";
use Firebase\JWT\JWT;
$message = "";
include("../config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";

if (!empty($_POST['edit_cust_name'])){
    $cust_id = $_POST['edit_cust_id'];
    $edit_cust_name = $_POST['edit_cust_name'];
    $edit_mobile = $_POST['edit_mobile'];
    $edit_cust_email = $_POST['edit_email'];
    $edit_role = $_POST['edit_role'];
    $edit_cust_fistname= $_POST['edit_fistname'];
    $edit_cust_lastname = $_POST['edit_lastname'];
    $edit_cust_address = $_POST['cust_edit_address'];

    $service_url = $rest_api_uri . "users/edit_iot_users.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'cust_id' => $cust_id,
        'edit_cust_name' => $edit_cust_name,
        'edit_mobile' => $edit_mobile,
        'edit_cust_email' => $edit_cust_email,
        'edit_role' => $edit_role,
        'edit_cust_fistname' => $edit_cust_fistname,
        'edit_cust_lastname' => $edit_cust_lastname,
        'edit_cust_address' => $edit_cust_address,
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
        die('error occured: ' . $decoded->errormessage);
        $errors[] = "Users Not Updated.";
        $message_stauts_class = 'alert-danger';
        $import_status_message = 'User Not Updated.';
    }
    $errors[] = "User Updated Successfully.";
    $message_stauts_class = 'alert-success';
    $import_status_message = 'User Updated Successfully.';
    header('Location: create_users.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Users</title>
    <style>
        body
        {margin: 0; height: 100%; overflow: hidden}
    </style>


    <!-- plugins:css -->
</head>

<body>
<div class="container-scroller">
    <?php include ('../admin_menu.php'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <?php include ('../header.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Edit Users</li>
                        </ol>
                    </nav>
                </div>
                <div class="row">
                    <div class="col-md-10 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Edit User </h4>

                                <form action="" method="post" id="" enctype="multipart/form-data">
                                    <?php
                                    $cust_id = $_GET['cust_id'];

                                    $sql = "select * from iot_users where cust_id = '$cust_id' and is_deleted != 1";
                                    $res = mysqli_query($iot_db, $sql);
                                    $row = mysqli_fetch_array($res);

                                    $cust_id = $row['cust_id'];
                                    $cust_name = $row['cust_name'];
                                    $mobile= $row['mobile'];
                                    $cust_email = $row['cust_email'];
                                    $role = $row['role'];
                                    $cust_profile_pic = $row['cust_profile_pic'];
                                    $cust_fistname = $row['cust_fistname'];
                                    $cust_lastname= $row['cust_lastname'];
                                    $cust_address = $row['cust_address'];
                                    ?>
                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">User Name : </label>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="edit_cust_id" id="edit_cust_id" value="<?php echo $cust_id; ?>">

                                            <input type="text" class="form-control" name="edit_cust_name" id="edit_cust_name" value="<?php echo $cust_name; ?>" placeholder="Enter User Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Email</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="edit_email" id="edit_email" value="<?php echo $cust_email; ?>" placeholder="Enter Email">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">First Name : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="edit_fistname" id="edit_fistname" value="<?php echo $cust_fistname; ?>" placeholder="Enter First Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Last Name : </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="edit_lastname" id="edit_lastname" value="<?php echo $cust_lastname; ?>" placeholder="Enter Last Name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Mobile :</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" name="edit_mobile" id="edit_mobile" value="<?php echo $mobile; ?>" placeholder="Enter Mobile">

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Role : </label>
                                        <div class="col-sm-9">
                                            <select name="edit_role" id="edit_role"  class="form-control form-select select2" data-placeholder="Select role" >
                                                <option value=""> Select Role </option>
                                                <?php
                                                 if ($role == 1){
                                                     $selected = 'selected';
                                                 }else{
                                                     $selected = '';
                                                 }

                                                echo "<option value='" . $role . "' $selected>" . "customer";"</option>";

                                                 ?>
                                            </select>
                                        </div>
                                    </div>
<!--                                    <div class="form-group row">-->
<!--                                        <label  class="col-sm-3 col-form-label">Profile pic :</label>-->
<!--                                        <div class="col-sm-9">-->
<!--                                            <input type="file" class="form-control" value="--><?php //echo $cust_profile_pic; ?><!--" name="edit_cust_profile_pic" id="edit_cust_profile_pic" placeholder="Enter Profile Pic">-->
<!---->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="form-group row">
                                        <label  class="col-sm-3 col-form-label">Address :</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="cust_edit_address" value="<?php echo $cust_address; ?>" id="cust_edit_address" placeholder="Enter Address">

                                        </div>
                                    </div>
                                    <button type="submit" name="submit_btn" id="submit_btn" class="btn btn-primary mr-2">Update</button>
                                    <button class="btn btn-dark">Cancel</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

