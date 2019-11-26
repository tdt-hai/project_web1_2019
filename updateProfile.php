<?php
require_once 'init.php';

if (!$currentUser) {
    header('Location: index.php');
    exit();
}
?>
<?php include 'header.php'; ?>

<h1>Đổi thông tin cá nhân</h1>
<?php if ("POST" == $_SERVER["REQUEST_METHOD"]) : ?>
    <?php
        
        $f_name = $_POST['f-name'];
        $l_name = $_POST['l-name'];
        $birthday = $_POST['b-date'];
        $phonenumber = $_POST['phoneNumber'];
        $check = false;

        //var_dump ($_POST['f-name']);
        
        // Check
        if(empty($f_name))
                {
                ?>
                 <div class="alert alert-danger" role="alert">
                        Không được bỏ trống Họ. <a href="./updateProfile.php">Quay lại</a>
                 </div>
                <?php
                } else if(empty($l_name))
                {
                ?>
                 <div class="alert alert-danger" role="alert">
                        Không được bỏ trống Tên. <a href="./updateProfile.php">Quay lại</a>
                 </div>
                <?php 
                }

        // Upload Image
        $fileSize = $_FILES['profilePicture']['size'];
        if (isset($_FILES['profilePicture'])) {
            $fileName = $_FILES['profilePicture']['name'];
            $fileTmp  = $_FILES['profilePicture']['tmp_name'];
            $fileArr = explode('.', $fileName);
            $newImageName = 'avatar_' . $currentUser['username'] . '.jpg' /* . $fileArr[1]*//* $fileArr[1] is ending of fileName (.jpg/.jpeg ...) */;

            if (isset($fileName) && !empty($fileName)) {
                // lay duoi file
                $extension = substr($fileName, strpos($fileName, '.') + 1);
                // kiem tra xem co dung la file hinh anh hay khong
                if ($extension == "jpg" && $fileSize <= 500000) {

                    $imagetmp = file_get_contents($fileTmp);
                    if (updateUserProfilePicture($currentUser['id'], $imagetmp) && !empty(trim($f_name)) && !empty(trim($l_name))) {
                       
                        updateUserProfile($currentUser['id'], $f_name, $l_name, $phonenumber, $birthday);
                        $noti_succ = 'Thông tin cập nhật thành công! <a href="index.php">Trở về trang chủ.</a>';
                        $check     = true;
                        updateUserProfilePicture($currentUser['id'], $imagetmp);
                    } else {
                        ?>
                    <div class="alert alert-warning" role="alert">
                        Cập nhật ảnh đại diện thất bại <a href="./update-profile.php">Quay lại</a>
                    </div>
                <?php

                                }
                            } else {
                                ?>
                <div class="alert alert-danger" role="alert">
                    Chỉ hỗ trợ file JPG và dung lượng nhỏ hơn hoặc bằng 5Mb. <a href="./updateProfile.php">Quay lại</a>
                </div>
    <?php
                }
            } else {
                updateUserProfile($currentUser['id'], $f_name, $l_name, $phonenumber, $birthday);
                $noti_succ = 'Thông tin cập nhật thành công! <a href="index.php">Trở về trang chủ.</a>';
                $check = true;
            }
        }
        ?>
    <?php if ($check) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $noti_succ; ?>
        </div>
    <?php endif; ?>

<?php else : ?>
    <div>
        <form action="updateProfile.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profilePicture">Choose your profile picture</label>
                <input type="file" class="form-control-file" id="profilePicture" name="profilePicture">
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col">
                        <label for="f-name">Họ</label>
                        <input type="text" class="form-control" id="f-name" name="f-name" placeholder="Họ" value="<?php echo $currentUser['firstname']; ?>">
                    </div>
                    <div class="col">
                        <label for="l-name">Tên</label>
                        <input type="text" class="form-control" id="l-name" name="l-name" placeholder="Tên" value="<?php echo $currentUser['lastname']; ?>">
                    </div>
                    <div class="col">
                        <label for="phoneNumber">Số điện thoại</label>
                        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Số điện thoại" value="<?php echo $currentUser['phoneNumber']; ?>">
                    </div>
                    <div class="col">
                        <label for="b-name">Ngày sinh</label>
                        <input type="date" class="form-control" id="b-date" name="b-date" value="<?php echo $currentUser['Birthday']; ?>">
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Xác nhận</button>
            </div>
        </form>
    </div>

<?php endif; ?>

<?php include 'footer.php'; ?>

<?php

?>