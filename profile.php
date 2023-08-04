<?php
include("config.php");
$temp = "";
if (!isset($_SESSION['user'])) {
    header('location: logout.php');
}
$usr = $_SESSION['user'];
// to display error msg
if (!empty($_SESSION['import_status_message'])) {
    $message_stauts_class = $_SESSION['message_stauts_class'];
    $import_status_message = $_SESSION['import_status_message'];
    $_SESSION['message_stauts_class'] = '';
    $_SESSION['import_status_message'] = '';
}
if (count($_POST) > 0) {
    $uploadPath = 'user_images/';
    $statusMsg = '';
    $upload = 0;
    $pin = $_SESSION["pin"];
    //validate the pin
    $password = $_POST['newpin'];

    if( strlen($password ) > 4 ) {
        $error .= "Pin too long!";
    }
    if( strlen($password ) < 4 ) {
        $error .= "Pin too short!";
    }
    if( !preg_match("@[0-9]@", $password ) ) {
        $error .= "Pin must Numeric!";
    }
    $pin_flag = $_SESSION["pin_flag"];
    if (!empty($_FILES['file']['name'])) {
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $fileTemp = $_FILES['file']['tmp_name'];
        $filePath = $uploadPath . basename($fileName);
        // Allow certain file formats
        $allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
        if (in_array($fileType, $allowTypes)) {
            $rotation = $_POST['rotation'];
            if ($rotation == -90 || $rotation == 270) {
                $rotation = 90;
            } elseif ($rotation == -180 || $rotation == 180) {
                $rotation = 180;
            } elseif ($rotation == -270 || $rotation == 90) {
                $rotation = 270;
            }
            if (!empty($rotation)) {
                switch ($fileType) {
                    case 'image/png':
                        $source = imagecreatefrompng($fileTemp);
                        break;
                    case 'image/gif':
                        $source = imagecreatefromgif($fileTemp);
                        break;
                    default:
                        $source = imagecreatefromjpeg($fileTemp);
                }
                $imageRotate = imagerotate($source, $rotation, 0);
                switch ($fileType) {
                    case 'image/png':
                        $upload = imagepng($imageRotate, $filePath);
                        break;
                    case 'image/gif':
                        $upload = imagegif($imageRotate, $filePath);
                        break;
                    default:
                        $upload = imagejpeg($imageRotate, $filePath);
                }
            } elseif (move_uploaded_file($fileTemp, $filePath)) {
                $upload = 1;
            } else {
                $statusMsg = 'File upload failed, please try again.';
            }
        } else {
            $statusMsg = 'Sorry, only JPG/JPEG/PNG/GIF files are allowed to upload.';
        }
        if ($upload == 1) {
            if($error){

            }else{
                if ($pin_flag == "1") {
                    $_SESSION["pin"] = $_POST['pin'];
                    $sql = "update cam_users set pin='$password',password_pin = '$password',profile_pic='$fileName',firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
                } else {
                    $sql = "update cam_users set profile_pic='$fileName',firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
                }
                $result1 = mysqli_query($db, $sql);
                if ($result1) {
                    $_SESSION["fullname"] = $_POST['firstname'] . "&nbsp;" . $_POST['lastname'];
                    $message_stauts_class = 'alert-success';
                    $import_status_message = 'Success: Profile Updated Sucessfully.';
                } else {
                    $message_stauts_class = 'alert-danger';
                    $import_status_message = 'Error: Please Try Again.';
                }
            }
            $_SESSION["uu_img"] = $fileName;
        } else {
            echo '<h4>' . $statusMsg . '</h4>';
        }
    } else {
        if($error){

        }else{
            if ($pin_flag == "1") {
                $_SESSION["pin"] = $_POST['pin'];
                $sql = "update cam_users set pin='$password',password_pin = '$password',firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
            } else {
                $sql = "update cam_users set firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
            }
            $_SESSION["fullname"] = $_POST['firstname'] . "&nbsp;" . $_POST['lastname'];
            $result1 = mysqli_query($db, $sql);
            if ($result1) {
                $message_stauts_class = 'alert-success';
                $import_status_message = 'Success: Profile Updated Sucessfully.';
            } else {
                $message_stauts_class = 'alert-danger';
                $import_status_message = 'Error: Please Try Again.';
            }
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PN</title>
    <!-- plugins:css -->
    <style>
        .page-body-wrapper{
            width: 100%!important;
        }
        .navbar{
            left: 0!important;
        }
        .img-lg {
            width: 158px!important;
            height: 158px!important;
            float: right;
        }
        .navbar .navbar-menu-wrapper .navbar-toggler:not(.navbar-toggler-right) {
            font-size: 0.875rem;
            display: none;
        }
        .navbar .navbar-brand-wrapper {
            width: 130px!important;
        }
    </style>
<body>
<div class="container-scroller">
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->

        <?php include ('s_header.php'); ?>
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <?php
                    $query = sprintf("SELECT * FROM  cam_users where user_name = '$usr'  ; ");
                    $qur = mysqli_query($db, $query);
                    while ($rowc = mysqli_fetch_array($qur)) {
                        ?>
                        <div class="col-md-2 grid-margin stretch-card">
                        </div>
                        <div class="col-md-2 grid-margin">
                            <?php if(!empty($rowc["u_profile_pic"])){ ?>
                                <img class="img-lg rounded-circle" src="user_images/<?php echo $rowc["u_profile_pic"]; ?>" alt="">
                            <?php }else{ ?>
                                <img class="img-lg rounded-circle" src="user_images/user.png" alt="">
                            <?php } ?>
                        </div>
                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="post" id="update_profile" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">User Name : </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="username" value="<?php echo $rowc["user_name"]; ?>" class="form-control" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">First Name : </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="firstname" value="<?php echo $rowc["firstname"]; ?>" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Last Name : </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="lastname" value="<?php echo $rowc["lastname"]; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Mobile : </label>
                                            <div class="col-sm-9">
                                                <input type="text" name="mobile" value="<?php echo $rowc["mobile"]; ?>" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Email : </label>
                                            <div class="col-sm-9">
                                                <input type="email" name="email" value="<?php echo $rowc["email"]; ?>" class="form-control" >
                                            </div>
                                        </div>
                                        <!--<div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Password : </label>
                                        <div class="col-sm-9">
                                            <input type="text" name="password" value="<?php /*echo decryptIt($rowc["u_password"]); */?>" class="form-control" >
                                        </div>
                                    </div>-->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Upload New Image : </label>
                                            <div class="col-sm-9">
                                                <input type="file" name="file" id="file" class="form-control">
                                                <div id="imgPreview"></div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary mr-2">Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<script>
    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgPreview + img').remove();
                $('#imgPreview').after('<img src="' + e.target.result + '" class="pic-view" width="200" height="150" float="left"/>');
            };
            reader.readAsDataURL(input.files[0]);
            $('.img-preview').show();
        } else {
            $('#imgPreview + img').remove();
            $('.img-preview').hide();
        }
    }
    $("#file").change(function () {
        // Image preview
        filePreview(this);
    });
    $(function () {
        var rotation = 0;
        $("#rright").click(function () {
            rotation = (rotation - 90) % 360;
            $(".pic-view").css({'transform': 'rotate(' + rotation + 'deg)'});
            if (rotation != 0) {
                $(".pic-view").css({'width': '100px', 'height': '132px'});
            } else {
                $(".pic-view").css({'width': '24%', 'height': '132px'});
            }
            $('#rotation').val(rotation);
        });
        $("#rleft").click(function () {
            rotation = (rotation + 90) % 360;
            $(".pic-view").css({'transform': 'rotate(' + rotation + 'deg)'});
            if (rotation != 0) {
                $(".pic-view").css({'width': '100px', 'height': '132px'});
            } else {
                $(".pic-view").css({'width': '24%', 'height': '132px'});
            }
            $('#rotation').val(rotation);
        });
    });
</script>
</body>
</html>

