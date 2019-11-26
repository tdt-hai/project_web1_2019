<?php
require_once 'init.php';

if (!$currentUser) {
 header('Location: index.php');
 exit();
}
?>
<?php include 'header.php'; ?>

<h1>Đổi mật khẩu</h1>
<?php if ("POST" == $_SERVER["REQUEST_METHOD"]): ?>
<?php

$currentPass = $_POST['currentPass'];
$newPass     = $_POST['newPass'];
$repass      = $_POST['re-password'];

$check = false;

// Check
if (!empty(trim($currentPass)) && !empty(trim($newPass)) && !empty(trim($repass))) {
 if (password_verify($currentPass, $currentUser['password'])) {
  if (strlen(trim($newPass)) >= 6 && strlen(trim($newPass)) <= 15) {
   if ($currentPass != $newPass) {
    if ($newPass == $repass) {
     // Updating
     
            updateUserPassword($currentUser['email'], $newPass);
            $noti_succ = 'Thay đổi mật khẩu thành công! <a href="index.php">Trở về trang chủ.</a>';
            $check     = true;
    } else {
            $noti_err_pass = 'Mật khẩu xác nhận không chính xác! <a href="changePassword.php">Trở về.</a>';
            $check         = false;
    }
   } else {
            $noti_err_pass = 'Mật khẩu mới phải khác mật khẩu cũ! <a href="changePassword.php">Trở về.</a>';
            $check         = false;
   }
  } else {
            $noti_err_pass = 'Vui lòng nhập mật khẩu từ 6 đến 15 ký tự! <a href="changePassword.php">Trở về.</a>';
            $check         = false;
  }
 } else {
            $noti_err_pass = 'Mật khẩu hiện tại không đúng! <a href="changePassword.php">Trở về.</a>';
            $check         = false;
 }
} else {
            $noti_err_pass = 'Vui lòng nhập đầy đủ thông tin! <a href="changePassword.php">Trở về.</a>';
            $check         = false;
}
?>

<?php if ($check): ?>
<div class="alert alert-success" role="alert">
    <?php echo $noti_succ; ?>
</div>
<?php else: ?>
<div class="alert alert-danger" role="alert">
    <?php echo $noti_err_pass; ?>
</div>
<?php endif; ?>

<?php else: ?>
<div>
    <form action="changePassword.php" method="POST">
        <div class="form-group">
            <label for="currentPass">Mật khẩu hiện tại</label>
            <input type="password" class="form-control" id="currentPass" name="currentPass"
                   placeholder="Mật khẩu hiện tại">
        </div>
        <div class="form-group">
            <label for="newPass">Mật khẩu mới</label>
            <input type="password" class="form-control" id="newPass" name="newPass"
                   placeholder="Mật khẩu mới">
        </div>
        <div class="form-group">
            <label for="re-password">Nhập lại mật khẩu mới</label>
            <input type="password" class="form-control" id="re-password" name="re-password"
                   placeholder="Xác nhận lại mật khẩu">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Xác nhận</button>
        </div>
    </form>
</div>

<?php endif; ?>

<?php include 'footer.php'; ?>