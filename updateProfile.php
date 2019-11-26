<?php
require_once 'init.php';

if (!$currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>

<h1>Đổi thông tin cá nhân</h1>
<?php if ("POST" == $_SERVER["REQUEST_METHOD"]): ?>
<?php
$f_name = $_POST['f-name'];
$l_name = $_POST['l-name'];

$check = false;

// Check
if (!empty(trim($f_name)) && !empty(trim($l_name))) {
 // Updating
 updateUserProfile($currentUser['id'], $f_name, $l_name);
 $noti_succ = 'Thông tin cập nhật thành công! <a href="index.php">Trở về trang chủ.</a>';
 $check     = true;
} else {
 $noti_err = 'Họ tên không được bỏ trống!';
 $check    = false;
}

// Upload Image
$fileSize = $_FILES['profilePicture']['size'];
if (isset($_FILES['profilePicture']) && $fileSize > 0 && $fileSize < 500000) {
 $fileName = $_FILES['profilePicture']['name'];
 $fileTmp  = $_FILES['profilePicture']['tmp_name'];

 // Separate file name by '.'
 $fileArr = explode('.', $fileName);
 // Change image's name
 $newImageName = 'avatar_' . $currentUser['username'] . '.jpg' /* . $fileArr[1]*//* $fileArr[1] is ending of fileName (.jpg/.jpeg ...) */;

 $imagetmp =file_get_contents($fileTmp);
 if (updateUserProfilePicture($currentUser['id'], $imagetmp)) {
  $noti_succ = 'Thông tin cập nhật thành công! <a href="index.php">Trở về trang chủ.</a>';
 } else {
  $noti_err = 'Không thể tải ảnh đại diện! Vui lòng thử lại! <a href="updateProfile.php">Trở về.</a>';
  $check    = false;
 }
}

?>

<?php if ($check): ?>
<div class="alert alert-success" role="alert">
    <?php echo $noti_succ; ?>
</div>
<?php else: ?>
<div class="alert alert-danger" role="alert">
    <?php echo $noti_err; ?>
</div>
<?php endif; ?>

<?php else: ?>
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
                    <input type="text" class="form-control" id="f-name" name="f-name"
                           placeholder="Họ" value="<?php echo $currentUser['firstname']; ?>">
                </div>
                <div class="col">
                    <label for="l-name">Tên</label>
                    <input type="text" class="form-control" id="l-name" name="l-name"
                           placeholder="Tên" value="<?php echo $currentUser['lastname']; ?>">
                </div>
                <div class="col">
                    <label for="b-name">Ngày sinh</label>
                    <input type="date" class="form-control" id="b-date" name="b-date"
                           value="<?php echo $currentUser['Birthday']; ?>">
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