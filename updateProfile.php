<?php
require_once 'init.php';

if (!$currentUser) {
    header('Location: index.php');
    exit();
}
?>
<?php include 'header.php'; ?>
<div class="hold-transition login-page">
    <div class="login-box">
        <?php
            if ("POST" == $_SERVER["REQUEST_METHOD"]) {
                $f_name = $_POST['f-name'];
                $l_name = $_POST['l-name'];
                $birthday = $_POST['b-date'];
                $phonenumber = $_POST['phoneNumber'];
                $education = $_POST['education'];
                $location = $_POST['location'];
                $skill = $_POST['skill'];
                $notes = $_POST['note'];
                // Upload Image
                $fileSize = $_FILES['profilePicture']['size'];
                if (isset($_FILES['profilePicture'])) {
                    $fileName = $_FILES['profilePicture']['name'];
                    $fileTmp  = $_FILES['profilePicture']['tmp_name'];
                    if (isset($fileName) && !empty($fileName)) {
                        // lay duoi file
                        $extension = substr($fileName, strpos($fileName, '.') + 1);
                        // kiem tra xem co dung la file hinh anh hay khong
                        if ($extension == "jpg" && $fileSize <= 500000) {

                            $imagetmp = file_get_contents($fileTmp);
                            if (updateUserProfilePicture($currentUser['id'], $imagetmp) && !empty(trim($f_name)) && !empty(trim($l_name))) {
                                updateUserProfile($currentUser['id'], $f_name, $l_name, $phonenumber, $birthday,$education,$location,$skill,$notes);
                                ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Thành công!</h5>
            Thông tin cập nhật thành công!
        </div>
        <?php
                                updateUserProfilePicture($currentUser['id'], $imagetmp);
                            } else {
                                ?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
            Cập nhật ảnh đại diện thất bại
        </div>
        <?php
                            }
                            } else {
                            ?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
            Chỉ hỗ trợ file JPG và dung lượng nhỏ hơn hoặc bằng 5Mb.
        </div>
        <?php
                        }
                    } 
                    else if (empty($f_name) || empty($l_name) || empty($birthday) || empty($phonenumber))
                    {
                        ?>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Cảnh báo!</h5>
            Vui lòng nhập đầy đủ thông tin
        </div>
        <?php
                    }
                    else {
                        updateUserProfile($currentUser['id'], $f_name, $l_name, $phonenumber, $birthday,$education,$location,$skill,$notes);
                        ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Thành công!</h5>
            Thông tin cập nhật thành công!
        </div>
        <?php
                    }
                }
        }
        ?>

        <div class="login-logo">
            <a><b>Lotus</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg"> Bạn chỉ còn một bước nữa từ mật khẩu mới, khôi phục mật khẩu của bạn ngay bây
                    giờ.</p>
                <form action="updateProfile.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profilePicture" name="profilePicture"
                                accept="image/jpeg">
                            <label class="custom-file-label" for="exampleInputFile">image</label>
                        </div>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-upload"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="f-name" name="f-name" placeholder="Họ"
                            value="<?php echo $currentUser['firstname']; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="l-name" name="l-name" placeholder="Tên"
                            value="<?php echo $currentUser['lastname']; ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $currentUser['phoneNumber']; ?>" data-inputmask='"mask": "9999-999-999"' data-mask>
                        
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-phone-alt"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                         <input type="text" class="form-control" id="b-date" name="b-date" value="<?php echo $currentUser['Birthday']; ?>" data-inputmask-alias="datetime"
                                data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-birthday-cake"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"  name="education" value="<?php echo $currentUser['Education']; ?>" placeholder="Học ở đâu ?">
                        
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-university"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"  name="location" value="<?php echo $currentUser['Location']; ?>" placeholder="Thành phố hiện tại của bạn">
                        
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-city"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="skill" value="<?php echo $currentUser['Skill']; ?>" placeholder="Kĩ năng của bạn">
                        
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lightbulb"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="note" value="<?php echo $currentUser['Notes']; ?>" placeholder="Bio">
                        
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-history"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>